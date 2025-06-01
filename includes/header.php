<?php
// Проверка активной страницы для подсветки пункта меню
$current_page = basename($_SERVER['PHP_SELF']);

// Функция для определения активного пункта меню
function isActive($page) {
    global $current_page;
    return $current_page == $page ? 'active' : '';
}
?>

<!-- Подключение стилей для header -->
<link rel="stylesheet" href="/assets/css/header.css">

<!-- Шапка сайта -->
<header class="site-header">
    <div class="container header-container">
        <!-- Логотип и название -->
        <div class="logo-flex">
            <img src="/assets/icons/logo.svg" alt="Логотип" class="logo-img">
            <div class="logo-block">
                <span class="logo-text">Феечки улыбок</span>
                <span class="logo-desc">Детская стоматология</span>
            </div>
        </div>
        <!-- Навигационное меню -->
        <nav class="main-nav" aria-label="Главное меню">
            <button class="nav-toggle" aria-label="Открыть меню">
                <!-- Иконка бургера (три полоски) -->
                <span></span><span></span><span></span>
            </button>
            <ul class="nav-list">
                <li class="nav-item <?php echo isActive('index.php'); ?>"><a href="/index.php">Главная</a></li>
                <li class="nav-item <?php echo isActive('about.php'); ?>"><a href="/about.php">О нас</a></li>
                <li class="nav-item <?php echo isActive('services.php'); ?>"><a href="/services.php">Услуги</a></li>
                <li class="nav-item <?php echo isActive('signup.php'); ?>"><a href="/signup.php">Записаться</a></li>
                <li class="nav-item <?php echo isActive('contacts.php'); ?>"><a href="/contacts.php">Контакты</a></li>
                  <!-- Иконка пользователя -->
                  <li class="nav-item">
                    <a href="/login.php">
                      <img class="user-icon" src="/assets/icons/user.svg" alt="Пользователь">
                    </a>
                  </li>
            </ul>
        </nav>
    </div>
</header>

<!-- Подключение скрипта для header (бургер-меню) -->
<script src="/assets/js/header.js"></script>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>