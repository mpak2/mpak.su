<? # modules/admin/init.php

if(mpsettings($t = "admin", "Разделы") && ($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");
}
if(mpsettings($t = "admin_access", "Доступы") && ($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");
}