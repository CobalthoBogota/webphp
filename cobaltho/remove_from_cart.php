<?php
require_once "includes/cart_helpers.php";

$id = $_GET['id'] ?? null;
$nombre = $_GET['nombre'] ?? '';
$redirect = $_GET['redirect'] ?? 'cart';
$isAjax = isset($_GET['ajax']) && $_GET['ajax'] === '1';

if (!csrf_is_valid($_GET['csrf_token'] ?? '')) {
    if ($isAjax) {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['ok' => false, 'message' => 'Solicitud no valida.']);
        exit;
    }

    redirect_to($redirect === 'index' ? 'index.php?cart=open' : 'cart.php');
}

if ($id !== null && isset($_SESSION['cart'][$id])) {
    unset($_SESSION['cart'][$id]);
} elseif (isset($_SESSION['cart'][$nombre])) {
    unset($_SESSION['cart'][$nombre]);
}

// reindex opcional (limpieza)
$_SESSION['cart'] = array_filter($_SESSION['cart'] ?? []);

if ($isAjax) {
    header('Content-Type: application/json');
    echo json_encode([
        'ok' => true,
        'count' => cart_count(),
        'total' => cart_total(),
        'empty' => empty($_SESSION['cart'])
    ]);
    exit;
}

if ($redirect === 'index') {
    redirect_to("index.php?cart=open");
} else {
    redirect_to("cart.php");
}
?>
