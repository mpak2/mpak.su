<?

if(get($_POST, 'id') && ($arg['admin_access'] > 3)){
	$index_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_index", $w = array("id"=>$_POST['id']), $w += array("name"=>"Новая страница", "text"=>"Текст страницы"));
	exit((string)$index_id);
}
