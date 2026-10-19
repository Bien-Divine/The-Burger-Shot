<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

require_login();

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
            <div class="p-4 rounded-4 h-100"
                 style="background:var(--bg-2);border:1px solid var(--line)">
              <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                  <div class="h6 mb-0">Order #<?= (int)$order['id'] ?></div>
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
              <div class="d-flex justify-content-between fw-bold">
                <span>Total</span>
                <span class="text-accent">$<?= number_format((float)$order['total'], 2) ?></span>
              </div>
              <div class="small text-secondary mt-2">
                <i class="bi bi-geo-alt"></i> <?= e($order['address']) ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>