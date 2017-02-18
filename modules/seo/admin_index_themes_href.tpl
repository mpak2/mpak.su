<div class="admin_index_themes_href">

	<? /*if(get($_POST, 'submit') != "post"):// mpre("Данные для сохранения не заданы", $_POST); ?>
	<? elseif(!get($_POST, 'index') || !get($_POST, 'location')): mpre("Внутренний и внешний адрес не заданы"); ?>
	<? elseif(!$index = fk("index", $w = ['name'=>$_POST['index']], $w, $w)): mpre("Ошибка обновления внешнего адреса"); ?>
	<? elseif(!$location = fk("location", $w = ['name'=>$_POST['location']], $w, $w)): mpre("Ошибка обновления внутреннего адреса", $_POST); ?>
	<? elseif(!$themes_index = rb("themes-index", "id", get($_POST, 'themes_index'))): mpre("Не установлен хост адреса"); ?>
	<? elseif(!$index_themes = fk("index_themes", $w = ['themes_index'=>$themes_index['id'], 'index_id'=>$index['id'], 'location_id'=>$location['id']], $w += ['uid'=>$conf['user']['uid'], 'title'=>$_POST['title'], 'description'=>$_POST['description'], 'keywords'=>$_POST['keywords']], $w)): mpre("Ошибка установки мета информации"); ?>
	<? else: mpre("Сохранение", $index_themes) ?>
	<? endif;*/ ?>

	<? if($SEO_INDEX_THEMES = []): mpre("Создание пустого массива") ?>
	<? elseif(!$_POST):// mpre("Данные формы не найдены"); ?>
	<? elseif((!$themes_index = rb("themes-index", "id", get($_POST, "themes_index"))) &0): mpre("Хост не найден") ?>
	<? elseif((!$seo_index = rb("index", "name", (get($_POST, 'index') ? "[{$_POST['index']}]" : false))) &0): mpre("Внешний адрес не задан"); ?>
	<? elseif((!$seo_location = rb("location", "name", (get($_POST, 'location') ? "[{$_POST['location']}]" : false))) &0): mpre("Внутренний адрес не задан"); ?>
	<? elseif(!get($_POST, 'index') && !get($_POST, 'location')): mpre("Андеса страниц не заданы") ?>
	<? elseif(!$seo_index && !$seo_location): $SEO_INDEX_THEMES = []; ?>
		<? if(!get($_POST, 'submit') == 'post'): ?>
		<? elseif(!get($_POST, 'index') || !get($_POST, 'location')): mpre("Не заданы адреса для создания страницы"); ?>
		<? endif; ?>
	<? elseif(get($_POST, 'submit') == 'post'): mpre("Обновление мета информации") ?>
		<? if(!$themes_index): mpre("Не задан хост для создания страницы") ?>
		<? elseif(!$index = fk("seo-index", $w = ['name'=>get($_POST, 'index')], $w)): mpre("Ошибка создания внешнего адреса", $w) ?>
		<? elseif(!$location = fk("seo-location", $w = ['name'=>get($_POST, 'location')], $w)): mpre("Ошибка создания внутреннего адреса", $w) ?>
		<? elseif(!$index_themes = fk("seo-index_themes", $w = ['themes_index'=>$themes_index['id'], 'index_id'=>$index['id']], $w += ['location_id'=>$location['id'], 'title'=>get($_POST, 'title'), 'description'=>get($_POST, 'description'), 'keywords'=>get($_POST, 'keywords')], $w)): mpre("Ошибка создания адреса", $w) ?>
		<? elseif(get($_POST, 'location_themes') && (!$location_themes = fk("seo-location_themes", $w = ['index_id'=>$index['id'], 'location_id'=>$location['id']], $w += ['themes_index'=>$themes_index['id']], $w))): mpre("Ошибка создания перенаправления") ?>
		<? else:// mpre("Обновление мета информации страницы") ?>
			<? $SEO_INDEX_THEMES = [$index_themes['id']=>$index_themes] ?>
		<? endif; ?>
	<? elseif(!$SEO_INDEX_THEMES = rb("seo-index_themes", "themes_index", "index_id", "location_id", "id", (get($themes_index, 'id') ?: true), (get($seo_index, 'id') ?: true), (get($seo_location, 'id') ?: true))): mpre("По заданным параметрам ничего не найдено") ?>
	<? else:// mpre($SEO_INDEX_THEMES, $seo_index, $seo_location) ?>
	<? endif; ?>
	<style>
		.admin_index_themes_href select {width:30%;}
		.admin_index_themes_href input[type="text"] {width:30%;}
		.admin_index_themes_href .meta input[type="text"] {width:100%;}
		.admin_index_themes_href fieldset > pre {/*padding:-10px;*/}
		.admin_index_themes_href .on {color:green;}
		.admin_index_themes_href .off {color:red;}
	</style>
	<h1>Ссылки</h1>
	<form method="post">
		<input type="hidden" name="id" value="<?=(empty($index_themes) ? "" : get($index_themes, 'id'))?>">
		<p>
			<select name="themes_index">
				<option></option>
				<? foreach(rb("themes-index") as $themes_index): ?>
					<option value="<?=$themes_index['id']?>" <?=(!empty($themes_index) && get($_POST, "themes_index") == get($themes_index, 'id') ? "selected" : "")?>><?=$themes_index['name']?></option>
				<? endforeach; ?>
			</select>
			<input type="text" name="index" value="<?=get($_POST, 'index')?>" placeholder="Внешний адрес">
			<input type="text" name="location" value="<?=get($_POST, 'location')?>" placeholder="Внутренний адрес">
			<label><input name="location_themes" type="checkbox" <?=(get($_POST, 'location_themes') ? "checked" : "")?>> Перенаправление</label>
		</p>
		<div class="meta">
			<p><input type="text" name="title" value="<?=(1 == count($SEO_INDEX_THEMES) ? get(first($SEO_INDEX_THEMES), 'title') : get($_GET, 'title'))?>" placeholder="Заголовок"></p>
			<p><input type="text" name="description" value="<?=(1 == count($SEO_INDEX_THEMES) ? get(first($SEO_INDEX_THEMES), 'description') : get($_GET, 'description'))?>" placeholder="Описание"></p>
			<p><input type="text" name="keywords" value="<?=(1 == count($SEO_INDEX_THEMES) ? get(first($SEO_INDEX_THEMES), 'keywords') : get($_GET, 'keywords'))?>" placeholder="Ключевые слова"></p>
		</div>
		<p>
			<button type="submit">Найти</button>
			<button type="submit" name="submit" value="post" <?=((!get($_POST, 'index') && !get($_POST, 'location')) ? "disabled" : "")?>>Обновить</button>
		</p>
	</form>
	<ul>
		<? foreach($SEO_INDEX_THEMES as $seo_index_themes): ?>
			<li>
				<? if(!$themes_index = rb("themes-index", "id", $seo_index_themes['themes_index'])): mpre("Ошибка выборки хоста адреса") ?>
				<? elseif(!$index = rb("seo-index", "id", $seo_index_themes['index_id'])): mpre("Ошибка выборки внешнего адреса") ?>
				<? elseif(!$location = rb("seo-location", "id", $seo_index_themes['location_id'])): mpre("Ошибка выборки внутреннего адреса") ?>
				<? elseif((!$location_themes = rb("seo-location_themes", "index_id", "location_id", $index['id'], $location['id'])) &0): mpre("Перенаправление не задано") ?>
				<? else: mpre("<a target='blank' href='//{$themes_index['name']}'>{$themes_index['name']}</a> Внешний: <a target='blank' href='//{$themes_index['name']}{$index['name']}'>{$index['name']}</a> Внутненний: <a target='blank' href='//{$themes_index['name']}{$location['name']}'>{$location['name']}</a> Перенаправление: <b>". ($location_themes ? "<span class='on'>Вкл</span>" : "<span class='off'>Выкл</span>"). "</b>", array_intersect_key($seo_index_themes, array_flip(['title', 'description', 'keywords']))) ?>
				<? endif; ?>
			</li>
		<? endforeach; ?>
	</ul>
</div>