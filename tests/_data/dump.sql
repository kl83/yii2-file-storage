-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Сен 14 2017 г., 08:59
-- Версия сервера: 5.7.19-log
-- Версия PHP: 7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- База данных: `filestorage`
--

-- --------------------------------------------------------

--
-- Структура таблицы `kl83_file`
--

CREATE TABLE `kl83_file` (
  `id` int(10) UNSIGNED NOT NULL,
  `idx` int(11) UNSIGNED NOT NULL,
  `fileSetId` int(11) UNSIGNED NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdBy` int(11) UNSIGNED NOT NULL,
  `relPath` varchar(2500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `kl83_file_set`
--

CREATE TABLE `kl83_file_set` (
  `id` int(10) UNSIGNED NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdBy` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `kl83_file`
--
ALTER TABLE `kl83_file`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fileSetId` (`fileSetId`);

--
-- Индексы таблицы `kl83_file_set`
--
ALTER TABLE `kl83_file_set`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `kl83_file`
--
ALTER TABLE `kl83_file`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT для таблицы `kl83_file_set`
--
ALTER TABLE `kl83_file_set`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;
