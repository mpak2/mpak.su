<? die;

echo '<p>'.$sql = "DROP Table {$conf['db']['prefix']}{$arg['modpath']}_index";
mpqw($sql);

echo '<p>'.$sql = "DROP Table {$conf['db']['prefix']}{$arg['modpath']}_period";
mpqw($sql);

echo '<p>'.$sql = "DROP Table {$conf['db']['prefix']}{$arg['modpath']}_projects";
mpqw($sql);

echo '<p>'.$sql = "DROP Table {$conf['db']['prefix']}{$arg['modpath']}_projects_payment";
mpqw($sql);

echo '<p>'.$sql = "DROP Table {$conf['db']['prefix']}{$arg['modpath']}_projects_works";
mpqw($sql);

echo '<p>'.$sql = "DROP Table {$conf['db']['prefix']}{$arg['modpath']}_tasks";
mpqw($sql);

echo '<p>'.$sql = "DROP Table {$conf['db']['prefix']}{$arg['modpath']}_tasks_comments";
mpqw($sql);

echo '<p>'.$sql = "DROP Table {$conf['db']['prefix']}{$arg['modpath']}_tasks_status";
mpqw($sql);

echo '<p>'.$sql = "DROP Table {$conf['db']['prefix']}{$arg['modpath']}_wages";
mpqw($sql);

echo '<p>'.$sql = "DROP Table {$conf['db']['prefix']}{$arg['modpath']}_workers";
mpqw($sql);

echo '<p>'.$sql = "DROP Table {$conf['db']['prefix']}{$arg['modpath']}_works";
mpqw($sql);

mpqw("DELETE FROM `{$conf['db']['prefix']}settings` WHERE `modpath`='cost'");

?>