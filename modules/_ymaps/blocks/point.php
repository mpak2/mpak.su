<? die; # Реклама

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

if($_POST['point']){
	if($mpdbf = mpdbf($tn = "{$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}", array('time'=>time(), 'uid'=>$conf['user']['uid'])+$_POST['point'])){
		mpqw("INSERT INTO $tn SET ". $mpdbf);
		if($_FILES && ($id = mysql_insert_id()) && ($f = mpfn($tn, 'img', $id, 'point'))){
			mpqw("UPDATE $tn SET img=\"". mpquot($f). "\" WHERE id=". (int)$id);
		}
		header("Location: {$_SERVER['REQUEST_URI']}");
	}
}

$point = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} WHERE uid=". (int)$arg['uid']));
$sity = spisok("SELECT * FROM {$conf['db']['prefix']}users_sity WHERE x>0 AND y>0 ORDER BY name");

?>
<script>
	$(document).ready(function(){
		$(".delpoint").click(function(){
			if (confirm('Вы уверенны?')){
				$.post('/<?=$arg['modpath']?>:delpoint/null', {id:$(this).attr('id')}, function(data){
					if(data != 'deleted'){
						alert(data);
						return false;
					}
				});
				$(this).parent().parent().parent().parent().hide('slow');
			} return false;
		});
	});
</script>
<div>
	<? foreach($point as $k=>$v): ?>
		<div style="overflow:hidden;">
			<div style="float:right;"><img src="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:<?=$arg['fn']?>/w:70/h:70/c:1/null/img.jpg"></div>
			<div style="font-weight:bold;margin:3px;">
				<? if($v['uid'] == $conf['user']['uid']): ?>
					<span>
						<a href="/" onclick="return false;"><img class="delpoint" id="<?=$v['id']?>" src="/img/del.png"></a>
					</span>
				<? endif; ?>
				<?=$v['name']?>
			</div>
			<div style="margin:3px;"><?=$sity[ $v['sity_id'] ]?></div>
			<div style="margin:3px; font-style:italic;"><?=$v['description']?></div>
			<div style="margin:3px;"><a href="<?=$v['link']?>"><?=$v['link']?></a></div>
		</div>
	<? endforeach; ?>
</div>
<? if($arg['uid'] == $conf['user']['uid']): ?>
	<form method="post" enctype="multipart/form-data">
		<div>
			<select name="point[sity_id]">
				<? foreach($sity as $k=>$v): ?>
					<option value="<?=$k?>"><?=$v?></option>
				<? endforeach; ?>
			</select>
		</div>
		<div style="margin:5px;"><input name="point[name]" type="name" style="width:97%;" title="Заголовок"></div>
		<div style="margin:5px;"><textarea name="point[description]" style="width:97%;" title="Текст"></textarea></div>
		<div style="margin:5px;"><input name="point[link]" type="name" style="width:97%;" title="Ссылка"></div>
		<div style="margin:5px;"><input type="file" name="point[img]" style="width:80%;"> <input type="submit" value="Добавить"></div>
	</form>
<? endif; ?>
