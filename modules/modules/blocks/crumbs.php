<? # Нуль

if ((int)$arg['confnum']){
/*	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

echo <<<EOF
	<form method="post">
		<input type="text" name="param" value="$param"> <input type="submit" value="Сохранить">
	</form>
EOF;*/

	return;
}//$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));
//$uid = $_GET['id'] && array_key_exists('users', $_GET['m']) ? $_GET['id'] : $conf['user']['id'];
//if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){};

$crumbs = array();
list($f, $m) = each(array_flip($_GET['m']));
$crumbs[ $conf['modules'][ $m ]['name'] ] = "/{$conf['modules'][ $m ]['modname']}";

if($m == "pages"){
	if($_GET['id'])
		$page = mpql(mpqw("SELECT title, kid FROM {$conf['db']['prefix']}pages_index WHERE id=". (int)$_GET['id']), 0);
	if($cat = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}pages_cat WHERE id=". (int)max($page['kid'], $_GET['cid'])), 0))
		$crumbs[ $cat['name'] ] = "/{$conf['modules'][ $m ]['modname']}:list/cid:". (int)$cat['id'];
	if($page)
		$crumbs[ $page['title'] ] = "/{$conf['modules'][ $m ]['modname']}/". (int)$page['title'];
}else{
	
}

?>
<? foreach($crumbs as $k=>$v): ?>
	/ <a href="<?=$v?>"><?=$k?></a>
<? endforeach; ?>
