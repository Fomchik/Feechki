<?php
require_once 'config.php';
require_once 'auth_functions.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Используем функцию logoutUser для правильного выхода
logoutUser();

// Redirect to home page
header('Location: /');
exit();
?>
