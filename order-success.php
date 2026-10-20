<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

$orderId = $_SESSION['last_guest_order'] ?? null;

if (!$orderId) {
    header('Location: index.php');
    exit;
}

// Fetch the order to show details
$stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ?');
$stmt->execute([$orderId]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: index.php');
    exit;
}

// Fetch order items
$stmt = $pdo->prepare('SELECT * FROM order_items WHERE order_id = ?');
$stmt->execute([$orderId]);
$items = $stmt->fetchAll();

$pageTitle = 'Order Confirmed';
require __DIR__ . '/includes/header.php';
?>

<section class="section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 col-xl-6 text-center">
        
        <div class="mb-4">
          <div class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle mb-3" style="width: 80px; height: 80px;">
            <i class="bi bi-check-lg" style="font-size: 3rem;"></i>
          </div>
          <h1 class="section-title">Order <span class="text-accent">Confirmed!</span></h1>
          <p class="lead text-secondary">Thank you, <?= e($order['customer_name']) ?>. We're firing up the grill.</p>
        </div>

        <div class="p-4 rounded-4 text-start mb-4" style="background:var(--bg-2);border:1px solid var(--line)">
          <div class="d-flex justify-content-between border-bottom border-secondary-subtle pb-2 mb-2">
            <span class="text-secondary">Order Number</span>
            <span class="fw-bold">#<?= $orderId ?></span>
          </div>
          <div class="d-flex justify-content-between border-bottom border-secondary-subtle py-2 mb-2">
            <span class="text-secondary">Deliver to</span>
            <span class="fw-bold text-end" style="max-width: 200px;"><?= e($order['address']) ?></span>
          </div>
          <div class="d-flex justify-content-between border-bottom border-secondary-subtle py-2 mb-2">
            <span class="text-secondary">Total</span>
            <span class="fw-bold text-accent">$<?= number_format((float)$order['total'], 2) ?></span>
          </div>
          <div class="d-flex justify-content-between py-1">
            <span class="text-secondary">Payment Method</span>
            <span class="fw-bold">Cash on Delivery</span>
          </div>
        </div>

        <!-- The Account Creation Prompt -->
        <div class="p-4 rounded-4" style="background:linear-gradient(135deg,var(--bg-3),var(--bg-2));border:1px solid var(--accent);">
          <h4 class="h5 mb-2">Hungry for more?</h4>
          <p class="small text-secondary mb-4">
            Create an account to save this order to your history, track future deliveries, and check out in seconds next time.
          </p>
          <!-- Pre-fill register page with their details -->
          <a href="register.php?email=<?= urlencode($order['email']) ?>&name=<?= urlencode($order['customer_name']) ?>" class="btn btn-accent w-100 mb-2">
            Create an account
          </a>
          <a href="home.php" class="btn btn-outline-accent w-100">
            Continue browsing
          </a>
        </div>

      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>