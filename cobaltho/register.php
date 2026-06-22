<?php
require_once "includes/app.php";
require_once "includes/db.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '' || $email === '' || $password === '') {
        $error = "Completa todos los campos.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Ingresa un correo valido.";
    } elseif (strlen($password) < 6) {
        $error = "La contrasena debe tener minimo 6 caracteres.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $role = "customer";
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $hash, $role);

        if ($stmt->execute()) {
            session_regenerate_id(true);

            $_SESSION['user'] = [
                "id" => $stmt->insert_id,
                "name" => $name,
                "email" => $email,
                "role" => $role
            ];
            redirect_to("index.php");
        }

        $error = "Ese correo ya esta registrado.";
    }
}

include "includes/header.php";
?>

<section class="auth-page">
  <form method="post" class="auth-card">
    <?php echo csrf_field(); ?>
    <h2>Crear cuenta</h2>
    <?php if ($error): ?><p class="form-error"><?php echo e($error); ?></p><?php endif; ?>
    <input type="text" name="name" placeholder="Nombre" required>
    <input type="email" name="email" placeholder="Correo" required>
    <input type="password" name="password" placeholder="Contrasena" minlength="6" required>
    <button class="btn btn-carrito" type="submit">Registrarme</button>
    <a href="<?php echo app_url('login.php'); ?>">Ya tengo cuenta</a>
  </form>
</section>

<?php include "includes/footer.php"; ?>
