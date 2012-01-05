<? die;

echo '<p>'.$sql = "DROP TABLE {$conf['db']['prefix']}{$arg['modpath']}_index";
mpqw($sql);

echo '<p>'.$sql = "DROP TABLE {$conf['db']['prefix']}{$arg['modpath']}_order";
mpqw($sql);

echo '<p>'.$sql = "DROP TABLE {$conf['db']['prefix']}{$arg['modpath']}_type";
mpqw($sql);

echo '<p>'.$sql = "DROP TABLE {$conf['db']['prefix']}{$arg['modpath']}_zakaz";
mpqw($sql);

?>