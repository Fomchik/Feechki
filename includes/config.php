<?php
// Проверяем наличие драйвера PDO MySQL
if (!extension_loaded('pdo_mysql')) {
    die('PDO MySQL драйвер не установлен. Пожалуйста, установите расширение pdo_mysql в php.ini');
}

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'feechki_db';

try {
    // Проверяем существование базы данных
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Проверяем существование базы данных
    $stmt = $pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbname'");
    if (!$stmt->fetch()) {
        die("База данных '$dbname' не существует. Пожалуйста, создайте базу данных.");
    }
    
    // Подключаемся к конкретной базе данных
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    // Выводим подробную информацию об ошибке
    die("Ошибка подключения к базе данных: " . $e->getMessage() . 
        "\nКод ошибки: " . $e->getCode() . 
        "\nПроверьте:\n" .
        "1. Запущен ли MySQL сервер\n" .
        "2. Правильность данных для подключения\n" .
        "3. Существует ли база данных '$dbname'");
}
?>
