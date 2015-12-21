<? # Нуль

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
//$uid = $_GET['id'] && array_key_exists('users', $_GET['m']) ? $_GET['id'] : $conf['user']['id'];
if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){
	if($_POST['del']){
		mpqw("DELETE FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE uid=". (int)$conf['user']['uid']. " AND id=". (int)$_POST['del']);
	}elseif($_POST['qw_id'] && $_POST['ans']){
		mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']}_index SET ans=\"". mpquot($_POST['ans']). "\" WHERE uid=". (int)$conf['user']['uid']. " AND id=". (int)$_POST['qw_id']);
	} echo 1; exit;
};

$qw = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE uid=". (int)$arg['uid']));

?>
<? if($qw): ?>
	<style>
		.qw_<?=$arg['blocknum']?> textarea { width:100%; }
		.ask_<?=$arg['blocknum']?> { display:none; }
	</style>
	<script>
		$(function(){
			$(".ans_<?=$arg['blocknum']?>").click(function(){
				$(this).parents("[qw_id]").find(".ask_<?=$arg['blocknum']?>").slideToggle('slow');
			});
			$(".del_<?=$arg['blocknum']?>").click(function(){
				if(confirm("Удалить вопрос?")){
					qw_id = $(this).parents("[qw_id]").attr("qw_id");// alert(qw_id);
					$.post("/blocks/<?=$arg['blocknum']?>/null", {del:qw_id}, function(data){
						if(isNaN(data)){ alert(data) }else{
							$("[qw_id="+qw_id+"]").remove();
						}
					});
				}
			});
			$(".add_<?=$arg['blocknum']?>").mousedown(function(){
				qw_id = $(this).parents("[qw_id]").attr("qw_id");// alert(qw_id);
				ans = $(this).parents("[qw_id]").find(".text_<?=$arg['blocknum']?>").val();
				$.post("/blocks/<?=$arg['blocknum']?>/null", {qw_id:qw_id, ans:ans}, function(data){
					$("[qw_id="+qw_id+"]").find(".ask_<?=$arg['blocknum']?>").hide();
					$("[qw_id="+qw_id+"]").find(".ans_<?=$arg['blocknum']?>").text("Правка");
				});
			});
			$(".open_<?=$arg['blocknum']?>").click(function(){
				qw_id = $(this).parents("[qw_id]").attr("qw_id");// alert(qw_id);
				text = $("[qw_id="+qw_id+"]").find(".text_<?=$arg['blocknum']?>").val();// alert(text);
				$("[qw_id="+qw_id+"]").find(".ask_<?=$arg['blocknum']?>").html(text).slideToggle('slow');
			});
		});
	</script>
	<? foreach($qw as $k=>$v): ?>
		<div qw_id="<?=$v['id']?>" class="qw_<?=$arg['blocknum']?>">
			<div style="float:right;">
				<? if($v['uid'] == $conf['user']['uid']): ?>
					<a class="ans_<?=$arg['blocknum']?>" href="javascript: return false;"><?=($v['ans'] ? "Правка" : "Ответить")?></a>
					<a class="del_<?=$arg['blocknum']?>" href="javascript: return false;">Удалить</a>
				<? else: ?>
					<a class="open_<?=$arg['blocknum']?>" href="javascript: return false;">Смотреть</a>
				<? endif; ?>
			</div>
			<div>
				<div style="margin:5px;"><a href="<?=$v['href']?>">http://<?=mpidn($_SERVER['HTTP_HOST'])?><?=$v['href']?></a></div>
				<div><?=$v['qw']?></div>
			</div>
			<div class="ask_<?=$arg['blocknum']?>" style="font-style:italic; margin:10px 0 0 50px;">
				<div><textarea class="text_<?=$arg['blocknum']?>"><?=htmlspecialchars($v['ans'])?></textarea></div>
				<div style="text-align:right;">
					<input class="add_<?=$arg['blocknum']?>" type="button" value="Добавить ответ">
				</div>
			</div>
		</div>
	<? endforeach; ?>
<? endif; ?>
