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
//$uid = $_GET['id'] && array_key_exists('users', $_GET['m']) ? $_GET['id'] : $conf['user']['id'];
if(($arg['access'] > 1) && array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){
	if($_POST['del'] && $arg['access'] > 2){
		mpqw("DELETE FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} WHERE uid=". (int)$conf['user']['uid']. " AND id=". (int)$_POST['del']); exit;
	}else if($mpdbf = mpdbf($tn = "{$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}", $post = (array('time'=>time(), 'uid'=>$conf['user']['uid'])+$_POST))){
		mpqw("INSERT INTO $tn SET $mpdbf");
		$data = array(array('id'=>mysql_insert_id())+$post);
	}
}else{
	$data = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index ORDER BY time DESC"));
}

?>
<? if(empty($_POST)): ?>
	<style>
		.dat_<?=$arg['blocknum']?> textarea {width:95%;}
	</style>
	<script src="/include/jquery/jquery.iframe-post-form.js"></script>
	<script>
		$(function(){
			$(".dat_<?=$arg['blocknum']?> form").iframePostForm({
				complete:function (data){
					if(isNaN(data)){// alert(data); }else{
						text = $("<div>").append(data).find(".data_<?=$arg['blocknum']?>").html();
						$(".data_<?=$arg['blocknum']?>").prepend(text);
					}
				}
			})//.find("textarea").text('');
			$(".add_<?=$arg['blocknum']?>").click(function(){
				$(this).next(".dat_<?=$arg['blocknum']?>").slideToggle();
			});
			$(".del_<?=$arg['blocknum']?>").live("click", function(){
				data_id = $(this).parents("[data_id]").attr("data_id");// alert(data_id);
				$.post("/blocks/<?=$arg['blocknum']?>/null", {del:data_id}, function(data){
					if(isNaN(data)){ alert(data) }else{
						$(".data_<?=$arg['blocknum']?> > div[data_id="+data_id+"]").slideToggle().remove();
					}
				});
			});
		});
	</script>

	<div class="add_<?=$arg['blocknum']?>" style="text-align:right; margin-bottom:10px;">
		<a href="javascript:return false;">Добавить вопрос</a>
	</div>
	<div class="dat_<?=$arg['blocknum']?>" style="display:none;">
		<form action="/blocks/<?=$arg['blocknum']?>/null" method="post">
			<div><textarea name="qw"></textarea></div>
			<div style="text-align:right;">
				<input type="submit" value="Добавить вопрос">
			</div>
		</form>
	</div>
<? endif; ?>
<div class="data_<?=$arg['blocknum']?>">
	<? foreach($data as $k=>$v): ?>
		<div data_id="<?=$v['id']?>" style="overflow:hidden;">
			<span style="float:right;">
				<span><?=date('d.m.Y', $v['time'])?></span>
				<? if($arg['access'] > 2): ?>
					<span class="del_<?=$arg['blocknum']?>" style="cursor:pointer;">
						<img src="/img/del.png">
					</span>
				<? endif; ?>
			</span>
			<span><?=$v['qw']?></span>
		</div>
	<? endforeach; ?>
</div>