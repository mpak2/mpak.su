<? die; # Нуль

if ((int)$arg['confnum']){
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

	$pages = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index ORDER BY id DESC"));
?>
	<form method="post" style="padding:10px;">
		<h3 style="margin-bottom:10px;">
			<a target="blank" href="/<?=$arg['modpath']?>/<?=$param['pid']?>">
				<?=$pages[ $param['pid'] ]['name']?>
			</a>
		</h3>
<!--		<input type="text" name="param[pid]" value="<?=$param['pid']?>">-->
		<select name="param[pid]">
			<option></option>
			<? foreach($pages as $k=>$v): ?>
				<option value="<?=$v['id']?>" <?=($param['pid'] == $v['id'] ? "selected" : "")?>><?=$v['name']?></option>
			<? endforeach; ?>
		</select>
		<input type="submit" value="Сохранить">
	</form>
<?

	return;
}$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));
//$uid = $_GET['id'] && array_key_exists('users', $_GET['m']) ? $_GET['id'] : $conf['user']['id'];
//if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){};

$page = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE id=". (int)$param['pid']), 0); //$dat

?>
<? if($arg['access'] > 3): ?>
	<div style="text-align:right;">
		<a href="/?m[<?=$arg['modpath']?>]=admin&r=mp_pages_index&where[id]=<?=$page['id']?>">
			<img src="/img/edit.png">
		</a>
	</div>
<? endif; ?>
<div>
	<?=$page['text']?>
</div>