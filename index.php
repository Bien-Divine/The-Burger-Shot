<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

$featured = $pdo->query(
    "SELECT * FROM menu_items WHERE is_available = 1
     ORDER BY FIELD(category,'burger','pizza','shawarma','spring-rolls','parfait'), RAND()
     LIMIT 8"
)->fetchAll();

$pageTitle = 'Home';
require __DIR__ . '/includes/header.php';
?>

<!-- ============ HERO ============ -->
<section class="hero">
  <div class="container">
    <div class="row">
      <div class="col-lg-8">
        <span class="hero-eyebrow"><i class="bi bi-fire"></i> Fresh off the grill</span>
        <h1 class="display-1">SMASHED.<br>GRILLED.<br><span class="text-accent">DEVOURED.</span></h1>
        <p class="lead fs-5 mb-4">
          Hand-pressed patties, toasted brioche and sauces we refuse to share the recipe for.
          Plus pizza, shawarma, spring rolls and parfaits — because not everyone wants a burger.
          <em>(But most people do.)</em>
        </p>
        <div class="d-flex flex-wrap gap-3">
          <a href="order.php" class="btn btn-accent btn-lg"><i class="bi bi-bag-check"></i> Order now</a>
          <a href="home.php" class="btn btn-outline-accent btn-lg">See the menu</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============ FEATURED ============ -->
<section class="section">
  <div class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
      <div>
        <h2 class="section-title">Crowd <span class="text-accent">favourites</span></h2>
        <p class="section-sub mb-0">The stuff people come back for. Every single time.</p>
      </div>
      <a href="home.php" class="btn btn-outline-accent">Full menu <i class="bi bi-arrow-right"></i></a>
    </div>
    <div class="row g-3 g-md-4">
      <?php foreach ($featured as $item) echo render_menu_card($item); ?>
    </div>
  </div>
</section>

<!-- ============ WHY US ============ -->
<section class="section pt-0">
  <div class="container">
    <div class="row g-4">
      <?php
      $perks = [
          ['bi-fire',        'Flame-grilled',  'Real fire, real smoke, real flavour. No shortcuts.'],
          ['bi-stopwatch',   'Under 20 min',   'Cooked to order and out the door in a flash.'],
          ['bi-truck',       'Fast delivery',  'Hot food at your door across the whole city.'],
          ['bi-shield-check','Fresh daily',    'Buns baked each morning, produce delivered at dawn.'],
      ];
      foreach ($perks as [$icon, $title, $text]): ?>
        <div class="col-6 col-lg-3">
          <div class="p-4 h-100 rounded-4" style="background:var(--bg-2);border:1px solid var(--line)">
            <i class="bi <?= $icon ?> fs-2 text-accent"></i>
            <h3 class="h6 mt-3 mb-1"><?= $title ?></h3>
            <p class="small text-secondary mb-0"><?= $text ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ CTA ============ -->
<section class="section pt-0">
  <div class="container">
    <div class="p-4 p-md-5 rounded-4 text-center position-relative overflow-hidden"
         style="background:linear-gradient(135deg,var(--bg-3),var(--bg-2));border:1px solid var(--line)">
      <h2 class="section-title mb-2">Hungry <span class="text-accent">yet?</span></h2>
      <p class="section-sub mx-auto mb-4">Create an account, build your bag and we'll handle the rest.</p>
      <div class="d-flex flex-wrap justify-content-center gap-3">
        <a href="register.php" class="btn btn-accent btn-lg">Create account</a>
        <a href="order.php" class="btn btn-outline-accent btn-lg">Start ordering</a>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>