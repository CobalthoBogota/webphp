<?php
require_once "includes/app.php";
require_once "includes/db.php";

require_post();
require_csrf();

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    redirect_to("index.php");
}

$stmt = $conn->prepare("SELECT id, name, price, stock FROM products WHERE id = ? AND active = 1 LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product || (int) $product['stock'] <= 0) {
    $_SESSION['message'] = "Producto agotado.";
    redirect_to("index.php");
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_SESSION['cart'][$id])) {
    if ($_SESSION['cart'][$id]['cantidad'] < (int) $product['stock']) {
        $_SESSION['cart'][$id]['cantidad']++;
        $_SESSION['message'] = "Producto anadido al carrito.";
    } else {
        $_SESSION['message'] = "No hay mas stock disponible.";
    }
} else {
    $_SESSION['cart'][$id] = [
        "id" => (int) $product['id'],
        "nombre" => $product['name'],
        "precio" => (int) $product['price'],
        "cantidad" => 1
    ];
    $_SESSION['message'] = "Producto anadido al carrito.";
}

redirect_to("index.php");
?>
