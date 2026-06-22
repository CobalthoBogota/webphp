<?php
require_once "includes/app.php";
require_once "includes/db.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $user = null;

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
    }

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);

        $_SESSION['user'] = [
            "id" => $user['id'],
            "name" => $user['name'],
            "email" => $user['email'],
            "role" => $user['role']
        ];
        redirect_to("index.php");
    }

    $error = "Correo o contrasena incorrectos.";
}

include "includes/header.php";
?>

<section class="auth-page">
  <form method="post" class="auth-card">
    <?php echo csrf_field(); ?>
    <h2>Ingresar</h2>
    <?php if ($error): ?><p class="form-error"><?php echo e($error); ?></p><?php endif; ?>
    <input type="email" name="email" placeholder="Correo" required>
    <input type="password" name="password" placeholder="Contrasena" required>
    <button class="btn btn-carrito" type="submit">Entrar</button>
    <a href="<?php echo app_url('register.php'); ?>">Crear cuenta</a>
  </form>
</section>

<?php include "includes/footer.php"; ?>
