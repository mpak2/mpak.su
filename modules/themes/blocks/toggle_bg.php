<? # Нуль

if ((int)$arg['confnum']){
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

	$cat = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}files_cat"));
?>
	<style>
		#param_<?=$arg['blocknum']?> div { margin:5px; }
	</style>
	<form id="param_<?=$arg['blocknum']?>" method="post">
		<div><?=$cat[ $param['cat_id'] ]['name']?></div>
		<div>
			<select name="param[cat_id]">
				<? foreach($cat as $k=>$v): ?>
					<option value="<?=$v['id']?>" <?=($param['cat_id'] == $v['id'] ? "selected" : "")?>><?=$v['name']?></option>
				<? endforeach; ?>
			</select>
		</div>
		<div><input type="text" name="param[interval]" value="<?=$param['interval']?>"></div>
		<div><input type="submit" value="Сохранить"></div>
	</form>
<?

	return;
} $param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));
	$bg = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}files_files WHERE cat_id=". (int)$param['cat_id']));
//$uid = $_GET['id'] && array_key_exists('users', $_GET['m']) ? $_GET['id'] : $conf['user']['id'];
//if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){};

//$dat = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} LIMIT 10")); //$dat

?>
<script>
	$(function(){
		var pos = 0;
		var bg = [
			<? foreach($bg as $k=>$v): ?>
				"/files/<?=$v['id']?>/w:<?=$v['w']?>/h:<?=$v['h']?>/c:<?=$v['c']?>/null/images/bg.png",
			<? endforeach; ?>
		];
		setInterval(function(){
			if(++pos >= bg.length){
				pos = 0;
			}// alert(pos);
			$("body").css("background-image", "url("+bg[pos]+")");
		}, <?=(int)$param['interval']?>*1000); $("body").css("background-image", "url("+bg[pos]+")");
	});
</script>
