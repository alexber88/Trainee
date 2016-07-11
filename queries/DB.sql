-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               5.7.8-rc - MySQL Community Server (GPL)
-- ОС Сервера:                   Linux
-- HeidiSQL Версия:              9.3.0.4984
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Дамп структуры базы данных company
CREATE DATABASE IF NOT EXISTS `company` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `company`;


-- Дамп структуры для таблица company.accounter_data
CREATE TABLE IF NOT EXISTS `accounter_data` (
  `employee_id` int(10) unsigned NOT NULL,
  `address` varchar(255) NOT NULL DEFAULT '',
  `passport` varchar(50) NOT NULL DEFAULT '',
  `ident_number` varchar(50) NOT NULL DEFAULT '',
  `salary_per_month` int(11) NOT NULL DEFAULT '0',
  KEY `employee_id` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы company.accounter_data: ~3 rows (приблизительно)
/*!40000 ALTER TABLE `accounter_data` DISABLE KEYS */;
INSERT INTO `accounter_data` (`employee_id`, `address`, `passport`, `ident_number`, `salary_per_month`) VALUES
	(1, 'khr', '', 'sdfgsdfsf', 3000),
	(2, 'khr', '', '', 5000),
	(3, 'sdfsdf', '', '', 2500);
/*!40000 ALTER TABLE `accounter_data` ENABLE KEYS */;


-- Дамп структуры для таблица company.bonuses_penalties
CREATE TABLE IF NOT EXISTS `bonuses_penalties` (
  `employee_id` int(11) NOT NULL,
  `payment_day_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  KEY `employee_id` (`employee_id`),
  KEY `payment_day_id` (`payment_day_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы company.bonuses_penalties: ~4 rows (приблизительно)
/*!40000 ALTER TABLE `bonuses_penalties` DISABLE KEYS */;
INSERT INTO `bonuses_penalties` (`employee_id`, `payment_day_id`, `amount`) VALUES
	(1, 2, 1000),
	(3, 3, -500),
	(2, 3, 1500),
	(2, 4, 1000);
/*!40000 ALTER TABLE `bonuses_penalties` ENABLE KEYS */;


-- Дамп структуры для таблица company.employee
CREATE TABLE IF NOT EXISTS `employee` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы company.employee: ~3 rows (приблизительно)
/*!40000 ALTER TABLE `employee` DISABLE KEYS */;
INSERT INTO `employee` (`id`, `name`, `email`) VALUES
	(1, 'emp1', 'emp1@mail.com'),
	(2, 'emp2', 'emp2@mail.com'),
	(3, 'emp3', 'emp3@mail.com');
/*!40000 ALTER TABLE `employee` ENABLE KEYS */;


-- Дамп структуры для таблица company.month_payment
CREATE TABLE IF NOT EXISTS `month_payment` (
  `employee_id` int(10) unsigned NOT NULL,
  `payment` int(10) unsigned NOT NULL,
  `payment_day_id` int(11) NOT NULL,
  KEY `employee_id` (`employee_id`),
  KEY `payment_day_id` (`payment_day_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы company.month_payment: ~12 rows (приблизительно)
/*!40000 ALTER TABLE `month_payment` DISABLE KEYS */;
INSERT INTO `month_payment` (`employee_id`, `payment`, `payment_day_id`) VALUES
	(1, 3000, 1),
	(2, 5000, 1),
	(3, 2500, 1),
	(1, 4000, 2),
	(2, 5000, 2),
	(3, 2500, 2),
	(1, 3000, 3),
	(2, 6500, 3),
	(3, 2000, 3),
	(1, 3000, 4),
	(2, 6000, 4),
	(3, 2500, 4);
/*!40000 ALTER TABLE `month_payment` ENABLE KEYS */;


-- Дамп структуры для таблица company.payment_day
CREATE TABLE IF NOT EXISTS `payment_day` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы company.payment_day: ~4 rows (приблизительно)
/*!40000 ALTER TABLE `payment_day` DISABLE KEYS */;
INSERT INTO `payment_day` (`id`, `date`) VALUES
	(1, '2016-04-11'),
	(2, '2016-05-11'),
	(3, '2016-06-11'),
	(4, '2016-07-11');
/*!40000 ALTER TABLE `payment_day` ENABLE KEYS */;


-- Дамп структуры для таблица company.working_time
CREATE TABLE IF NOT EXISTS `working_time` (
  `employee_id` int(10) unsigned NOT NULL,
  `date` date NOT NULL,
  `hours` tinyint(4) NOT NULL,
  KEY `employee_id` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы company.working_time: ~9 rows (приблизительно)
/*!40000 ALTER TABLE `working_time` DISABLE KEYS */;
INSERT INTO `working_time` (`employee_id`, `date`, `hours`) VALUES
	(1, '2016-04-10', 8),
	(2, '2016-04-10', 5),
	(3, '2016-04-10', 7),
	(1, '2016-05-10', 5),
	(2, '2016-05-10', 6),
	(3, '2016-05-10', 2),
	(1, '2016-06-10', 7),
	(2, '2016-06-10', 3),
	(3, '2016-06-10', 6);
/*!40000 ALTER TABLE `working_time` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
