<? # Нуль

if(array_key_exists('confnum', $arg)){
/*	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

echo <<<EOF
	<form method="post">
		<input type="text" name="param" value="$param"> <input type="submit" value="Сохранить">
	</form>
EOF;*/

	return;
}//$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));
//$uid = $_GET['id'] && array_key_exists('users', $_GET['m']) ? $_GET['id'] : $conf['user']['id'];
//if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){};


$news = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_post"));

?>
<script>
	setInterval(function(){
		obj = $("#base_news_<?=$arg['blocknum']?> > div:visible")
		if($(obj).next().length){
			$(obj).next().show();
		}else{
			$("#base_news_<?=$arg['blocknum']?> > div").first().show();
		} $(obj).hide();
	}, 10000+Math.random()*2000);
</script>
<div id="base_news_<?=$arg['blocknum']?>">
	<? foreach($news as $k=>$v): ?>
		<div style="display:<?=($k ? "none" : "block")?>">
			<div class="toggle_content">
				<div style="float:left; margin: 0 10px 10px 0;">
					<img src="/<?=$arg['modname']?>:img/<?=$v['id']?>/w:70/h:70/c:1/null/img.jpg">
					<?=$v['fm']?> <?=$v['im']?> <?=$v['ot']?>
				</div>
				<div><?=substr(strip_tags($v['text']), 0, 200)?></div>
			</div>
			<div style="text-align:right; margin:10px 0;"><a href="/<?=$arg['modname']?>/<?=$v['id']?>">Читать полностью</a></div>
		</div>
	<? endforeach; ?>
</div>
