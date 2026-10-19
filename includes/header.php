<?php
if (!isset($pdo)) {
    require_once __DIR__ . '/../config/db.php';
}
require_once __DIR__ . '/functions.php';

$user      = current_user($pdo);
$pageTitle = $pageTitle ?? 'The Burger Shot';
$current   = basename($_SERVER['SCRIPT_NAME']);
$navItems  = [
    'index.php'  => 'Home',
    'home.php'   => 'Menu',
    'order.php'  => 'Order',
    'about.php'  => 'About Us',
];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle) ?> — The Burger Shot</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top bs-navbar">
  <div class="container">
    <a class="navbar-brand" href="index.php">THE BURGER <span class="text-accent">SHOT</span></a>

    <div class="d-flex align-items-center gap-2 order-lg-3">
      <button class="btn cart-btn" type="button"
              data-bs-toggle="offcanvas" data-bs-target="#cartDrawer" aria-label="Open bag">
        <i class="bi bi-bag fs-5"></i>
        <span class="cart-badge" data-cart-count>0</span>
      </button>
      <button class="navbar-toggler border-0" type="button"
              data-bs-toggle="collapse" data-bs-target="#mainNav" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>

    <div class="collapse navbar-collapse order-lg-2" id="mainNav">
      <ul class="navbar-nav mx-auto">
        <?php foreach ($navItems as $file => $label): ?>
          <li class="nav-item">
            <a class="nav-link <?= $current === $file ? 'active' : '' ?>" href="<?= $file ?>"><?= $label ?></a>
          </li>
        <?php endforeach; ?>
      </ul>

      <div class="d-flex flex-column flex-lg-row gap-2 mt-3 mt-lg-0">
        <?php if ($user): ?>
          <div class="dropdown">
            <button class="btn btn-outline-accent dropdown-toggle w-100" data-bs-toggle="dropdown">
              <i class="bi bi-person-circle"></i> <?= e(explode(' ', $user['full_name'])[0]) ?>
            </button>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
              <li><a class="dropdown-item" href="orders.php"><i class="bi bi-receipt"></i> My Orders</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="logout.php"><i class="bi bi-box-arrow-right"></i> Sign out</a></li>
            </ul>
          </div>
        <?php else: ?>
          <a href="login.php" class="btn btn-outline-accent">Sign in</a>
          <a href="register.php" class="btn btn-accent">Create account</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<?php if ($msg = flash('success')): ?>
  <div class="container mt-3"><div class="alert alert-success alert-dismissible fade show">
    <?= e($msg) ?><button class="btn-close" data-bs-dismiss="alert"></button>
  </div></div>
<?php endif; ?>
<?php if ($msg = flash('info')): ?>
  <div class="container mt-3"><div class="alert alert-info alert-dismissible fade show">
    <?= e($msg) ?><button class="btn-close" data-bs-dismiss="alert"></button>
  </div></div>
<?php endif; ?>