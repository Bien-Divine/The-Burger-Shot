<?php
declare(strict_types=1);

/* ---------- Output / security ---------- */
function e($v): string {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

function csrf_token(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function verify_csrf(?string $token): bool {
    return !empty($_SESSION['csrf']) && is_string($token)
        && hash_equals($_SESSION['csrf'], $token);
}

/* ---------- Flash messages ---------- */
function flash(string $key, ?string $msg = null): ?string {
    if ($msg !== null) { $_SESSION['flash'][$key] = $msg; return null; }
    $m = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $m;
}

/* ---------- Auth ---------- */
function is_logged_in(): bool {
    return isset($_SESSION['user_id']);
}

function current_user(PDO $pdo): ?array {
    static $user = null;
    if (!is_logged_in()) return null;
    if ($user === null) {
        $stmt = $pdo->prepare('SELECT id, full_name, email, phone FROM users WHERE id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch() ?: null;
    }
    return $user;
}

function require_login(): void {
    if (!is_logged_in()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? 'home.php';
        flash('info', 'Please sign in to continue.');
        header('Location: login.php');
        exit;
    }
}

/* ---------- Session cart ---------- */
function cart(): array { return $_SESSION['cart'] ?? []; }

function cart_add(int $id, int $qty = 1): void {
    $qty = max(1, $qty);
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;
}

function cart_set(int $id, int $qty): void {
    if ($qty <= 0) { unset($_SESSION['cart'][$id]); return; }
    $_SESSION['cart'][$id] = $qty;
}

function cart_remove(int $id): void { unset($_SESSION['cart'][$id]); }
function cart_clear(): void { $_SESSION['cart'] = []; }
function cart_count(): int { return array_sum(cart()); }

function cart_details(PDO $pdo): array {
    $cart = cart();
    if (!$cart) return ['items' => [], 'total' => 0.0];

    $ids  = array_keys($cart);
    $in   = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare(
        "SELECT id, name, price, image_url FROM menu_items
         WHERE id IN ($in) AND is_available = 1"
    );
    $stmt->execute($ids);

    $items = [];
    $total = 0.0;
    foreach ($stmt->fetchAll() as $row) {
        $qty        = (int)$cart[$row['id']];
        $line       = (float)$row['price'] * $qty;
        $total     += $line;
        $items[]    = [
            'id'         => (int)$row['id'],
            'name'       => $row['name'],
            'price'      => (float)$row['price'],
            'image_url'  => $row['image_url'],
            'qty'        => $qty,
            'line_total' => $line,
        ];
    }
    return ['items' => $items, 'total' => $total];
}

/* ---------- Reusable menu card ---------- */
function render_menu_card(array $item, bool $showAdd = true): string {
    $id    = (int)$item['id'];
    $name  = e($item['name']);
    $desc  = e($item['description'] ?? '');
    $img   = e($item['image_url']);
    $cat   = e(ucwords(str_replace('-', ' ', $item['category'])));
    $price = '$' . number_format((float)$item['price'], 2);

    ob_start(); ?>
    <div class="col-6 col-lg-4 col-xl-3">
      <article class="menu-card">
        <div class="thumb">
          <img src="<?= $img ?>" alt="<?= $name ?>" loading="lazy">
          <span class="badge-cat"><?= $cat ?></span>
        </div>
        <div class="body">
          <h3 class="h6 mb-1"><?= $name ?></h3>
          <p class="small text-secondary flex-grow-1 mb-2"><?= $desc ?></p>
          <div class="d-flex align-items-center justify-content-between gap-2">
            <span class="price"><?= $price ?></span>
          </div>
          <?php if ($showAdd): ?>
            <button class="btn btn-accent btn-sm w-100 mt-2" data-add-to-cart="<?= $id ?>">
              <i class="bi bi-bag-plus"></i> Add to bag
            </button>
          <?php endif; ?>
        </div>
      </article>
    </div>
    <?php return (string)ob_get_clean();
}