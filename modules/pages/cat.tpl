<div>
	<? if($cat = rb("pages-cat", "id", get($_GET, 'id'))): ?>
		<h1><?=$cat['name']?></h1>
		<? if(get($cat, 'cat_id')): ?>
			<div class="bradcrumbs">
				<? $function = function($cat) use(&$function, $tpl, $arg){ ?>
					<? if($c = $tpl['cat'][ $cat['cat_id'] ]): ?>
						<? $function($c) ?>
					<? endif; ?> &raquo; <a href="/<?=$arg['modpath']?>:cat/<?=$cat['id']?>"><?=$cat['name']?></a>
				<? }; $function($tpl['cat'][ $cat['cat_id'] ]); ?>
			</div>
		<? endif; ?>

		<? if(!$PAGES_INDEX = rb("pages-index", "cat_id", "id", $cat['id'])): mpre("Статей в категории не найдено") ?>
		<? else: ?>
			<ul>
				<? foreach($PAGES_INDEX as $pages_index): ?>
					<li><a href="/pages:index/<?=$pages_index['id']?>"><?=$pages_index['name']?></a></li>
				<? endforeach; ?>
			</ul><br /><br />
		<? endif; ?>
	<? endif; ?>
</div>
