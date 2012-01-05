<? die; # ЯндексКарта

if ((int)$arg['confnum']){
/*	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

echo <<<EOF
	<form method="post">
		<input type="text" name="param" value="$param"> <input type="submit" value="Сохранить">
	</form>
EOF;*/

	return;
}

if($_POST['x'] && $_POST['y'] && $_GET['id']){
	mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']}_placemark SET x=". mpquot(substr($_POST['x'], 0, 8)). ", y=". mpquot(substr($_POST['y'], 0, 8)). " WHERE id=". (int)$_GET['id']. ($arg['access'] >= 5 ? '' : " AND uid=". (int)$conf['user']['uid']));
	echo (mysql_affected_rows() == 1 ? "accept" : "error"); exit;
}elseif(array_key_exists('add', $_GET) && $_POST){
	$conf['tpl']['sity'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_sity WHERE id=". (int)($conf['user']['sess']['sity_id'] ?: $conf['user']['sity_id'])), 0);
	if($mpdbf = mpdbf("{$conf['db']['prefix']}{$arg['modpath']}_placemark", array('uid'=>$conf['user']['uid'], 'x'=>(int)$conf['tpl']['sity']['x'], 'y'=>(int)$conf['tpl']['sity']['y'], 'period'=>(time()+max(86400, (int)$_POST['period'])))+$_POST)){
		mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_placemark SET $mpdbf");
		header("Location: /{$arg['modpath']}/drive:". mysql_insert_id());
	}
//	mpqw($sql = "INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_placemark SET name=\"". mpquot($_POST['name']). "\", type_id=". (int)$_POST['type_id']. ", wont_id=". (int)$_POST['wont_id']. ", description=\"". mpquot($_POST['description']). "\", price=\"". mpquot($_POST['price']). "\", period=". (time()+max(86400, (int)$_POST['period'])). ", uid=". (int)($conf['settings']['default_usr'] == $conf['user']['uname'] ? -$conf['user']['sess']['id'] : $conf['user']['uid']). ", x=". (int)$conf['tpl']['sity']['x']. ", y=". (int)$conf['tpl']['sity']['y']);
	$cnt = mpql(mpqw("SELECT COUNT(*) AS cnt FROM {$conf['db']['prefix']}{$arg['modpath']}_placemark"), 0, 'cnt');
	mpqw("UPDATE {$conf['db']['prefix']}settings SET value=". (int)$cnt. " WHERE name=\"ymaps_cnt\"");
}

$conf['tpl']['placemark'] = mpql(mpqw($sql = "SELECT p.*, u.name AS uname FROM {$conf['db']['prefix']}{$arg['modpath']}_placemark AS p LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_type AS t ON t.id=p.type_id LEFT JOIN {$conf['db']['prefix']}users AS u ON p.uid=u.id WHERE 1=1". ($_GET['drive'] ? " AND p.id=". (int)$_GET['drive'] : ''). ($_GET['type_id'] ? " AND type_id=". (int)$_GET['type_id'] : '')));
//mpre($conf['tpl']['placemark']);
$conf['tpl']['sity'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_sity WHERE id=". (int)($conf['user']['sess']['sity_id'] ?: $conf['user']['sity_id'])), 0);
$conf['tpl']['logo'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_logo"), 'wont_id', 'type_id');

?>
	<? if($_SERVER['REQUEST_URI'] == '/' || array_key_exists('ymaps', $_GET['m'])): ?>
		<script src="http://api-maps.yandex.ru/1.1/index.xml?key=<?=$conf['settings']['ymaps_key']?>" type="text/javascript"></script>
		<script type="text/javascript">
			window.onload = function () {
				var map = new YMaps.Map(document.getElementById("YMapsID"));
				var zoom = new YMaps.Zoom({
					customTips: [{ index: 2, value: "Дальняк" },{ index: 8, value: "Межгород" },{ index: 14, value: "По городу" }]
				}); map.addControl(zoom);

			<? if($_GET['drive']): ?>
				var coords = new YMaps.GeoPoint(<?=$conf['tpl']['sity']['x']?>, <?=$conf['tpl']['sity']['y']?>);
//				var coords = new YMaps.GeoPoint(<?=$conf['tpl']['placemark'][0]['x']?>, <?=$conf['tpl']['placemark'][0]['y']?>);
				var placemark = new YMaps.Placemark(coords, {draggable: true});// Создает и добавляет метку на карту
				placemark.setIconContent("Таскайте меня!!!");
				placemark.name = "Определите место на карте";
				placemark.description = "<a href=\"/\">Вернуться на карту</a>";
				map.addOverlay(placemark);
				var distance = 0, prev;// Обнуляет переменные, содержащие общее расстояние, пройденное меткой и предыдущую точку пути
				YMaps.Events.observe(placemark, placemark.Events.DragStart, function (obj) {// Прикрепляет обработчики событий метки
					prev = obj.getGeoPoint().copy();
				});
				YMaps.Events.observe(placemark, placemark.Events.Drag, function (obj) {
					var current = obj.getGeoPoint().copy();// Расчитывает общее расстояние, пройденное меткой при перетаскивании (в метрах)
					distance += current.distance(prev);
					prev = current;
					obj.setIconContent("Пробег: " + YMaps.humanDistance(distance));
				});
				YMaps.Events.observe(placemark, placemark.Events.DragEnd, function (obj) {// Устанавливает содержимое балуна
					var pnt = placemark.getGeoPoint();
					$.post('/<?=$arg["modpath"]?>/<?=$_GET["drive"]?>', {"x": pnt.getX(), "y": pnt.getY()}, function(data) {/* $(".result").html(data); */});
					placemark.name = "Координаты сохранены";
					placemark.description = "<a href=\"/\">Вернуться на карту</a>";
//					placemark.description = "положение: " + pnt;
					placemark.openBalloon();
					obj.setIconContent(null);// Стирает содержимое метки и обнуляет расстояние
					obj.update();
				});
			<? else: ?>
	//				var coords = new YMaps.GeoPoint(<?=$conf['settings']["{$arg['modpath']}_startx"]?>, <?=$conf['settings']["{$arg['modpath']}_starty"]?>);
					<? foreach($conf['tpl']['placemark'] as $k=>$v): ?>
						var s = new YMaps.Style();
						<? if(true): ?>
							s.iconStyle = new YMaps.IconStyle();
							<? if($logo = $conf['tpl']['logo'][ $v['wont_id'] ][ $v['type_id'] ]): ?>
								s.iconStyle.href = "/<?=$arg['modpath']?>:img/<?=(int)$logo['id']?>/tn:logo/w:25/h:25/c:1/null/img.jpg";
							<? else: ?>
								s.iconStyle.href = "/<?=$arg['modpath']?>:img/<?=(int)$v['wont_id']?>/tn:wont/w:25/h:25/c:1/null/img.jpg";
							<? endif; ?>
							s.iconStyle.size = new YMaps.Point(25, 25);
							s.iconStyle.offset = new YMaps.Point(-16, -37);
						<? endif; ?>
						var placemark = new YMaps.Placemark(new YMaps.GeoPoint(<?=$v['x']?>, <?=$v['y']?>), {style: s});
						placemark.name = '';
						<? if($v['uid'] < 0): ?>
							placemark.name = "<a href=\"/<?=$arg['modpath']?>/drive:<?=$v['id']?>\" style=\"float:left;\"><img src=\"/img/edit.png\"></a>";
						<? else: ?>
							placemark.name = "";
						<? endif; ?>
						<? if($v['uid'] > 0): ?>
							placemark.name += "<span><a href=\"/users/<?=$v['uid']?>\"><?=addslashes($v['uname'])?></a></span>&nbsp;";
						<? endif; ?>
						placemark.name += "<?=addslashes($v['name'])?>";
						placemark.description = "<div><?=addslashes($v['description'])?></div><div style=float:right;font-weight:bold;><?=addslashes($v['price'])?></div>";
						placemark.setIconContent("<?=addslashes($v['content'])?>");
						map.addOverlay(placemark);
					<? endforeach; ?>
				<? endif; ?>
				var coords = new YMaps.GeoPoint(<?=$conf['tpl']['sity']['x']?>, <?=$conf['tpl']['sity']['y']?>);
	//			var coords = new YMaps.GeoPoint(<?=$conf['settings']["{$arg['modpath']}_startx"]?>, <?=$conf['settings']["{$arg['modpath']}_starty"]?>);
				map.setCenter(coords, <?=$conf['tpl']['sity']['z']?>);
			}
		</script>
		<div id="YMapsID" style="width:100%;height:320px"></div>
	<? endif; ?>
