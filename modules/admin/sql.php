<?

mpqw("INSERT INTO `{$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}` (`id`, `name`) VALUES ('3', 'Содержимое')");
mpqw("INSERT INTO `{$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}` (`id`, `name`) VALUES ('6', 'Управление')");
mpqw("INSERT INTO `{$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_access` (`id`, `name`) VALUES (1,'Чтение'),(2,'Добавление'),(3,'Запись'),(4,'Модератор'),(5,'Админ'),(6,'Запрет');");
mpqw("UPDATE `{$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_access` SET `id`=0 WHERE `id`=6;");

?>
