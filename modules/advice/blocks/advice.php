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
} //$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));


if(array_key_exists('null', $_GET) && array_key_exists('blocks', $_GET['m']) && ($_GET['id'] == $arg['blocknum']) && $_POST){
	$advice = mpql(mpqw("SELECT MAX(id) AS max, MIN(id) AS min FROM {$conf['db']['prefix']}{$arg['modpath']}_index"), 0)+mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE (gid IN (". implode(', ', array_keys($conf['user']['gid'])). ") OR gid=0) AND id". ($_POST['type'] == 'next' ? ">" : "<"). (int)$_POST['id']. " ORDER BY id ". ($_POST['type'] == 'next' ? "" : " DESC"). " LIMIT 1"), 0);
}else{
	$advice = mpql(mpqw($sql = "SELECT MAX(id) AS max, MIN(id) AS min FROM {$conf['db']['prefix']}{$arg['modpath']}_index"), 0)+mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE (gid IN (". implode(', ', array_keys($conf['user']['gid'])). ") OR gid=0) ORDER BY RAND() LIMIT 1"), 0);
//	$advice = mpql(mpqw($sql = "SELECT *, MAX(id) AS max, MIN(id) AS min FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE gid IN (". implode(', ', array_keys($conf['user']['gid'])). ") OR gid=0 LIMIT 1"), 0);
}

?>
<? if(!array_key_exists('null', $_GET)): ?>
<script language="javascript">
	$(document).ready(function(){
		$(".href_<?=$arg['blocknum']?>").live("click", function(){
			var type = $(this).attr("type");
			var num = $(this).attr("num");
			$.post("/blocks/<?=$arg['blocknum']?>/null", {id:num, type:type}, function(data){
				$("#data_<?=$arg['blocknum']?>").html(data);
			});
			return false;
		});
	});
</script>
<? endif; ?>
<div id="data_<?=$arg['blocknum']?>">
	<div style="margin:7px;">
		<span>
			<? if($advice['id'] > $advice['min']): ?>
				<a class="href_<?=$arg['blocknum']?>" type="prev" num="<?=$advice['id']?>" href="/">Предыдущий</a>
			<? else: ?>
				Предыдущий
			<? endif; ?>
		</span>
		<span style="float:right;">
			<? if($advice['id'] < $advice['max']): ?>
				<a class="href_<?=$arg['blocknum']?>" type="next" num="<?=$advice['id']?>" href="/">Следующий</a></span>
			<? else: ?>
				Следующий
			<? endif; ?>
	</div>
	<div style="font-weight:bold; margin:7px;">#<?=$advice['id']?> <?=$advice['name']?></div>
	<div style="font-style:italic;"><?=$advice['description']?></div>
	<div style="text-align:right; margin-top:5px;"><a href="<?=$advice['href']?>"><?=$advice['link']?></a></div>
</div>