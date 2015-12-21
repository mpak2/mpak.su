<?

header("Content-type: text/plain; charset=utf-8");
$_GET['null'] = '';

$conf['tpl']['modules'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}modules WHERE folder NOT LIKE \"%.%\""));
$conf['tpl']['user-agent'] = array('Googlebot', 'Yandex');

?>
