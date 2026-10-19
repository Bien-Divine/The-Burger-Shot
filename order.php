<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

$items = $pdo->query(
    "SELECT * FROM menu_items WHERE is_available = 1
     ORDER BY FIELD(category,'burger','pizza','shawarma','spring-rolls','parfait'), name"
)->fetchAll();

// Group by category for section headings
$grouped = [];
foreach ($items as $it) $grouped[$it['category']][] = $it;

$labels = [
    'burger'       => 'Burgers',
    'pizza'        => 'Pizza',
    'shawarma'     => 'Shawarma',
    'spring-rolls' => 'Spring Rolls',
    'parfait'      => 'Parfaits',
];

$pageTitle = 'Order';
require __DIR__ . '/includes/header.php';
?>

<section class="pt-5 pb-3">
  <div class="container">
    <h1 class="section-title">Place your <span class="text-accent">order</span></h1>
    <p class="section-sub">Tap <strong>Add to bag</strong> on anything you like. Your bag saves as you browse.</p>
  </div>
</section>

<section class="pb-5">
  <div class="container">
    <?php foreach ($grouped as $cat => $list): ?>
      <div class="mb-5">
        <h2 class="h4 mb-3"><span class="text-accent">/</span> <?= e($labels[$cat] ?? ucfirst($cat)) ?></h2>
        <div class="row g-3 g-md-4">
          <?php foreach ($list as $item) echo render_menu_card($item); ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- sticky order bar (mobile only) -->
<div class="order-bar d-lg-none" id="orderBar" hidden>
  <div>
    <div class="small text-secondary"><span data-cart-count>0</span> item(s)</div>
    <div class="fw-bold text-accent" id="barTotal">$0.00</div>
  </div>
  <button class="btn btn-accent" data-bs-toggle="offcanvas" data-bs-target="#cartDrawer">
    Review order <i class="bi bi-arrow-right"></i>
  </button>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>