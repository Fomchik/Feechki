<?php
header('Content-Type: application/json');

// Основная директория с изображениями
$baseDir = __DIR__ . '/../assets/images/';

// Категории и соответствующие папки
$categories = [
    'all' => ['interior', 'patients', 'team', 'equipment'],
    'interior' => ['interior'],
    'patients' => ['patients'],
    'team' => ['team'],
    'equipment' => ['equipment']
];

// Создаем массив для отладочной информации
$debug = [
    'base_dir' => $baseDir,
    'categories_exist' => [],
    'files_found' => []
];

// Массив для хранения информации об изображениях
$images = [];

// Поддерживаемые форматы файлов
$supportedFormats = ['jpg', 'jpeg', 'png', 'webp'];

// Заполнение массива изображениями из всех категорий
foreach ($categories['all'] as $category) {
    $dir = $baseDir . $category;
    $debug['categories_exist'][$category] = is_dir($dir);
    
    if (is_dir($dir)) {
        $files = scandir($dir);
        $debug['files_found'][$category] = [];
        
        foreach ($files as $file) {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            
            if (in_array($ext, $supportedFormats) && !is_dir($dir . '/' . $file)) {
                $images[] = [
                    'path' => '/assets/images/' . $category . '/' . $file, 
                    'category' => $category,
                    'filename' => $file,
                    'alt' => ucfirst(pathinfo($file, PATHINFO_FILENAME))
                ];
                $debug['files_found'][$category][] = $file;
            }
        }
    }
}

// Выводим результаты и отладочную информацию
echo json_encode([
    'images' => $images,
    'debug' => $debug
]);
?>