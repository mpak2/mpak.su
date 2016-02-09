<div style="margin:10px;">
	<form method="post">
		<input type="text" value="<?=get($tpl, 'search')?>" name="search" placeholder="Запрос поиска" style="min-width:50%;">
		<button>Найти</button>
	</form>
	<p><div id="resultStats" style="color:#808080;">Результатов: <?=(int)get($tpl, "counter")?><nobr> (<!-- [settings:microtime] --> сек.)&nbsp;</nobr></div></p>
	<? if($result = get($tpl, 'result')): ?>
		<? foreach($result as $table=>$val): ?>
			<? if($param = get($tpl, "param", $table)): ?>
				<? foreach($val['list'] as $index): ?>
					<div style="margin: 15px 3px 3px 3px; width:80%;">
						<div style="float:right;">(<?=$param['name']?>)</div>
						<div style="font-style:italic; font-weight:bold;">
							<?=strip_tags(get($index, 'name'))?>
						</div>
						<p><?=mb_substr(strip_tags(implode(" ", $index)), 0, 350)?></p>
						<div>
							<a href="<?=($href = strtr($param['href'], mpzam($index)))?>" style="color:#093;"><?=$href?></a>
						</div>
					</div>
				<? endforeach; ?>
			<? endif; ?>
		<? endforeach; ?>
	<? endif; ?>
	<div style="margin:10px;">
		<?=get($tpl, 'pager')?>
	</div>
</div>
