<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json; charset=utf-8');

$action = $_POST['action'] ?? $_GET['action'] ?? 'get';
$id     = isset($_POST['id'])  ? (int)$_POST['id']  : 0;
$qty    = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;

try {
    switch ($action) {
        case 'add':
            $stmt = $pdo->prepare('SELECT id FROM menu_items WHERE id = ? AND is_available = 1');
            $stmt->execute([$id]);
            if (!$stmt->fetch()) {
                echo json_encode(['ok' => false, 'error' => 'Item unavailable']);
                exit;
            }
            cart_add($id, $qty);
            break;

        case 'set':    cart_set($id, $qty); break;
        case 'remove': cart_remove($id);    break;
        case 'clear':  cart_clear();        break;
        case 'get':
        default:                            break;
    }

    $details = cart_details($pdo);
    echo json_encode([
        'ok'     => true,
        'items'  => $details['items'],
        'total'  => round($details['total'], 2),
        'count'  => cart_count(),
    ]);
} catch (Throwable $t) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Server error']);
}