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

$tpl['event'] = rb($conf['event'], "log", "id", "[1,2]");

if($_POST['max']){
	$uid = array();
	foreach($tpl['event'] as $event){
		if($_POST['max'][ $event['id'] ]){
			$tpl['event_logs'][ $event['id'] ] = qn($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_event_logs WHERE uid<>". (int)$conf['user']['uid']. " AND event_id=". (int)$event['id']. " AND id>". (int)$_POST['max'][ $event['id'] ]);
			$uid += rb($tpl['event_logs'][ $event['id'] ], "uid");
		}
	} $tpl['uid'] = qn("SELECT id, name FROM {$conf['db']['prefix']}users WHERE id IN (". in($uid). ")");
	exit(json_encode(array("event"=>$tpl['event'], "event_logs"=>$tpl['event_logs'], "uid"=>$tpl['uid'])));
}

?>
<script src="/include/jquery/pnotify-1.1.1/jquery.pnotify.js"></script>
<link rel="stylesheet" href="/include/jquery/pnotify-1.1.1/jquery.pnotify.default.css" type="text/css">
<link rel="stylesheet" href="/include/jquery/jquery-ui/themes/redmond/jquery-ui-1.8.23.custom.css" type="text/css">
<script>
	var max = <?=json_encode(array_column($tpl['event'], "log_last", "id"))?>;
	$(function(){
		setInterval(function(){
			$.post("/blocks/<?=$arg['blocknum']?>/null", {max:max}, function(json){
//				console.log("json:", json, "length:", json["event_logs"].length);
				$.each(json["event_logs"], function(k, event){
					$.each(event, function(key, event_logs){
						if(json.event[ event_logs["event_id"] ].name == "Открытие страницы сайта"){
							event_logs.description = "<a target=blank href='"+event_logs.description+"'>"+event_logs.description+"</a>";
						}// console.log("uid:", json.uid[ event_logs["uid"] ])
						
						$.pnotify({
							pnotify_title: (typeof(json.uid[ event_logs["uid"] ]) != "undefined" ? json.uid[ event_logs["uid"] ].name : "гость"+ event_logs["uid"]),
							pnotify_text: "<strong>"+json.event[ event_logs["event_id"] ].name+"</strong><br /><br />"+event_logs.description,
							pnotify_type: "info",
							pnotify_error_icon: "ui-icon ui-icon-signal-diag"
						});
						if(max[event_logs.event_id] < event_logs.id){
							max[event_logs.event_id] = event_logs.id;
						}// console.log("max:", max);
					})
				});
			}, "json").fail(function(error){
				alert(error.responseText);
			});
		}, 5000);
	});
</script>