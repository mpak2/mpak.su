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
} $param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));
//$uid = $_GET['id'] && array_key_exists('users', $_GET['m']) ? $_GET['id'] : $conf['user']['id'];
if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){
	mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize(array_merge((array)$param, array($_POST['param']=>$_POST['val'])))."' WHERE id = ". (int)$arg['blocknum']);exit;
};// mpre($param);

$snowflake = array(
	"data:image/png;base64,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",
);

$snow = array("Отключить", "Включить");

?>
<? if($arg['access'] >= 3): ?>
	<style>
		.settings_<?=$arg['blocknum']?> > div {overflow:hidden;}
		.settings_<?=$arg['blocknum']?> > div > div:nth-child(1) {float:right; width:80px;}
	</style>
	<script src="/include/jquery/my/jquery.klesh.select.js"></script>
	<script>
		$(function(){
			$(".klesh_<?=$arg['blocknum']?>").klesh("/blocks/<?=$arg['blocknum']?>/null");
			$(".klesh_snow_<?=$arg['blocknum']?>").klesh("/blocks/<?=$arg['blocknum']?>/null", function(){
			}, <?=json_encode($snow)?>);
		});
	</script>
	<div class="settings_<?=$arg['blocknum']?>">
		<div>
			<div param="snow" class="klesh_snow_<?=$arg['blocknum']?>"><?=($snow[ (int)$param['snow'] ])?></div>
			<div>Снег</div>
		</div>
		<div>
			<div param="snow_speed" class="klesh_<?=$arg['blocknum']?>"><?=($param['snow_speed'] ?: 50000)?></div>
			<div>Скорость</div>
		</div>
		<div>
			<div param="snow_intensive" class="klesh_<?=$arg['blocknum']?>"><?=($param['snow_intensive'] ?: 1000)?></div>
			<div>Интервал</div>
		</div>
	</div>
<? endif; ?>
<? if($param['snow']): ?>
	<script type="text/javascript">
		snow_intensive=<?=($param['snow_intensive'] ?: 1000)?>;
		snow_speed=<?=($param['snow_speed'] ?: 50000)?>;
		snow_src=new Array('img/sneg1.gif','img/sneg2.gif','img/sneg3.gif','img/sneg4.png');
	
		$(document).ready(snow_start);
	
		function snow_start() {
			snow_id=1;
				snow_y=$(document).height()
			setInterval(function() {
					snow_x=Math.random()*document.body.offsetWidth-100;
					snow_img=(snow_src instanceof Array ? snow_src[Math.floor(Math.random()*snow_src.length)] : snow_src);
					snow_elem='<img class="png" id="snow'+snow_id+'" style="position:absolute; left:'+snow_x+'px; top:0;z-index:10000" src="<?=$snowflake[0]?>"/>';
					$("body").append(snow_elem);
					snow_move(snow_id);
					snow_id++;
			},snow_intensive);
		}
	
		function snow_move(id) {
			$('#snow'+id).animate({top:snow_y,left:"+="+Math.random()*100},snow_speed,function() {
			$(this).empty().remove();
		});}
	</script>
<? endif; ?>