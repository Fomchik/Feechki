<?php
// Запускаем сессию
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Подключаем необходимые файлы
require_once 'config.php';
require_once 'auth_functions.php';

// Определяем, какое действие нужно выполнить
$action = $_POST['action'] ?? '';
$response = ['success' => false, 'message' => 'Неизвестное действие'];

if ($action === 'register') {
    // Валидация данных
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    // Простые проверки
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        $response = ['success' => false, 'message' => 'Все поля обязательны для заполнения'];
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response = ['success' => false, 'message' => 'Введите корректный email'];
    } elseif (strlen($password) < 6) {
        $response = ['success' => false, 'message' => 'Пароль должен содержать не менее 6 символов'];
    } elseif ($password !== $confirmPassword) {
        $response = ['success' => false, 'message' => 'Пароли не совпадают'];
    } else {
        // Регистрация пользователя
        $response = registerUser($username, $email, $password, $pdo);
    }
} elseif ($action === 'login') {
    // Валидация данных
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']) && $_POST['remember'] == '1';
    
    if (empty($login) || empty($password)) {
        $response = ['success' => false, 'message' => 'Все поля обязательны для заполнения'];
    } else {
        // Авторизация пользователя
        $response = loginUser($login, $password, $remember, $pdo);
    }
} elseif ($action === 'logout') {
    // Выход пользователя
    logoutUser();
    $response = ['success' => true, 'message' => 'Вы успешно вышли из системы'];
}

// Определяем, как отвечать - AJAX или редирект
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

if ($isAjax) {
    // Если запрос через AJAX, отправляем JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Иначе делаем редирект
    if ($response['success']) {
        // Если операция успешна, редиректим на главную
        header('Location: /');
    } else {
        // Иначе возвращаем на страницу с ошибкой
        $_SESSION['auth_error'] = $response['message'];
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}
exit;
?>