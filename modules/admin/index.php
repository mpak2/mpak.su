<? die;

if($_GET['hide']){
	mpqw("UPDATE {$conf['db']['prefix']}modules SET admin=0 WHERE id=".(int)$_GET['hide']);
}elseif($_GET['display']){
	mpqw($sql = "UPDATE {$conf['db']['prefix']}modules SET admin=".(int)$_GET['id']."  WHERE id=".(int)$_GET['display']);
}

if(empty($_GET['id'])){
	$lnk = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}admin"));
	header("Location: /{$arg['modpath']}/".(int)$lnk['0']['id']); exit;
} mpevent("Вход на админстраницу", $_GET['id']);

$conf['tpl']['hide'] = mpql(mpqw($sql = "SELECT m.*, a.id as aid FROM {$conf['db']['prefix']}{$arg['modpath']} AS a RIGHT JOIN {$conf['db']['prefix']}modules AS m ON a.id=m.admin WHERE a.id IS NULL ORDER BY m.name"));

$conf['tpl'][$arg['fn']] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']} WHERE id=".(int)$_GET['id']), 0);

$conf['tpl']['modules'] = mpql(mpqw("SELECT SQL_CALC_FOUND_ROWS * FROM {$conf['db']['prefix']}modules WHERE admin=". (int)$conf['tpl'][$arg['fn']]['id']));
$conf['tpl']['cnt'] = mpql(mpqw("SELECT FOUND_ROWS()/10 AS cnt"), 0, 'cnt');

foreach((array)$conf['tpl']['modules'] as $k=>$v){
	if($conf['modules'][$v['id']]['access'] < 4) unset($conf['tpl']['modules'][$k]);
}

?>