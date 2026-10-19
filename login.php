<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

if (is_logged_in()) { header('Location: home.php'); exit; }

$errors = [];
$email  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf'] ?? null)) {
        $errors[] = 'Your session expired. Please try again.';
    }

    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $errors[] = 'Please enter your email and password.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare('SELECT id, full_name, password_hash FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user_id']   = (int)$user['id'];
            flash('success', 'Welcome back, ' . explode(' ', $user['full_name'])[0] . '!');

            $redirect = $_SESSION['redirect_after_login'] ?? 'home.php';
            unset($_SESSION['redirect_after_login']);
            header('Location: ' . $redirect);
            exit;
        }
        $errors[] = 'Incorrect email or password.';
    }
}

$pageTitle = 'Sign in';
require __DIR__ . '/includes/header.php';
?>

<div class="auth-wrap">
  <div class="container">
    <div class="auth-card mx-auto">
      <h1 class="h3 mb-1">Welcome <span class="text-accent">back</span></h1>
      <p class="small text-secondary mb-4">Sign in to reorder your favourites.</p>

      <?php if ($errors): ?>
        <div class="alert alert-danger small mb-4"><?= e($errors[0]) ?></div>
      <?php endif; ?>

      <form method="post" novalidate>
        <input type="hidden" name="csrf" value="<?= csrf_token() ?>">

        <div class="mb-3">
          <label class="form-label" for="email">Email</label>
          <input type="email" class="form-control" id="email" name="email"
                 required value="<?= e($email) ?>" placeholder="you@example.com">
        </div>

        <div class="mb-4">
          <label class="form-label" for="password">Password</label>
          <input type="password" class="form-control" id="password" name="password"
                 required placeholder="Your password">
        </div>

        <button class="btn btn-accent w-100" type="submit">Sign in</button>
      </form>

      <p class="small text-secondary text-center mt-4 mb-0">
        New here? <a href="register.php" class="text-accent">Create an account</a>
      </p>
    </div>
  </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>