<? die;

mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_kat (`id`, `name`, `sort`, `description`) VALUES ('1', 'Отклонено', '1', 'Работа над сайтом')");
mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_kat (`id`, `name`, `sort`, `description`) VALUES ('2', 'В работе', '2', 'Темы для статей в докементацию сайта')");
mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_kat (`id`, `name`, `sort`, `description`) VALUES ('5', 'Выполнено', '3', 'Завершенная работа')");
mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_kat (`id`, `name`, `sort`, `description`) VALUES ('6', 'Информация', '4', 'Информационные сообщения')");

?>