<?php
require_once __DIR__ . '/cart_helpers.php';

$cart = cart_items();
$total = cart_total();

// Generar el mensaje de WhatsApp con los productos
$mensaje = "Hola! Me interesa hacer esta compra:%0A%0A";
foreach ($cart as $item) {
    $cantidad = (int) ($item['cantidad'] ?? 1);
    $precio = (int) ($item['precio'] ?? 0);
    $nombre = $item['nombre'] ?? 'Producto';
    $mensaje .= "• " . urlencode($nombre) . " x" . $cantidad . " = " . number_format($precio * $cantidad, 0, ',', '.') . "%0A";
}
$mensaje .= "%0ATotal: $" . number_format($total, 0, ',', '.') . "%0A%0A¿Cuál es el siguiente paso?";

// Número de WhatsApp (sin + ni espacios)
$whatsapp_number = "573157083477";
$whatsapp_url = "https://wa.me/" . $whatsapp_number . "?text=" . $mensaje;
?>

<div id="cartSidebar" class="cart-sidebar">
  <div class="cart-header">
    <h2>Tu carrito</h2>
    <button type="button" onclick="closeCart()" class="close-btn" aria-label="Cerrar carrito">x</button>
  </div>

  <div class="cart-body">
    <?php if (!empty($cart)): ?>
      <?php foreach ($cart as $item): ?>
        <?php
          $cantidad = (int) ($item['cantidad'] ?? 1);
          $precio = (int) ($item['precio'] ?? 0);
          $subtotal = $cantidad * $precio;
          $nombre = $item['nombre'] ?? 'Producto';
        ?>

        <div class="cart-item" data-cart-item>
          <div>
            <p><?php echo e($nombre); ?></p>
            <small><?php echo $cantidad; ?> x <?php echo money($precio); ?></small>
          </div>

          <div class="cart-item-actions">
            <strong><?php echo money($subtotal); ?></strong>
            <a
              href="<?php echo cart_remove_url($item, 'index'); ?>"
              class="remove-cart-item"
              data-remove-cart-item
              data-product-name="<?php echo e($nombre); ?>"
              onclick="return confirm('Seguro que quieres eliminar <?php echo e($nombre); ?> del carrito?');"
              aria-label="Eliminar <?php echo e($nombre); ?>"
            >
              Eliminar
            </a>
          </div>
        </div>
      <?php endforeach; ?>

      <div class="cart-total" data-cart-total>
        <span>Total</span>
        <strong data-cart-total-value><?php echo money(cart_total()); ?></strong>
      </div>

      <div class="cart-buttons">
        <a class="btn btn-carrito" href="<?php echo app_url('cart.php'); ?>" data-cart-checkout>
          Ver carrito completo
        </a>
        
        <a class="btn btn-whatsapp-cart" href="<?php echo $whatsapp_url; ?>" target="_blank" rel="noopener noreferrer">
          📱 Comprar por WhatsApp
        </a>
      </div>
    <?php else: ?>
      <p class="cart-empty">Tu carrito esta vacio</p>
    <?php endif; ?>
  </div>
</div>

<div id="cartOverlay" class="cart-overlay" onclick="closeCart()"></div>
