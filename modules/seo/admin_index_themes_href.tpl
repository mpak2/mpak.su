<div class="admin_index_themes_href">
	<? if(get($_POST, 'submit') != "post"):// mpre("Данные для сохранения не заданы", $_POST); ?>
	<? elseif(!get($_POST, 'index') || !get($_POST, 'location')): mpre("Внутренний и внешний адрес не заданы"); ?>
	<? elseif(!$index = fk("index", $w = ['name'=>$_POST['index']], $w, $w)): mpre("Ошибка обновления внешнего адреса"); ?>
	<? elseif(!$location = fk("location", $w = ['name'=>$_POST['location']], $w, $w)): mpre("Ошибка обновления внутреннего адреса", $_POST); ?>
	<? elseif(!$themes_index = rb("themes-index", "id", get($_POST, 'themes_index'))): mpre("Не установлен хост адреса"); ?>
	<? elseif(!$index_themes = fk("index_themes", $w = ['themes_index'=>$themes_index['id'], 'index_id'=>$index['id'], 'location_id'=>$location['id']], $w += ['uid'=>$conf['user']['uid'], 'title'=>$_POST['title'], 'description'=>$_POST['description'], 'keywords'=>$_POST['keywords']], $w)): mpre("Ошибка установки мета информации"); ?>
	<? else: mpre("Сохранение", $index_themes) ?>
	<? endif; ?>

	<? if((!$index = $location = $index_themes = []) && !$_POST):// mpre("Данные формы не найдены"); ?>
	<? elseif(!$index = rb("index", "name", (get($_POST, 'index') ? "[{$_POST['index']}]" : false))): mpre("Внешний адрес не задан"); ?>
	<? elseif(!$location = rb("location", "name", (get($_POST, 'index') ? "[{$_POST['index']}]" : false))):// mpre("Внутренний адрес не задан"); ?>
		<? if(!$index_themes = rb("index_themes", "themes_index", "index_id", (get($_POST, 'themes_index') ?: true), $index['id'])):  ?>
		<? elseif(!$location = rb("location", "id", $index_themes['location_id'])): mpre("Внутренний адрес не найден"); ?>
			<? mpre("Внешний адрес", $location) ?>
		<? endif; ?>
	<? endif; ?>
	<style>
		.admin_index_themes_href select {width:30%;}
		.admin_index_themes_href input[type="text"] {width:30%;}
		.admin_index_themes_href .meta input[type="text"] {width:100%;}
	</style>
	<h1>Ссылки</h1>
	<form method="post">
		<input type="hidden" name="id" value="<?=get($index_themes, 'id')?>">
		<p>
			<select name="themes_index">
				<? foreach(rb("themes-index") as $themes_index): ?>
					<option value="<?=$themes_index['id']?>" <?=(get($_POST, "themes_index") == $themes_index['id'] ? "selected" : "")?>><?=$themes_index['name']?></option>
				<? endforeach; ?>
			</select>
			<input type="text" name="index" value="<?=get($index, 'name')?>" placeholder="Внешний адрес">
			<input type="text" name="location" value="<?=get($location, 'name')?>" placeholder="Внутренний адрес">
		</p>
		<div class="meta">
			<p><input type="text" name="title" value="<?=get($index_themes, 'title')?>" placeholder="Заголовок"></p>
			<p><input type="text" name="description" value="<?=get($index_themes, 'description')?>" placeholder="Описание"></p>
			<p><input type="text" name="keywords" value="<?=get($index_themes, 'keywords')?>" placeholder="Ключевые слова"></p>
		</div>
		<p>
			<button type="submit">Найти</button>
			<button type="submit" name="submit" value="post" <?=(get($index_themes, 'id') ? "" : "disabled")?>>Обновить</button>
		</p>
	</form>
</div>