<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'About Us';
require __DIR__ . '/includes/header.php';
?>

<section class="pt-5 pb-4">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <span class="hero-eyebrow"><i class="bi bi-egg-fried"></i> Since 2019</span>
        <h1 class="section-title">We just wanted a <span class="text-accent">better burger.</span></h1>
        <p class="text-secondary">
          The Burger Shot started as a two-person stall with one flat-top grill and a
          stubborn belief that a burger should be simple, honest and unreasonably good.
        </p>
        <p class="text-secondary">
          Six years later we're still pressing every patty by hand, still baking our buns
          each morning, and still refusing to freeze anything. Along the way we picked up
          a pizza oven, a shawarma spit and a serious parfait habit — because our regulars
          asked nicely.
        </p>
        <div class="row g-3 mt-4">
          <?php
          $stats = [
              ['6+',    'Years grilling'],
              ['40k+',  'Burgers served'],
              ['16',    'Menu items'],
              ['4.8★',  'Average rating'],
          ];
          foreach ($stats as [$num, $label]): ?>
            <div class="col-6 col-md-3">
              <div class="stat-num"><?= $num ?></div>
              <div class="small text-secondary"><?= $label ?></div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="col-lg-6">
        <img class="about-img"
             src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=1000&q=80"
             alt="Inside The Burger Shot">
      </div>
    </div>
  </div>
</section>

<section class="section pt-0">
  <div class="container">
    <h2 class="section-title mb-4">What we <span class="text-accent">stand for</span></h2>
    <div class="row g-4">
      <?php
      $values = [
          ['bi-hand-thumbs-up', 'No shortcuts',   'Fresh beef, hand-pressed daily. Buns baked in-house every morning.'],
          ['bi-people',         'Made for people', 'Generous portions, fair prices and staff who remember your order.'],
          ['bi-recycle',        'Kinder packaging', 'Recyclable boxes and compostable cutlery on every delivery.'],
      ];
      foreach ($values as [$icon, $title, $text]): ?>
        <div class="col-md-4">
          <div class="p-4 h-100 rounded-4" style="background:var(--bg-2);border:1px solid var(--line)">
            <i class="bi <?= $icon ?> fs-2 text-accent"></i>
            <h3 class="h6 mt-3"><?= $title ?></h3>
            <p class="small text-secondary mb-0"><?= $text ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>