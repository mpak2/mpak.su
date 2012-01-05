<? die;

echo '<p>'.$sql = "DROP TABLE {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_desc";
mpqw($sql);

echo '<p>'.$sql = "DROP TABLE {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_img";
mpqw($sql);

echo '<p>'.$sql = "DROP TABLE {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_list";
mpqw($sql);

echo '<p>'.$sql = "DROP TABLE {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_obj";
mpqw($sql);

mpqw("DELETE FROM `mp_settings` WHERE `modpath`='services'");

?>