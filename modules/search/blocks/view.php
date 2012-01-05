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
if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST['id']){
	$search = mpql(mpqw("SELECT s.*, u.name FROM {$conf['db']['prefix']}{$arg['modpath']}_index AS s LEFT JOIN {$conf['db']['prefix']}users AS u ON s.uid=u.id ORDER BY s.id DESC LIMIT 1"));
	if($_POST['id'] == $search[0]['id']) exit;
}else{
	$search = mpql(mpqw("SELECT s.*, u.name FROM {$conf['db']['prefix']}{$arg['modpath']}_index AS s LEFT JOIN {$conf['db']['prefix']}users AS u ON s.uid=u.id ORDER BY s.id DESC LIMIT 16"));
}

?>
<? if(!$_POST['id']): ?>
<script>
	var post = function(){
		var id = $(".search_<?=$arg['blocknum']?>:first").text();
		$("#data_<?=$arg['blocknum']?>").find("div:first").css("background-color", "#fff");
		$.post("/blocks/<?=$arg['blocknum']?>/null", {id:id}, function(data){
			if(data){
				$("#data_<?=$arg['blocknum']?>").find("div:last").hide();
				$("#data_<?=$arg['blocknum']?>").prepend(data);
				$("#data_<?=$arg['blocknum']?>").find("div:first").css("background-color", "#ffaaaa");
			}
		}); setTimeout ("post()", 5000);
	}; setTimeout ("post()", 5000);
</script>
<? endif; ?>
<div id="data_<?=$arg['blocknum']?>">
	<? foreach($search as $k=>$v): ?>
		<div style="clear:both;">
			<span class="search_<?=$arg['blocknum']?>" style="display:none"><?=$v['id']?></span>
			<span style="float:right;"><?=$v['date']?></span>
			<span style="float:right; margin:0 3px;">
				<a href="/users/<?=$v['uid']?>">
					<?=($v['name'] ?: $conf['settings']['default_usr'])?>
				</a>
			</span>
			<span>
				<a href="/<?=$arg['modpath']?>/<?=$v['id']?>"><?=$v['search']?></a>
				<span title="просмотрено/найдено страниц"><?=$v['count']?>/<?=$v['pages']?> стр.</span>
			</span>
		</div>
	<? endforeach; ?>
</div>