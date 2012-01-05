<? die;

echo '<p>'.$sql = "DROP TABLE {$conf['db']['prefix']}{$arg['modpath']}";
mpqw($sql);

echo '<p>'.$sql = "DROP TABLE {$conf['db']['prefix']}{$arg['modpath']}_post";
mpqw($sql);

echo '<p>'.$sql = "DROP TABLE {$conf['db']['prefix']}{$arg['modpath']}_value";
mpqw($sql);

?>