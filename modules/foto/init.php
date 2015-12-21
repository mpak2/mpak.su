<?

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cat_id` (`cat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251";
mpqw($sql);

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_imgs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `img` varchar(255) NOT NULL,
  `hide` int(11) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `kid` (`kid`),
  KEY `uid` (`uid`),
  KEY `hide` (`hide`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251";
mpqw($sql);

mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('{$arg['modpath']}', '{$arg['modpath']}_cat', 'Категории', '4', '')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('{$arg['modpath']}', '{$arg['modpath']}_imgs', 'Изображения', '4', '')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('{$arg['modpath']}', '{$arg['modpath']}_lightbox', '<script type=\"text/javascript\" src=\"/include/jquery-lightbox-0.5/js/jquery.lightbox-0.5.js\"></script><link rel=\"stylesheet\" type=\"text/css\" href=\"/include/jquery-lightbox-0.5/css/jquery.lightbox-0.5.css\" media=\"screen\" /><script type=\"text/javascript\">\$(function(){\$(\'#gallery, .gallery\').find(\'a\').lightBox();});</script>', '1', 'Подключение лайтбокса')");
