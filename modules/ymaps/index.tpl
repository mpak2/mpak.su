    <script src="http://api-maps.yandex.ru/1.1/index.xml?key=<?=$conf['settings']['ymaps_key']?>" type="text/javascript"></script>

    <script type="text/javascript">
        window.onload = function () {
			var map = new YMaps.Map(document.getElementById("YMapsID"));
			var zoom = new YMaps.Zoom({
				customTips: [{ index: 1, value: "Мелко" },{ index: 9, value: "Средне" },{ index: 16, value: "Крупно" }]
			}); map.addControl(zoom);
			var coords = new YMaps.GeoPoint(<?=$conf['settings']["{$arg['modpath']}_startx"]?>, <?=$conf['settings']["{$arg['modpath']}_starty"]?>);

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
					$.post('/<?=$arg["modpath"]?>/<?=$_GET["drive"]?>', {"x": pnt.getX(), "y": pnt.getY()}, function(data) {
//						$(".result").html(data);
					});
					placemark.name = "Координаты сохранены";
					placemark.description = "<a href=\"/\">Вернуться на карту</a>";

			$('#basic-modal').click(function(){
				alert('modal');
				$('#basic-modal-content').modal();
				return false;
			});

//					placemark.description = "положение: " + pnt;
					placemark.openBalloon();
					obj.setIconContent(null);// Стирает содержимое метки и обнуляет расстояние
					obj.update();
				});
//				alert('Определите место обьявления на карте...');
			<? else: ?>
				<? foreach($conf['tpl']['placemark'] as $k=>$v): ?>
					var s = new YMaps.Style();
					<? if($v['img']): ?>
						s.iconStyle = new YMaps.IconStyle();
						s.iconStyle.href = "/<?=$arg['modpath']?>:img/<?=$v['drive']?>/tn:placemark/w:30/h:30/c:1/null/img.jpg";
						s.iconStyle.size = new YMaps.Point(30, 30);
						s.iconStyle.offset = new YMaps.Point(-1, -1);
					<? endif; ?>
					var placemark = new YMaps.Placemark(new YMaps.GeoPoint(<?=$v['x']?>, <?=$v['y']?>), {style: s});
					placemark.name = "<?=$v['name']?>";
					placemark.description = "<?=$v['description']?> <b><?=$v['price']?></b>";
					placemark.setIconContent("<?=$v['content']?>");
					map.addOverlay(placemark);
				<? endforeach; ?>
			<? endif; ?>
			map.setCenter(coords, <?=(int)$conf['settings']["{$arg['modpath']}_zoom"]?>);
			<? if($_GET['addr']): ?>

				var geocoder = new YMaps.Geocoder("<?=$_GET['addr']?>");
				YMaps.Events.observe(geocoder, geocoder.Events.Load, function (geocoder) {
					map.addOverlay(geocoder.get(0));
					map.setBounds(geocoder.get(0).getBounds());
				});

			<? endif; ?>
        }
	</script>
	<div style="float:left;" class="result"></div>
	<div id="YMapsID" style="width:100%;height:500px"></div>