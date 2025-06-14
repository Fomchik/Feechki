<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'config.php';
require_once 'auth_functions.php';

checkRememberCookie($pdo);
$isLoggedIn = is_logged_in();

$userAvatar = null;
if ($isLoggedIn) {
    $userAvatar = getUserAvatar($_SESSION['user_id'], $pdo);
}

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
<link rel="stylesheet" href="/assets/css/auth_modal.css">
<link rel="stylesheet" href="/assets/css/user-dropdown.css">

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
                <li class="nav-item <?php echo isActive('reviews.php'); ?>"><a href="/reviews.php">Отзывы</a></li>
                <li class="nav-item <?php echo isActive('services.php'); ?>"><a href="/services.php">Услуги</a></li>
                <li class="nav-item <?php echo isActive('about.php'); ?>"><a href="/about.php">О нас</a></li>
                <li class="nav-item <?php echo isActive('contacts.php'); ?>"><a href="/contacts.php">Контакты</a></li>
                  <!-- Иконка пользователя -->
                  <li class="nav-item avatar">
                    <?php if ($isLoggedIn): ?>
                        <div class="user-menu">
                            <div class="user-menu__trigger">
                                <div class="avatar-circle" style="background-color: <?= $userAvatar['color'] ?>;">
                                    <?= $userAvatar['initial'] ?>
                                </div>
                            </div>                            
                            <div class="user-dropdown">
                                <div class="user-dropdown__header">
                                    <div class="user-info">
                                        <span class="user-name">
                                            <?= htmlspecialchars($_SESSION['first_name'] ?? '') ?> 
                                            <?= htmlspecialchars($_SESSION['last_name'] ?? '') ?>
                                        </span>
                                        <span class="user-email"><?= htmlspecialchars($_SESSION['email'] ?? '') ?></span>
                                    </div>
                                </div>
                                <div class="user-dropdown__menu">
                                    <a href="/profile.php">Профиль</a>
                                    <a href="/records.php">Записи</a>
                                    <a href="/settings.php">Настройки</a>
                                    <a href="/includes/logout.php" class="logout-link">Выход</a>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                    <a href="#" class="login-button" id="openLoginModal">
                      <img class="user-icon" src="/assets/icons/user.svg" alt="Пользователь">
                    </a>
                    <?php endif; ?>
                  </li>
            </ul>
        </nav>
                <div>
                <li class="nav-item <?php echo isActive('signup.php'); ?>"><a href="/signup.php" class="btn primary-nav"><img src="/assets/icons/calendar.svg" alt="Календарь"> Записаться на прием</a></li>
                </div>
    </div>
</header>

<!-- Подключение скрипта для header (бургер-меню) -->
<script src="/assets/js/header.js"></script>