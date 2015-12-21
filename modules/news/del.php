<?

echo '<p>'.$sql = "DROP Table {$conf['db']['prefix']}{$arg['modpath']}_kat";
mpqw($sql);

echo '<p>'.$sql = "DROP Table {$conf['db']['prefix']}{$arg['modpath']}_post";
mpqw($sql);

mpqw("DELETE FROM `{$conf['db']['prefix']}settings` WHERE `modpath`='news'");

?>
