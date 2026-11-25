<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

require_login();

// Handle the "Order Again" request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reorder_id'])) {
    if (!verify_csrf($_POST['csrf'] ?? null)) {
        flash('info', 'Session expired. Please try again.');
    } else {
        $orderId = (int)$_POST['reorder_id'];
        
        // 1. Security Check: Make sure this order belongs to the user
        $stmt = $pdo->prepare('SELECT id FROM orders WHERE id = ? AND user_id = ?');
        $stmt->execute([$orderId, $_SESSION['user_id']]);
        
        if ($stmt->fetch()) {
            // 2. Fetch the items, ensuring they are still available on the menu
            $stmt = $pdo->prepare('
                SELECT oi.menu_item_id, oi.quantity 
                FROM order_items oi
                JOIN menu_items mi ON oi.menu_item_id = mi.id
                WHERE oi.order_id = ? AND mi.is_available = 1
            ');
            $stmt->execute([$orderId]);
            $reorderItems = $stmt->fetchAll();
            
            if ($reorderItems) {
                cart_clear(); // Clear current cart to exactly match the old order
                
                foreach ($reorderItems as $item) {
                    cart_add((int)$item['menu_item_id'], (int)$item['quantity']);
                }
                
                flash('success', 'Your previous order has been added to your bag.');
                header('Location: checkout.php');
                exit;
            } else {
                flash('info', 'Sorry, the items from this order are currently unavailable.');
                header('Location: orders.php');
                exit;
            }
        }
    }
}

// Fetch all orders for the user
$stmt = $pdo->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll();

$itemsByOrder = [];
if ($orders) {
    $ids = array_column($orders, 'id');
    $in  = implode(',', array_fill(0, count($ids), '?'));
    $st  = $pdo->prepare("SELECT * FROM order_items WHERE order_id IN ($in)");
    $st->execute($ids);
    foreach ($st->fetchAll() as $row) {
        $itemsByOrder[$row['order_id']][] = $row;
    }
}

// Calculate the user's specific order number based on total orders
$totalUserOrders = count($orders);
$currentUserOrderNum = $totalUserOrders;

$pageTitle = 'My Orders';
require __DIR__ . '/includes/header.php';
?>

<section class="pt-5 pb-5">
  <div class="container">
    <h1 class="section-title mb-4">My <span class="text-accent">orders</span></h1>

    <?php if (!$orders): ?>
      <div class="text-center py-5 rounded-4"
           style="background:var(--bg-2);border:1px solid var(--line)">
        <i class="bi bi-receipt fs-1 text-secondary"></i>
        <p class="mt-3 text-secondary">You haven't placed an order yet.</p>
        <a href="order.php" class="btn btn-accent">Order something</a>
      </div>
    <?php else: ?>
      <div class="row g-4">
        <?php foreach ($orders as $order): ?>
          <div class="col-lg-6">
            <div class="p-4 rounded-4 h-100 d-flex flex-column"
                 style="background:var(--bg-2);border:1px solid var(--line)">
              
              <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                  <!-- User-specific order number -->
                  <div class="h6 mb-0">Order #<?= $currentUserOrderNum ?></div>
                  <div class="small text-secondary">
                    <?= date('d M Y, H:i', strtotime($order['created_at'])) ?>
                  </div>
                </div>
                <span class="badge rounded-pill <?= $order['status'] === 'delivered'
                    ? 'text-bg-success'
                    : ($order['status'] === 'cancelled' ? 'text-bg-danger' : 'text-bg-warning') ?>">
                  <?= e(ucfirst($order['status'])) ?>
                </span>
              </div>

              <?php foreach ($itemsByOrder[$order['id']] ?? [] as $it): ?>
                <div class="d-flex justify-content-between small py-1">
                  <span class="text-secondary"><?= $it['quantity'] ?> × <?= e($it['item_name']) ?></span>
                  <span>$<?= number_format($it['unit_price'] * $it['quantity'], 2) ?></span>
                </div>
              <?php endforeach; ?>

              <hr class="border-secondary-subtle">
              
              <div class="d-flex justify-content-between fw-bold mb-2">
                <span>Total</span>
                <span class="text-accent">$<?= number_format((float)$order['total'], 2) ?></span>
              </div>
              
              <div class="small text-secondary mb-4">
                <i class="bi bi-geo-alt"></i> <?= e($order['address']) ?>
              </div>

              <!-- Order Again Form -->
              <form method="post" class="mt-auto">
                <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
                <!-- Still uses the real database ID under the hood -->
                <input type="hidden" name="reorder_id" value="<?= (int)$order['id'] ?>">
                <button type="submit" class="btn btn-accent w-100">
                  <i class="bi bi-arrow-repeat"></i> Order Again
                </button>
              </form>

            </div>
          </div>
        <?php 
          $currentUserOrderNum--; // Decrease the number for the next (older) order
        endforeach; 
        ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>