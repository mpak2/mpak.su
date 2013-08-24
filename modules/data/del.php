<? die;

echo '<p>'.$sql = "DROP Table {$conf['db']['prefix']}{$arg['modpath']}_cat";
mpqw($sql);

echo '<p>'.$sql = "DROP Table {$conf['db']['prefix']}{$arg['modpath']}_index";
mpqw($sql);

echo '<p>'.$sql = "DROP Table {$conf['db']['prefix']}{$arg['modpath']}_type";
mpqw($sql);

mpqw("DELETE FROM `{$conf['db']['prefix']}settings` WHERE `modpath`='{$arg['modpath']}'");
