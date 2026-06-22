<?php
require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/cart_helpers.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>COBALTHO</title>
  <link rel="stylesheet" href="<?php echo app_url('css/main.css'); ?>">
</head>

<body>

<header class="header">
  <div class="logo">COBALTHO</div>

  <nav>
    <a href="<?php echo app_url('index.php'); ?>">Inicio</a>
    <a href="<?php echo app_url('index.php#productos'); ?>">Tienda</a>
    <a href="#">Rinconcito</a>
    <a href="#">Contacto</a>
    <?php if (is_logged_in()): ?>
      <a href="<?php echo app_url('orders.php'); ?>">Mis pedidos</a>
      <?php if (is_admin()): ?>
        <a href="<?php echo app_url('admin.php'); ?>">Admin</a>
      <?php endif; ?>
      <a href="<?php echo app_url('logout.php?csrf_token=' . urlencode(csrf_token())); ?>">Salir</a>
    <?php else: ?>
      <a href="<?php echo app_url('login.php'); ?>">Ingresar</a>
    <?php endif; ?>
    <a href="#" class="cart-icon" onclick="openCart(); return false;">
      Carrito <span id="cart-count"><?php echo cart_count(); ?></span>
    </a>
  </nav>
</header>
