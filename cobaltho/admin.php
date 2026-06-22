<?php
require_once "includes/auth.php";
require_once "includes/db.php";

require_admin();

$products = $conn->query("SELECT id, name, price, stock, active FROM products ORDER BY id DESC");
$orders = $conn->query("SELECT o.id, o.total, o.status, o.created_at, u.name AS customer FROM orders o JOIN users u ON u.id = o.user_id ORDER BY o.id DESC LIMIT 20");

include "includes/header.php";
?>

<section class="productos admin-page">
  <div class="admin-heading">
    <h2>Panel admin</h2>
    <a class="btn btn-carrito" href="<?php echo app_url('admin_product.php'); ?>">Subir producto</a>
  </div>

  <h3>Productos</h3>
  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Producto</th>
          <th>Precio</th>
          <th>Stock</th>
          <th>Estado</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php while ($product = $products->fetch_assoc()): ?>
          <tr>
            <td><?php echo e($product['name']); ?></td>
            <td><?php echo money($product['price']); ?></td>
            <td><?php echo $product['stock']; ?></td>
            <td><?php echo $product['active'] ? 'Activo' : 'Oculto'; ?></td>
            <td><a href="<?php echo app_url('admin_product.php?id=' . urlencode($product['id'])); ?>">Editar</a></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <h3>Pedidos recientes</h3>
  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Pedido</th>
          <th>Cliente</th>
          <th>Estado</th>
          <th>Total</th>
          <th>Fecha</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($order = $orders->fetch_assoc()): ?>
          <tr>
            <td>#<?php echo $order['id']; ?></td>
            <td><?php echo e($order['customer']); ?></td>
            <td><?php echo e($order['status']); ?></td>
            <td><?php echo money($order['total']); ?></td>
            <td><?php echo e($order['created_at']); ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</section>

<?php include "includes/footer.php"; ?>
