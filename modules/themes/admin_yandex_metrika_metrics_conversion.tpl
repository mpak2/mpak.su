<div class="<?=$arg['modpath']?> <?=$arg['fn']?>">
	<h1>Просмот страниц конверсия</h1>
	<style>
		.<?=$arg['modpath']?>.<?=$arg['fn']?> .table > div.th span {color:#ddd; text-align:center;}
		.<?=$arg['modpath']?>.<?=$arg['fn']?> .table > div > span.itogo {background-color:#777; color:white; text-align:center;}
		.<?=$arg['modpath']?>.<?=$arg['fn']?> .table > div > span.itogo > span {color:#ccc;}
		.<?=$arg['modpath']?>.<?=$arg['fn']?> .table > div:hover > span.itogo {background-color:#aaa;}
		.<?=$arg['modpath']?>.<?=$arg['fn']?> .table > div:hover {background-color:#eee;}
		.<?=$arg['modpath']?>.<?=$arg['fn']?> .table > div > span.active {background-color:#eee;}
		.<?=$arg['modpath']?>.<?=$arg['fn']?> .table > div.active {background-color:#aaa;}
		.<?=$arg['modpath']?>.<?=$arg['fn']?> .table > div.active > span.active {background-color:#888;}
	</style>
	<? if($tpl['yandex_metrika_period'] = rb("yandex_metrika_period")): ?>
		<div class="table">
			<div class="th">
				<span>пп</span>
				<span>Счетчик</span>
				<span>Хост</span>
				<? foreach($tpl['yandex_metrika_period'] as $yandex_metrika_period): ?>
					<span><?=$yandex_metrika_period['date1']?>&nbsp;/&nbsp;<?=((strtotime($yandex_metrika_period['date2'])-strtotime($yandex_metrika_period['date1']))/86400+1)?></span>
				<? endforeach; ?>
				<span>Результат</span>
			</div>
			<? foreach(rb("yandex_metrika") as $yandex_metrika): ?>
				<? if($index = rb("index", "id", $yandex_metrika['index_id'])): ?>
					<div>
						<span><?=($nn = (empty($nn) ? 1 : ++$nn))?>.</span>
						<span><?=$yandex_metrika['id']?></span>
						<span><?=$index['name']?></span>
						<? foreach($tpl['yandex_metrika_period'] as $yandex_metrika_period): ?>
							<? if($yandex_metrika_metrics = rb("yandex_metrika_metrics", "yandex_metrika_id", "yandex_metrika_period_id", "yandex_metrika_dimensions_id", $yandex_metrika['id'], $yandex_metrika_period['id'], 0)): ?>
								<?// mpre("Результат измерений за данный период", $yandex_metrika_metrics); ?>
							<? endif; ?>
							<span>
								<span title="Визиты"><?=($host[$yandex_metrika['id']]["visits"][] = $week[$yandex_metrika_period['id']]["visits"][] = $visits = get($yandex_metrika_metrics, "visits"))?></span>
								<span title="Просмотры"><?=($host[$yandex_metrika['id']]["pageviews"][] = $week[$yandex_metrika_period['id']]["pageviews"][] = $pageviews = get($yandex_metrika_metrics, "pageviews"))?></span>
								<b title="Конверсия"><?=($week[$yandex_metrika['id']]["conversion"][] = ($pageviews ? number_format($pageviews / $visits, 2) : 0))?></b>
							</span>
						<? endforeach; ?>
						<span class="itogo">
							<span title="Визиты"><?=($visits = array_sum($host[$yandex_metrika['id']]["visits"]))?></span>
							<span title="Просмотры"><?=($pageviews = array_sum($host[$yandex_metrika['id']]["pageviews"]))?></span>
							<b title="Конверсия">
								<?=($conversion = ($pageviews ? number_format($pageviews / $visits, 2) : 0))?>
								<? if($conversion != $yandex_metrika['conversion']): ?>
									<? $yandex_metrika = fk("yandex_metrika", array("id"=>$yandex_metrika['id']), null, array('conversion'=>$conversion));# mpre("Сохраняем результат конверсии", $yandex_metrika); ?>
								<? endif; ?>
							</b>
						</span>
					</div>
				<? endif; ?>
			<? endforeach; ?>
			<div class="th">
				<span></span>
				<span>Среднее</span>
				<span>Итого</span>
				<? foreach($tpl['yandex_metrika_period'] as $yandex_metrika_period): ?>
					<span>
						<span title="Визиты"><?=($itogo["visits"][] = $visits = array_sum($week[$yandex_metrika_period['id']]["visits"]))?></span>
						<span title="Просмотры"><?=($itogo["pageviews"][] = $pageviews = array_sum($week[$yandex_metrika_period['id']]["pageviews"]))?></span>
						<b title="Конверсия"><?=($pageviews ? number_format($pageviews / $visits, 2) : 0)?></b>
					</span>
				<? endforeach; ?>
				<span>
					<span title="Визиты"><?=($visits = array_sum($itogo["visits"]))?></span>
					<span title="Просмотры"><?=($pageviews = array_sum($itogo["pageviews"]))?></span>
					<b title="Конверсия"><?=($pageviews ? number_format($pageviews / $visits, 2) : 0)?></b>
				</span>
			</div>
		</div>
	<? else: mpre("Периоды не найдены"); endif; ?>
</div>
