<? die; # Нуль

if ((int)$arg['confnum']){
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

?>
	<form method="post" style="padding:10px;">
		<h3 style="margin-bottom:10px;">
			<a target="blank" href="/?m[settings]=admin&where[name]=themes_start_act">
				<?=$param['name']?>
			</a>
		</h3>
		<input type="text" name="param[name]" value="<?=$param['name']?>">
		<input type="submit" value="Сохранить">
	</form>
<?

	return;
}$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));
//$uid = $_GET['id'] && array_key_exists('users', $_GET['m']) ? $_GET['id'] : $conf['user']['id'];
if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){
	mpsettings($param['name'], $_POST['val']); exit;
};

$set = mpsettings($param['name']);

?>
<script src="/include/jquery/my/jquery.klesh.select.js"></script>
<script>
	$(function(){
		$(".klesh_<?=$arg['blocknum']?>").klesh("/blocks/<?=$arg['blocknum']?>/null");
	});
</script>
<div>
	<div class="klesh_<?=$arg['blocknum']?>"><?=$set?></div>
</div>