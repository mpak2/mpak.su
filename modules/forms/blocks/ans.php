<? # Нуль

if ((int)$arg['confnum']){
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

	$index = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index"));

?>
	<div style="margin:10px;">
		<div><?=$index[ $param['index_id'] ]['name']?></div>
		<form method="post">
<!--			<div><input type="text" name="param" value="<?=$param['index_id']?>"> <input type="submit" value="Сохранить"></div>-->
			<select name="param[index_id]">
				<? foreach($index as $k=>$v): ?>
					<option value="<?=$v['id']?>"><?=$v['name']?></option>
				<? endforeach; ?>
			</select>
			<input type="submit" value="Сохранить">
		</form>
	</div>
<?

	return;
} $param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));
//$uid = $_GET['id'] && array_key_exists('users', $_GET['m']) ? $_GET['id'] : $conf['user']['id'];
//if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){};

$ans = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_anket WHERE uid=". (int)$arg['uid']. " AND index_id=". (int)$param['index_id']));

?>
<? if($param['index_id']): ?>
	<div style="text-align:right;"><a href="/<?=$arg['modpath']?>/<?=$param['index_id']?>">Задать вопрос</a></div>
	<div>
		<? foreach($ans as $k=>$v): ?>
			<div><?=$v['text']?></div>
		<? endforeach; ?>
	</div>
<? endif; ?>
