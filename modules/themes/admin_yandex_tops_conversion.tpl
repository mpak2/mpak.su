<? if($YANDEX_TOPS_INDEX = rb("yandex_tops_index", (get($_GET, 'limit') ?: 100))): ?>
	<p><?=$tpl['pager']?></p>
	<div class="table">
		<div class="th">
			<span>Сайт</span>
			<span>Запрос в поиске</span>
			<span>Просмотров</span>
			<span>Кликов</span>
			<span>Конверсия</span>
		</div>
		<? foreach($YANDEX_TOPS_INDEX as $yandex_tops_index): ?>
			<? if(!$yandex_tops = rb("yandex_tops", "id", $yandex_tops_index["yandex_tops_id"])): mpre("Запрос не найден"); ?>
			<? elseif(!$index = rb("index", "id", $yandex_tops_index['index_id'])): mpre("Хост не найден"); ?>
			<? else:// mpre($yandex_tops_index); ?>
				<? if($yandex_tops_index['view'] && ($conversion = number_format($yandex_tops_index['clicks']/$yandex_tops_index['view'], 5)) != ($_conversion = $yandex_tops_index['conversion'])): ?>
					<? $yandex_tops_index = fk("yandex_tops_index", ['id'=>$yandex_tops_index['id']], null, ['conversion'=>$conversion]) ?>
					<? mpre("Обновлена конверсия для [{$yandex_tops['name']}] {$_conversion} {$conversion}"); ?>
				<? endif; ?>
				<div>
					<span><?=$index['name']?></span>
					<span><?=$yandex_tops['name']?></span>
					<span><?=$yandex_tops_index['view']?></span>
					<span><?=$yandex_tops_index['clicks']?></span>
					<span><?=$yandex_tops_index['conversion']?></span>
				</div>
			<? endif; ?>
		<? endforeach; ?>
	</div>
<? else: mpre("Данные индексации не найдены"); endif; ?>
