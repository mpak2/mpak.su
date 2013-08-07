<? die; # Города

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

$conf['tpl']['sity'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}ymaps_sity WHERE x>0 AND y>0 ORDER BY name"), 'id');
$conf['tpl']['type'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}ymaps_type ORDER BY name"));
$sity_id = $conf['user']['sess']['sity_id'] ?: $conf['user']['sity_id'];

?>
<div style="float:left;">
	<script>
		$(document).ready(function(){
			$('.sity').change(function(){
				document.location.href = '/ymaps/sity_id:'+ $(this).find('option:selected').val();
			});
			$('.type_id').change(function(){
				document.location.href = '/ymaps/type_id:'+ $(this).find('option:selected').val();
			});
		});
	</script>
	<select class="sity">
		<? foreach($conf['tpl']['sity'] as $k=>$v): ?>
			<option value="<?=$v['id']?>"<?=($v['id'] == $sity_id ? ' selected' : '')?>><?=$v['name']?></option>
		<? endforeach; ?>
	</select>
	<select class="type_id">
		<? foreach($conf['tpl']['type'] as $k=>$v): ?>
			<option value="<?=$v['id']?>"<?=($_GET['type_id'] == $v['id'] ? " selected" : '')?>><?=$v['name']?></option>
		<? endforeach; ?>
	</select>
</div>
<ul type="circle" style="display:inline;z-index:-10;">
<!--	<li style="display:inline;"><a href="/ymaps/sity_id:1">Москва</a></li>-->
	<li style="display:inline;">
		<a href="/ymaps/sity_id:<?=$conf['tpl']['sity'][ $conf['user']['sity_id'] ]['id']?>">
			<?=$conf['tpl']['sity'][ $conf['user']['sity_id'] ]['name']?>
		</a>
	</li>
	<!-- <li style="display:inline;"><a href="/ymaps/sity_id:165">Бельдяжки</a></li> -->
</ul>

