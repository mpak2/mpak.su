<?

if($_GET['hide']){
	qw("UPDATE {$conf['db']['prefix']}modules SET admin=0 WHERE id=".(int)$_GET['hide']);
}elseif($_GET['display']){
	qw($sql = "UPDATE {$conf['db']['prefix']}modules SET admin=".(int)$_GET['id']."  WHERE id=".(int)$_GET['display']);
}

if(empty($_GET['id'])){
	$lnk = ql("SELECT * FROM {$conf['db']['prefix']}admin");
	header("Location: /{$arg['modpath']}/".(int)$lnk['0']['id']); exit;
} mpevent("Вход на админстраницу", $_GET['id']);

//foreach((array)$tpl['modules'] as $k=>$v){
//	if($conf['modules'][$v['id']]['access'] < 4) unset($tpl['modules'][$k]);
//}
