<div>
	<div class="sess_map" style="margin-bottom:5px;">
		Лимит: <select class="limit" style="width:50px;">
			<? for($i = 10; $i <= 100; $i+=10): ?>
				<option <?=($_GET['limit'] == $i ? "selected" : "")?>><?=$i?></option>
			<? endfor; ?>
		</select>
		Группа <select class="grp" style="width:250px;">
			<option></option>
			<? foreach($tpl['grp'] as $v): ?>
				<option value="<?=$v['id']?>" <?=($v['id'] == $_GET['grp_id'] ? "selected" : "")?>><?=$v['name']?></option>
			<? endforeach; ?>
		</select>
		<script>
			$(function(){
				$(".sess_map select").change(function(){
					var limit = $(this).parents(".sess_map").find("select.limit option:selected").val();// alert(limit);
					var grp_id = $(this).parents(".sess_map").find("select.grp option:selected").val();// alert(grp_id);
					document.location.href = "/<?=$arg['modname']?>"+(limit > 0 ? "/limit:"+limit : "")+(grp_id > 0 ? "/grp_id:"+grp_id : "");
				});
			});
		</script>
	</div>
	<!-- Этот блок кода нужно вставить в ту часть страницы, где вы хотите разместить карту (начало) -->
		<div id="ymaps-map-id_135500339044851231440" style="width: 100%; height: 550px;"></div>
		<div style="width: 450px; text-align: right;">
			<a href="http://api.yandex.ru/maps/tools/constructor/index.xml" target="_blank" style="color: #1A3DC1; font: 13px Arial, Helvetica, sans-serif;">Создано с помощью инструментов Яндекс.Карт</a>
		</div>
		<script type="text/javascript">
			function fid_135500339044851231440(ymaps) {
				var map = new ymaps.Map("ymaps-map-id_135500339044851231440", {
					center: [31.337304,58.590562], zoom: 3,
					type: "yandex#map"
				});
				map.controls
					.add("zoomControl")
					.add("mapTools")
					.add(new ymaps.control.TypeSelector(["yandex#map", "yandex#satellite", "yandex#hybrid", "yandex#publicMap"]));
				<? foreach($tpl['sess'] as $v): ?>
					map.geoObjects.add(new ymaps.Placemark([<?=$v['geo']?>],
						{presetStorage:'twirl#yellowStretchyIcon', balloonContent:"<b>Вход</b>: <?=date("Y.m.d H:i:s", $v['last_time'])?><br /><b>Агент</b>: <?=$v['agent']?><br /><b>Источник</b>: <a target=_blank href=\"<?=$v['ref']?>\"><?=$v['ref']?></a><br /><b>Открытых страниц</b>: <?=$v['count']?> / <?=$v['cnull']?><br /><b>Время на сайте</b>: <?=gmdate('H:i:s', $v['count_time'])?>", iconContent:"<?=$v['uname']?><?=($v['uname'] == $conf['settings']['default_usr'] ? -$v['id'] : "")?>"},
						{preset: 'twirl#lightblueStretchyIcon'}
					));
				<? endforeach; ?>
			};
		</script>
		<script type="text/javascript" src="http://api-maps.yandex.ru/2.0-stable/?lang=ru-RU&coordorder=longlat&load=package.full&wizard=constructor&onload=fid_135500339044851231440"></script>
	<!-- Этот блок кода нужно вставить в ту часть страницы, где вы хотите разместить карту (конец) -->
</div>