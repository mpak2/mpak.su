<? die;

echo '<p>'.$sql = "DROP TABLE {$conf['db']['prefix']}{$arg['modpath']}_index";
mpqw($sql);

echo '<p>'.$sql = "DROP TABLE {$conf['db']['prefix']}{$arg['modpath']}_money";
mpqw($sql);

?>