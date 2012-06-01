-- phpMyAdmin SQL Dump
-- version 3.4.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 01, 2012 at 04:22 PM
-- Server version: 5.1.62
-- PHP Version: 5.3.5-1ubuntu7.8

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
(18, 'php', 'public function _remap($method, $params = array())\n{\n    $method = ''process_''.$method;\n    if (method_exists($this, $method))\n    {\n        return call_user_func_array(array($this, $method), $params);\n    }\n    show_404();\n}');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `post_id`, `ord`, `name`) VALUES
(4, 1, 2, 'text'),
(5, 1, 3, 'code'),
(6, 1, 4, 'code'),
(7, 2, 1, 'text'),
(8, 3, 1, 'text'),
(9, 3, 2, 'code'),
(10, 4, 1, 'text'),
(11, 4, 2, 'code'),
(12, 5, 1, 'text'),
(13, 5, 2, 'cut'),
(14, 5, 3, 'text'),
(15, 4, 3, 'cut'),
(16, 4, 4, 'text'),
(17, 6, 1, 'text'),
(18, 6, 2, 'code');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `added_at`, `user_id`, `type`, `category_id`, `title`, `tags`, `rating`, `views`, `published`, `deleted`, `cut`, `full`) VALUES
(1, '2012-05-19 01:16:01', 4, 1, 0, 'Первый пост в супер блоге', 'пост, блог, сообщение', 0, 0, 0, 0, '', ''),
(2, '2012-05-28 16:12:58', 4, 1, 0, 'feefeerwe', 'rwerwerwer', 0, 0, 0, 0, '', ''),
(3, '2012-05-30 13:40:54', 4, 1, 0, 'Супер топик', 'топик, хуй', 0, 0, 0, 0, '', ''),
(4, '2012-05-31 14:33:48', 4, 1, 0, 'LAMP разработка под Ubuntu', 'linux, ubuntu, windows', 0, 0, 1, 0, '<p>\n    Хм, решил немного развеяться и написать об Ubuntu.<br />\nВ общем-то я давно знаком с этой системой, в первое знакомство она произвела приятно впечатление, легкость установки, простой пакетный менеджер и обилие пакетов - это радовало.<br />\nНо с годами я вижу, что система становится скоплением какого-то хлама, всё хуже и хуже.<br />\nВплоть до того, что я даже застрял на 11.04 и дальше у меня желания обновляться нету, а вот почему...</p>show code', '<p>\n    Хм, решил немного развеяться и написать об Ubuntu.<br />\nВ общем-то я давно знаком с этой системой, в первое знакомство она произвела приятно впечатление, легкость установки, простой пакетный менеджер и обилие пакетов - это радовало.<br />\nНо с годами я вижу, что система становится скоплением какого-то хлама, всё хуже и хуже.<br />\nВплоть до того, что я даже застрял на 11.04 и дальше у меня желания обновляться нету, а вот почему...</p>show code<p>\n    <br />\nЯ постонно веду разработку, пишу код, работаю над новыми проектами, допиливаю старые, делаю свои проекты, пишу статьи, читаю и всё это разумеется с лэптопа (ну больше читаю с планшета хотя). У меня есть два лэптопа, очень мощный Dell, заряженный по самые помядоры мощными начинками как хорошая видео карта, i7 проц b 8 gb памяти (я думаю на неделях я бы даже dvd-ram вынул бы и туда ssd влепил бы). И второй, с очень удобной клавой, небольшой Lenovo IdePad 11.6&quot;, который в общем-то тоже не детская игрушка и заряжен хорошим amd64 процессором, 4 GB памяти добиты.<br />\n<br />\nНу так вот, Dell у меня это основной гигант, ударный, на нём стоит Windows 7, я качаю и веду усидчивую разработку под Windows. Проблемы нет. Apache и PHP последние, всё ставил легко из msi пакетов, настраивал ручками, работают инструменты как часы, проверено годами.<br />\nИз баз постоянно стоят MySQL, MongoDB, тоже всё ручками. В фоне крутиться AIMP3, висит Skype, ICQ клиенты, ну и uTorrent покачивает иногда. В общем-то и всё. Обычно на оном я веду разработку под PHP и правлю старые Perl проекты и скрипты. Играл, но уже давно не было.<br />\nИз редакторов у меня всегда запущен NetBeans 7.1, иногда Aptana Studio и/или Sublime Text2. Два последних для разработки под Ruby, но это немного хитро, так как у меня в такой момент запущен еще Virtual Box с крутящейся Ubunt&#039;ой и общим между Win и VB папочкой, где ведётся разработка. Ну и браузер я перешел на Chrome, потому как FireFox последнее время стал жутко жрать и тормозить.<br />\nА ну и разумеется FAR manager запущен всегда, это то чего мне по привычке не хватает в Ubuntu (кто сразу начал строчить в комментах об MC может не делать этого, MC это не моя привычка, не те возможности и вообще нюхает ноги у FAR&#039;а). В общем это отличная рабочая машинка и очень производиельная, нареканий в работе нет. Машинкой и работой на DELL я полностью доволен.<br />\nНо, иногда бывает хочется пересесть на диван или на балконе посидеть с трубкой канабиса :))), положить ноут на коленки и просто попечатать на оном, вот как сейчас. Каждый раз выдергивать провода из алюминивого Делл-друга утомительно, тем более его размеры в общем-то большие.<br />\n<br />\nДля этого у меня и существует второй ноутбук - Lenovo. По умолчанию на нём стояла Винда, но я её снёс сразу же, ибо брал сразу с прицелом на Ubuntu и разработку под Ruby в &quot;родной&quot; среде. Как я сношался с EFI биосом и grub2 когда ставил пингвина, это другая история. Но в общем-то есть и работает, Ubuntu 11.04 + Gnome2 (unity ушёл лесом за корявость).<br />\nСтоит Apache, PHP, rvm + разные версии ruby с gemset на проекты. Из баз данных ставил MySQL, так же MongoDB.<br />\nРедактор у меня основной опять же NetBeans 7.0.1 (надо обновить) + Sublime Text2, стоит еще Aptana, но как-то давно его уже включаю.<br />\nОбычно фоном запущены еще музыкальные проигрыватели, сколько их уже поменял хрен вспомнишь, последний был Clementine, недавно снёс и заменил на Deadbeaf (спасибо kinbot&#039;у за совет). Так же весит на горячих клавишах Guake. Ну и кажись всё.<br />\nНу еще браузер Chromium пользую, есть Firefox но тоже не запускаю уже давно. Skype, QuitIM в фоне.<br />\n<br />\nВот всё бы вроде отлично, да вот жутко бывает не производительна эта Ubuntu, начал это замечать. Так отойдешь на минут 10 от NetBeans, потом возвращаешьс и можно минуты 2 ждать пока она лагает. Такое же с Chromium. А уж пожирающие python процессы при обновлении пакетов, которые могут и одно ядро на 100% сожрать я молчу. Жуть. И при этом мой Htop всегда показывает что в Ubuntu есть еще как минимум 2 gb памяти свободной! Это бесит, когда ты всё больше и больше работаешь в этой системе. Прям аж до чертиков начинает бесить.<br />\n<br />\nИ вот сравниваешь, сколько времени лагает Линукс и как производительна в разработке Винда (пусть даже с виртуальным линуксом в связке, который не тормозит) и это всё не в пользу пингвина, Винда выигрывает, потому как визуально я не ощущаю тормозов, резкое переключение, даже в интерфейсе Aero от Windows7!<br />\n<br />\nА Ubuntu начинает подташнивать, нажал, повис, подождал, не часто, но случается и очень часто начинаешь обращать на это внимание. Конечно можно погрешить на Java, раз уж NetBeans так провисает. Тогда как быть с Chromium? Или музыкальным плэйером, который решает процессор при проигрывании mp3 грузить на 15-20%?<br />\n<br />\nВот в общем посравниваешь так, визуально работать в Винде лучше, быстрее, продуктивнее и удобнее. Софт ставится очень быстро, настроил раз и работай, прежних &quot;мифов&quot; о синих экранах я уже давно не встречал.<br />\nА вот конечно серверно-консольные дела в Убунте это без базаров.<br />\n<br />\nНу а я в общем-то жду результатов от MacOS на новой работе у меня будет возможность персонально юзать это, посмотрим на homebrew, разработку в mamp, ruby, xcode, может действительно MacOS окажется Линуксом с человеческим лицом для разработчика, доживём и увидим!</p>'),
(5, '2012-05-31 23:06:59', 4, 1, 0, 'Текст с подкатом', 'текст, кат', 0, 0, 1, 0, '<p>\n     Это будет текст подкатом</p>', '<p>\n     Это будет текст подкатом</p><p>\n    а это уже большой и полный текст, йоу!!!!</p>'),
(6, '2012-06-01 14:42:51', 4, 1, 0, 'Новый топик с кодом', 'код, топик', 0, 0, 1, 0, '<p>\n    какой-то там текст</p><pre><code class="php">public function _remap($method, $params = array())\n{\n    $method = ''process_''.$method;\n    if (method_exists($this, $method))\n    {\n        return call_user_func_array(array($this, $method), $params);\n    }\n    show_404();\n}</code></pre>\n', '<p>\n    какой-то там текст</p><pre><code class="php">public function _remap($method, $params = array())\n{\n    $method = ''process_''.$method;\n    if (method_exists($this, $method))\n    {\n        return call_user_func_array(array($this, $method), $params);\n    }\n    show_404();\n}</code></pre>\n');

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
(1, 847, 1),
(2, 847, 1),
(3, 1, 1),
(4, 1, 1),
(5, 1, 1),
(6, 2, 1),
(7, 3, 1),
(8, 3, 1),
(9, 4, 1),
(10, 4, 1),
(11, 4, 1),
(12, 5, 1),
(13, 5, 1),
(14, 6, 1),
(7, 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tag` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tag` (`tag`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `tag`) VALUES
(9, 'linux'),
(6, 'rwerwerwer'),
(10, 'ubuntu'),
(11, 'windows'),
(4, 'блог'),
(13, 'кат'),
(14, 'код'),
(3, 'пост'),
(5, 'сообщение'),
(12, 'текст'),
(1, 'тема'),
(7, 'топик'),
(2, 'уно'),
(8, 'хуй');

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
(8, '', 'аывавыа ыавыа', ''),
(10, '', 'Хм, решил немного развеяться и написать об Ubuntu.\nВ общем-то я давно знаком с этой системой, в первое знакомство она произвела приятно впечатление, легкость установки, простой пакетный менеджер и обилие пакетов - это радовало.\nНо с годами я вижу, что система становится скоплением какого-то хлама, всё хуже и хуже.\nВплоть до того, что я даже застрял на 11.04 и дальше у меня желания обновляться нету, а вот почему...', ''),
(12, '', ' Это будет текст подкатом', ''),
(14, '', 'а это уже большой и полный текст, йоу!!!!', ''),
(16, '', '\nЯ постонно веду разработку, пишу код, работаю над новыми проектами, допиливаю старые, делаю свои проекты, пишу статьи, читаю и всё это разумеется с лэптопа (ну больше читаю с планшета хотя). У меня есть два лэптопа, очень мощный Dell, заряженный по самые помядоры мощными начинками как хорошая видео карта, i7 проц b 8 gb памяти (я думаю на неделях я бы даже dvd-ram вынул бы и туда ssd влепил бы). И второй, с очень удобной клавой, небольшой Lenovo IdePad 11.6&quot;, который в общем-то тоже не детская игрушка и заряжен хорошим amd64 процессором, 4 GB памяти добиты.\n\nНу так вот, Dell у меня это основной гигант, ударный, на нём стоит Windows 7, я качаю и веду усидчивую разработку под Windows. Проблемы нет. Apache и PHP последние, всё ставил легко из msi пакетов, настраивал ручками, работают инструменты как часы, проверено годами.\nИз баз постоянно стоят MySQL, MongoDB, тоже всё ручками. В фоне крутиться AIMP3, висит Skype, ICQ клиенты, ну и uTorrent покачивает иногда. В общем-то и всё. Обычно на оном я веду разработку под PHP и правлю старые Perl проекты и скрипты. Играл, но уже давно не было.\nИз редакторов у меня всегда запущен NetBeans 7.1, иногда Aptana Studio и/или Sublime Text2. Два последних для разработки под Ruby, но это немного хитро, так как у меня в такой момент запущен еще Virtual Box с крутящейся Ubunt&#039;ой и общим между Win и VB папочкой, где ведётся разработка. Ну и браузер я перешел на Chrome, потому как FireFox последнее время стал жутко жрать и тормозить.\nА ну и разумеется FAR manager запущен всегда, это то чего мне по привычке не хватает в Ubuntu (кто сразу начал строчить в комментах об MC может не делать этого, MC это не моя привычка, не те возможности и вообще нюхает ноги у FAR&#039;а). В общем это отличная рабочая машинка и очень производиельная, нареканий в работе нет. Машинкой и работой на DELL я полностью доволен.\nНо, иногда бывает хочется пересесть на диван или на балконе посидеть с трубкой канабиса :))), положить ноут на коленки и просто попечатать на оном, вот как сейчас. Каждый раз выдергивать провода из алюминивого Делл-друга утомительно, тем более его размеры в общем-то большие.\n\nДля этого у меня и существует второй ноутбук - Lenovo. По умолчанию на нём стояла Винда, но я её снёс сразу же, ибо брал сразу с прицелом на Ubuntu и разработку под Ruby в &quot;родной&quot; среде. Как я сношался с EFI биосом и grub2 когда ставил пингвина, это другая история. Но в общем-то есть и работает, Ubuntu 11.04 + Gnome2 (unity ушёл лесом за корявость).\nСтоит Apache, PHP, rvm + разные версии ruby с gemset на проекты. Из баз данных ставил MySQL, так же MongoDB.\nРедактор у меня основной опять же NetBeans 7.0.1 (надо обновить) + Sublime Text2, стоит еще Aptana, но как-то давно его уже включаю.\nОбычно фоном запущены еще музыкальные проигрыватели, сколько их уже поменял хрен вспомнишь, последний был Clementine, недавно снёс и заменил на Deadbeaf (спасибо kinbot&#039;у за совет). Так же весит на горячих клавишах Guake. Ну и кажись всё.\nНу еще браузер Chromium пользую, есть Firefox но тоже не запускаю уже давно. Skype, QuitIM в фоне.\n\nВот всё бы вроде отлично, да вот жутко бывает не производительна эта Ubuntu, начал это замечать. Так отойдешь на минут 10 от NetBeans, потом возвращаешьс и можно минуты 2 ждать пока она лагает. Такое же с Chromium. А уж пожирающие python процессы при обновлении пакетов, которые могут и одно ядро на 100% сожрать я молчу. Жуть. И при этом мой Htop всегда показывает что в Ubuntu есть еще как минимум 2 gb памяти свободной! Это бесит, когда ты всё больше и больше работаешь в этой системе. Прям аж до чертиков начинает бесить.\n\nИ вот сравниваешь, сколько времени лагает Линукс и как производительна в разработке Винда (пусть даже с виртуальным линуксом в связке, который не тормозит) и это всё не в пользу пингвина, Винда выигрывает, потому как визуально я не ощущаю тормозов, резкое переключение, даже в интерфейсе Aero от Windows7!\n\nА Ubuntu начинает подташнивать, нажал, повис, подождал, не часто, но случается и очень часто начинаешь обращать на это внимание. Конечно можно погрешить на Java, раз уж NetBeans так провисает. Тогда как быть с Chromium? Или музыкальным плэйером, который решает процессор при проигрывании mp3 грузить на 15-20%?\n\nВот в общем посравниваешь так, визуально работать в Винде лучше, быстрее, продуктивнее и удобнее. Софт ставится очень быстро, настроил раз и работай, прежних &quot;мифов&quot; о синих экранах я уже давно не встречал.\nА вот конечно серверно-консольные дела в Убунте это без базаров.\n\nНу а я в общем-то жду результатов от MacOS на новой работе у меня будет возможность персонально юзать это, посмотрим на homebrew, разработку в mamp, ruby, xcode, может действительно MacOS окажется Линуксом с человеческим лицом для разработчика, доживём и увидим!', ''),
(17, '', 'какой-то там текст', '');

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
(1, '2012-04-17 21:07:00', '2012-05-09 21:44:55', 'MpaK', 'mpak_work@inbox.ru', '864ec5bef5768c4252ecd320ec4228478c2fd849', 'f6e09db4f6e0', '858de61cecafe0d532003deedf143781.jpg', 0, 0, 'user'),
(2, '2012-04-17 21:39:21', '0000-00-00 00:00:00', 'MpaKus', 'mrak69@gmail.com', '6e736b8f4a8fc3e6c60261c822aaa106412fe420', 'a4d940f6279b', '', 0, 0, 'user'),
(4, '2012-04-17 21:51:59', '2012-06-01 14:39:52', 'Admin', 'mrak69@gmail.com', '04b23fa8f205a446303e020252b7391323738ea7', '5a3b07273666', '72a13bcacac59c2fdfe6c650e2d1c1fb.gif', 0, 0, 'admin');

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
