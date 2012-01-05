<? die;

mpqw("INSERT INTO `{$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_cat` SET `name`='Мои видео файлы'");
mpqw("INSERT INTO `{$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_ext` SET `name`='video/x-msvideo', `ext`='.avi', `description`='Видео файл'");
mpqw("INSERT INTO `{$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_ext` SET `name`='video/x-flv', `ext`='.flv', `description`='Флеш файл'");

?>