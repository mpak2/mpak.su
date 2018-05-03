<? # modules/themes/init.php

if(mpsettings($t = "themes_intersect", "Пересечения") && !tables($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `hide` smallint(6) DEFAULT NULL,
  `admin-access` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `href` varchar(255) DEFAULT NULL COMMENT 'Путь до файла',
  PRIMARY KEY (`id`),
  UNIQUE KEY `href_2` (`href`),
  KEY `href` (`href`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");
} 

mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('themes', 'theme', 'mpak.su', '1', 'Текущая тема')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('themes', 'title', 'Создание сайтов', '1', 'Заголовок страницы')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('themes', 'microtime', '1', '1', 'Время генерации страницы')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('themes', 'str_bottom', '<a href=\"http://mpak.su\">http://mpak.su</a>', '1', 'Надпись в нижней части')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('themes', 'start_mod', 'array://a:2:{s:1:\"m\";a:1:{s:5:\"pages\";s:0:\"\";}s:3:\"pid\";s:3:\"414\";}', '1', 'Модуль при загрузке')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('themes', 'theme_block_width', '20', '1', 'Ширина блока')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('themes', 'theme_block_color', '#a2fbb3', '1', 'Цвет блока')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('themes', 'theme/*:admin', 'zhiraf', '5', 'Тема для админстраниц всех модулей')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('themes', 'theme/admin:*', 'zhiraf', '5', 'Тема для всех страниц модуля админ')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('themes', 'keywords', 'Сайт бесплатно, быстрая регистрация, html страницы, верстка, программирование, удаленная работа', '1', 'Ключевые слова сайта.')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('themes', 'description', 'Создать сайт бесплатно за две минуты', '1', 'Описание сайта')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('themes', 'themes_watermark', 'img/logo_img.png:10:-10', '5', 'Ватермарк для изображений файлы')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('themes', 'themes_month', ',января,февраля,марта,апреля,мая,июня,июля,августа,сентября,октября,ноября,декабря', '0', 'Список названий месяцев')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('themes', 'inputmask', '<script src=\"/include/jquery/inputmask/jquery.inputmask.js\" type=\"text/javascript\"></script><script src=\"/include/jquery/inputmask/jquery.inputmask.extensions.js\" type=\"text/javascript\"></script><script src=\"/include/jquery/inputmask/jquery.inputmask.date.extensions.js\" type=\"text/javascript\"></script><script src=\"/include/jquery/inputmask/jquery.inputmask.numeric.extensions.js\" type=\"text/javascript\"></script><script src=\"/include/jquery/inputmask/jquery.inputmask.custom.extensions.js\" type=\"text/javascript\"></script>', '4', 'Все для шаблона ввода текста')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('themes', 'theme_exec', '1', '4', 'Исполнение шаблона')");
