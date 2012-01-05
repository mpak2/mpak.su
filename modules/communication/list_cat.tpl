<div>
	<? foreach($conf['tpl']['relations'] as $r=>$list): ?>
		<div>
			<div><?=$conf['tpl']['cat'][ $r ]['name']?></div>
			<? foreach($list as $v): ?>
				<div style="text-align:center;">
					<h3><?=$v['name']?></h3>
					<div><?=$v['description']?></div>
					<div><?=$v['contact']?></div>
				</div>
			<? endforeach; ?>
		</div>
	<? endforeach; ?>
</div>