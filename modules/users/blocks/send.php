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
	if($_POST['status']){
		echo mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_event_send",
			array("uid"=>$conf['user']['uid'], "event_id"=>$_POST['event_id']),
			array("uid"=>$conf['user']['uid'], "event_id"=>$_POST['event_id'], "status"=>$_POST['status']),
			array("status"=>$_POST['status'])
		);
	}else{
		mpqw("DELETE FROM {$conf['db']['prefix']}{$arg['modpath']}_event_send WHERE uid=". (int)$conf['user']['uid']. " AND event_id=". (int)$_POST['event_id']);
	} exit;
};

$event = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_event WHERE send=1"));
$send = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_event_send WHERE uid=". (int)$arg['uid']), 'event_id');

$status = array(
	"Включен",
	"Если не на сайте",
	"Выключен",
);

?>
<script>
	$(function(){
		$("#event_mail_<?=$arg['blocknum']?>").find(".status_<?=$arg['blocknum']?>").change(function(){
			event_id = $(this).parents("[event_id]").attr("event_id");// alert(event_id);
			status = $(this).find("option:selected").val();// alert(status);
			$.post("/blocks/<?=$arg['blocknum']?>/null", {event_id:event_id, status:status}, function(data){
				if(isNaN(data)){ alert(data) }
			});
		});
	});
</script>
<div id="event_mail_<?=$arg['blocknum']?>">
	<? foreach($event as $k=>$v): ?>
		<div event_id="<?=$v['id']?>" style="overflow:hidden;">
			<div style="float:right; width:150px;">
				<select class="status_<?=$arg['blocknum']?>" style="width:100%;">
					<? foreach($status as $n=>$z): ?>
						<option value="<?=$n?>" <?=($send[ $v['id'] ]['status'] == $n ? "selected" : "")?>><?=$z?></option>
					<? endforeach; ?>
				</select>
			</div>
			<div><?=$v['name']?></div>
		</div>
	<? endforeach; ?>
</div>
