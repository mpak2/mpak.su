<? # Нуль

if(array_key_exists('confnum', $arg)){
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

	$select = mpql(mpqw("SHOW TABLES"));

?>
	<form method="post" style="padding:10px;">
		<h3 style="margin-top:10px;"><?=$param['tab']?></h3>
		<div style="margin-top:10px;">
			<select name="param[tab]">
				<option></option>
				<? foreach($select as $k=>$v): ?>
					<option <?=($v["Tables_in_{$conf['db']['name']}"] == $param['tab'] ? "selected" : "")?>><?=$v["Tables_in_{$conf['db']['name']}"]?></option>
				<? endforeach; ?>
			</select>
		</div>
		<div>Таблица из которой будет возможно сделать выбор поля id, name</div>

		<h3 style="margin-top:10px;"><?=$param['name']?></h3>
		<div style="margin-top:10px;">
			<input type="text" name="param[name]" value="<?=$param['name']?>">
			<input type="submit" value="Сохранить">
		</div>
		<div>Имя параметра в котором будет сохраняться значение из списка</div>
	</form>
<? return;

}$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));
//$uid = $_GET['id'] && array_key_exists('users', $_GET['m']) ? $_GET['id'] : $conf['user']['id'];

if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){
	mpsettings($param['name'], $_POST['val']); exit;
};

$value = mpsettings($param['name']);
$select = spisok("SELECT id, name FROM ". mpquot($param['tab']). " ORDER BY name");

?>
<script src="/include/jquery/my/jquery.klesh.select.js"></script>
<script>
	$(function(){
		$(".klesh_<?=$arg['blocknum']?>").klesh("/blocks/<?=$arg['blocknum']?>/null", function(){
		}, <?=json_encode($select)?>);
	});
</script>
<div>
	<div class="klesh_<?=$arg['blocknum']?>"><?=$select[ (int)$value ]?></div>
</div>
