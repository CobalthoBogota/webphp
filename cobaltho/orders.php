<?php
require_once "includes/auth.php";
require_once "includes/db.php";

require_login();

$userId = (int) $_SESSION['user']['id'];
$stmt = $conn->prepare("SELECT id, total, status, created_at FROM orders WHERE user_id = ? ORDER BY id DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$orders = $stmt->get_result();

include "includes/header.php";
?>

<section class="productos">
  <h2>Mis pedidos</h2>

  <?php if ($orders->num_rows === 0): ?>
    <p>No tienes pedidos todavia.</p>
  <?php else: ?>
    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Pedido</th>
            <th>Estado</th>
            <th>Total</th>
            <th>Fecha</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($order = $orders->fetch_assoc()): ?>
            <tr>
              <td>#<?php echo $order['id']; ?></td>
              <td><?php echo e($order['status']); ?></td>
              <td><?php echo money($order['total']); ?></td>
              <td><?php echo e($order['created_at']); ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</section>

<?php include "includes/footer.php"; ?>
