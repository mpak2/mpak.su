<? foreach(rb("index_themes", "themes_index", "index_id", "id") as $__THEMES_INDEX): ?>
	<? foreach($__THEMES_INDEX as $_THEMES_INDEX): ?>
		<? if((count($_THEMES_INDEX) > 1) && ($themes_index = first($_THEMES_INDEX))): ?>
			<li>
				<a href="/seo:admin/r:mp_seo_index_themes?&where[index_id]=<?=$themes_index['index_id']?>&where[themes_index]=<?=$themes_index['themes_index']?>">
					<?=$themes_index['title']?>
				</a> [<?=count($_THEMES_INDEX)?>]
			</li>
		<? endif; ?>
	<? endforeach; ?>
<? endforeach; ?>
