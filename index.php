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
    if ($age % 10 == 1 && $age % 100 != 11) {
        return 'год';
    } elseif (($age % 10 >= 2 && $age % 10 <= 4) && !($age % 100 >= 12 && $age % 100 <= 14)) {
        return 'года';
    } else {
        return 'лет';
    }
}

function getReviews($pdo, $limit = 1)
{
    try {
        $stmt = $pdo->prepare("
         SELECT r.*, u.username, u.avatar_color 
         FROM reviews r 
         JOIN users u ON r.user_id = u.id
         WHERE r.status = 'approved'
         ORDER BY r.created_at DESC 
         LIMIT :limit
         ");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}
$reviews = getReviews($pdo, 1);
$review = $reviews[0] ?? null;

$sliderReviews = getReviews($pdo, 10);

/**
 * Функция для склонения имени ребенка в родительный падеж
 * @param string $name - имя ребенка
 * @return string - имя в родительном падеже
 */
function getChildNameGenitive($name)
{
    // Правила для женских имен
    $female_endings = [
        'а' => 'и', // Маша -> Маши
        'я' => 'и', // Катя -> Кати
        'ь' => 'и', // любовь -> любви
        'а' => 'ы',  // Диана -> Дианы
    ];

    // Правила для мужских имен
    $male_endings = [
        'й' => 'я', // Дмитрий -> Дмитрия
        'ь' => 'я', // Игорь -> Игоря
        '' => 'а',  // Максим -> Максима
        'а' => 'ы', // Дима -> Димы
    ];

    $name = trim($name);
    $last_char = mb_substr($name, -1, 1, 'UTF-8');
    $prelast_char = mb_strlen($name, 'UTF-8') > 1 ? mb_substr($name, -2, 1, 'UTF-8') : '';

    // Исключения
    $exceptions = [
        'Любовь' => 'Любви',
        'Илья' => 'Ильи',
        'Лев' => 'Льва',
    ];

    if (isset($exceptions[$name])) {
        return $exceptions[$name];
    }

    // Применяем правила склонения
    if (isset($female_endings[$last_char])) {
        return mb_substr($name, 0, -1, 'UTF-8') . $female_endings[$last_char];
    } elseif (in_array($last_char, ['й', 'ь']) || (mb_strtolower($last_char, 'UTF-8') == $last_char)) {
        if (isset($male_endings[$last_char])) {
            return mb_substr($name, 0, -1, 'UTF-8') . $male_endings[$last_char];
        } else {
            return $name . $male_endings[''];
        }
    }

    // Если правило не найдено, возвращаем имя без изменений
    return $name;
}
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Феечки улыбок - детская стоматологическая клиника</title>
    <link rel="stylesheet" href="/assets/css/home_style.css">
    <link rel="stylesheet" href="/assets/css/header.css">
    <link rel="stylesheet" href="/assets/css/auth_modal.css">
    <link rel="stylesheet" href="/assets/css/user-dropdown.css">
    <link rel="stylesheet" href="assets/css/footer_style.css">
</head>

<body>
    <?php include 'includes/header.php'; ?>
    <main>
        <section class="welcome-section">
            <div class="welcome-text">
                <h1>Добро пожаловать в <br> <span class="name-logo-text">"Феечки улыбок"</span></h1>
                <p class="welcome-desc">Детская стоматология, где каждый визит <br> превращается в <span class="text-viol">волшебное приключение</span>.
                    <br> Мы заботимся о здоровье улыбок ваших <br> малышей с любовью и профессионализмом.
                </p>
            </div>

            <!-- Отзыв -->
            <div class="reviews">
                <?php if ($review): ?>
                    <div class="reviews-card">
                        <div class="avatar" style='background-color: <?php echo  $review['avatar_color']; ?>'>
                            <?php echo getInitial($review['username']); ?>
                        </div>

                        <div class="rating">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <span class="star <?php echo ($i <= $review['rating']) ? 'filled' : ''; ?>">
                                    <img src="/assets/icons/star.svg" alt="Звезда" class="star-icon">
                                </span>
                            <?php endfor; ?>
                        </div>
                        <div class="review-text">
                            "<?php echo htmlspecialchars($review['review_text']); ?>"</div>
                        <div class="user-info-review">
                            <div class="user-name"><?php echo htmlspecialchars($review['display_name'] ?? $review['username']); ?></div>
                            <?php if ($review['show_child_info'] && $review['child_name'] && $review['child_age'] && $review['relation_to_child']): ?>
                                <div class="child-info">
                                    <?php
                                    $relation = htmlspecialchars($review['relation_to_child']);
                                    $child_name = htmlspecialchars($review['child_name']);
                                    $child_genitive = getChildNameGenitive($child_name);
                                    $child_age = $review['child_age'];
                                    $age_string = getAgeString($child_age);
                                    echo "$relation $child_genitive, $child_age $age_string";
                                    ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="no-reviews">
                        <p>У нас пока нет отзывов. Будьте первым, кто поделится впечатлениями!</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <hr>
        <!-- Преимущества -->
        <section class="advantages-section">
            <div class="plus">
                <ul class="advantages-list">
                    <li>Без боли и страха</li>
                    <li>Игровая зона</li>
                    <li>Опытные врачи</li>
                </ul>
            </div>
        </section>

        <!-- Кнопки -->
        <section class="info-section">
            <div class="buttons">
                <a href="/signup.php" class="calendar">
                    <img src="/assets/icons/calendar.svg" alt="Календарь"> Записаться на прием</a>
                <a href="/services.php" class="service-btn">
                    <img src="/assets/icons/cosmos-star.svg" alt="Звезда">Услуги</a>
            </div>
            <div class="number-schedule">
                <img src="/assets/icons/phone.svg" alt="Телефон">
                <span class="phone-number">+7 (987) 654-32-10</span>
                <img src="/assets/icons/viol-clock.svg" alt="Часы">
                <p class="working-hours">Будни: 9-21, выходные: 10-18</p>
            </div>
        </section>
        <!-- Услуги -->
        <section class="services">
            <div class="text-header">
                <h2 class="text-title">Услуги нашей клиники</h2>
                <p class="text-desc">Забота о здоровых улыбках ваших детей</p>
            </div>
            <div class="services-grid">
                <div class="services-card">
                    <div class="circle" style="background-color: #7C3AED;"><img src="/assets/icons/inspection.svg" alt="Профилактический осмотр"></div>
                    <h3 class="card-title">Профилактический осмотр</h3>
                    <p class="card-desc">Регулярные осмотры для раннего выявления проблем с зубами и профилактики кариеса у детей</p>
                    <div class="card-footer">
                        <span class="card-price">от 700 руб</span>
                        <a class="card-link" href="services.php">Записаться</a>
                    </div>
                </div>
                <div class="services-card">
                    <div class="circle" style="background-color: #F472B6;"><img src="/assets/icons/cleaning.svg" alt="Профессиональная чистка"></div>
                    <h3 class="card-title">Профессиональная чистка</h3>
                    <p class="card-desc">Бережная чистка зубов для удаления налета и предотвращения заболеваний полости рта</p>
                    <div class="card-footer">
                        <span class="card-price">от 4200 руб</span>
                        <a class="card-link" href="services.php">Записаться</a>
                    </div>
                </div>
                <div class="services-card">
                    <div class="circle" style="background-color: #FDE047;"><img src="/assets/icons/treatment.svg" alt="Лечение кариеса"></div>
                    <h3 class="card-title">Лечение кариеса</h3>
                    <p class="card-desc">Безболезненное лечение кариеса с использованием безопасных материалов для детских зубов</p>
                    <div class="card-footer">
                        <span class="card-price">от 2000 руб</span>
                        <a class="card-link" href="services.php">Записаться</a>
                    </div>
                </div>
                <div class="services-card">
                    <div class="circle" style="background-color: #86EFAC;"><img src="/assets/icons/orthodontics.svg" alt="Ортодонтия"></div>
                    <h3 class="card-title">Ортодонтия</h3>
                    <p class="card-desc">Коррекция прикуса с помощью брекетов и элайнеров, адаптированных для детей</p>
                    <div class="card-footer">
                        <span class="card-price">от 2500 руб</span>
                        <a class="card-link" href="services.php">Записаться</a>
                    </div>
                </div>
                <div class="services-card">
                    <div class="circle" style="background-color: #93C5FD;"><img src="/assets/icons/deletion.svg" alt="Удаление молочных зубов"></div>
                    <h3 class="card-title">Удаление молочных зубов</h3>
                    <p class="card-desc">Безопасное и безболезненное удаление молочных зубов при необходимости</p>
                    <div class="card-footer">
                        <span class="card-price">от 1600 руб</span>
                        <a class="card-link" href="services.php">Записаться</a>
                    </div>
                </div>
                <div class="services-card">
                    <div class="circle" style="background-color: #D8B4FE;"><img src="/assets/icons/fissure-sealing.svg" alt="Герметизация фиссур"></div>
                    <h3 class="card-title">Герметизация фиссур</h3>
                    <p class="card-desc">Защита зубов от кариеса с помощью специального покрытия для жевательных поверхностей</p>
                    <div class="card-footer">
                        <span class="card-price">от 2500 руб</span>
                        <a class="card-link" href="services.php">Записаться</a>
                    </div>
                </div>
            </div>
        </section>
        <!-- Галерея -->
        <section class="gallery">
            <div class="text-header">
                <h2 class="text-title">Наша клиника в фотографиях</h2>
                <p class="text-desc">Место, где лечение зубов становится увлекательным приключением</p>
            </div>
            <div class="filter-buttons">
                <a href="#" class="filter-btn active" data-filter="all">Все фото</a>
                <a href="#" class="filter-btn" data-filter="interior">Интерьер</a>
                <a href="#" class="filter-btn" data-filter="patients">Пациенты</a>
                <a href="#" class="filter-btn" data-filter="team">Команда</a>
                <a href="#" class="filter-btn" data-filter="equipment">Оборудование</a>
            </div>
            <div class="gallery-slider-container">
                <div class="gallery-slider">
                    <!-- Слайды будут добавлены динамически через JavaScript -->
                </div>

                <a href="javascript:void(0)" class="slider-arrow slider-prev-gallery" id="gallery-prev">
                    <div class="arrow-circle">❮</div>
                </a>
                <a href="javascript:void(0)" class="slider-arrow slider-next-gallery" id="gallery-next">
                    <div class="arrow-circle">❯</div>
                </a>
            </div>

            <div class="slider-dots">
                <!-- Точки будут добавлены динамически через JavaScript -->
            </div>
        </section>

        <!-- Отзывы -->
        <section class="section-reviews">
            <div class="text-header">
                <h2 class="text-title">Что говорят наши пациенты</h2>
                <p class="text-desc">Отзывы родителей о нашей клинике</p>
            </div>

            <div class="reviews-slider-container">
                <div class="reviews-slider">
                    <?php if (!empty($sliderReviews)): ?>
                        <?php
                        $numReviews = count($sliderReviews);
                        for ($i = 0; $i < $numReviews; $i += 2):
                            $review1 = $sliderReviews[$i];
                            $review2 = isset($sliderReviews[$i + 1]) ? $sliderReviews[$i + 1] : null;
                        ?>
                            <div class="reviews-slide <?php echo ($i === 0) ? 'active' : ''; ?>">
                                <div class="slider-reviews-card">
                                    <div class="slider-avatar" style="background-color: <?php echo $review1['avatar_color']; ?>">
                                        <?php echo getInitial($review1['username']); ?>
                                    </div>

                                    <div class="rating">
                                        <?php for ($j = 1; $j <= 5; $j++): ?>
                                            <span class="star <?php echo ($j <= $review1['rating']) ? 'filled' : ''; ?>">
                                                <img src="/assets/icons/star.svg" alt="Звезда" class="slider-star-icon">
                                            </span>
                                        <?php endfor; ?>
                                    </div>

                                    <div class="review-text">
                                        "<?php echo htmlspecialchars($review1['review_text']); ?>"
                                    </div>

                                    <div class="user-info-review">
                                        <div class="user-name">
                                            <?php echo htmlspecialchars($review1['display_name'] ?? $review1['username']); ?>
                                        </div>

                                        <?php if ($review1['show_child_info'] && $review1['child_name'] && $review1['child_age'] && $review1['relation_to_child']): ?>
                                            <div class="child-info">
                                                <?php
                                                $relation = htmlspecialchars($review1['relation_to_child']);
                                                $child_name = htmlspecialchars($review1['child_name']);
                                                $child_genitive = getChildNameGenitive($child_name);
                                                $child_age = $review1['child_age'];
                                                $age_string = getAgeString($child_age);
                                                echo "$relation $child_genitive, $child_age $age_string";
                                                ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <?php if ($review2): ?>
                                    <div class="slider-reviews-card">
                                        <div class="slider-avatar" style="background-color: <?php echo $review2['avatar_color']; ?>">
                                            <?php echo getInitial($review2['username']); ?>
                                        </div>

                                        <div class="rating">
                                            <?php for ($j = 1; $j <= 5; $j++): ?>
                                                <span class="star <?php echo ($j <= $review2['rating']) ? 'filled' : ''; ?>">
                                                    <img src="/assets/icons/star.svg" alt="Звезда" class="star-icon">
                                                </span>
                                            <?php endfor; ?>
                                        </div>

                                        <div class="review-text">
                                            "<?php echo htmlspecialchars($review2['review_text']); ?>"
                                        </div>

                                        <div class="user-info-review">
                                            <div class="user-name">
                                                <?php echo htmlspecialchars($review2['display_name'] ?? $review2['username']); ?>
                                            </div>

                                            <?php if ($review2['show_child_info'] && $review2['child_name'] && $review2['child_age'] && $review2['relation_to_child']): ?>
                                                <div class="child-info">
                                                    <?php
                                                    $relation = htmlspecialchars($review2['relation_to_child']);
                                                    $child_name = htmlspecialchars($review2['child_name']);
                                                    $child_genitive = getChildNameGenitive($child_name);
                                                    $child_age = $review2['child_age'];
                                                    $age_string = getAgeString($child_age);
                                                    echo "$relation $child_genitive, $child_age $age_string";
                                                    ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endfor; ?>
                    <?php else: ?>
                        <div class="reviews-slide active">
                            <div class="no-reviews">
                                <p>У нас пока нет отзывов. Будьте первым, кто поделится впечатлениями!</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <a href="javascript:void(0)" class="slider-arrow slider-prev" id="reviews-prev">
                    <div class="arrow-circle">❮</div>
                </a>
                <a href="javascript:void(0)" class="slider-arrow slider-next" id="reviews-next">
                    <div class="arrow-circle">❯</div>
                </a>
            </div>

            <a class="reviews-all" href="reviews.php"><img src="assets/icons/message.svg" alt="Отзыв"> Все отзывы</a>
        </section>

        <section class="section-contacts">
            <div class="text-header">
                <h2 class="text-title">Контакты и как до нас добраться</h2>
                <p class="text-desc">Мы находимся в самом центре города, в удобном месте для родителей и детей</p>
            </div>
            <div class="contacts-wrapper">
                <div class="contacts-card our-contacts">
                    <h3 class="card-title">Наши контакты</h3>
                    <div class="contact-item adress">
                        <div class="icon-container">
                            <img src="assets/icons/point.svg" alt="Точка">
                        </div>
                        <p>ул. Аллея Героев, 5, г. Волгоград, 400005(Центральный район, недалеко от площади Павших Борцов)</p>
                    </div>
                    <div class="contact-item phone">
                        <div class="icon-container">
                            <img src="assets/icons/phone.svg" alt="Телефон">
                        </div>
                        <p>+7 (987) 654-32-10</p>
                    </div>
                    <div class="contact-item email">
                        <div class="icon-container">
                            <img src="assets/icons/email.svg" alt="Почта">
                        </div>
                        <p><a href="mailto:feechki-ulibok@yadnex.ru">feechki-ulibok@yadnex.ru</a></p>
                    </div>
                    <div class="contact-item social">
                        <div class="icon-container">
                            <img src="assets/icons/telegram.svg" alt="Телеграм">
                        </div>
                        <p>
                            @feechki_ulibok_volgograd или<br>
                            t.me/feechki_ulibok
                        </p>
                    </div>
                    <div class="contact-item watsapp">
                        <div class="icon-container">
                            <img src="assets/icons/watsapp.svg" alt="Ватсап">
                        </div>
                        <p><a href="https://wa.me/79053972485">+7 (905) 397-24-85</a></p>
                    </div>
                </div>

                <div class="contacts-card works-hour">
                    <h3 class="card-title">Режим работы</h3>
                    <div class="schedule-item">
                        <p class="time card-desc">Пн-Пт: 9:00 – 21:00</p>
                        <p class="time card-desc">Сб-Вс: 10:00 – 18:00</p>
                    </div>
                </div>

                <div class="contacts-card map" id="yandex-map">
                    <!-- Карта будет загружена сюда через JS -->
                </div>

                <script src="https://api-maps.yandex.ru/2.1/?apikey=ваш_API_ключ&lang=ru_RU" type="text/javascript"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Функция инициализации карты
                        function initMap() {
                            var myMap = new ymaps.Map("yandex-map", {
                                center: [48.708441, 44.513696], // Координаты центра Волгограда (замените на ваши)
                                zoom: 16,
                                controls: ['zoomControl', 'geolocationControl']
                            });

                            // Добавление метки на карту
                            var myPlacemark = new ymaps.Placemark([48.708441, 44.513696], {
                                hintContent: 'Феечки улыбок',
                                balloonContent: 'ул. Аллея Героев, 5, г. Волгоград, 400005'
                            }, {
                                preset: 'islands#violetDentistIcon'
                            });

                            myMap.geoObjects.add(myPlacemark);
                        }

                        // Загружаем API Яндекс.Карт и вызываем функцию инициализации
                        if (typeof ymaps !== 'undefined') {
                            ymaps.ready(initMap);
                        } else {
                            console.error('Yandex Maps API not loaded');
                        }
                    });
                </script>


            </div>
        </section>
    </main>
    <?php include 'includes/footer.php'; ?>

    <?php include 'includes/auth_modals.php'; ?>
    <script src="/assets/js/auth_modal.js"></script>
    <script src="/assets/js/gallery.js"></script>
    <script src="/assets/js/reviews_slider.js"></script>
    <!-- </body>
</html> -->