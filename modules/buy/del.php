<? die;

echo '<p>'.$sql = "DROP Table {$conf['db']['prefix']}{$arg['modpath']}_basket";
mpqw($sql);

echo '<p>'.$sql = "DROP Table {$conf['db']['prefix']}{$arg['modpath']}_basket_orders";
mpqw($sql);

echo '<p>'.$sql = "DROP Table {$conf['db']['prefix']}{$arg['modpath']}_delivery";
mpqw($sql);

echo '<p>'.$sql = "DROP Table {$conf['db']['prefix']}{$arg['modpath']}_diameter";
mpqw($sql);

echo '<p>'.$sql = "DROP Table {$conf['db']['prefix']}{$arg['modpath']}_index";
mpqw($sql);

echo '<p>'.$sql = "DROP Table {$conf['db']['prefix']}{$arg['modpath']}_manufacturers";
mpqw($sql);

echo '<p>'.$sql = "DROP Table {$conf['db']['prefix']}{$arg['modpath']}_premium";
mpqw($sql);

echo '<p>'.$sql = "DROP Table {$conf['db']['prefix']}{$arg['modpath']}_price";
mpqw($sql);

echo '<p>'.$sql = "DROP Table {$conf['db']['prefix']}{$arg['modpath']}_type";
mpqw($sql);

echo '<p>'.$sql = "DROP Table {$conf['db']['prefix']}{$arg['modpath']}_vendor";
mpqw($sql);

echo '<p>'.$sql = "DROP Table {$conf['db']['prefix']}{$arg['modpath']}_zakaz";
mpqw($sql);

mpqw("DELETE FROM `{$conf['db']['prefix']}settings` WHERE `modpath`='price'");

?>