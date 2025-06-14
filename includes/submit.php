<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth_functions.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!is_logged_in()) {
    header('Location: /reviews.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Валидация и сбор данных
$display_name = trim($_POST['display_name'] ?? '');
$child_name = trim($_POST['child_name'] ?? '');
$child_age = isset($_POST['child_age']) ? (int)$_POST['child_age'] : null;
$relation_to_child = trim($_POST['relation_to_child'] ?? '');
$service_id = isset($_POST['service_id']) ? (int)$_POST['service_id'] : null;
$rating = isset($_POST['rating']) ? (int)$_POST['rating'] : null;
$review_text = trim($_POST['review_text'] ?? '');
$show_child_info = isset($_POST['show_child_info']) ? 1 : 0;

// Простая валидация
if (!$display_name || !$service_id || !$rating || !$review_text) {
    $_SESSION['review_error'] = true;
    header('Location: /reviews.php');
    exit;
}

// Получаем название услуги
$service_name = null;
if ($service_id) {
    $stmt = $pdo->prepare('SELECT name FROM services WHERE id = ?');
    $stmt->execute([$service_id]);
    $service_name = $stmt->fetchColumn();
}

// Сохраняем отзыв
$stmt = $pdo->prepare('INSERT INTO reviews (user_id, rating, review_text, child_name, child_age, relation_to_child, show_child_info, display_name, status, created_at, service_id, service_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?)');
$stmt->execute([
    $user_id,
    $rating,
    $review_text,
    $child_name ?: null,
    $child_age ?: null,
    $relation_to_child ?: null,
    $show_child_info,
    $display_name,
    'pending', 
    $service_id,
    $service_name
]);

$_SESSION['review_success'] = true;
header('Location: /reviews.php');
exit;