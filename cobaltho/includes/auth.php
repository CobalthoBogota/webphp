<?php
require_once __DIR__ . "/app.php";

function current_user()
{
    return $_SESSION['user'] ?? null;
}

function is_logged_in()
{
    return !empty($_SESSION['user']);
}

function is_admin()
{
    return is_logged_in() && (($_SESSION['user']['role'] ?? '') === 'admin');
}

function require_login()
{
    if (!is_logged_in()) {
        redirect_to("login.php");
    }
}

function require_admin()
{
    if (!is_admin()) {
        redirect_to("login.php");
    }
}
?>
