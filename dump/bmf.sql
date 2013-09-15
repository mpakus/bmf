-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 15, 2013 at 05:18 PM
-- Server version: 5.5.28-log
-- PHP Version: 5.4.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bmf`
--

-- --------------------------------------------------------

--
-- Table structure for table `codes`
--

CREATE TABLE IF NOT EXISTS `codes` (
  `module_id` int(10) NOT NULL,
  `language` varchar(32) NOT NULL,
  `full` text NOT NULL,
  PRIMARY KEY (`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `codes`
--

INSERT INTO `codes` (`module_id`, `language`, `full`) VALUES
(4, 'ruby', 'source ''https://rubygems.org''\n\ngem ''rails'', ''3.2.11''\n# Bundle edge Rails instead:\n# gem ''rails'', :git => ''git://github.com/rails/rails.git''\n\ngem ''mysql2''\ngem ''gon''\n\ngem ''bcrypt-ruby'', ''~> 3.0.0''\n\n# Use unicorn as the app server\ngroup :production do\n  gem ''unicorn''\nend');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) NOT NULL,
  `post_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `added_at` datetime NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`,`user_id`,`deleted`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mails`
--

CREATE TABLE IF NOT EXISTS `mails` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `added_at` datetime NOT NULL,
  `email` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `mails`
--

INSERT INTO `mails` (`id`, `added_at`, `email`) VALUES
(1, '2012-05-08 17:58:49', 'mrak69@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `post_id` int(10) NOT NULL,
  `ord` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `post_id`, `ord`, `name`) VALUES
(1, 1, 1, 'text'),
(2, 1, 2, 'cut'),
(3, 1, 3, 'text'),
(4, 1, 4, 'code'),
(5, 1, 5, 'text'),
(6, 2, 1, 'text'),
(10, 2, 5, 'picture'),
(13, 3, 1, 'text'),
(14, 3, 2, 'text');

-- --------------------------------------------------------

--
-- Table structure for table `pictures`
--

CREATE TABLE IF NOT EXISTS `pictures` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `module_id` int(10) NOT NULL,
  `alt` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `module_id` (`module_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `pictures`
--

INSERT INTO `pictures` (`id`, `module_id`, `alt`, `image`) VALUES
(3, 10, 'better_errors результаты работы', '0978a5f61faa157660a0760aed65cdcc.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `added_at` datetime NOT NULL,
  `user_id` int(10) NOT NULL,
  `type` int(2) NOT NULL,
  `category_id` int(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `tags` varchar(255) NOT NULL,
  `rating` int(4) NOT NULL,
  `views` int(4) NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `cut` text NOT NULL,
  `full` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `type_id` (`type`),
  KEY `deleted` (`deleted`),
  KEY `published` (`published`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `added_at`, `user_id`, `type`, `category_id`, `title`, `description`, `tags`, `rating`, `views`, `published`, `deleted`, `cut`, `full`) VALUES
(1, '2013-05-19 19:39:37', 4, 1, 0, 'Частые Bundler команды', 'tips &amp; tricks работы с bundler пакетным менеджером gem библиотек', 'bundler, tips', 0, 0, 1, 0, '<p>\n    Чаще всего в своих проектах для управления набором gem библиотек мы используем Bundler, а потом уже rvm gemset''ы.<br/>\r\nЭто маленькая шпаргалка о библиотеке Bundler, чтобы держать всю информацию об используемых библиотеках в вашем проекте в одном месте.<br/>\r\n<br/>\r\nBundler точно такой же gem как и любые другие, после его установки как gem install bundler он позволяе управлять библиотеками, их версиями и зависимостями в вашем проекте. Проект чаще всего может быть Rails3 и уже 4, Sinatra, Espresso, Cramp или вообще просто набор классов вашего приложения без использования каких либо фрэмворков.<br/>\r\n<br/>\r\nРассмотрим дальше работу с Bundler.</p><a href="/blog/show/1-chastie-bundler-komandi.html" class="read-more">Подробнее&hellip;</a>', '<p>\n    Чаще всего в своих проектах для управления набором gem библиотек мы используем Bundler, а потом уже rvm gemset''ы.<br/>\r\nЭто маленькая шпаргалка о библиотеке Bundler, чтобы держать всю информацию об используемых библиотеках в вашем проекте в одном месте.<br/>\r\n<br/>\r\nBundler точно такой же gem как и любые другие, после его установки как gem install bundler он позволяе управлять библиотеками, их версиями и зависимостями в вашем проекте. Проект чаще всего может быть Rails3 и уже 4, Sinatra, Espresso, Cramp или вообще просто набор классов вашего приложения без использования каких либо фрэмворков.<br/>\r\n<br/>\r\nРассмотрим дальше работу с Bundler.</p><p>\n    Чаще всего в своих проектах для управления набором gem библиотек мы используем Bundler, а потом уже rvm gemset''ы.<br/>\r\nЭто маленькая шпаргалка о библиотеке Bundler, чтобы держать всю информацию об используемых библиотеках в вашем проекте в одном месте.<br/>\r\n<br/>\r\nBundler точно такой же gem как и любые другие, после его установки как gem install bundler он позволяе управлять библиотеками, их версиями и зависимостями в вашем проекте. Проект чаще всего может быть Rails3 и уже 4, Sinatra, Espresso, Cramp или вообще просто набор классов вашего приложения без использования каких либо фрэмворков.<br/>\r\n<br/>\r\nBundler позволяет хранить в одном файле указания о том какие gem библиотеки вы используете в текущем проекте, какие версии и даже для какого окружения. Все директивы необходимых библиотек описываются в файле Gemfile и желательно в корне вашего приложения.<br/>\r\nОткрыв Gemfile на редактирование часто можно увидеть нечто подобное:<br/>\r\n</p><pre><code class="ruby">source ''https://rubygems.org''\n\ngem ''rails'', ''3.2.11''\n# Bundle edge Rails instead:\n# gem ''rails'', :git => ''git://github.com/rails/rails.git''\n\ngem ''mysql2''\ngem ''gon''\n\ngem ''bcrypt-ruby'', ''~> 3.0.0''\n\n# Use unicorn as the app server\ngroup :production do\n  gem ''unicorn''\nend</code></pre>\n<p>\n    Создав такой файл (или получив его уже из сгенерированного проекта) мы можем установить все библиотеки с зависимостями на наше компьютер, для этого нужно набрать в консоли и выполнить<br/>\r\n<br/>\r\n&gt; bundle install<br/>\r\n<br/>\r\nПосле чего Bundler прочитает Gemfile в текущей директории, построит дерево зависимостей всех библиотек, укажет на ошибки или конфликты и начнет скачивание, установку (иногда компиляцию) библиотек в директорию ~/.bundle или в случае использования rvm в папку текущего gemset (global по умолчанию).<br/>\r\n<br/>\r\nНемного о формате команд в Gemfile.<br/>\r\n<br/>\r\nsource ''https://rubygems.org'' — указывает наш источник, хранилище, некий cpan (pear) репозитарий всех библиотек<br/>\r\n<br/>\r\ngem ''rails'', ''3.2.11'' — указывает установить библиотеку rails (да Ruby on Rails это лишь набор библиотек связанных в один bundle), второй параметр это указание версии, если ее не указать, то Bundler будет искать последнюю и более или менее стабильную версию.<br/>\r\n<br/>\r\nНо иногда лучше указывать жестко версию или приблизительно, для этого есть все возможности:<br/>\r\ngem ''название-библиотеки'', ''&gt; 3.2.11'' — установить версию точно выше версии 3.2.11<br/>\r\ngem ''название-библиотеки'', ''=&gt; 3.2.11'' — выше или точную версию 3.2.11<br/>\r\ngem ''название-библиотеки'', ''&gt; 3.2.11'', ''&lt; 4.0.0'' — ниже 4-ой версии, но выше 3.2.11 версии<br/>\r\ngem ''название-библиотеки'', ''~&gt; 3.2'' — выше версии 3.2, но ниже мажорной (первый номер) 4.0 ( 3.2 &gt; x &lt; 4.0)<br/>\r\n<br/>\r\nДополнительно можно ставить gem библиотеки например из git репозитариев, к пример с самого популярного как github, для этого передается параметром хэш с символом :git<br/>\r\ngem ''letsrate'', :git =&gt; ''git://github.com/robban/letsrate.git''<br/>\r\n<br/>\r\nМожно установить gem и со своего компьютера, например из папки:<br/>\r\ngem ''succubus'', :path=&gt;''/home/mpak/gems/succubus''<br/>\r\n<br/>\r\nБлок group :production do… end позволяет включить установку gem библиотек описанных внутри блока только для определенного окружения, например для development нам достаточно иметь gem ''sqlite3'', а для production хотелось бы уже поработать с gem ''pg'' group позволяет задать различные библиотеки таким образом.<br/>\r\n<br/>\r\nЕсли производим какие изменения в Gemfile то следует запускать обновление библиотек:<br/>\r\n&gt; bundle update<br/>\r\n<br/>\r\nТак же у Bundler есть возможность выгрузить все библиотеки в проектную папку, это будет vendor/cache, после чего можно отдать проект на машину, где к примеру нет интернета, чтобы выгружать библиотеки, делается это командой:<br/>\r\n&gt; bundle package<br/>\r\n<br/>\r\nПосле чего Bundler будет ставить ваши gem''ы из этой папки.<br/>\r\n<br/>\r\nИ еще, постоянные установки ваших библиотек через gem или bundle заставляют генерироваться каждый раз документацию для каждой библиотеки, в целом это немного немного напрягает, из-за ненадобности, да и время тратится. Чтобы избежать постоянной генерации документации, пропишите в файле в домашней директории ~/.gemrc строку:<br/>\r\ngem: --no-ri --no-rdoc<br/>\r\n<br/>\r\nИ после чего постоянная генерация документации пропадет и установка библиотек станет заметно быстрее.<br/>\r\n<br/>\r\nУдачных установок.</p>'),
(2, '2013-05-20 18:28:31', 4, 1, 0, 'Умный отлов ошибок с better_errors', 'gem библиотеки для отлова ошибок better_errors, binding_of_caller, meta_requests, railspanel', 'debug, better_errors, railspanel, meta_requests', 0, 0, 1, 0, '<p>\n    better_errors<br/>\r\nrailspanel + meta_requests</p><a href="/blog/show/2-ymniy-otlov-oshibok-s-better_errors.html" class="read-more">Подробнее&hellip;</a>', '<p>\n    better_errors<br/>\r\nrailspanel + meta_requests</p><p>\n    </p><div class="picture"><a href="/files/picture/" target="_blank" class="picture-thumb"><img src="/files/picture/mini/0978a5f61faa157660a0760aed65cdcc.jpg" alt="better_errors результаты работы" /></a></div>'),
(3, '2013-05-25 21:04:15', 4, 1, 0, 'Алилуя', '', 'gem, gon', 0, 0, 0, 0, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tagged_objects`
--

CREATE TABLE IF NOT EXISTS `tagged_objects` (
  `tag_id` int(10) NOT NULL,
  `object_id` int(10) NOT NULL,
  `object_type` int(2) NOT NULL,
  KEY `object_id` (`object_id`,`object_type`),
  KEY `tag_id` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tagged_objects`
--

INSERT INTO `tagged_objects` (`tag_id`, `object_id`, `object_type`) VALUES
(1, 1, 1),
(2, 1, 1),
(3, 2, 1),
(4, 2, 1),
(5, 2, 1),
(6, 2, 1),
(7, 3, 1),
(8, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tag` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tag` (`tag`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `tag`) VALUES
(4, 'better_errors'),
(1, 'bundler'),
(3, 'debug'),
(7, 'gem'),
(8, 'gon'),
(6, 'meta_requests'),
(5, 'railspanel'),
(2, 'tips');

-- --------------------------------------------------------

--
-- Table structure for table `texts`
--

CREATE TABLE IF NOT EXISTS `texts` (
  `module_id` int(10) NOT NULL,
  `short` text NOT NULL,
  `full` text NOT NULL,
  `original` text NOT NULL,
  PRIMARY KEY (`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `texts`
--

INSERT INTO `texts` (`module_id`, `short`, `full`, `original`) VALUES
(1, 'Чаще всего в своих проектах для управления набором gem библиотек мы используем Bundler, а потом уже rvm gemset''ы.<br/>\r\nЭто маленькая шпаргалка о библ', 'Чаще всего в своих проектах для управления набором gem библиотек мы используем Bundler, а потом уже rvm gemset''ы.<br/>\r\nЭто маленькая шпаргалка о библиотеке Bundler, чтобы держать всю информацию об используемых библиотеках в вашем проекте в одном месте.<br/>\r\n<br/>\r\nBundler точно такой же gem как и любые другие, после его установки как gem install bundler он позволяе управлять библиотеками, их версиями и зависимостями в вашем проекте. Проект чаще всего может быть Rails3 и уже 4, Sinatra, Espresso, Cramp или вообще просто набор классов вашего приложения без использования каких либо фрэмворков.<br/>\r\n<br/>\r\nРассмотрим дальше работу с Bundler.', 'Чаще всего в своих проектах для управления набором gem библиотек мы используем Bundler, а потом уже rvm gemset''ы.\nЭто маленькая шпаргалка о библиотеке Bundler, чтобы держать всю информацию об используемых библиотеках в вашем проекте в одном месте.\n\nBundler точно такой же gem как и любые другие, после его установки как gem install bundler он позволяе управлять библиотеками, их версиями и зависимостями в вашем проекте. Проект чаще всего может быть Rails3 и уже 4, Sinatra, Espresso, Cramp или вообще просто набор классов вашего приложения без использования каких либо фрэмворков.\n\nРассмотрим дальше работу с Bundler.'),
(3, 'Чаще всего в своих проектах для управления набором gem библиотек мы используем Bundler, а потом уже rvm gemset''ы.<br/>\r\nЭто маленькая шпаргалка о библ', 'Чаще всего в своих проектах для управления набором gem библиотек мы используем Bundler, а потом уже rvm gemset''ы.<br/>\r\nЭто маленькая шпаргалка о библиотеке Bundler, чтобы держать всю информацию об используемых библиотеках в вашем проекте в одном месте.<br/>\r\n<br/>\r\nBundler точно такой же gem как и любые другие, после его установки как gem install bundler он позволяе управлять библиотеками, их версиями и зависимостями в вашем проекте. Проект чаще всего может быть Rails3 и уже 4, Sinatra, Espresso, Cramp или вообще просто набор классов вашего приложения без использования каких либо фрэмворков.<br/>\r\n<br/>\r\nBundler позволяет хранить в одном файле указания о том какие gem библиотеки вы используете в текущем проекте, какие версии и даже для какого окружения. Все директивы необходимых библиотек описываются в файле Gemfile и желательно в корне вашего приложения.<br/>\r\nОткрыв Gemfile на редактирование часто можно увидеть нечто подобное:<br/>\r\n', 'Чаще всего в своих проектах для управления набором gem библиотек мы используем Bundler, а потом уже rvm gemset''ы.\nЭто маленькая шпаргалка о библиотеке Bundler, чтобы держать всю информацию об используемых библиотеках в вашем проекте в одном месте.\n\nBundler точно такой же gem как и любые другие, после его установки как gem install bundler он позволяе управлять библиотеками, их версиями и зависимостями в вашем проекте. Проект чаще всего может быть Rails3 и уже 4, Sinatra, Espresso, Cramp или вообще просто набор классов вашего приложения без использования каких либо фрэмворков.\n\nBundler позволяет хранить в одном файле указания о том какие gem библиотеки вы используете в текущем проекте, какие версии и даже для какого окружения. Все директивы необходимых библиотек описываются в файле Gemfile и желательно в корне вашего приложения.\nОткрыв Gemfile на редактирование часто можно увидеть нечто подобное:\n'),
(5, 'Создав такой файл (или получив его уже из сгенерированного проекта) мы можем установить все библиотеки с зависимостями на наше компьюте', 'Создав такой файл (или получив его уже из сгенерированного проекта) мы можем установить все библиотеки с зависимостями на наше компьютер, для этого нужно набрать в консоли и выполнить<br/>\r\n<br/>\r\n&gt; bundle install<br/>\r\n<br/>\r\nПосле чего Bundler прочитает Gemfile в текущей директории, построит дерево зависимостей всех библиотек, укажет на ошибки или конфликты и начнет скачивание, установку (иногда компиляцию) библиотек в директорию ~/.bundle или в случае использования rvm в папку текущего gemset (global по умолчанию).<br/>\r\n<br/>\r\nНемного о формате команд в Gemfile.<br/>\r\n<br/>\r\nsource ''https://rubygems.org'' — указывает наш источник, хранилище, некий cpan (pear) репозитарий всех библиотек<br/>\r\n<br/>\r\ngem ''rails'', ''3.2.11'' — указывает установить библиотеку rails (да Ruby on Rails это лишь набор библиотек связанных в один bundle), второй параметр это указание версии, если ее не указать, то Bundler будет искать последнюю и более или менее стабильную версию.<br/>\r\n<br/>\r\nНо иногда лучше указывать жестко версию или приблизительно, для этого есть все возможности:<br/>\r\ngem ''название-библиотеки'', ''&gt; 3.2.11'' — установить версию точно выше версии 3.2.11<br/>\r\ngem ''название-библиотеки'', ''=&gt; 3.2.11'' — выше или точную версию 3.2.11<br/>\r\ngem ''название-библиотеки'', ''&gt; 3.2.11'', ''&lt; 4.0.0'' — ниже 4-ой версии, но выше 3.2.11 версии<br/>\r\ngem ''название-библиотеки'', ''~&gt; 3.2'' — выше версии 3.2, но ниже мажорной (первый номер) 4.0 ( 3.2 &gt; x &lt; 4.0)<br/>\r\n<br/>\r\nДополнительно можно ставить gem библиотеки например из git репозитариев, к пример с самого популярного как github, для этого передается параметром хэш с символом :git<br/>\r\ngem ''letsrate'', :git =&gt; ''git://github.com/robban/letsrate.git''<br/>\r\n<br/>\r\nМожно установить gem и со своего компьютера, например из папки:<br/>\r\ngem ''succubus'', :path=&gt;''/home/mpak/gems/succubus''<br/>\r\n<br/>\r\nБлок group :production do… end позволяет включить установку gem библиотек описанных внутри блока только для определенного окружения, например для development нам достаточно иметь gem ''sqlite3'', а для production хотелось бы уже поработать с gem ''pg'' group позволяет задать различные библиотеки таким образом.<br/>\r\n<br/>\r\nЕсли производим какие изменения в Gemfile то следует запускать обновление библиотек:<br/>\r\n&gt; bundle update<br/>\r\n<br/>\r\nТак же у Bundler есть возможность выгрузить все библиотеки в проектную папку, это будет vendor/cache, после чего можно отдать проект на машину, где к примеру нет интернета, чтобы выгружать библиотеки, делается это командой:<br/>\r\n&gt; bundle package<br/>\r\n<br/>\r\nПосле чего Bundler будет ставить ваши gem''ы из этой папки.<br/>\r\n<br/>\r\nИ еще, постоянные установки ваших библиотек через gem или bundle заставляют генерироваться каждый раз документацию для каждой библиотеки, в целом это немного немного напрягает, из-за ненадобности, да и время тратится. Чтобы избежать постоянной генерации документации, пропишите в файле в домашней директории ~/.gemrc строку:<br/>\r\ngem: --no-ri --no-rdoc<br/>\r\n<br/>\r\nИ после чего постоянная генерация документации пропадет и установка библиотек станет заметно быстрее.<br/>\r\n<br/>\r\nУдачных установок.', 'Создав такой файл (или получив его уже из сгенерированного проекта) мы можем установить все библиотеки с зависимостями на наше компьютер, для этого нужно набрать в консоли и выполнить\n\n> bundle install\n\nПосле чего Bundler прочитает Gemfile в текущей директории, построит дерево зависимостей всех библиотек, укажет на ошибки или конфликты и начнет скачивание, установку (иногда компиляцию) библиотек в директорию ~/.bundle или в случае использования rvm в папку текущего gemset (global по умолчанию).\n\nНемного о формате команд в Gemfile.\n\nsource ''https://rubygems.org'' - указывает наш источник, хранилище, некий cpan (pear) репозитарий всех библиотек\n\ngem ''rails'', ''3.2.11'' - указывает установить библиотеку rails (да Ruby on Rails это лишь набор библиотек связанных в один bundle), второй параметр это указание версии, если ее не указать, то Bundler будет искать последнюю и более или менее стабильную версию.\n\nНо иногда лучше указывать жестко версию или приблизительно, для этого есть все возможности:\ngem ''название-библиотеки'', ''> 3.2.11'' - установить версию точно выше версии 3.2.11\ngem ''название-библиотеки'', ''=> 3.2.11'' - выше или точную версию 3.2.11\ngem ''название-библиотеки'', ''> 3.2.11'', ''< 4.0.0'' - ниже 4-ой версии, но выше 3.2.11 версии\ngem ''название-библиотеки'', ''~> 3.2'' - выше версии 3.2, но ниже мажорной (первый номер) 4.0 ( 3.2 > x < 4.0)\n\nДополнительно можно ставить gem библиотеки например из git репозитариев, к пример с самого популярного как github, для этого передается параметром хэш с символом :git\ngem ''letsrate'', :git => ''git://github.com/robban/letsrate.git''\n\nМожно установить gem и со своего компьютера, например из папки:\ngem ''succubus'', :path=>''/home/mpak/gems/succubus''\n\nБлок group :production do ... end позволяет включить установку gem библиотек описанных внутри блока только для определенного окружения, например для development нам достаточно иметь gem ''sqlite3'', а для production хотелось бы уже поработать с gem ''pg'' group позволяет задать различные библиотеки таким образом.\n\nЕсли производим какие изменения в Gemfile то следует запускать обновление библиотек:\n> bundle update\n\nТак же у Bundler есть возможность выгрузить все библиотеки в проектную папку, это будет vendor/cache, после чего можно отдать проект на машину, где к примеру нет интернета, чтобы выгружать библиотеки, делается это командой:\n> bundle package\n\nПосле чего Bundler будет ставить ваши gem''ы из этой папки.\n\nИ еще, постоянные установки ваших библиотек через gem или bundle заставляют генерироваться каждый раз документацию для каждой библиотеки, в целом это немного немного напрягает, из-за ненадобности, да и время тратится. Чтобы избежать постоянной генерации документации, пропишите в файле в домашней директории  ~/.gemrc строку:\ngem: --no-ri --no-rdoc\n\nИ после чего постоянная генерация документации пропадет и установка библиотек станет заметно быстрее.\n\nУдачных установок.'),
(6, 'better_errors<br/>\r\nrailspanel + meta_requests', 'better_errors<br/>\r\nrailspanel + meta_requests', 'better_errors\nrailspanel + meta_requests'),
(13, 'first text', 'first text', 'first text'),
(14, 'second text', 'second text', 'second text');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `registered_at` datetime NOT NULL,
  `logined_at` datetime NOT NULL,
  `login` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `salt` varchar(128) NOT NULL,
  `avatar` varchar(128) NOT NULL,
  `rating` int(11) NOT NULL,
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `role` varchar(16) NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `registered_at`, `logined_at`, `login`, `email`, `password`, `salt`, `avatar`, `rating`, `banned`, `role`) VALUES
(4, '2012-04-17 21:51:59', '2013-09-15 22:01:59', 'Admin', 'admin@bmf.ru', '04b23fa8f205a446303e020252b7391323738ea7', '5a3b07273666', '3fff9e9a0025d2431ae2a3fb4dc70515.png', 0, 0, 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE IF NOT EXISTS `votes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `added_at` datetime NOT NULL,
  `post_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `weight` int(2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
