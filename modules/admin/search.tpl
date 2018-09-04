<div style="margin:10px;">
	<form method="get">
		<input type="text" value="<?=get($tpl, 'search')?>" name="search" placeholder="Запрос поиска" style="min-width:50%;">
		<button>Найти</button>
	</form>
	<? if(!$result = get($tpl, 'result')): mpre("Результат расчетов не найден") ?>
	<? elseif(!array_filter($list = array_column($result, 'list', 'name'))): mpre("Ничего не найдено") ?>
	<?// elseif(!$mpager = mpager(10)): mpre("ОШИБКА пагинации") ?>
	<? elseif(!is_string($search = get($_REQUEST, 'search'))): mpre("Запрос не найден") ?>
	<? else:// mpre($search) ?>
		<p><div id="resultStats" style="color:#808080;"><nobr> (<!-- [settings:microtime] --> сек.)&nbsp;</nobr></div></p>
		<div><?=get($tpl, "pager")?></div>
		<? foreach($result as $table=>$tab):// mpre($tab) ?>
			<? if($param = get($tpl, "param", $table)): ?>
				<? foreach($tab['list'] as $index): ?>
					<? if(!$name = strip_tags(implode(" ", $index))): mpre("ОШИБКА получения суммы всех имен") ?>
					<? elseif(!$name = mb_substr($name, 0, 350)): mpre("Ограницение текста по ширине") ?>
					<? elseif(!$name = strtr($name, [$search=>"<span style=\"background-color:yellow\">{$search}</span>"])): mpre("Подсвечивание искомого текста") ?>
					<? else: ?>
						<div style="margin: 15px 3px 3px 3px; width:80%;">
							<div style="float:right;">(<?=$param['name']?>)</div>
							<div style="font-style:italic; font-weight:bold;">
								<?=strip_tags(get($index, 'name'))?>
							</div>
							<p><?=$name?></p>
							<div>
								<a href="<?=($href = strtr($param['href'], mpzam($index)))?>" style="color:#093;"><?=$href?></a>
							</div>
						</div>
					<? endif; ?>
				<? endforeach; ?>
			<? endif; ?>
		<? endforeach; ?>
		<div><?=get($tpl, "pager")?></div>
	<? endif; ?>
</div>
