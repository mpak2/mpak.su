<? die;

if($_POST['id'] && ($arg['access'] > 3)){
	$index_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_index", $w = array("id"=>$_POST['id']), $w += array("name"=>"Новая страница", "text"=>"Текст страницы"));
	exit((string)$index_id);
}

$tpl['cat'] = qn("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_cat");
$tpl['index'] = qn("SELECT *
	FROM {$conf['db']['prefix']}{$arg['modpath']}_index
	WHERE 1". ($_GET['id'] ? " AND id=". (int)$_GET['id'] : " LIMIT 100")
);// mpre($tpl['index']);

/** Запрос данных для построения хлебных крошек в соответствии с меню сайта **/
//$tpl['menu_index'] = qn("SELECT * FROM {$conf['db']['prefix']}menu_index");

if($index = $tpl['index'][ $_GET['id'] ]){
	$conf['settings']['title'] = $index['name'];
	if($index['description']){
		$conf['settings']['description'] = $index['description'];
	} if($index['keywords']){
		$conf['settings']['keywords'] = $index['keywords'];
	} if($index['title']){
		$conf['settings']['title'] = $index['title'];
	}
}