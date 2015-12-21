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

$event = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} ORDER BY name"));

if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){
	if($event[$_POST['event_id']]){
		echo "<div>{$event[$_POST['event_id']]['name']}</div>";
		echo "<div>". date("Y.m.d H:i:s"). ":". array_shift(explode('.', microtime()))."</div>";

		ob_start();
		$return = mpevent($event[$_POST['event_id']]['name'], $_POST['mess_id'], $_POST['own_id']);
		$content = ob_get_contents();
		ob_end_clean();

		echo "<div style=color:red;>==>{$return}<==</div>";
		echo "<div style=color:blue;>{$content}</div>";
	}
	exit;
};

?>
<div>
	<script>
		$(function(){
			$("#btn_<?=$arg['blocknum']?>").mousedown(function(){
				event_id = $("#event_<?=$arg['blocknum']?>").find("option:selected").val();// alert(event_id);
				mess_id = $("#mess_<?=$arg['blocknum']?>").val();// alert(mess_id);
				own_id = $("#own_<?=$arg['blocknum']?>").val();// alert(own_id);
				$.post("/blocks/<?=$arg['blocknum']?>/null", {event_id:event_id, mess_id:mess_id, own_id:own_id}, function(data){
					$("#return_<?=$arg['blocknum']?>").html(data);
				});
			});
		});
	</script>
	<select id="event_<?=$arg['blocknum']?>" style="max-width:200px;">
		<option value="0"></option>
		<? foreach($event as $k=>$v): ?>
			<option value="<?=$v['id']?>"><?=$v['name']?></option>
		<? endforeach; ?>
	</select>
	<div style="text-align:right; margin:5px;">
		<input id="mess_<?=$arg['blocknum']?>" type="text" style="width:80px;">
		<input id="own_<?=$arg['blocknum']?>" type="text" style="width:80px;" value="<?=$conf['user']['uid']?>">
		<input id="btn_<?=$arg['blocknum']?>" type="button" value="Событие">
	</div>
	<div id="return_<?=$arg['blocknum']?>"></div>
</div>
