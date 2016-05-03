<?

if(array_key_exists("hide", $_GET)){
	qw("UPDATE {$conf['db']['prefix']}modules_index SET admin=0 AND id=". (int)$_GET['hide']);
}elseif(array_key_exists('display', $_GET)){
	qw($sql = "UPDATE {$conf['db']['prefix']}modules_index SET admin=".(int)$_GET['id']."  WHERE id=".(int)$_GET['display']);
}
if(empty($_GET['id'])){
	$lnk = ql("SELECT * FROM {$conf['db']['prefix']}admin");
	exit(header("Location: /{$arg['modpath']}/".(int)$lnk['0']['id']));
} mpevent("Вход на админстраницу", $_GET['id']);
