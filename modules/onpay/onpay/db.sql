--СЃРѕР·РґР°РЅРёРµ СЃС‚СЂСѓРєС‚СѓСЂС‹ Р‘Р”
CREATE TABLE `operations` (
  `id` int(11) NOT NULL auto_increment,
  `sum` decimal default NULL,
  `user_id` int(11) default NULL,
  `status` int(11) default 0,
  `type` varchar(64) default NULL,
  `comment` varchar(255) default NULL,
  `description` varchar(255) default NULL,
  `date` datetime default NULL,
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE `balances` (
  `id` int(11) NOT NULL,
  `sum` decimal default NULL,
  `date` datetime default NULL,
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8;