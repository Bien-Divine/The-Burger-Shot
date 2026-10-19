<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

if (is_logged_in()) { header('Location: home.php'); exit; }

$errors = [];
$old = ['full_name' => '', 'email' => '', 'phone' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf'] ?? null)) {
        $errors[] = 'Your session expired. Please try again.';
    }

    $old['full_name'] = trim($_POST['full_name'] ?? '');
    $old['email']     = trim($_POST['email'] ?? '');
    $old['phone']     = trim($_POST['phone'] ?? '');
    $password         = $_POST['password'] ?? '';
    $confirm          = $_POST['confirm']  ?? '';

    if (mb_strlen($old['full_name']) < 2)                       $errors[] = 'Please enter your full name.';
    if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL))      $errors[] = 'Please enter a valid email address.';
    if (strlen($password) < 8)                                  $errors[] = 'Password must be at least 8 characters.';
    if ($password !== $confirm)                                 $errors[] = 'Passwords do not match.';

    if (!$errors) {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$old['email']]);

        if ($stmt->fetch()) {
            $errors[] = 'An account with that email already exists.';
        } else {
            $stmt = $pdo->prepare(
                'INSERT INTO users (full_name, email, phone, password_hash) VALUES (?,?,?,?)'
            );
            $stmt->execute([
                $old['full_name'],
                $old['email'],
                $old['phone'] ?: null,
                password_hash($password, PASSWORD_DEFAULT),
            ]);

            $_SESSION['user_id'] = (int)$pdo->lastInsertId();
            flash('success', 'Welcome to The Burger Shot, ' . $old['full_name'] . '!');
            header('Location: ' . ($_SESSION['redirect_after_login'] ?? 'home.php'));
            unset($_SESSION['redirect_after_login']);
            exit;
        }
    }
}

$pageTitle = 'Create account';
require __DIR__ . '/includes/header.php';
?>

<div class="auth-wrap">
  <div class="container">
    <div class="auth-card mx-auto">
      <h1 class="h3 mb-1">Create your <span class="text-accent">account</span></h1>
      <p class="small text-secondary mb-4">Save your details and reorder in two taps.</p>

      <?php if ($errors): ?>
        <div class="alert alert-danger">
          <ul class="mb-0 ps-3 small">
            <?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form method="post" novalidate>
        <input type="hidden" name="csrf" value="<?= csrf_token() ?>">

        <div class="mb-3">
          <label class="form-label" for="full_name">Full name</label>
          <input class="form-control" id="full_name" name="full_name" required
                 value="<?= e($old['full_name']) ?>" placeholder="Ada Obi">
        </div>

        <div class="mb-3">
          <label class="form-label" for="email">Email</label>
          <input type="email" class="form-control" id="email" name="email" required
                 value="<?= e($old['email']) ?>" placeholder="you@example.com">
        </div>

        <div class="mb-3">
          <label class="form-label" for="phone">Phone <span class="text-secondary">(optional)</span></label>
          <input class="form-control" id="phone" name="phone"
                 value="<?= e($old['phone']) ?>" placeholder="+234 800 000 0000">
        </div>

        <div class="row g-3 mb-4">
          <div class="col-sm-6">
            <label class="form-label" for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password"
                   required minlength="8" placeholder="Min. 8 characters">
          </div>
          <div class="col-sm-6">
            <label class="form-label" for="confirm">Confirm password</label>
            <input type="password" class="form-control" id="confirm" name="confirm"
                   required minlength="8" placeholder="Repeat password">
          </div>
        </div>

        <button class="btn btn-accent w-100" type="submit">Create account</button>
      </form>

      <p class="small text-secondary text-center mt-4 mb-0">
        Already have an account? <a href="login.php" class="text-accent">Sign in</a>
      </p>
    </div>
  </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>