<div class="MainMenu">
	<ul>
		<? foreach($menu[0] as $v): ?>
			<li><a href="<?=$v['link']?>"><?=$v['name']?></a>
			<? if($menu[ $v['id'] ]): ?>
				<ul>
					<? foreach($menu[ $v['id'] ] as $m): ?>
						<li><a href="<?=$m['link']?>"><?=$m['name']?></a></li>
					<? endforeach; ?>
				</ul>
			<? endif; ?>
			</li>
		<? endforeach; ?>
	</ul>
</div>
