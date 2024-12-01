<?php
session_start();

unset($_SESSION['user']);

if (isset($_COOKIE['remember_me_email'])) {
    setcookie('remember_me_email', '', time() - 3600, "/");
}

if (isset($_COOKIE['remember_me_password'])) {
    setcookie('remember_me_password', '', time() - 3600, "/");
}

session_destroy();

header('Location: login.php');
exit();
