<? # Нуль

if(array_key_exists('confnum', $arg)){
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

$post = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index")); //$dat

?>
<select onchange="document.location.href = this.options[this.selectedIndex].value;">
	<option value="/">Выберете раздел</option>
	<? foreach($post as $k=>$v): ?>
		<option value="/<?=$arg['modname']?>/<?=$v['id']?>"><?=$v['name']?></option>
	<? endforeach; ?>
</select>
