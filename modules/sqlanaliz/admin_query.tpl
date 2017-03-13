<h1>Массовая вставка в БД из списка</h1>
<? if(!$TABLES = tables()): mpre("Ошибка получения списка таблиц") ?>
<? elseif(!$_TABLES = array_keys($TABLES)): mpre("Ошибка получения списка имен таблиц") ?>
<? elseif((!$table_name = get($_POST, 'tables')) &0): mpre("Имя выбранной таблицы") ?>
<? elseif(!empty($table_name) && (!$FIELDS = fields($table_name))): mpre("Ошибка получения полей таблицы") ?>
<? elseif((!$field_name = get($_POST, 'fields')) &0): mpre("Поле для вставки не установлено") ?>
<? else:// mpre($_POST) ?>
	<form method="post">
		<p>
			<select name="tables">
				<option value="">Выберете таблицу</option>
				<? foreach($_TABLES as $tables): ?>
					<option <?=($table_name == $tables ? "selected" : "")?>><?=$tables?></option>
				<? endforeach; ?>
			</select>
			<select name="fields">
				<option value="">Выберете поле</option>
				<? if(!$_FIELDS = array_keys($FIELDS)): mpre("Ошибка получеия имен полей таблицы") ?>
				<? else: ?>
					<? foreach($_FIELDS as $fields): ?>
						<option <?=($field_name == $fields ? "selected" : "")?>><?=$fields?></option>
					<? endforeach; ?>
				<? endif; ?>
			</select>
			<button type="submit">Вставить</button>
		</p>
		<p><textarea name="list" style="height:300px; width:80%;" <?=(empty($table_name) ? "disabled" : "")?>><?=get($_POST, 'list')?></textarea></p>
	</form>

	<? if(!$table_name): mpre("Таблица не задана") ?>
	<? elseif(!$field_name): mpre("Поле таблицы не задано") ?>
	<? elseif(!$LIST = get($_POST, 'list')): mpre("Список не задан") ?>
	<? elseif(!$_LIST = explode("\n", $LIST)): mpre("Ошибка получения списка элементов") ?>
	<? else:// mpre($_LIST) ?>
		<? foreach($_LIST as $list): ?>
			<? if(!$index = fk($table_name, null, $w = [$field_name=>trim($list)], $w)): mpre("Ошибка установки значения поля") ?>
			<? else: mpre("{$table_name}.{$index['id']} {$field_name}=>{$list}"); endif; ?>
		<? endforeach; ?>
	<? endif; ?>
<? endif; ?>