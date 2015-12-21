<?

if (array_key_exists('null', $_GET)) header('Content-type: text/xml');
mpqw("SET NAMES UTF8");
$conf['tpl']['xml'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_post". ($_GET['kid'] ? " WHERE kid=".(int)$_GET['kid'] : ''). " ORDER BY id DESC LIMIT 10"));

?>
