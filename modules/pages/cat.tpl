<div>
	<? if(!$cat = rb("pages-cat", "id", get($_GET, 'id'))): mpre("Категория не установлена") ?>
	<? elseif(!$PAGES_INDEX = rb("pages-index", "cat_id", "id", $cat['id'])): mpre("Статей в категории не найдено") ?>
	<? else: ?>
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

		<ul>
			<? foreach($PAGES_INDEX as $pages_index): ?>
				<? if(!$href = "/pages:index/{$pages_index['id']}"): mpre("ОШИБКА формирования адреса на категорию") ?>
				<? elseif(!$seo_cat = seo($href)): mpre("ОШИБКА получения СЕО адреса страницы категории") ?>
				<? else: ?>
					<li><a href="<?=$seo_cat?>"><?=$pages_index['name']?></a></li>
				<? endif; ?>
			<? endforeach; ?>
		</ul><br /><br />
	<? endif; ?>
</div>
