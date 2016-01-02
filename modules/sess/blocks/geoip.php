<? # Нуль

if(array_key_exists('confnum', $arg)){
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST){
		$param = array($_POST['param']=>$_POST['val'])+(array)$param;
		mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	} if(array_key_exists("null", $_GET)) exit;

	$klesh = array(
/*		($f = "Ширина")=>($param[ $f ] = $param[ $f ] ?: 200),
		($f = "Высота")=>($param[ $f ] = $param[ $f ] ?: 200),
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

if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){
	$geoname_id = mpfdk("{$conf['db']['prefix']}users_geoname", array("geonameId"=>$_POST['geoname']['geonameId']), $_POST['geoname'], $_POST['geoname']);
	if($conf['user']['uid'] > 0){
		if(array_key_exists("geoname_id", $conf['user']) && !$conf['user']['geoname_id']){
			mpqw($sql = "UPDATE {$conf['db']['prefix']}users SET geoname_id=". (int)$geoname_id. " WHERE id=". (int)$conf['user']['id']);
		}
		if((array_key_exists("geo", $conf['user'])) && empty($conf['user']['geo'])){
			mpqw($sql = "UPDATE {$conf['db']['prefix']}users SET geo=\"". mpquot($_POST['smart']['latitude']. ",". $_POST['smart']['longitude']). "\" WHERE id=". (int)$conf['user']['id']);
		}
	}
	if((array_key_exists("geo", $conf['user']['sess'])) && empty($conf['user']['sess']['geo'])){
		mpqw($sql = "UPDATE {$conf['db']['prefix']}{$arg['modpath']} SET geo=\"". mpquot($_POST['smart']['longitude']. ",". $_POST['smart']['latitude']). "\" WHERE id=". (int)$conf['user']['sess']['id']);
	} if(array_key_exists("geoname_id", $conf['user']['sess']) && !$conf['user']['sess']['geoname_id']){
		mpqw($sql = "UPDATE {$conf['db']['prefix']}{$arg['modpath']} SET geoname_id=". (int)$geoname_id. " WHERE id=". (int)$conf['user']['sess']['id']);
	} exit("<div class=\"geoip_{$arg['blocknum']}\"><b>{$_POST['smart']['hidden']['countryName']}</b> {$_POST['smart']['hidden']['city']}</div>");
};

if(!array_key_exists("geoname_id", $conf['user'])){ # Поля геоимени в таблицу пользователя
	mpqw("ALTER TABLE `{$conf['db']['prefix']}users` ADD `geoname_id` int(11) NOT NULL AFTER `last_time`");
	mpqw("ALTER TABLE `{$conf['db']['prefix']}users` ADD INDEX (`geoname_id`)");
}

if(!array_key_exists("geo", $conf['user'])){ # Поле координат в таблицу пользователя
	mpqw("ALTER TABLE `{$conf['db']['prefix']}users` ADD `geo` varchar(255) NOT NULL AFTER `geoname_id`");
} if(!array_key_exists("geo", $conf['user']['sess'])){ # Поле координат в таблицу сессий
	mpqw("ALTER TABLE `{$conf['db']['prefix']}sess` ADD `geo` varchar(255) NOT NULL AFTER `last_time`");
}

?>

<? if(	!array_key_exists("null", $_GET) && (
		((array_key_exists("geo", $conf['user']['sess']) && !$conf['user']['sess']['geo']) || (array_key_exists("geoname_id", $conf['user']['sess']) && empty($conf['user']['sess']['geoname_id']))) ||
		($conf['user']['uid'] > 0 && ((array_key_exists("geo", $conf['user']) && empty($conf['user']['geo'])) || (array_key_exists("geoname_id", $conf['user']) && empty($conf['user']['geoname_id']))))
	)): ?>
	<script>
		$(function(){
			$.ajax({
				dataType: "jsonp",
				url: "http://smart-ip.net/geoip-json?lang=ru",
				jsonpCallback: function(smart){
					$.getJSON("http://ws.geonames.org/searchJSON?country=RU&maxRows=10&name_startsWith="+smart.city, function(geoname){ console.log("geoname:", geoname);
						$.post("/blocks/<?=$arg['blocknum']?>/null", {smart:smart, geoname:geoname.geonames[0]}, function(data){
							if(html = $("<div>").html(data).find(".geoip_<?=$arg['blocknum']?>").html()){
								$(".geoip_<?=$arg['blocknum']?>").append(html);
							}else{ alert(data) }
						});
					});
				},
				jsonp:function(){
					console.log("jsonp");
				},
/*			$.getJSON("http://smart-ip.net/geoip-json?lang=ru", function(smart){
				$.getJSON("http://ws.geonames.org/searchJSON?country=RU&maxRows=10&name_startsWith="+smart.city, function(geoname){ console.log("geoname:", geoname);
					$.post("/blocks/<?=$arg['blocknum']?>/null", {smart:smart, geoname:geoname.geonames[0]}, function(data){
						if(html = $("<div>").html(data).find(".geoip_<?=$arg['blocknum']?>").html()){
							$(".geoip_<?=$arg['blocknum']?>").append(html);
						}else{ alert(data) }
					});
				});
			});*/
			});
		});
	</script>
<? endif; ?>
<div class="geoip_<?=$arg['blocknum']?>" style="text-align:center;"><?=$data?></div>
