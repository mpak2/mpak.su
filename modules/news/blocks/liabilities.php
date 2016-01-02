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
if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){
	if($_POST['read']){
		mpdk("{$conf['db']['prefix']}{$arg['modpath']}_read", array("time"=>time(), "uid"=>$conf['user']['uid'], "post_id"=>$_POST['read']), array("time"=>time())); echo $_POST['read'];
	} exit;
};

$post = mpql(mpqw("SELECT p.* FROM {$conf['db']['prefix']}{$arg['modpath']}_post AS p LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_read AS r ON p.id=r.post_id AND r.uid=". (int)$conf['user']['uid']. " WHERE p.for=1 AND r.id IS NULL ORDER BY p.id DESC"));

?>
<? if($post): ?>
	<script>
		$(function(){
			$(".read_<?=$arg['blocknum']?>").mousedown(function(){
				news_id = $(this).parents("[news_id]").attr("news_id");// alert(news_id);
				$.post("/blocks/<?=$arg['blocknum']?>/null", {read:news_id}, function(data){
					if(isNaN(data)){ alert(data) }else{
						$(".news_<?=$arg['blocknum']?>[news_id="+news_id+"]").hide(200).destroy();
					}
				});
			});
		});
	</script>
	<div style="margin:0 10px;">
		<? foreach($post as $k=>$v): ?>
			<div class="news_<?=$arg['blocknum']?>" news_id="<?=$v['id']?>" style="border-bottom:1px dashed #444; overflow:hidden; padding-top:10px;">
				<div style="float:right;"><?date('Y.m.d H:i:s', $v['time'])?></div>
				<h2 style="margin:10px; float:left;"><?=$v['name']?></h2>
				<div><?strip_tags($v['text'])?></div>
				<div style="text-align:right; margin-bottom:10px;">
					<a href="/<?=$arg['modpath']?>/<?=$v['id']?>">Читать полностью</a>
					<span style="inlite-block; margin:0 20px;"><input class="read_<?=$arg['blocknum']?>" type="button" value="Я ознакомился"></span>
				</div>
			</div>
		<? endforeach; ?>
	</div>
<? endif; ?>
