<? die;

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_balances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `sum` decimal(10,0) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
mpqw($sql);

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_operations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sum` decimal(10,0) DEFAULT NULL,
  `uid` int(11) NOT NULL,
  `status` int(11) DEFAULT '0',
  `type` varchar(64) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `mp_sess` varchar(255) NOT NULL,
  `PHPSESSID` varchar(255) NOT NULL,
  `m` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `type` (`type`,`comment`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
mpqw($sql);

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_pay` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `sum` float NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `time` (`time`),
  KEY `uid` (`uid`),
  KEY `sum` (`sum`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251";
mpqw($sql);

//echo '<p>'.$sql = "CREATE VIEW `{$conf['db']['prefix']}{$arg['modpath']}_sum` AS select `b`.`id` AS `id`,`b`.`uid` AS `uid`,((`b`.`sum` + ifnull(`o`.`sum`,0)) - ifnull(sum(`p`.`sum`),0)) AS `sum` from ((`{$conf['db']['prefix']}{$arg['modpath']}_balances` `b` left join `{$conf['db']['prefix']}{$arg['modpath']}_operations` `o` on(((`b`.`uid` = `o`.`uid`) and (`o`.`status` = 1)))) left join `{$conf['db']['prefix']}{$arg['modpath']}_pay` `p` on((`b`.`uid` = `p`.`uid`))) group by `b`.`uid`";

echo '<p>'.$sql = "CREATE VIEW `{$conf['db']['prefix']}{$arg['modpath']}_sum` AS SELECT
	b.id AS id, b.uid AS uid, (b.sum + SUM(DISTINCT o.sum) + SUM(DISTINCT p.sum)) AS sum
	from (({$conf['db']['prefix']}{$arg['modpath']}_balances b
	left join {$conf['db']['prefix']}{$arg['modpath']}_operations AS o ON (((b.uid = o.uid) and (o.status = 1))))
	left join {$conf['db']['prefix']}{$arg['modpath']}_pay AS p ON ((b.uid = p.uid))) group by b.uid";
mpqw($sql);

mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('onpay', 'onpay_private_code', '', '0', 'Секретный код платежа')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('onpay', 'onpay_onpay_form', '', '0', 'Логин в системе оплат')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('onpay', 'onpay_currency', 'руб.', '1', 'Валюта баланса')");

?>