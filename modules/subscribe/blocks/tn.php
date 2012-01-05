<? die; # Нуль

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
//if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){};

//$tn = mpql(mpqw("SHOW TABLES WHERE `table` LIKE \"{$conf['db']['prefix']}{$arg['modpath']}%\"")); //$dat
$tn = mpql(mpqw("SHOW TABLES WHERE `Tables_in_{$conf['db']['name']}` LIKE \"{$conf['db']['prefix']}{$arg['modpath']}%\" AND `Tables_in_{$conf['db']['name']}` <> \"{$conf['db']['prefix']}{$arg['modpath']}_mail\" AND `Tables_in_{$conf['db']['name']}` <> \"{$conf['db']['prefix']}{$arg['modpath']}_unsubscribe\"")); //$dat

?>
<script>
	$(function(){
		$("#subscribe_<?=$arg['blocknum']?> a").click(function(){
			$("#subscribe_list_<?=$arg['blocknum']?>").toggle("slow");
		});
	});
</script>
<div id="subscribe_<?=$arg['blocknum']?>">
	<div style="margin:3px; float:right;"><a href="javascript: return false;">Скрыть</a></div>
	<div id="subscribe_list_<?=$arg['blocknum']?>" style="display:none;">
		<? foreach($tn as $k=>$v): ?>
			<div><a href="/<?=$arg['modpath']?>:<?=substr($v["Tables_in_{$conf['db']['name']}"], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))?>"><?=$v["Tables_in_{$conf['db']['name']}"]?></a></div>
		<? endforeach; ?>
	</div>
</div>