-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Июн 14 2025 г., 12:25
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
(18, 18, 5, 'Делали эстетическую реставрацию переднего зуба сыну после того, как он его сломал на тренировке. Результат потрясающий — зуб как родной! Никита теперь улыбается во весь рот. Врач — настоящий мастер, спасибо огромное!', 'Никита', 13, 'Папа', 1, 'Михаил Зайцев', 'approved', '2025-06-13 22:10:40', NULL, 12, 'Эстетическая реставрация');

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
(18, 'Михаил_Зайцев', 'mikhail.zaytsev@mail.ru', '$2y$12$randomHash18', '#5856d6', NULL, '2025-06-12 21:55:40');

--
-- Индексы сохранённых таблиц
--

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
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT для таблицы `services`
--
ALTER TABLE `services`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
