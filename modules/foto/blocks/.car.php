<? die; # Мои фото

if ((int)$arg['confnum']){
/*	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

echo <<<EOF
	<form method="post">
		<input type="text" name="param" value="$param"> <input type="submit" value="Сохранить">
	</form>
EOF;*/

	return;
}
//$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));

if(($tn = "{$conf['db']['prefix']}{$arg['modpath']}_img") && $_FILES['foto']){
	$id = mpql(mpqw("SELECT max(id)+1 AS id FROM $tn"), 0, 'id');
	if($fn = mpfn($tn, 'img', $id, 'foto')){
		mpqw($sql = "INSERT INTO $tn SET kid=". (int)$_POST['cat_id']. ", uid=". (int)$conf['user']['uid']. ", img=\"". mpquot($fn). "\"");
	}
}

$uid = (array_key_exists($conf['modules']['users']['modname'], $_GET['m']) && $_GET['id'] ? $_GET['id'] : $conf['user']['uid']);
$cars = mpql(mpqw("SELECT * FROM $tn WHERE uid=". (int)$uid. " ORDER BY id DESC"));
$cat = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_cat"));

?>
<!-- [settings:folo_lightbox] -->
<div style="margin:5px; text-align:right;">
	<a href="/foto">Просмотреть все фото</a>
</div>
<form method="post" enctype="multipart/form-data">
	<input type="file" name="foto[img]" style="width:230px;">
	<span>
		<select name="cat_id">
			<? foreach($cat as $k=>$v): ?>
				<option value="<?=$v['id']?>"><?=$v['name']?></option>
			<? endforeach; ?>
		</select>
		<span><input type="submit" value="Добавить фото"></span>
	</span>
</form>
<div style="margin-top:10px;">
	<? foreach($cars as $k=>$v): ?>
		<div style="float:left; margin:3px;" id="gallery">
			<a href="/<?=$arg['modpath']?>:img/<?=$v['id']?>/w:600/h:500/null/img.jpg">
				<img src="/<?=$arg['modpath']?>:img/<?=$v['id']?>/w:70/h:70/c:1/null/img.jpg">
			</a>
		</div>
	<? endforeach; ?>
</div>
