<h1>Восстановление заговлоков страниц</h1>
<form method="post">
	<? if(get($conf, 'settings', 'themes_index')): ?>
		<p>
			<select name="themes_index">
				<option value=""></option>
				<? foreach(rb("themes-index") as $themes_index): ?>
					<option value="<?=$themes_index['id']?>" <?=((get($_POST, 'themes_index')) == $themes_index['id'] ? "selected" : "")?>><?=$themes_index['name']?></option>
				<? endforeach; ?>
			</select>
		</p>
	<? endif; ?>
	<p>
		<input type="text" name="from" value="<?=get($_POST, "from")?>" placeholder="С каких адресов">
		<input type="text" name="to" value="<?=get($_POST, "to")?>" placeholder="На какие адреса">
		<p>
			<label><input type="checkbox" name="f[title]" <?=(get($_POST, "f", "title") ? "checked" : "")?>> Заголовки</label>
			<label><input type="checkbox" name="f[description]" <?=(get($_POST, "f", "description") ? "checked" : "")?>> Описание</label>
			<label><input type="checkbox" name="f[keywords]" <?=(get($_POST, "f", "keywords") ? "checked" : "")?>> Ключевики</label>
		</p>
		<p>
			<label>
				<input type="checkbox" name="location_themes" <?=(get($_POST, "location_themes") ? "checked" : "")?>>
				Включить перенаправление
			</label>
		</p>
		<button method="post">Восстановить</button>
	</p>
</form>
<? if($_POST): ?>
	<? if(!($themes_index = rb("themes-index", "id", get($_POST, 'themes_index'))) && false): mpre("Хост не найден"); ?>
	<? elseif(!$seo_index_from = rb("seo-index", "name", $w = "[". get($_POST, 'from'). "]")): mpre("Внешний адрес откуда не найден {$w}"); ?>
	<? elseif(!$SEO_INDEX_THEMES_FROM = rb("seo-index_themes", "themes_index", "index_id", "id", (get($_POST, 'themes_index') ?: true), $seo_index_from['id'])): mpre("Адреса источника на сайте не найдены"); ?>
	<? elseif(!$seo_index_to = rb("seo-index", "name", $w = "[". get($_POST, 'to'). "]")): mpre("Внешний адрес куда не найден {$w}"); ?>
	<? elseif(!$SEO_INDEX_THEMES_TO = rb("seo-index_themes", "themes_index", "index_id", "id", (get($_POST, 'themes_index') ?: true), $seo_index_to['id'])): mpre("Адреса назначения на сайте не найдены"); ?>
	<? else: ?>
		<? foreach($SEO_INDEX_THEMES_FROM as $seo_index_themes_from): ?>
			<? if(!$seo_index_themes_to = rb($SEO_INDEX_THEMES_TO, "themes_index", $seo_index_themes_from['themes_index'])): mpre("Адрес куда переносить не наден"); ?>
			<? //elseif(!$intersect_key = ): mpre("Данные для изменения не найдены"); ?>
			<? else: mpre("Изменения", array_intersect_key($seo_index_themes_from, get($_POST, 'f') ?: [])); mpre("Было", $seo_index_themes_from, $seo_index_themes_to) ?>
				<? if($seo_index_themes_to = fk("seo-index_themes", ['id'=>$seo_index_themes_to['id']], null, array_intersect_key($seo_index_themes_from, get($_POST, 'f') ?: []))): ?>
					<? mpre("Стало", $seo_index_themes_to) ?>
				<? endif; ?>
				<? if(get($_POST, 'location_themes')): # Перенаправление ?>
					<? if(!$seo_location = rb("location", "id", $seo_index_themes_from['location_id'])): mpre("Внутренний адрес старой ссылки не найден"); ?>
					<? elseif(!$seo_location_themes = rb("location_themes", "themes_index", "location_id", $seo_index_themes_from['themes_index'], $seo_location['id'])): mpre("Переход со старого адреса не найден", $seo_location); ?>
					<? elseif(!$seo_location_themes = fk("location_themes", ['id'=>$seo_location_themes['id']], null, ['uid'=>$conf['user']['uid'], 'index_id'=>$seo_index_to['id']])): mpre("Ошибка установки перенаправления на старый сайт"); ?>
					<? elseif(!$seo_index_themes_from = fk("index_themes", ['id'=>$seo_index_themes_from['id']], null, ['seo-index'=>$seo_index_to['id']])): mpre("Ошибка обвновления перехода на внешнюю страницу"); ?>
					<?// elseif(!$seo_index_from = rb("index")) ?>
					<? else: ?>
						<? mpre("Перенаправление с внутренней на внутренюю", $seo_location_themes) ?>
						<? mpre("Перенаправление с внешней на внешнюю", $seo_index_themes_from) ?>
					<? endif; ?>
				<? endif; ?>
			<? endif; ?>
		<? endforeach; ?>
	<? endif; ?>
<? else: mpre("Данные не заданы"); endif; ?>

