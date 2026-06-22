<?php
require_once "includes/app.php";
require_csrf();
unset($_SESSION['user']);
redirect_to("index.php");
?>
