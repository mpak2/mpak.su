<? die;

$tpl['index'] = qn("SELECT *
	FROM {$conf['db']['prefix']}{$arg['modpath']}_index
	WHERE 1". ($_GET['id'] ? " AND id=". (int)$_GET['id'] : " LIMIT 100")
);

if($index = $tpl['index'][ $_GET['id'] ]){
	$conf['settings']['title'] = $index['name'];
}