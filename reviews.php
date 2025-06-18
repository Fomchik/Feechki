<?php
require_once 'includes/config.php';
require_once 'includes/auth_functions.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

checkRememberCookie($pdo);
$isLoggedIn = is_logged_in();

function getInitial($username)
{
    return mb_substr($username, 0, 1, 'UTF-8');
}



function getAgeString($age)
{
    if ($age % 10 == 1 && $age % 100 != 11) return 'год';
    if (in_array($age % 10, [2, 3, 4]) && !in_array($age % 100, [12, 13, 14])) return 'года';
    return 'лет';
}

function getChildNameGenitive($name)
{
    $exceptions = [
        'Любовь' => 'Любви',
        'Илья' => 'Ильи',
        'Лев' => 'Льва',
        'Павел' => 'Павла',
        'Фома' => 'Фомы',
        'Никита' => 'Никиты',
        'Марк' => 'Марка',
    ];

    if (isset($exceptions[$name])) {
        return $exceptions[$name];
    }

    $lastChar = mb_substr($name, -1, 1, 'UTF-8');
    $prevChar = mb_substr($name, -2, 1, 'UTF-8');
    $base = mb_substr($name, 0, -1);

    if ($lastChar === 'а') {
        if (in_array($prevChar, ['ш', 'щ', 'ж', 'ч'])) {
            return $base . 'и';
        }
        return $base . 'ы';
    }

    if ($lastChar === 'я') {
        return $base . 'и';
    }

    if (in_array($lastChar, ['й', 'ь'])) {
        return $base . 'я';
    }

    return $name . 'а';
}

$userAvatar = null;
if ($isLoggedIn) {
    $userAvatar = getUserAvatar($_SESSION['user_id'], $pdo);
}


$ratingFilter = isset($_GET['rating']) && is_numeric($_GET['rating']) ? (int)$_GET['rating'] : null;
$serviceFilter  = isset($_GET['service']) && is_numeric($_GET['service']) ? (int)$_GET['service'] : null;

$where  = "WHERE r.status = 'approved' AND r.rating BETWEEN 1 AND 5";
$params = [];

if ($ratingFilter) {
    $where .= " AND r.rating = :rating";
    $params[':rating'] = $ratingFilter;
}

if ($serviceFilter) {
    $where .= " AND r.service_id = :service";
    $params[':service'] = $serviceFilter;
}

// Подсчёт общего количества отзывов с учетом фильтров
$sqlCount = "SELECT COUNT(*) FROM reviews r $where";
$stmtCount = $pdo->prepare($sqlCount);
foreach ($params as $key => $val) {
    $stmtCount->bindValue($key, $val);
}
$stmtCount->execute();
$totalFilteredReviews = (int)$stmtCount->fetchColumn();

// Подсчёт рейтингов с учетом фильтров
$ratingWhere = "WHERE r.status = 'approved' AND r.rating BETWEEN 1 AND 5";
$ratingParams = [];

if ($serviceFilter) {
    $ratingWhere .= " AND r.service_id = :service";
    $ratingParams[':service'] = $serviceFilter;
}

$sqlFilteredRatings = "
    SELECT rating, COUNT(*) as count 
    FROM reviews r
    $ratingWhere
    GROUP BY rating
";
$stmtRatings = $pdo->prepare($sqlFilteredRatings);
foreach ($ratingParams as $key => $val) {
    $stmtRatings->bindValue($key, $val);
}
$stmtRatings->execute();

$ratingCounts = [];
while ($row = $stmtRatings->fetch(PDO::FETCH_ASSOC)) {
    $ratingCounts[(int)$row['rating']] = (int)$row['count'];
}
$totalReviews = array_sum($ratingCounts);

// Получаем общее количество отзывов без учета фильтрации
$sqlAllCount = "SELECT COUNT(*) FROM reviews WHERE status = 'approved' AND rating BETWEEN 1 AND 5";
$totalAllReviews = (int)$pdo->query($sqlAllCount)->fetchColumn();

// Пагинация
$reviewsPerPage = 6;
$totalPages = $totalFilteredReviews > 0 ? ceil($totalFilteredReviews / $reviewsPerPage) : 1;
$page = isset($_GET['page']) ? max(1, min((int)$_GET['page'], $totalPages)) : 1;
$offset = ($page - 1) * $reviewsPerPage;

// Выборка отзывов
$sql = "
    SELECT 
        r.*, 
        u.username, 
        u.avatar_color,
        s.name AS service_name                                       
    FROM reviews r
    JOIN users u ON r.user_id = u.id
    LEFT JOIN services s ON r.service_id = s.id                      
    $where
    ORDER BY r.created_at DESC
    LIMIT :limit OFFSET :offset
";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limit',  $reviewsPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset,         PDO::PARAM_INT);
foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val);
}
$stmt->execute();
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Формирование списка сервисов (только из текущей выборки)
$sqlServices = "
    SELECT DISTINCT s.name 
    FROM services s
    JOIN reviews r ON r.service_id = s.id
    WHERE r.status = 'approved'
";
$stmtServices = $pdo->query($sqlServices);
$services = $stmtServices->fetchAll(PDO::FETCH_COLUMN);
sort($services);

// Средняя оценка
$averageRating = $totalReviews
    ? round(array_sum(array_map(fn($r, $c) => $r * $c, array_keys($ratingCounts), $ratingCounts)) / $totalReviews, 1)
    : 0;

$displayCount = $totalReviews >= 199
    ? ($totalReviews < 200 ? '100+' : $totalReviews)
    : $totalReviews;
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Отзывы родителей наших маленьких пациентов</title>
    <link rel="stylesheet" href="/assets/css/header.css">
    <link rel="stylesheet" href="/assets/css/reviews_style.css">
    <link rel="stylesheet" href="/assets/css/auth_modal.css">
    <link rel="stylesheet" href="/assets/css/user-dropdown.css">
    <link rel="stylesheet" href="/assets/css/footer_style.css">
</head>

<body>
    <?php include 'includes/header.php'; ?>
    <main>
        <section class="reviews-section">
            <div class="reviews-header">
                <h1 class="reviews-title">Озывы о нашей <br> клинике</h1>
                <p class="reviews-desc">Более <?php echo htmlspecialchars($totalAllReviews); ?> историй от родителей <br> наших маленьких пациентов</p>
            </div>
            <div class="fairy-img">
                <img src="/assets/images/fairy_2.png" alt="fairy_boy">
                <img src="/assets/images/fairy_1.png" alt="fairy_girl">
            </div>
            <div class="stats-reviews">
                <!-- Левая часть -->
                <div class="left-panel">
                    <div class="stars-rank">
                        <div class="average-rank"><?php echo htmlspecialchars($averageRating); ?></div>
                        <div class="count-rank"><?php echo htmlspecialchars($displayCount); ?> отзывов</div>
                    </div>
                    <div class="filter_reviews">
                        <select name="rating" id="rating-filter">
                            <option value="">Все оценки</option>
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <option value="<?php echo $i; ?>" <?php echo $ratingFilter == $i ? 'selected' : ''; ?>>
                                    <?php echo $i; ?> звезд<?php echo $i == 1 ? 'а' : ($i < 5 ? 'ы' : ''); ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                        <select name="service" id="service-filter">
                            <option value="">Выберите услугу</option>
                            <?php
                            // Получаем все услуги из таблицы services
                            $stmtAllServices = $pdo->query("SELECT id, name FROM services ORDER BY name");
                            $allServices = $stmtAllServices->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($allServices as $service): ?>
                                <option value="<?php echo $service['id']; ?>" <?php echo $serviceFilter === (int)$service['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($service['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <!-- Правая часть -->
                <div class="rank-star">
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <?php
                        $count = $ratingCounts[$i] ?? 0;
                        $percentage = $totalReviews ? ($count / $totalReviews) * 100 : 0;
                        ?>
                        <div class="rank-line">
                            <div class="star-label"><?php echo $i; ?></div>
                            <div class="line-bar">
                                <div class="fille-bar" style="width: <?php echo $percentage; ?>%;"></div>
                            </div>
                            <div class="star-count"><?php echo $count; ?></div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </section>
        <section class="reviews">
            <?php foreach ($reviews as $review): ?>
                <div class="review-card">
                    <!-- Аватар -->
                    <div class="avatar-review" style="background-color: <?= $review['avatar_color']; ?>;">
                        <?= getInitial($review['username']); ?>
                    </div>

                    <!-- Основной блок -->
                    <div class="review-body">
                        <!-- Хедер: имя, инфо о ребёнке и услуге -->
                        <div class="review-header">
                            <div class="user-info-group">
                                <div class="user-name">
                                    <?= htmlspecialchars($review['display_name'] ?? $review['username']); ?>
                                </div>
                                <?php if (
                                    $review['show_child_info'] &&
                                    $review['child_name'] &&
                                    $review['child_age'] &&
                                    $review['relation_to_child']
                                ): ?>
                                    <div class="child-info">
                                        <?= htmlspecialchars($review['relation_to_child']) . ' ' .
                                            getChildNameGenitive($review['child_name']) . ', ' .
                                            $review['child_age'] . ' ' . getAgeString($review['child_age']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if (!empty($review['service_name'])): ?>
                                <span class="review-service">
                                    <?= htmlspecialchars($review['service_name']); ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Текст отзыва -->
                        <div class="review-text">
                            "<?= htmlspecialchars($review['review_text']); ?>"
                        </div>

                        <!-- Футер: рейтинг и мета -->
                        <div class="review-footer">
                            <div class="rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="star <?= $i <= $review['rating'] ? 'filled' : ''; ?>">
                                        <img src="/assets/icons/star.svg" alt="Звезда" class="star-icon">
                                    </span>
                                <?php endfor; ?>
                            </div>
                            <div class="review-meta">
                                <span class="review-date"><?= date("d.m.y", strtotime($review['created_at'])); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>

        <!-- Пагинация -->
        <?php if (isset($totalPages) && $totalPages > 1): ?>
            <div class="pagination">
                <a href="?<?= htmlspecialchars(http_build_query(array_merge($_GET, ['page' => max(1, $page - 1)]))) ?>"
                    class="page-link <?= $page === 1 ? 'disabled' : '' ?> previous-page">
                    &#x276E;
                </a>
                <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                    <a href="?<?= htmlspecialchars(http_build_query(array_merge($_GET, ['page' => $p]))) ?>"
                        class="page-link <?= (int)$p === (int)$page ? 'active' : '' ?>">
                        <?= $p ?>
                    </a>
                <?php endfor; ?>
                <a href="?<?= htmlspecialchars(http_build_query(array_merge($_GET, ['page' => min($totalPages, $page + 1)]))) ?>"
                    class="page-link <?= $page === $totalPages ? 'disabled' : '' ?> next-page">
                    &#x276F;
                </a>
            </div>
        <?php endif; ?>

        <!-- Форма отзыва -->
        <div class="form-background">
            <?php if (isset($_SESSION['review_success']) || isset($_SESSION['review_error'])): ?>
                <div class="notification <?php echo isset($_SESSION['review_success']) ? 'notification-success' : 'notification-error'; ?>">
                    <?php if (isset($_SESSION['review_success'])): ?>
                        <span>Спасибо! Ваш отзыв отправлен и появится после модерации.</span>
                        <?php unset($_SESSION['review_success']); ?>
                    <?php elseif (isset($_SESSION['review_error'])): ?>
                        <span>Произошла ошибка при отправке отзыва. Пожалуйста, заполните все обязательные поля.</span>
                        <?php unset($_SESSION['review_error']); ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <section class="form-review">
            <h2>Написать отзыв</h2>
            <form action="includes/submit.php" method="POST" class="review-form">
                <label class="form-group">Ваше имя (для отображения)
                    <input type="text" name="display_name" required>
                </label>
                <div class="child-info-fields">
                    <label class="form-group-review">Имя ребенка
                        <input type="text" name="child_name">
                    </label>
                    <label class="form-group-review">Возраст ребенка
                        <input type="number" name="child_age" min="1" max="17">
                    </label>
                </div>
                <label class="form-group-review">Кем вы приходитесь ребенку
                    <select name="relation_to_child">
                        <option value="Мама">Мама</option>
                        <option value="Папа">Папа</option>
                    </select>
                </label>
                <label class="form-group-review">Услуга
                    <select name="service_id" required>
                        <option value="">выберите услугу</option>
                        <?php
                        $stmtSvc = $pdo->query("SELECT id, name FROM services ORDER BY name");
                        while ($svc = $stmtSvc->fetch(PDO::FETCH_ASSOC)):
                        ?>
                            <option value="<?= $svc['id'] ?>">
                                <?= htmlspecialchars($svc['name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </label>
                <label class="form-group-review">Оценка
                    <div class="rating-input">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <input type="radio" id="star-<?= $i ?>" name="rating" value="<?= $i ?>" required>
                            <label for="star-<?= $i ?>" title="<?= $i ?> звезда">
                                <img src="/assets/icons/star.svg" alt="<?= $i ?> звезда" />
                            </label>
                        <?php endfor; ?>
                    </div>
                </label>
                <label class="form-group-review">Отзыв
                    <textarea name="review_text" rows="4" required></textarea>
                </label>
                <label class="checkbox-group-review">
                    <input type="checkbox" name="show_child_info" value="1">
                    Показывать имя и возраст ребенка в отзыве
                </label>
                <div class="button-container">
                <button type="submit" class="btn-submit">Отправить отзыв</button>
                </div>
            </form>
        </section>
    </main>
    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/auth_modals.php'; ?>

    <script>
        window.isLoggedIn = <?= json_encode($isLoggedIn) ?>;
    </script>
    <script src="/assets/js/auth_modal.js"></script>
    <script src="/assets/js/user-dropdown.js"></script>
    <script src="/assets/js/reviews_ajax.js"></script>
</body>

</html>