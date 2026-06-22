<?php
require_once __DIR__ . '/app.php';

function cart_items()
{
    return $_SESSION['cart'] ?? [];
}

function cart_count()
{
    $count = 0;

    foreach (cart_items() as $item) {
        $count += (int) ($item['cantidad'] ?? 1);
    }

    return $count;
}

function cart_total()
{
    $total = 0;

    foreach (cart_items() as $item) {
        $quantity = (int) ($item['cantidad'] ?? 1);
        $price = (int) ($item['precio'] ?? 0);
        $total += $quantity * $price;
    }

    return $total;
}

function cart_remove_url($item, $redirect = 'cart')
{
    if (!empty($item['id'])) {
        $query = 'id=' . urlencode((string) $item['id']);
    } else {
        $query = 'nombre=' . urlencode((string) ($item['nombre'] ?? ''));
    }

    if ($redirect !== 'cart') {
        $query .= '&redirect=' . urlencode($redirect);
    }

    $query .= '&csrf_token=' . urlencode(csrf_token());

    return app_url('remove_from_cart.php?' . $query);
}
?>
