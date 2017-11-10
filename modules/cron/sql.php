<?	
	qw("INSERT INTO `{$conf['db']['prefix']}{$variables['arg']['modpath']}_index` (`id`, `name`, `cron`, `path`, `hide`) VALUES (1,'Пример','30 * * * *','modules/path/to/file.php',1)");
	qw("INSERT INTO `{$conf['db']['prefix']}{$variables['arg']['modpath']}_settings` (`id`, `name`, `value`, `description`) VALUES (1,'bin','php','Команда запуска')");
	qw("INSERT INTO `{$conf['db']['prefix']}{$variables['arg']['modpath']}_settings` (`id`, `name`, `value`, `description`) VALUES (2,'log_days','30','	Количество дней хранения логов')");
?>