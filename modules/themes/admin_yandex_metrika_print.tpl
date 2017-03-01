<? if(!$INDEX = rb("index")): mpre("Хосты не найдены"); ?>
<? elseif(!$YANDEX_METRIKA = rb("yandex_metrika")): mpre("Метрики хостов не найдены") ?>
<? elseif(!$YANDEX_METRIKA_DIMENSIONS = rb("yandex_metrika_dimensions")): mpre("Измерения не найдены") ?>
<? elseif(!$YANDEX_METRIKA_PERIOD = rb("yandex_metrika_period",5)): mpre("Периоды не найдены")?>
<? elseif(!get($_GET,"now") && (!$YANDEX_METRIKA_PERIOD = rb(array_slice($YANDEX_METRIKA_PERIOD,1),"id"))): mpre("Ошибка исключения первого элемента") ?>
<? elseif(!$yandex_metrika_period = first($YANDEX_METRIKA_PERIOD)): mpre("Ошибка получения периода сортировки") ?>
<? elseif(!$YANDEX_METRIKA_METRICS = rb("yandex_metrika_metrics","yandex_metrika_period_id","yandex_metrika_dimensions_id","id",$YANDEX_METRIKA_PERIOD,"[NULL,0]")): mpre("Значений метрик не найдено") ?>
<? elseif(!$SORT = rb($YANDEX_METRIKA_METRICS,"yandex_metrika_period_id","id",$yandex_metrika_period["id"])): mpre("Ошибка получения метрик периода сортировки") ?>
<? elseif(($SORT2 = array_column($SORT,"users","yandex_metrika_id")) && !arsort($SORT2)): mpre("Ошибка форматирования метрик периода сортировки") ?>
<? elseif($VISIBLE = []): mpre("Массив видимых сайтов") ?>
<? else:// mpre($YANDEX_METRIKA_PERIOD) ?>
	<? if(!array_key_exists("null",$_GET)): ?>
		<p><a href="/themes:admin_yandex_metrika_print/null">Версия для печати</a> <a href="/themes:admin_yandex_metrika_print/now:1">Текущая неделя</a></p>
	<? endif; ?>
	<style>
				.table {border-collapse:collapse; font-size:8px;}
				.table > div > span { vertical-align:middle;}
				.table > div > span:first-child {width:30px;}
				.table > div:hover {background-color:#f4f4f4;}
				.table > div >span:hover {background-color:#eee;}
				div.table {display:table; width:100%;}
				div.table > div {display:table-row;}
				div.table > div > span {display:table-cell; vertical-align:top; border:1px solid #d4d1d1;}
				div.table > div.th >span { font-weight:bold; border:1px solid #d4d1d1;}
	</style>
	<div class="table">
		<div class="th">
			<span>№</span>
			<span>сайт</span>
			<? foreach($YANDEX_METRIKA_PERIOD as $yandex_metrika_period): ?>
				<span><?=$yandex_metrika_period["date1"]?> - <?=$yandex_metrika_period["date2"]?></span>
			<? endforeach; ?>
		</div>
		<?// foreach($INDEX as $index): ?>
		<? foreach($SORT2 as $metrika_id=>$sort): ?>
			<? if(!$yandex_metrika = rb($YANDEX_METRIKA,"id",$metrika_id)): mpre("Метрика сортировки не найдена") ?>
			<? elseif(!$index = rb($INDEX,"id",$yandex_metrika["index_id"])): mpre("Хост по метрике не найден") ?>
			<? elseif(preg_match("#[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+#", $index['name'])):// mpre("Технический хост") ?>
			<? elseif($index["index_id"]): //mpre("Сайт является зеркалом") ?>
			<? elseif(!$VISIBLE[$yandex_metrika["id"]] = $yandex_metrika): mpre("Ошибка добавления метрики в список видимых") ?>
			<? else: ?>
				<div>
					<span><?=(empty($n) ? $n=1 : ++$n)?></span>
					<span><?=$index["name"]?></span>
					<? foreach($YANDEX_METRIKA_PERIOD as $yandex_metrika_period): ?>
						<span>
							<? if(!$yandex_metrika = rb($YANDEX_METRIKA,"index_id",$index["id"])): //mpre("Метрика сайта не найдена {$index["name"]}")?>
								Нет метрики
							<? elseif(!$yandex_metrika_metrics = rb($YANDEX_METRIKA_METRICS,"yandex_metrika_id","yandex_metrika_period_id",$yandex_metrika["id"],$yandex_metrika_period["id"])): // mpre("Метрика сайта не найдена {$index["name"]}") ?>
								Нет данных
							<? else: ?>
								<?=$yandex_metrika_metrics["users"] ?>
							<? endif; ?>
						</span>
					<? endforeach; ?>
				</div>
			<? endif; ?>
		<? endforeach; ?>
		<div><?// mpre($VISIBLE) ?>
			<span></span>
			<span><b>Итого</b></span>
			<? foreach($YANDEX_METRIKA_PERIOD as $yandex_metrika_period): ?>
				<? if(!$sum_YANDEX_METRIKA_METRICS = rb($YANDEX_METRIKA_METRICS,"yandex_metrika_period_id","yandex_metrika_id","id",$yandex_metrika_period["id"],$VISIBLE)): mpre("Метрики для периода не найдены") ?>
				<? elseif(!$SUM = array_column($sum_YANDEX_METRIKA_METRICS,"users")): mpre("Ошибка выборки значений пользователей") ?>
				<? elseif(!$sum = array_sum($SUM)): mpre("Ошибка получения значений суммы") ?>
				<? else: //mpre($SUM)?>
					<span><b><?=$sum?></b></span>
				<? endif; ?>
			<? endforeach; ?>
		</div>
	</div>
<? endif; ?>