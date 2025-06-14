<?php 
require_once 'includes/config.php';
require_once 'includes/auth_functions.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

checkRememberCookie($pdo);
$isLoggedIn = is_logged_in();

function getInitial($username) {
    return mb_substr($username, 0, 1, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Услуги стоматологической клиники "Феечки улыбок"</title>
    <link rel="stylesheet" href="/assets/css/header.css">
    <link rel="stylesheet" href="/assets/css/services_style.css">
    <link rel="stylesheet" href="/assets/css/auth_modal.css">
    <link rel="stylesheet" href="/assets/css/user-dropdown.css">
    <link rel="stylesheet" href="/assets/css/footer_style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main>
        <!-- Здесь должен быть контент страницы услуг -->
        <section class="services-hero">
            <div class="container">
                <h1>Наши услуги</h1>
                <p class="subtitle">Качественное лечение для здоровья зубов ваших детей</p>
            </div>
        </section>
        
        <!-- Здесь вставьте остальной контент страницы услуг -->
    </main>
    
    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/auth_modals.php'; ?>
    
    <script src="/assets/js/auth_modal.js"></script>
</body>
</html>