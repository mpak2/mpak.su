<?

qw("CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");

qw("CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_index` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `hide` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `qw` text NOT NULL,
  `ans` text NOT NULL,
  `href` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cat_id` (`cat_id`),
  KEY `hide` (`hide`),
  KEY `time` (`time`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");

mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('faq', 'faq_index', 'Чаво', '4', '')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('faq', 'faq_cat', 'Категории', '4', '')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('faq', 'faq', '<script>\$(function(){uid=\$(\"#faq\").parents(\"[uid]\").attr(\"uid\");\$(\"#faq\").load(\"/faq:ask\"+(uid?\"/uid:\"+uid:\'\')+\"/null\")})</script><div id=\"faq\"></div>', '1', 'Установка кода часто задаваемых вопросов на сайт.')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('faq', 'faq_tpl_exceptions', '1', '4', '')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('faq', 'faq_index=>title', 'time,uid,cat_id,hide,sort,qw', '4', '')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('faq', 'faq', '<script>\$(function(){uid=\$(\"#faq\").parents(\"[uid]\").attr(\"uid\");\$(\"#faq\").load(\"/faq:ask\"+(uid?\"/uid:\"+uid:\'\')+\"/null\")})</script><div id=\"faq\"></div>', '1', 'Установка кода часто задаваемых вопросов на сайт.')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('faq', 'faq_tpl_exceptions', '1', '4', '')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('faq', 'faq_index=>title', 'time,uid,cat_id,hide,sort,qw', '4', '')");

?>
