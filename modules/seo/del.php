<? die;

echo '<p>'.$sql = "DROP Table {$conf['db']['prefix']}{$arg['modpath']}_redirect";
mpqw($sql);

echo '<p>'.$sql = "DROP Table {$conf['db']['prefix']}{$arg['modpath']}_sitemap";
mpqw($sql);

?>