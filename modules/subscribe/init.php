<? die;

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_mail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `index_id` int(11) NOT NULL,
  `fn` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_id` (`index_id`),
  KEY `fn` (`fn`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251";
mpqw($sql);

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_unsubscribe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `index_id` int(11) NOT NULL,
  `fn` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_id` (`index_id`),
  KEY `fn` (`fn`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251";
mpqw($sql);

echo '<p>'.$sql = "CREATE VIEW `{$conf['db']['prefix']}{$arg['modpath']}_ya_chastny_biznes` AS select `mp_ya_index`.`id` AS `id`,`mp_ya_index`.`time` AS `time`,`mp_ya_index`.`site_id` AS `site_id`,`mp_ya_index`.`name` AS `name`,`mp_ya_index`.`count` AS `count`,`mp_ya_index`.`description` AS `description`,`mp_ya_index`.`name` AS `mail` from `mp_ya_index` where `mp_ya_index`.`site_id` in (select `st`.`id` AS `id` from ((`mp_ya_cat` `c` left join `mp_ya_search` `s` on((`s`.`cat_id` = `c`.`id`))) left join `mp_ya_site` `st` on((`s`.`id` = `st`.`search_id`))) where (`c`.`id` = 1))";
mpqw($sql);

echo '<p>'.$sql = "CREATE VIEW `{$conf['db']['prefix']}{$arg['modpath']}_ya_zarabotok` AS select `mp_ya_index`.`id` AS `id`,`mp_ya_index`.`time` AS `time`,`mp_ya_index`.`site_id` AS `site_id`,`mp_ya_index`.`name` AS `name`,`mp_ya_index`.`count` AS `count`,`mp_ya_index`.`description` AS `description`,`mp_ya_index`.`name` AS `mail` from `mp_ya_index` where `mp_ya_index`.`site_id` in (select `st`.`id` AS `id` from ((`mp_ya_cat` `c` left join `mp_ya_search` `s` on((`s`.`cat_id` = `c`.`id`))) left join `mp_ya_site` `st` on((`s`.`id` = `st`.`search_id`))) where (`c`.`id` = 2))";
mpqw($sql);

mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('subscribe', 'subscribe_send', '1', '1', 'Включение рассылки')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('subscribe', 'subscribe_send_from', 'subscribe@mpak.su', '1', 'Обратный адрес рассылки')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('subscribe', 'subscribe_send_text', '', '0', 'Текст сообщения')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('subscribe', 'subscribe_send_subject', '', '0', 'Заголовок сообщения')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('subscribe', 'delete_mail', '0', '1', 'Удаление почты при отписывании')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('subscribe', 'unsubscribe_mess', 'Выключить рассылку можно по ссылке:', '1', 'Ссылка на страницу отключения рассылки')");

?>