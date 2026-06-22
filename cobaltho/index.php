<?php
require_once "includes/cart_helpers.php";
require_once "includes/db.php";
include("includes/header.php");

if (!empty($_SESSION['message'])) {
    echo '<div class="alerta-carrito">' . e($_SESSION['message']) . '</div>';
    unset($_SESSION['message']);
}

$productos = $conn->query("SELECT id, name, description, price, stock, image FROM products WHERE active = 1 ORDER BY id DESC");
?>

<section class="hero">
  <div class="hero-content">
    <h1 class="hero-logo">COBALTHO BISUTERIA</h1>
    <p>Bisuteria moderna y accesorios en Bogota</p>

    <div class="hero-buttons">
      <a href="<?php echo app_url('index.php#productos'); ?>" class="btn-primary">Ver tienda</a>
      <a href="https://wa.me/573157083477" class="btn-secondary">WhatsApp</a>
    </div>
  </div>
</section>

<section class="categorias">
  <div class="cat">Bisuteria</div>
  <div class="cat">El Rinconcito</div>
  <div class="cat">Regalos</div>
  <div class="cat">Nuevos</div>
</section>

<section class="productos" id="productos">
  <h2>Productos destacados</h2>

  <div class="grid-productos">
    <?php while ($p = $productos->fetch_assoc()): ?>
      <div class="producto">
        <div class="producto-img">
          <img src="<?php echo e($p['image']); ?>" alt="<?php echo e($p['name']); ?>">
        </div>

        <h3><?php echo e($p['name']); ?></h3>

        <p class="precio">
          <?php echo money($p['price']); ?>
        </p>
        <p class="stock-label">Stock: <?php echo (int) $p['stock']; ?></p>

        <div class="botones-producto">
          <?php if ((int) $p['stock'] > 0): ?>
            <form method="post" action="<?php echo app_url('add_to_cart.php?id=' . urlencode($p['id'])); ?>" class="inline-form">
              <?php echo csrf_field(); ?>
              <button type="submit" class="btn btn-carrito">Anadir al carrito</button>
            </form>
          <?php else: ?>
            <span class="btn btn-disabled">Agotado</span>
          <?php endif; ?>

          <a href="https://wa.me/573157083477?text=Estoy%20interesado%20en%20<?php echo urlencode($p['name']); ?>" class="btn btn-whatsapp">
            WhatsApp
          </a>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</section>

<section class="confianza">
  <div>Envios en Bogota</div>
  <div>Pago por WhatsApp</div>
  <div>Atencion rapida</div>
</section>

<section class="rinconcito">
  <h2>El Rinconcito</h2>
  <p>Linea infantil de COBALTHO</p>
</section>

<?php include "includes/cart_sidebar.php"; ?>

<?php if (isset($_GET['cart']) && $_GET['cart'] === 'open'): ?>
  <script>
    window.addEventListener('DOMContentLoaded', openCart);
  </script>
<?php endif; ?>

<?php include("includes/footer.php"); ?>
