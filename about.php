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
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="/assets/css/header.css">
    <link rel="stylesheet" href="/assets/css/auth_modal.css">
    <link rel="stylesheet" href="/assets/css/user-dropdown.css">
    <link rel="stylesheet" href="/assets/css/footer_style.css">
    <link rel="stylesheet" href="/assets/css/about_style.css">
</head>

<body>
    <?php include 'includes/header.php'; ?>
    <main>
        <section class="section-mission">
            <img src="/assets/images/image_fairy_1.png" alt="Фея">
            <div class="mission-text">
                <h2>Миссия</h2>
                <p>Дарим уверенные и искренние улыбки детям <br> каждый день</p>
                <ul>
                    <li>Цель нашей клиники - создать место, где каждый ребенок чувствует себя спокойно.</li>
                    <li>Индивидуальный подход к каждому маленькому пациенту.</li>
                </ul>
            </div>
        </section>
        <section class="section-approach">
            <div class="container-approach">
                <div class="approach-item">
                    <div class="circle">
                        <img src="/assets/icons/horse-icon.svg" alt="Лошадь">
                    </div>
                    <p>Игровая зона</p>
                </div>
                <div class="approach-item">
                    <div class="circle">
                        <img src="/assets/icons/smile-icon.svg" alt="Улыбка">
                    </div>
                    <p>Без боли</p>
                </div>
                <div class="approach-item">
                    <div class="circle">
                        <img src="/assets/icons/doctor-icon.svg" alt="Стоматолог">
                    </div>
                    <p>Опытные врачи</p>
                </div>
            </div>
        </section>
        <section class="section-team">
            <div class="header-team">
                <div class="team-text">
                    <h2>Наша команда</h2>
                    <p>Волшебники, готовые всегда помочь</p>
                </div>
                <img src="/assets/images/image_fairy_2.png" alt="Фея" class="fairy-image">
            </div>
            <div class="container-team">
                <div class="team-item">
                    <img src="/assets/images/team/team 4.png" alt="Врач">
                    <p class="name">Глухов Тимофей Петрович</p>
                    <p class="position">Анестезиолог-реаниматолог</p>
                </div>
                <div class="team-item">
                    <img src="/assets/images/team/team 2.png" alt="Врач">
                    <p class="name">Акимов Павел Тимурович</p>
                    <p class="position">Детский стоматолог</p>
                </div>
                <div class="team-item">
                    <img src="/assets/images/team/team 1.png" alt="Врач">
                    <p class="name">Белова Елена Даниловна</p>
                    <p class="position">Стоматолог-ортодонт</p>
                </div>
                <div class="team-item">
                    <img src="/assets/images/team/team 3.png" alt="Врач">
                    <p class="name">Чистякова Мия Львовна</p>
                    <p class="position">Детский стоматолог</p>
                </div>
            </div>
        </section>
        <section class="section-contacts">
            <div class="header-contacts">
                <div class="contacts-text">
                    <h2>Связаться с феями</h2>
                    <p>Мы всегда на связи, чтобы помочь</p>
                </div>
                <img src="/assets/images/image_fairy_3.png" alt="Фея">
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
            <div class="button-container">
                <a href="services.php" class="button">Наши услуги</a>
            </div>
        </section>
    </main>
    <?php include 'includes/footer.php'; ?>
</body>

</html>