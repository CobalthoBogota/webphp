<?php
require_once "includes/cart_helpers.php";
require_once "includes/auth.php";
require_once "includes/db.php";

require_login();
require_post();
require_csrf();

$cart = cart_items();

if (empty($cart)) {
    $_SESSION['message'] = "Tu carrito esta vacio.";
    redirect_to("index.php");
}

$conn->begin_transaction();

try {
    $total = 0;
    $items = [];

    foreach ($cart as $key => $item) {
        $productId = (int) ($item['id'] ?? $key);
        $quantity = (int) ($item['cantidad'] ?? 1);

        $stmt = $conn->prepare("SELECT id, name, price, stock FROM products WHERE id = ? AND active = 1 FOR UPDATE");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $product = $stmt->get_result()->fetch_assoc();

        if (!$product || $product['stock'] < $quantity) {
            throw new Exception("No hay stock suficiente para " . ($item['nombre'] ?? 'un producto') . ".");
        }

        $subtotal = $product['price'] * $quantity;
        $total += $subtotal;
        $items[] = [$product, $quantity, $subtotal];
    }

    $userId = (int) $_SESSION['user']['id'];
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)");
    $stmt->bind_param("ii", $userId, $total);
    $stmt->execute();
    $orderId = $stmt->insert_id;

    $itemStmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, price, quantity, subtotal) VALUES (?, ?, ?, ?, ?, ?)");
    $stockStmt = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");

    foreach ($items as $entry) {
        [$product, $quantity, $subtotal] = $entry;
        $productId = (int) $product['id'];
        $name = $product['name'];
        $price = (int) $product['price'];

        $itemStmt->bind_param("iisiii", $orderId, $productId, $name, $price, $quantity, $subtotal);
        $itemStmt->execute();

        $stockStmt->bind_param("ii", $quantity, $productId);
        $stockStmt->execute();
    }

    $conn->commit();
    unset($_SESSION['cart']);
    $_SESSION['message'] = "Pedido creado correctamente.";
    redirect_to("orders.php");
} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['message'] = $e->getMessage();
    redirect_to("cart.php");
}
?>
