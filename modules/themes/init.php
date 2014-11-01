<? die;

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modpath` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `theme` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251";
mpqw($sql);

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_blk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `theme` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251";
mpqw($sql);

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_grab` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251";
mpqw($sql);

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_svg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `svg` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251";
mpqw($sql);

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
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('themes', 'description', 'Создать сайт бесплатно за две минуты. Удаленная работа. Разработка портальной системы', '1', 'Описание сайта')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('themes', 'theme/develop:develop', 'zhiraf', '5', 'Тема для страницы планировщика')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('themes', 'theme_logo', 'i/logo_img.png:10:-10', '5', 'Логотип на графические файлы')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('themes', 'themes_month', ',января,февраля,марта,апреля,мая,июня,июля,августа,сентября,октября,ноября,декабря', '0', 'Список названий месяцев')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('themes', 'inputmask', '<script src=\"/include/jquery/inputmask/jquery.inputmask.js\" type=\"text/javascript\"></script><script src=\"/include/jquery/inputmask/jquery.inputmask.extensions.js\" type=\"text/javascript\"></script><script src=\"/include/jquery/inputmask/jquery.inputmask.date.extensions.js\" type=\"text/javascript\"></script><script src=\"/include/jquery/inputmask/jquery.inputmask.numeric.extensions.js\" type=\"text/javascript\"></script><script src=\"/include/jquery/inputmask/jquery.inputmask.custom.extensions.js\" type=\"text/javascript\"></script>', '4', 'Все для шаблона ввода текста')");

?>