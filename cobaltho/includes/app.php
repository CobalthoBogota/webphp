<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function app_base_url()
{
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $base = str_replace('\\', '/', dirname($scriptName));

    if ($base === '/' || $base === '.') {
        return '';
    }

    return rtrim($base, '/');
}

function app_url($path = '')
{
    $path = ltrim((string) $path, '/');
    $base = app_base_url();

    return $path === '' ? ($base . '/') : ($base . '/' . $path);
}

function redirect_to($path)
{
    header('Location: ' . app_url($path));
    exit;
}

function is_post()
{
    return ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST';
}

function csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_field()
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function csrf_is_valid($token)
{
    return is_string($token) && hash_equals($_SESSION['csrf_token'] ?? '', $token);
}

function require_csrf()
{
    $token = $_POST['csrf_token'] ?? $_GET['csrf_token'] ?? '';

    if (!csrf_is_valid($token)) {
        http_response_code(403);
        exit('Solicitud no valida.');
    }
}

function require_post()
{
    if (!is_post()) {
        redirect_to('index.php');
    }
}

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function money($value)
{
    return '$' . number_format((int) $value, 0, ',', '.');
}
?>
