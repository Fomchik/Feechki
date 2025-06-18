<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

 //Проверяет, авторизован ли пользователь
 
function is_logged_in() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}


 //Генерирует случайный цвет аватара из заданных вариантов

function generateAvatarColor() {
    $colors = [
        '#6c4ade', // фиолетовый
        '#4cd964', // зеленый
        '#5ac8fa'  // синий
    ];
    
    return $colors[array_rand($colors)];
}


 //Получает информацию об аватаре пользователя

function getUserAvatar($user_id, $pdo) {
    try {
        // Проверка существования пользователя
        $stmt = $pdo->prepare('SELECT username, avatar_color FROM users WHERE id = :id');
        $stmt->execute(['id' => $user_id]);
        $user = $stmt->fetch();
        
        if ($user) {
            $initial = strtoupper(substr($user['username'], 0, 1));
            return [
                'initial' => $initial,
                'color' => $user['avatar_color']
            ];
        }
    } catch (PDOException $e) {
        // Логирование ошибки
        error_log("Ошибка получения аватара: " . $e->getMessage());
    }
    
    return [
        'initial' => '?',
        'color' => '#6c4ade'
    ];
}

//Регистрирует нового пользователя

function registerUser($username, $email, $password, $pdo) {
    try {
        // Проверка существования пользователя
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email OR username = :username');
        $stmt->execute(['email' => $email, 'username' => $username]);
        $existingUser = $stmt->fetch();
        
        if ($existingUser) {
            if ($existingUser['email'] === $email) {
                return ['success' => false, 'message' => 'Пользователь с таким email уже существует'];
            } else {
                return ['success' => false, 'message' => 'Пользователь с таким логином уже существует'];
            }
        }
        
        // Хеширование пароля
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Генерация цвета для аватара
        $avatarColor = generateAvatarColor();
        
        // Добавление пользователя в БД
        $stmt = $pdo->prepare('INSERT INTO users (username, email, password, avatar_color, registered_at) VALUES (:username, :email, :password, :avatar_color, NOW())');
        $stmt->execute([
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword,
            'avatar_color' => $avatarColor
        ]);
        
        $userId = $pdo->lastInsertId();
        
        // Авторизуем пользователя
        $_SESSION['user_id'] = $userId;
        $_SESSION['username'] = $username;
        
        return [
            'success' => true,
            'message' => 'Вы успешно зарегистрировались',
            'user_id' => $userId
        ];
    } catch (PDOException $e) {
        // Логирование ошибки
        error_log("Ошибка регистрации: " . $e->getMessage());
        return ['success' => false, 'message' => 'Произошла ошибка при регистрации'];
    }
}

 //Авторизует пользователя

function loginUser($login, $password, $remember, $pdo) {
    try {
        // Ищем пользователя по логину или email
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :login OR email = :login');
        $stmt->execute(['login' => $login]);
        $user = $stmt->fetch();
        
        if (!$user || !password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Неверный логин или пароль'];
        }
        
        // Авторизуем пользователя в сессии
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        
        // Если выбрана опция "Запомнить меня"
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $hash = password_hash($token, PASSWORD_DEFAULT);
            
            // Сохраняем токен в БД
            $stmt = $pdo->prepare('UPDATE users SET remember_token = :token WHERE id = :id');
            $stmt->execute([
                'token' => $hash,
                'id' => $user['id']
            ]);
            
            // Устанавливаем куки на 30 дней
            setcookie('remember_user', $user['id'] . ':' . $token, time() + 60*60*24*30, '/', '', false, true);
        }
        
        return ['success' => true];
        
    } catch (PDOException $e) {
        // Логирование ошибки
        error_log("Ошибка авторизации: " . $e->getMessage());
        return ['success' => false, 'message' => 'Произошла ошибка при авторизации'];
    }
}
//Выход пользователя из системы

function logoutUser() {
    // Очищаем сессию
    $_SESSION = [];
    
    //Удаяем сессию
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    session_destroy();
    
    // Удаляем куку "Запомнить меня"
    if (isset($_COOKIE['remember_user'])) {
        setcookie('remember_user', '', time() - 3600, '/');
    }
}

//Проверяет куку "Запомнить меня" и авторизует пользователя

function checkRememberCookie($pdo) {
    // Если пользователь уже авторизован, ничего не делаем
    if (is_logged_in()) {
        return;
    }
    
    if (isset($_COOKIE['remember_user'])) {
        list($userId, $token) = explode(':', $_COOKIE['remember_user']);
        
        try {
            $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
            $stmt->execute(['id' => $userId]);
            $user = $stmt->fetch();
            
            if ($user && $user['remember_token'] && password_verify($token, $user['remember_token'])) {
                // Авторизуем пользователя
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                // Обновляем токен для безопасности
                $newToken = bin2hex(random_bytes(32));
                $hash = password_hash($newToken, PASSWORD_DEFAULT);
                
                $stmt = $pdo->prepare('UPDATE users SET remember_token = :token WHERE id = :id');
                $stmt->execute([
                    'token' => $hash,
                    'id' => $user['id']
                ]);
                
                // Обновляем куку
                setcookie('remember_user', $user['id'] . ':' . $newToken, time() + 60*60*24*30, '/', '', false, true);
            }
        } catch (PDOException $e) {
            // Логирование ошибки
            error_log("Ошибка проверки куки: " . $e->getMessage());
        }
    }
}
?>