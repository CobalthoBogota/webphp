<?php
require_once "includes/cart_helpers.php";
include "includes/header.php";

$cart = cart_items();
?>

<section class="productos">
  <h2>Tu carrito</h2>

  <?php if (empty($cart)): ?>
    <p>Tu carrito esta vacio.</p>
  <?php else: ?>
    <?php foreach ($cart as $item): ?>
      <?php
        $cantidad = (int) ($item['cantidad'] ?? 1);
        $precio = (int) ($item['precio'] ?? 0);
        $subtotal = $precio * $cantidad;
        $nombre = $item['nombre'] ?? 'Producto';
      ?>

      <div class="producto">
        <h3><?php echo e($nombre); ?> x<?php echo $cantidad; ?></h3>
        <p><?php echo money($subtotal); ?></p>

        <a
          href="<?php echo cart_remove_url($item); ?>"
          class="btn btn-whatsapp"
          onclick="return confirm('Seguro que quieres eliminar <?php echo e($nombre); ?> del carrito?');"
        >
          Eliminar
        </a>
      </div>
    <?php endforeach; ?>

    <h3>Total: <?php echo money(cart_total()); ?></h3>

    <form method="post" action="<?php echo app_url('checkout.php'); ?>" class="inline-form">
      <?php echo csrf_field(); ?>
      <button type="submit" class="btn btn-carrito">Finalizar compra</button>
    </form>
  <?php endif; ?>
</section>

<?php include "includes/footer.php"; ?>
