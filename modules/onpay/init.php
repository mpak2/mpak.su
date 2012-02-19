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

echo '<p>'.$sql = "SELECT p.uid, SUM(p.sum) AS sum FROM {$conf['db']['prefix']}users AS u LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_pay AS p ON (u.id=p.uid) GROUP BY p.uid";
mpqw($sql);

echo '<p>'.$sql = "SELECT o.uid, SUM(o.sum) AS sum FROM {$conf['db']['prefix']}users AS u LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_operations AS o ON (u.id=o.uid AND o.status=1) GROUP BY o.uid";
mpqw($sql);

echo '<p>'.$sql = "SELECT u.id AS uid, b.sum+o.sum+p.sum AS sum FROM {$conf['db']['prefix']}users AS u LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_balances AS b ON (u.id=b.uid) LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_operations_sum AS o ON (u.id=o.uid) LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_pay_sum AS p ON (u.id=p.uid)";
mpqw($sql);

mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('onpay', 'onpay_private_code', '', '0', 'Секретный код платежа')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('onpay', 'onpay_onpay_form', '', '0', 'Логин в системе оплат')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('onpay', 'onpay_currency', 'руб.', '1', 'Валюта баланса')");

?>