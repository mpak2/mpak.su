<? die; # Нуль

if ((int)$arg['confnum']){
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST){
		$param = array($_POST['param']=>$_POST['val'])+(array)$param;
		mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	} if(array_key_exists("null", $_GET)) exit;

	$klesh = array(
/*		"Количество символов"=>0,
		"Курс доллара"=>30,
		"Список"=>array(
			1=>"Одын",
			2=>"Два",
		),
		"Город"=>spisok("SELECT id, name FROM {$conf['db']['prefix']}users_sity ORDER BY name"),*/
	);

?>
		<!-- Настройки блока -->
	<script src="/include/jquery/my/jquery.klesh.select.js"></script>
	<script>
		$(function(){
			<? foreach($klesh as $k=>$v): ?>
				<? if(gettype($v) == 'array'): ?>
					$(".klesh_<?=strtr(md5($k), array("="=>''))?>").klesh("/?m[blocks]=admin&r=mp_blocks&null&conf=<?=$arg['confnum']?>", function(){
					}, <?=json_encode($v)?>);
				<? else: ?>
					$(".klesh_<?=strtr(md5($k), array("="=>''))?>").klesh("/?m[blocks]=admin&r=mp_blocks&null&conf=<?=$arg['confnum']?>");
				<? endif; ?>
			<? endforeach; ?>
		});
	</script>
	<div style="margin-top:10px;">
		<? foreach($klesh as $k=>$v): ?>
			<div style="overflow:hidden;">
				<div style="width:200px; float:left; padding:5px; text-align:right; font-weight:bold;"><?=$k?> :</div>
				<? if(gettype($v) == 'array'): ?>
					<div class="klesh_<?=strtr(md5($k), array("="=>''))?>" param="<?=$k?>"><?=$v[ $param[$k] ]?></div>
				<? else: ?>
					<div class="klesh_<?=strtr(md5($k), array("="=>''))?>" param="<?=$k?>"><?=($param[$k] ?: $v)?></div>
				<? endif; ?>
			</div>
		<? endforeach; ?>
	</div>
<? return;

}//$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));
//$uid = $_GET['id'] && array_key_exists('users', $_GET['m']) ? $_GET['id'] : $conf['user']['id'];

foreach($conf['event'] as $v){
	if($v['log_last']){
		$event[ $v['id'] ] = array_intersect_key($v, array_flip(array("id", "name", "log_last", "last")));
	}
}// mpre($event);// mpre($conf['event']);

if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum'])){
	foreach($_POST['event'] as $v){
		if($v['log_last'] != $conf['event'][ $v['name'] ]['log_last']){
			$log[ $v['id'] ] = mpql(mpqw($sql = "SELECT l.*, u.name AS uname FROM {$conf['db']['prefix']}{$arg['modpath']}_event_logs AS l LEFT JOIN {$conf['db']['prefix']}users AS u ON (l.uid=u.id) WHERE l.event_id=". (int)$v['id']. " AND l.id>". (int)$v['log_last']. " LIMIT 1"), 0);
		}
	} exit($log ? json_encode(array("log"=>$log, "event"=>$event)) : 0);
}

//$dat = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} LIMIT 10"));

?>
<script src="/include/jquery/pnotify-1.1.1/jquery.pnotify.js"></script>
<link rel="stylesheet" href="/include/jquery/pnotify-1.1.1/jquery.pnotify.default.css" type="text/css">
<style>
	.ui-pnotify {background-color:#eee; border:1px solid #888; border-radius:10px;}
</style>
<script>
	var event = <?=json_encode($event)?>;
	$(function(){
		setInterval(function(){
			$.post("/blocks/<?=$arg['blocknum']?>/null", {event:event}, function(data){
				if(isNaN(data)){
					if(json = $.parseJSON(data)){
						$.each(json["log"], function(key, val){
							if(event[ val["event_id"] ]["name"] == "Просмотр фото"){
								val["description"] = "<a href=\""+val["description"]+"\">"+val["description"]+"</a>";
							}else if(event[ val["event_id"] ]["name"] == "Открытие страницы сайта"){
								val["description"] = "<a href=\""+val["description"]+"\">"+val["description"]+"</a>";
							} if(val["uid"] != <?=$conf['user']['uid']?>){
								$.pnotify({
									pnotify_title: event[ val["event_id"] ]["name"],
									pnotify_text: val["description"]+"<span style='float:right'>"+(val["uid"] > 0 ? "<a href=\"/users/"+val["uid"]+"\">"+val["uname"]+"</a>" : "<?=$conf['settings']['default_usr']?>"+val["uid"])+"</span>",
									pnotify_type: "error",
									pnotify_error_icon: "ui-icon ui-icon-signal-diag"
								});
							}
						}); event = json["event"];
					}else{
						alert(data);
					}
				}
			});
		}, 5000);
	});
</script>