<? if($INDEX = rb("index")): ?>
	<? foreach(rb($INDEX, 'theme', 'id') as $theme=>$INDEX_THEME): ?>
		<div>
			<h2><?=$theme?> (<?=count($INDEX_THEME)?>)</h2>
			<ul>
				<p>
					<? foreach($INDEX_THEME as $index): ?>
						<li style="display:inline-block;">
							<?=aedit("/themes:admin/r:mp_themes_index?&where[id]={$index['id']}")?>
							<a href="//<?=$index['name']?>" style="margin-right:20px;"><?=$index['name']?></a>
						</li>
					<? endforeach; ?>
				</p>
			</ul>
		</div>
	<? endforeach; ?>
<? else: mpre("Сайнты не найдены"); endif; ?>
