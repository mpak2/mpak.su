<?

if (array_key_exists('null', $_GET)) header('Content-type: text/xml');
mpqw("SET NAMES UTF8");
$conf['tpl']['xml'] = mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index ORDER BY id DESC LIMIT 100"));

?>
