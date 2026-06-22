<?php
require_once "includes/auth.php";
require_once "includes/db.php";

require_admin();

$id = (int) ($_GET['id'] ?? 0);
$product = [
    "name" => "",
    "description" => "",
    "price" => 0,
    "stock" => 0,
    "image" => "img/producto1.jpg",
    "active" => 1
];
$error = "";

if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $found = $stmt->get_result()->fetch_assoc();

    if ($found) {
        $product = $found;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();

    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = (int) ($_POST['price'] ?? 0);
    $stock = (int) ($_POST['stock'] ?? 0);
    $image = trim($_POST['image'] ?? 'img/producto1.jpg');
    $active = isset($_POST['active']) ? 1 : 0;

    if ($image === '') {
        $image = 'img/producto1.jpg';
    }

    if ($name === '') {
        $error = "El nombre del producto es obligatorio.";
    } elseif ($price < 0 || $stock < 0) {
        $error = "Precio y stock no pueden ser negativos.";
    } else {
        if ($id > 0) {
            $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock = ?, image = ?, active = ? WHERE id = ?");
            $stmt->bind_param("ssiisii", $name, $description, $price, $stock, $image, $active, $id);
            $stmt->execute();
        } else {
            $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock, image, active) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssiisi", $name, $description, $price, $stock, $image, $active);
            $stmt->execute();
        }

        redirect_to("admin.php");
    }

    $product = [
        "name" => $name,
        "description" => $description,
        "price" => $price,
        "stock" => $stock,
        "image" => $image,
        "active" => $active
    ];
}

include "includes/header.php";
?>

<section class="auth-page">
  <form method="post" class="auth-card product-form">
    <?php echo csrf_field(); ?>
    <h2><?php echo $id > 0 ? 'Editar producto' : 'Subir producto'; ?></h2>
    <?php if ($error): ?><p class="form-error"><?php echo e($error); ?></p><?php endif; ?>
    <input type="text" name="name" placeholder="Nombre" value="<?php echo e($product['name']); ?>" required>
    <textarea name="description" placeholder="Descripcion"><?php echo e($product['description']); ?></textarea>
    <input type="number" name="price" placeholder="Precio" value="<?php echo (int) $product['price']; ?>" min="0" required>
    <input type="number" name="stock" placeholder="Stock" value="<?php echo (int) $product['stock']; ?>" min="0" required>
    <input type="text" name="image" placeholder="Ruta de imagen" value="<?php echo e($product['image']); ?>">
    <label class="checkbox-row">
      <input type="checkbox" name="active" <?php echo $product['active'] ? 'checked' : ''; ?>>
      Producto visible
    </label>
    <button class="btn btn-carrito" type="submit">Guardar</button>
    <a href="<?php echo app_url('admin.php'); ?>">Volver al panel</a>
  </form>
</section>

<?php include "includes/footer.php"; ?>
