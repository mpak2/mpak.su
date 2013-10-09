<? die;

$tpl['cat'] = qn("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_cat");
$tpl['index'] = qn("SELECT *
	FROM {$conf['db']['prefix']}{$arg['modpath']}_index
	WHERE 1". ($_GET['id'] ? " AND id=". (int)$_GET['id'] : " LIMIT 100")
);// mpre($tpl['index']);

if($index = $tpl['index'][ $_GET['id'] ]){
	if($index['title']){
		$conf['settings']['title'] = $index['title'];
	} if($index['keywords']){
		$conf['settings']['keywords'] = $index['keywords'];
	} if($index['description']){
		$conf['settings']['description'] = $index['description'];
	} $conf['settings']['title'] = $index['name'];
}