<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

$categories = [
    'all'          => 'All',
    'burger'       => 'Burgers',
    'pizza'        => 'Pizza',
    'shawarma'     => 'Shawarma',
    'spring-rolls' => 'Spring Rolls',
    'parfait'      => 'Parfaits',
];

$active = $_GET['category'] ?? 'all';
if (!array_key_exists($active, $categories)) $active = 'all';

if ($active === 'all') {
    $items = $pdo->query(
        "SELECT * FROM menu_items WHERE is_available = 1
         ORDER BY FIELD(category,'burger','pizza','shawarma','spring-rolls','parfait'), name"
    )->fetchAll();
} else {
    $stmt = $pdo->prepare(
        'SELECT * FROM menu_items WHERE is_available = 1 AND category = ? ORDER BY name'
    );
    $stmt->execute([$active]);
    $items = $stmt->fetchAll();
}

$pageTitle = 'Menu';
require __DIR__ . '/includes/header.php';
?>

<section class="pt-5 pb-4">
  <div class="container">
    <h1 class="section-title">The <span class="text-accent">Menu</span></h1>
    <p class="section-sub">Burgers, pizza, shawarma, spring rolls and parfaits — all made fresh.</p>
  </div>
</section>

<section class="pb-5">
  <div class="container">
    <div class="d-flex gap-2 overflow-auto pb-2 mb-4">
      <?php foreach ($categories as $key => $label): ?>
        <a href="home.php?category=<?= urlencode($key) ?>"
           class="pill <?= $active === $key ? 'active' : '' ?>"><?= e($label) ?></a>
      <?php endforeach; ?>
    </div>

    <?php if (!$items): ?>
      <p class="text-secondary">Nothing here yet — check back soon.</p>
    <?php else: ?>
      <div class="row g-3 g-md-4">
        <?php foreach ($items as $item) echo render_menu_card($item); ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>