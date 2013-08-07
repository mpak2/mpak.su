<? if($$arg['fe'] = $tpl[ $arg['fe'] ][ $_GET['id'] ]): ?>
	<?=aedit("/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_index&where[id]={$index['id']}")?>
	<h1><?=$index['name']?></h1>
	<script src="http://api-maps.yandex.ru/2.0-stable/?load=package.standard&lang=ru-RU" type="text/javascript"></script>
	<script type="text/javascript">
		ymaps.ready(init);
		var myMap;
		function init(){     
			myMap = new ymaps.Map ("map", {
				center: [<?=$index['latitude']?>, <?=$index['longitude']?>],
				zoom: <?=$index['zoom']?>,
			});
			myMap.controls.add(new ymaps.control.ZoomControl());
			<? foreach(rb($tpl['placemark'], "index_id", "id", $index['id']) as $placemark): ?>
				myMap.geoObjects.add(new ymaps.Placemark([<?=$placemark['latitude']?>, <?=$placemark['longitude']?>], {
					iconContent: "<?=$placemark['name']?>",
					balloonContentHeader: "<?=$placemark['name']?>",
					balloonContentBody: "<?=$placemark['text']?>",
					balloonContentFooter: "<?=$placemark['description']?>"
				}, {
					preset: 'twirl#blueStretchyIcon' // иконка растягивается под контент
				}));
            <? endforeach; ?>
		}
	</script>
	<div id="map" style="width:<?=($index['width'] ?: "100%")?>; height:<?=($index['height'] ?: "500px")?>; float:right;"></div>
	<div><?=$index['text']?></div>
<? else: ?>
	<div><?=$tpl['mpager']?></div>
	<? foreach($tpl[ $arg['fe'] ] as $$arg['fe']): ?>
		<div><a href="/<?=$arg['modname']?>:<?=$arg['fe']?>/<?=${$arg['fe']}['id']?>"><?=${$arg['fe']}['name']?></a></div>	
	<? endforeach; ?>
	<div><?=$tpl['mpager']?></div>
<? endif; ?>
