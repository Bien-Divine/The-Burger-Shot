<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

// NO LONGER REQUIRES LOGIN!

$details = cart_details($pdo);
if (!$details['items']) {
    flash('info', 'Your bag is empty — add something tasty first.');
    header('Location: order.php');
    exit;
}

// Get user data if they are logged in, otherwise null
$user   = is_logged_in() ? current_user($pdo) : null;
$errors = [];
$old    = [
    'customer_name' => $user['full_name'] ?? '',
    'email'         => $user['email'] ?? '',
    'phone'         => $user['phone'] ?? '',
    'address'       => '',
    'notes'         => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf'] ?? null)) {
        $errors[] = 'Your session expired. Please try again.';
    }

    foreach ($old as $key => $_) {
        $old[$key] = trim($_POST[$key] ?? '');
    }

    if (mb_strlen($old['customer_name']) < 2)  $errors[] = 'Please enter your name.';
    if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email address.';
    if (mb_strlen($old['phone']) < 7)          $errors[] = 'Please enter a valid phone number.';
    if (mb_strlen($old['address']) < 8)        $errors[] = 'Please enter a delivery address.';

    if (!$errors) {
        try {
            $pdo->beginTransaction();

            // Insert order (user_id can be NULL for guests)
            $stmt = $pdo->prepare(
                'INSERT INTO orders (user_id, email, customer_name, phone, address, notes, total)
                 VALUES (?,?,?,?,?,?,?)'
            );
            $stmt->execute([
                is_logged_in() ? $_SESSION['user_id'] : null,
                $old['email'],
                $old['customer_name'],
                $old['phone'],
                $old['address'],
                $old['notes'] ?: null,
                round($details['total'], 2),
            ]);
            $orderId = (int)$pdo->lastInsertId();

            $ins = $pdo->prepare(
                'INSERT INTO order_items (order_id, menu_item_id, item_name, unit_price, quantity)
                 VALUES (?,?,?,?,?)'
            );
            foreach ($details['items'] as $it) {
                $ins->execute([$orderId, $it['id'], $it['name'], $it['price'], $it['qty']]);
            }

            $pdo->commit();
            cart_clear();

            // Redirect based on login status
            if (is_logged_in()) {
                flash('success', "Order #{$orderId} placed! We're firing up the grill.");
                header('Location: orders.php');
            } else {
                // Store the order ID in session so the success page can read it
                $_SESSION['last_guest_order'] = $orderId;
                header('Location: order-success.php');
            }
            exit;
        } catch (Throwable $t) {
            $pdo->rollBack();
            $errors[] = 'Could not place your order. Please try again.';
        }
    }
}

$pageTitle = 'Checkout';
require __DIR__ . '/includes/header.php';
?>

<section class="pt-5 pb-3">
  <div class="container">
    <h1 class="section-title">Check<span class="text-accent">out</span></h1>
    <?php if (!is_logged_in()): ?>
      <div class="alert alert-info d-inline-block py-2 mt-2">
        <i class="bi bi-info-circle"></i> You are checking out as a guest. 
        <a href="login.php" class="alert-link">Sign in</a> for faster checkout next time.
      </div>
    <?php endif; ?>
  </div>
</section>

<section class="pb-5">
  <div class="container">
    <?php if ($errors): ?>
      <div class="alert alert-danger">
        <ul class="mb-0 ps-3 small">
          <?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <div class="row g-4">
      <div class="col-lg-7">
        <form method="post" class="p-4 rounded-4" style="background:var(--bg-2);border:1px solid var(--line)" novalidate>
          <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
          <h2 class="h6 mb-3 text-uppercase" style="letter-spacing:1.5px">Delivery details</h2>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label" for="customer_name">Full name</label>
              <input class="form-control" id="customer_name" name="customer_name" required value="<?= e($old['customer_name']) ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label" for="email">Email</label>
              <input type="email" class="form-control" id="email" name="email" required value="<?= e($old['email']) ?>" placeholder="For your receipt">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label" for="phone">Phone number</label>
            <input class="form-control" id="phone" name="phone" required value="<?= e($old['phone']) ?>" placeholder="+234 800 000 0000">
          </div>

          <div class="mb-3">
            <label class="form-label" for="address">Delivery address</label>
            <textarea class="form-control" id="address" name="address" rows="3" required placeholder="Street, area, city, landmark"><?= e($old['address']) ?></textarea>
          </div>

          <div class="mb-4">
            <label class="form-label" for="notes">Notes for the kitchen <span class="text-secondary">(optional)</span></label>
            <input class="form-control" id="notes" name="notes" value="<?= e($old['notes']) ?>" placeholder="No onions, extra sauce…">
          </div>

          <button class="btn btn-accent w-100 btn-lg" type="submit">
            Place order · $<?= number_format($details['total'], 2) ?>
          </button>
        </form>
      </div>

      <div class="col-lg-5">
        <div class="p-4 rounded-4" style="background:var(--bg-2);border:1px solid var(--line)">
          <h2 class="h6 mb-3 text-uppercase" style="letter-spacing:1.5px">Your order</h2>
          <?php foreach ($details['items'] as $it): ?>
            <div class="d-flex justify-content-between align-items-start py-2 border-bottom border-secondary-subtle">
              <div>
                <div class="small fw-semibold"><?= e($it['name']) ?></div>
                <div class="small text-secondary"><?= $it['qty'] ?> × $<?= number_format($it['price'], 2) ?></div>
              </div>
              <div class="small fw-bold">$<?= number_format($it['line_total'], 2) ?></div>
            </div>
          <?php endforeach; ?>
          <div class="d-flex justify-content-between fs-5 fw-bold mt-3">
            <span>Total</span>
            <span class="text-accent">$<?= number_format($details['total'], 2) ?></span>
          </div>
          <p class="small text-secondary mt-2 mb-0">
            <i class="bi bi-cash-coin"></i> Pay on delivery.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>