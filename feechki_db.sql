-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Июн 22 2025 г., 15:06
-- Версия сервера: 5.7.44
-- Версия PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `feechki_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `service_id` int(10) UNSIGNED DEFAULT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `status` enum('scheduled','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'scheduled',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `appointments`
--

INSERT INTO `appointments` (`id`, `user_id`, `doctor_id`, `service_id`, `appointment_date`, `appointment_time`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 2, '2025-07-12', '10:00:00', 'scheduled', '2025-06-15 14:30:00', NULL),
(2, 2, 1, 1, '2025-07-07', '14:30:00', 'scheduled', '2025-06-14 09:15:00', NULL),
(3, 3, 2, 8, '2025-06-30', '11:30:00', 'scheduled', '2025-06-12 16:45:00', NULL),
(4, 4, 3, 3, '2025-06-25', '09:00:00', 'scheduled', '2025-06-10 13:20:00', NULL),
(5, 5, 4, 5, '2025-07-15', '15:45:00', 'scheduled', '2025-06-18 10:30:00', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `specialization` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `doctors`
--

INSERT INTO `doctors` (`id`, `full_name`, `specialization`, `avatar`, `status`) VALUES
(1, 'Чистякова Мия Львовна', 'Детский стоматолог', 'doctor_chistjakova.jpg', 'active'),
(2, 'Боброва Алиса Дмитриевна', 'Ортодонт', 'doctor_bobrova.jpg', 'active'),
(3, 'Соколов Иван Артемович', 'Детский стоматолог-хирург', 'doctor_sokolov.jpg', 'active'),
(4, 'Ковалева Елена Сергеевна', 'Детский стоматолог', 'doctor_kovaleva.jpg', 'active');

-- --------------------------------------------------------

--
-- Структура таблицы `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(1) NOT NULL,
  `review_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `child_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `child_age` int(2) DEFAULT NULL,
  `relation_to_child` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `show_child_info` tinyint(1) DEFAULT '0',
  `display_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `service_id` int(10) UNSIGNED DEFAULT NULL,
  `service_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `rating`, `review_text`, `child_name`, `child_age`, `relation_to_child`, `show_child_info`, `display_name`, `status`, `created_at`, `updated_at`, `service_id`, `service_name`) VALUES
(1, 2, 5, 'Маша всегда боялась стоматологов, а здесь даже не заплакала! Врачи умеют найти подход к детям.', 'Маша', 6, 'Мама', 1, 'Анна Петрова', 'approved', '2025-06-07 13:40:17', NULL, 1, 'Удаление молочных зубов'),
(2, 1, 5, 'Приятная атмосфера и вежливые врачи. Нам всё подробно рассказали перед процедурой, ребёнок сидел спокойно, как будто просто в гости пришёл. Большое спасибо команде!', 'Диана', 5, 'Папа', 1, 'Иван Смирнов', 'approved', '2025-06-07 21:11:27', NULL, 2, 'Профилактический осмотр'),
(3, 3, 5, 'Очень понравилось, как всё организовано — быстро, аккуратно, и с вниманием к мелочам. Сыну всё объяснили, показали инструменты, он ушёл довольный и с подарком.', 'Михаил', 4, 'Мама', 1, 'Елена С.', 'approved', '2025-06-10 11:53:47', NULL, 2, 'Профилактический осмотр'),
(4, 4, 4, 'Отличная детская стоматология! Вчера были на приёме с дочкой, ей 6 лет. Врачи умеют найти подход даже к самым капризным детям. Алиса сначала боялась, но благодаря игровой форме осмотра, быстро успокоилась. Лечение прошло безболезненно. Единственный минус - немного высокие цены, но качество того стоит.', 'Алиса', 12, 'Папа', 1, 'Сергей Иванов', 'approved', '2025-06-10 11:53:47', NULL, 1, 'Удаление молочных зубов'),
(5, 5, 5, 'Удаляли кариес — всё прошло быстро и безболезненно. Врач рассказал, что будет делать, ребёнок не успел испугаться. Спасибо за профессионализм.', 'Петя', 10, 'Мама', 1, 'Анастасия Р.', 'approved', '2025-06-11 18:48:01', NULL, 3, 'Лечение кариеса'),
(6, 6, 5, 'Проходили профилактический осмотр перед школой. Всё чётко, по делу, без навязывания лишнего. Рекомендую тем, кто ценит качество и время.', 'Оля', 7, 'Папа', 1, 'Дмитрий К.', 'approved', '2025-06-11 18:48:01', NULL, 2, 'Профилактический осмотр'),
(7, 7, 5, 'Были на профилактическом осмотре с сыном. Клиника просто находка! Врач так здорово общалась с Арсением, показала ему мультики и подарила яркую зубную щётку. Он теперь сам напоминает, что надо чистить зубы. Очень довольны, обязательно вернёмся!', 'Арсений', 5, 'Мама', 1, 'Мария Колченко', 'approved', '2025-06-13 09:30:22', NULL, 2, 'Профилактический осмотр'),
(8, 8, 4, 'Удаляли молочный зуб дочке. Она очень боялась, но доктор всё сделал быстро и аккуратно. Аня даже не заплакала, хотя обычно она паникует. Минус — пришлось подождать 15 минут в очереди, но в целом всё прошло хорошо.', 'Аня', 6, 'Папа', 1, 'Алексей Соловьев', 'approved', '2025-06-13 10:45:45', NULL, 1, 'Удаление молочных зубов'),
(9, 9, 5, 'Лечили кариес сыну. Врач невероятно терпеливый, всё объяснил Вите, даже показал, как работает бормашина, чтобы он не боялся. Процедура прошла без слёз, сын ушёл с наклейкой и улыбкой. Рекомендую всем!', 'Витя', 8, 'Мама', 1, 'Ольга Васильева', 'approved', '2025-06-13 12:00:10', NULL, 3, 'Лечение кариеса'),
(10, 10, 5, 'Ставили брекеты дочери. Ортодонт подробно рассказал, как ухаживать за ними, и ответил на все наши вопросы. Лиза сначала переживала, но теперь с радостью ходит на коррекции. Видно, что врачи любят свою работу!', 'Лиза', 14, 'Папа', 1, 'Николай Борисов', 'approved', '2025-06-13 12:35:33', NULL, 8, 'Установка брекетов'),
(11, 11, 4, 'Делали фторирование зубов сыну. Процедура прошла быстро, но Дима немного капризничал, так как не любит сидеть на месте. Врач был доброжелательный, но хотелось бы чуть больше игр или отвлечений для ребёнка. Результат отличный!', 'Дима', 7, 'Мама', 1, 'Екатерина Лебедева', 'approved', '2025-06-13 13:25:57', NULL, 6, 'Фторирование зубов'),
(12, 12, 5, 'Профессиональная чистка для сына была просто супер! Врач рассказывал шутки, и Егор даже хихикал во время процедуры. Зубы теперь блестят, а сын просит записать его снова. Спасибо за такой позитивный подход!', 'Егор', 9, 'Папа', 1, 'Игорь Павлов', 'approved', '2025-06-13 14:40:19', NULL, 5, 'Профессиональная чистка'),
(13, 13, 5, 'Лечили гингивит у дочки. Врач очень внимательно отнёсся к нашей проблеме, объяснил, как правильно чистить зубы и дёсны. Соня сначала стеснялась, но мультики и доброта врача сделали своё дело. Всё прошло отлично!', 'Соня', 12, 'Мама', 1, 'Татьяна Морозова', 'approved', '2025-06-13 15:55:42', NULL, 11, 'Лечение гингивита'),
(14, 14, 3, 'Приводил сына на герметизацию фиссур. Сама процедура прошла нормально, но организация немного хромает — запись была на 10:00, а приняли нас только в 10:20. Врач был добрый, но Максиму было скучно ждать. Результатом довольны.', 'Максим', 8, 'Папа', 1, 'Владимир Козлов', 'approved', '2025-06-13 17:10:08', NULL, 4, 'Герметизация фиссур'),
(15, 15, 5, 'Делали реминерализацию эмали дочке. Врач так здорово всё объяснила, что Маша даже не поняла, что это лечение. Ей подарили наклейку и рассказали, как ухаживать за зубками. Теперь она всем хвастается, какие у неё крепкие зубы!', 'Маша', 4, 'Мама', 1, 'Наталья Семенова', 'approved', '2025-06-13 18:25:31', NULL, 9, 'Реминерализация эмали'),
(16, 16, 5, 'Лечили пульпит сыну. Очень переживали, но врач всё сделал безболезненно, с анестезией. Саша даже не понял, что было что-то серьёзное. Теперь зуб в порядке, а сын с радостью ходит на проверки. Спасибо за профессионализм!', 'Саша', 10, 'Папа', 1, 'Павел Григорьев', 'approved', '2025-06-13 19:40:54', NULL, 7, 'Лечение пульпита'),
(17, 17, 4, 'Удаляли зубной камень дочке. Процедура прошла быстро, но Кира немного нервничала из-за звука инструментов. Врач постарался её успокоить, показав мультики. Всё хорошо, но хотелось бы чуть больше внимания к ребёнку.', 'Кира', 11, 'Мама', 1, 'Светлана Федорова', 'approved', '2025-06-13 20:55:17', NULL, 10, 'Удаление зубного камня'),
(18, 18, 5, 'Делали эстетическую реставрацию переднего зуба сыну после того, как он его сломал на тренировке. Результат потрясающий — зуб как родной! Никита теперь улыбается во весь рот. Врач — настоящий мастер, спасибо огромное!', 'Никита', 13, 'Папа', 1, 'Михаил Зайцев', 'approved', '2025-06-13 22:10:40', NULL, 12, 'Эстетическая реставрация'),
(19, 19, 4, 'У сына начали появляться проблемы с прикусом, обратились в вашу клинику на консультацию.\r\nВрач всё подробно объяснил, сделали снимки, подобрали план лечения. Сейчас носим съёмную пластинку, уже видны первые результаты. Очень внимательное отношение. Спасибо за профессионализм.', 'Александр', 15, 'Папа', 0, 'Сан Саныч', 'approved', '2025-06-14 15:09:36', NULL, 15, 'Коррекция прикуса'),
(20, 20, 5, 'Вылечили кариеса и спустя какое-то время возник нарыв, обратились в клинику, как оказалось это пульпит, процедура шла 40 минут, все быстро и качественно', NULL, NULL, 'Папа', 0, 'Егор', 'approved', '2025-06-14 15:22:11', NULL, 7, 'Лечение пульпита'),
(21, 20, 5, 'Хорошая клиника', 'Марк', 12, 'Папа', 0, 'Леонид', 'approved', '2025-06-14 15:41:35', NULL, 2, 'Профилактический осмотр'),
(22, 23, 3, 'Удалили зуб, все нормально', 'Влад', 12, 'Папа', 1, 'Антон П', 'pending', '2025-06-18 06:24:37', NULL, 1, 'Удаление молочных зубов'),
(23, 23, 4, 'Приводил сына удалять зуб в эту клинику, теперь привел дочь, ставить брекеты', 'Лиза', 10, 'Папа', 1, 'Антон П', 'pending', '2025-06-18 06:30:07', NULL, 8, 'Установка брекетов');

-- --------------------------------------------------------

--
-- Структура таблицы `services`
--

CREATE TABLE `services` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `services`
--

INSERT INTO `services` (`id`, `name`) VALUES
(4, 'Герметизация фиссур'),
(13, 'Детская анестезия'),
(15, 'Коррекция прикуса'),
(11, 'Лечение гингивита'),
(3, 'Лечение кариеса'),
(14, 'Лечение периодонтита'),
(7, 'Лечение пульпита'),
(5, 'Профессиональная чистка'),
(2, 'Профилактический осмотр'),
(9, 'Реминерализация эмали'),
(10, 'Удаление зубного камня'),
(1, 'Удаление молочных зубов'),
(8, 'Установка брекетов'),
(6, 'Фторирование зубов'),
(12, 'Эстетическая реставрация');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar_color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#6c4ade',
  `remember_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `registered_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `avatar_color`, `remember_token`, `registered_at`) VALUES
(1, 'fff', 'fffomka49@gmail.com', '$2y$12$hVlG.Msb4YPUi.D9uJERwuhcHx8Nn9R72BgmiZVTBL5HqRFZoYe.2', '#4cd964', NULL, '2025-06-01 21:46:04'),
(2, 'Анна', 'anna_petrova@gmail.com', '$2y$12$sHkJDPGriFBSdEP9dZyLZeCI9.YK3dA4dadB79jXhNXmCkF3ShAaK', '#4cd964', NULL, '2025-06-07 16:01:06'),
(3, 'Елена', 'elena.sokolova@gmail.com', '$2y$12$hB7dK8LpQfS7xJ3nM5vZo.9cR1DUVr2jKLzj5mTH3rC4qLzRvCfa2', '#ff9500', NULL, '2025-06-10 11:53:47'),
(4, 'Сергей', 'sergey.ivanov@mail.ru', '$2y$12$pWu1XoT5f3QsRmT7RfKueeU8o.mP3GxEAYGr8I4.q1r2GjvXBswDG', '#007aff', NULL, '2025-06-10 11:53:47'),
(5, 'Анастасия', 'anastasia.r@mail.ru', '$2y$12$demoHash1', '#ff2d55', NULL, '2025-06-11 18:48:01'),
(6, 'Дмитрий', 'd.kuznetsov@mail.ru', '$2y$12$demoHash2', '#34c759', NULL, '2025-06-11 18:48:01'),
(7, 'Мария Колченко', 'maria.kolchenko@gmail.com', '$2y$12$randomHash7', '#ff3b30', NULL, '2025-06-05 09:15:22'),
(8, 'Алексей Соловьев', 'alexey.solovyev@mail.ru', '$2y$12$randomHash8', '#007aff', NULL, '2025-06-05 10:30:45'),
(9, 'Ольга_Васильева', 'olga.vasilyeva@gmail.com', '$2y$12$randomHash9', '#34c759', NULL, '2025-06-05 11:45:10'),
(10, 'Николай_Борисов', 'nikolay.borisov@mail.ru', '$2y$12$randomHash10', '#ff9500', NULL, '2025-06-06 12:20:33'),
(11, 'Екатерина_Лебедева', 'ekaterina.lebedeva@gmail.com', '$2y$12$randomHash11', '#5856d6', NULL, '2025-10-12 13:10:57'),
(12, 'Игорь_Павлов', 'igor.pavlov@mail.ru', '$2y$12$randomHash12', '#ff2d55', NULL, '2025-06-12 14:25:19'),
(13, 'Татьяна_Морозова', 'tatiana.morozova@gmail.com', '$2y$12$randomHash13', '#4cd964', NULL, '2025-06-12 15:40:42'),
(14, 'Владимир_Козлов', 'vladimir.kozlov@mail.ru', '$2y$12$randomHash14', '#ffcc00', NULL, '2025-06-12 16:55:08'),
(15, 'Наталья_Семенова', 'natalia.semenova@gmail.com', '$2y$12$randomHash15', '#007aff', NULL, '2025-06-12 18:10:31'),
(16, 'Павел_Григорьев', 'pavel.grigoryev@mail.ru', '$2y$12$randomHash16', '#34c759', NULL, '2025-06-12 19:25:54'),
(17, 'Светлана_Федорова', 'svetlana.fedorova@gmail.com', '$2y$12$randomHash17', '#ff9500', NULL, '2025-06-12 20:40:17'),
(18, 'Михаил_Зайцев', 'mikhail.zaytsev@mail.ru', '$2y$12$randomHash18', '#5856d6', NULL, '2025-06-12 21:55:40'),
(19, 'leo528', 'leoscachkov@gmail.com', '$2y$12$xupyk3yw04Z5TFv1h0c3YewwbsK85CDau5k0scEu71BKkP.DCLCoy', '#ff3b30', NULL, '2025-06-14 13:39:18'),
(20, 'egor', 'egor@gmail.com', '$2y$12$OGPypDieJHk2tykj6caaQux3kkDD4vm9n7agaDBrwAJMqB219sANO', '#6c4ade', NULL, '2025-06-14 15:18:25'),
(21, 'ilya', 'ilyatest@gmail.com', '$2y$12$syixQyfzY5chVMtqKRn10OWFxkwI0.5pbM4KRW7PYUJMQV3Olk1ve', '#5ac8fa', NULL, '2025-06-18 04:47:29'),
(22, 'leo636363', 'leo36@gmail.com', '$2y$12$H6fqkyf6OKgYl0JTPwE.xuta8WzeEK6uCsgwT0/92nUkxZggkkXBm', '#6c4ade', NULL, '2025-06-18 04:57:01'),
(23, 'devtols', 'devtols@gmail.com', '$2y$12$aCckuF3ZvoR3XMzLLYTckO8BWhdbN6IS5MAiloCTyeIujxZMlWBya', '#4cd964', NULL, '2025-06-18 05:01:06'),
(24, 'kyl', 'lyj@gmail.com', '$2y$12$vJ3ss4i5mW7hf8K7DH3v6uMlr8BgZQ0l1JV.Lua5x5bWMGsGa/Ndu', '#6c4ade', NULL, '2025-06-18 05:41:01'),
(25, 'developer', 'mode@gmail.com', '$2y$12$TsrRLSb0Tcw5lcZwyZvfv.71nUA3VQhsnyUKkF/ujFvRJw4omNGDi', '#6c4ade', NULL, '2025-06-18 06:15:03'),
(26, 'deva', 'veda@gmail.com', '$2y$12$r0UYBNDdbIEQ.lrjeqi57.p3sBnGUKqnKRLRPtZHMStOPlBwcbH12', '#6c4ade', NULL, '2025-06-18 06:17:14');

-- --------------------------------------------------------

--
-- Структура таблицы `user_details`
--

CREATE TABLE `user_details` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `gender` enum('male','female','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `user_details`
--

INSERT INTO `user_details` (`id`, `user_id`, `full_name`, `phone`, `birth_date`, `gender`, `address`, `updated_at`) VALUES
(1, 1, 'Иван Смирнов', '+7 (912) 345-67-89', '1990-05-18', 'male', 'г. Москва, ул. Ленина, д. 15, кв. 27', '2025-06-01 21:46:04'),
(2, 2, 'Анна Петрова', '+7 (925) 123-45-67', '1985-07-21', 'female', 'г. Москва, ул. Пушкина, д. 10, кв. 15', '2025-06-07 16:01:06'),
(3, 3, 'Елена Соколова', '+7 (929) 765-43-21', '1988-12-05', 'female', 'г. Москва, Проспект Мира, д. 78, кв. 45', '2025-06-10 11:53:47'),
(4, 4, 'Сергей Иванов', '+7 (915) 555-44-33', '1982-03-15', 'male', 'г. Москва, ул. Жукова, д. 25, кв. 12', '2025-06-10 11:53:47'),
(5, 5, 'Анастасия Рокотова', '+7 (926) 987-65-43', '1992-11-30', 'female', 'г. Москва, ул. Гагарина, д. 8, кв. 93', '2025-06-11 18:48:01');

-- --------------------------------------------------------

--
-- Структура таблицы `visit_history`
--

CREATE TABLE `visit_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `service_id` int(10) UNSIGNED DEFAULT NULL,
  `visit_date` date NOT NULL,
  `visit_time` time NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `visit_history`
--

INSERT INTO `visit_history` (`id`, `user_id`, `doctor_id`, `service_id`, `visit_date`, `visit_time`, `notes`, `created_at`) VALUES
(1, 1, 1, 2, '2025-06-05', '11:30:00', 'Профилактический осмотр. Состояние зубов хорошее.', '2025-06-05 12:30:00'),
(2, 2, 1, 1, '2025-05-25', '10:00:00', 'Удаление молочного зуба прошло без осложнений.', '2025-05-25 10:45:00'),
(3, 3, 3, 2, '2025-06-01', '14:15:00', 'Проведен профилактический осмотр. Обнаружен начальный кариес на 55 зубе.', '2025-06-01 15:00:00'),
(4, 4, 2, 1, '2025-05-20', '09:00:00', 'Удаление молочного зуба 74. Процедура прошла успешно.', '2025-05-20 09:30:00'),
(5, 5, 1, 3, '2025-05-15', '16:30:00', 'Лечение кариеса на зубе 54. Установлена пломба.', '2025-05-15 17:15:00'),
(6, 1, 3, 6, '2025-04-10', '13:45:00', 'Проведено фторирование зубов.', '2025-04-10 14:30:00'),
(7, 2, 4, 5, '2025-03-22', '11:30:00', 'Выполнена профессиональная чистка зубов.', '2025-03-22 12:15:00'),
(8, 3, 2, 3, '2025-04-05', '10:00:00', 'Лечение кариеса на зубах 84 и 85.', '2025-04-05 11:00:00'),
(9, 4, 1, 9, '2025-02-15', '15:00:00', 'Проведена реминерализация эмали.', '2025-02-15 15:45:00'),
(10, 5, 3, 4, '2025-01-20', '09:30:00', 'Выполнена герметизация фиссур.', '2025-01-20 10:15:00');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Индексы таблицы `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Индексы таблицы `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Индексы таблицы `user_details`
--
ALTER TABLE `user_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Индексы таблицы `visit_history`
--
ALTER TABLE `visit_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `service_id` (`service_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT для таблицы `services`
--
ALTER TABLE `services`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT для таблицы `user_details`
--
ALTER TABLE `user_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `visit_history`
--
ALTER TABLE `visit_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_3` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_details`
--
ALTER TABLE `user_details`
  ADD CONSTRAINT `user_details_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `visit_history`
--
ALTER TABLE `visit_history`
  ADD CONSTRAINT `visit_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `visit_history_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `visit_history_ibfk_3` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
