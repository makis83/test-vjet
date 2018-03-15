-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Мар 15 2018 г., 17:31
-- Версия сервера: 5.7.21-0ubuntu0.17.10.1
-- Версия PHP: 7.2.3-1+ubuntu17.10.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `test2`
--

-- --------------------------------------------------------

--
-- Структура таблицы `test_comments`
--

CREATE TABLE `test_comments` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'autoincrement',
  `publication` smallint(5) UNSIGNED NOT NULL COMMENT 'publication',
  `added_by` smallint(5) UNSIGNED NOT NULL COMMENT 'user',
  `added_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'date and time',
  `comment` text COMMENT 'comment'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='stores comments';

-- --------------------------------------------------------

--
-- Структура таблицы `test_publications`
--

CREATE TABLE `test_publications` (
  `id` smallint(5) UNSIGNED NOT NULL COMMENT 'autoincrement',
  `added_by` smallint(5) UNSIGNED NOT NULL COMMENT 'user',
  `added_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'date and time',
  `subject` varchar(120) NOT NULL COMMENT 'subject',
  `introtext` varchar(120) NOT NULL COMMENT 'introtext',
  `content` text NOT NULL COMMENT 'content'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='stores available publications';

-- --------------------------------------------------------

--
-- Структура таблицы `test_users`
--

CREATE TABLE `test_users` (
  `id` smallint(5) UNSIGNED NOT NULL COMMENT 'autoincrement',
  `name` char(100) NOT NULL COMMENT 'user''s full name',
  `email` char(60) NOT NULL COMMENT 'email'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='stores list of users';

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `test_comments`
--
ALTER TABLE `test_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `added_by` (`added_by`),
  ADD KEY `publication` (`publication`);

--
-- Индексы таблицы `test_publications`
--
ALTER TABLE `test_publications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subject` (`subject`),
  ADD KEY `added_by` (`added_by`);

--
-- Индексы таблицы `test_users`
--
ALTER TABLE `test_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `test_comments`
--
ALTER TABLE `test_comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'autoincrement', AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `test_publications`
--
ALTER TABLE `test_publications`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'autoincrement', AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `test_users`
--
ALTER TABLE `test_users`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'autoincrement', AUTO_INCREMENT=9;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `test_comments`
--
ALTER TABLE `test_comments`
  ADD CONSTRAINT `fk_test_comments_1` FOREIGN KEY (`publication`) REFERENCES `test_publications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_test_comments_2` FOREIGN KEY (`added_by`) REFERENCES `test_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `test_publications`
--
ALTER TABLE `test_publications`
  ADD CONSTRAINT `fk_test_publications_1` FOREIGN KEY (`added_by`) REFERENCES `test_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
