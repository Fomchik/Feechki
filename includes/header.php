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
                <li class="nav-item <?php echo isActive('about.php'); ?>"><a href="/about.php">О нас</a></li>
                <li class="nav-item <?php echo isActive('services.php'); ?>"><a href="/services.php">Услуги</a></li>
                <li class="nav-item <?php echo isActive('signup.php'); ?>"><a href="/signup.php">Записаться</a></li>
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
    
<!-- Мольное окно -->
 <div id="loginModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Вход</h2>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
            <form id= "loginForm" method="post" action="includes/auth_handler.php">
                <input type='hidden' name='action' value='login'>
                <div class="form-group">
                    <label for="login">Ваш email или логин</label>
                    <input type="text" id="login" name="login" required>
                </div>
                <div class='form-group'>
                     <label for="login_password">Ваш пароль</label>
                     <input type="password" id="login_password" name="password" required>
                </div>
                <div class="form-group remember-me">
                    <input type="checkbox" id="remember" name="remember" value="1">
                    <label for="remember">Запомнить меня</label>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn primary">Войти</button>
                </div>
                <div class="form-footer">
                    <p>
                        Не зарегистрированы? 
                        <a href="#" id="showRegisterModal">Зарегистрироваться</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
 </div>
 <!-- Мольное окно регистрации -->
  <div id="registerModal" class="modal">
    <div class="modal-content">
         <div class="modal-header">
            <h2>Регистрация</h2>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
            <form id="registerForm" method="post" action="auth_handler.php">
                <input type="hidden" name="action" value="register">
        <div class="form-group">
            <label for="username">Ваш логин</label>
            <input type="text" id="username" name="username" required>
        </div>
         <div class="form-group">
                    <label for="email">Ваш email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                                <div class="form-group">
                    <label for="register_password">Ваш пароль</label>
                    <input type="password" id="register_password" name="password" required>
                </div>
                 <div class="form-group">
                    <label for="confirm_password">Повторите пароль</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                                <div class="form-actions">
                    <button type="submit" class="btn primary">Регистрация</button>
                </div>
                  <div class="form-footer">
                    <p>
                        Вы уже зарегистрированы? 
                        <a href="#" id="showLoginModal">Тогда войдите</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
    <script src="/assets/js/auth_modal.js"></script>
</body>
</html>