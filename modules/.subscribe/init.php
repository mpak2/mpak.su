<? die;

//echo '<p>'.$sql = "CREATE ALGORITHM=UNDEFINED DEFINER=`shop_mpak_su`@`localhost` SQL SECURITY DEFINER VIEW `{$conf['db']['prefix']}{$arg['modpath']}_index` AS select `mp_business_index`.`id` AS `id`,`mp_business_index`.`cat_id` AS `cat_id`,`mp_business_index`.`href` AS `href`,`mp_business_index`.`name` AS `name`,`mp_business_index`.`site` AS `site`,`mp_business_index`.`tel` AS `tel`,`mp_business_index`.`addr` AS `addr`,`mp_business_index`.`mail` AS `mail`,`mp_business_index`.`time` AS `time`,`mp_business_index`.`count` AS `count`,`mp_business_index`.`description` AS `description`,`mp_business_index`.`text` AS `text` from `mp_business_index`";
//mpqw($sql);

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_mail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `index_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_id` (`index_id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251";
mpqw($sql);

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_unsubscribe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `index_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_id` (`index_id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251";
mpqw($sql);

mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('subscribe', 'subscribe_send', '1', '1', 'Включение рассылки')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('subscribe', 'subscribe_send_from', 'subscribe@mpak.su', '1', 'Обратный адрес рассылки')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('subscribe', 'subscribe_send_text', '', '0', 'Текст сообщения')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('subscribe', 'subscribe_send_subject', '', '0', 'Заголовок сообщения')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('subscribe', 'delete_mail', '0', '1', 'Удаление почты при отписывании')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('subscribe', 'unsubscribe_mess', 'Выключить рассылку можно по ссылке:', '1', 'Ссылка на страницу отключения рассылки')");

?>