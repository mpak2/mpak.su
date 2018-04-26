<h1>Массовая вставка в БД из списка</h1>
<? if(!$TABLES = tables()): mpre("Ошибка получения списка таблиц") ?>
<? elseif(!$_TABLES = array_keys($TABLES)): mpre("Ошибка получения списка имен таблиц") ?>
<? elseif(!is_string($table_name = (get($_GET, 'table')) ?: "")): mpre("Имя выбранной таблицы") ?>
<? elseif(!is_array($FIELDS = ($table_name ? fields($table_name) : []))): mpre("Ошибка получения полей таблицы") ?>
<? elseif(!is_string($field_name = (get($_POST, 'fields') ?: ""))): mpre("Поле для вставки не установлено") ?>
<? else:// mpre($field_name) ?>
	<style>
		.table.fields > div > span { padding:3px; }
	</style>
	<script async>
		(function($, script){
			$(script).parent().on("change", "select[name=tables]", function(e){
				var table = $(e.currentTarget).find("option:selected").val();
				var href = "/sqlanaliz:admin_query"+ (table ? "/table:"+table : "");
				document.location.href = href;
			}).on("change", "select[name=fields]", function(e){
				var field = $(e.currentTarget).find("option:selected").val();
				$(e.delegateTarget).trigger("fields", field);
			}).on("fields", function(e, field){
				console.log("field:", field);
				if(field){
					$(e.delegateTarget).find(".table.fields").hide();
				}else{
					$(e.delegateTarget).find(".table.fields").show();
				}
			}).one("init", function(e){
				var field = $("select[name=fields]").find("option:selected").val();
				$(e.delegateTarget).trigger("fields", field);
			}).ready(function(e){ $(script).parent().trigger("init"); })
		})(jQuery, document.currentScript)
	</script>
	<form method="post">
		<p>
			<select name="tables">
				<option value="">Выберете таблицу</option>
				<? foreach($_TABLES as $tables): ?>
					<option <?=(get($_GET, 'table') == $tables ? "selected" : "")?>><?=$tables?></option>
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
			
			<label>
				<input type="radio" name="act" value="verbose" <?=(("verbose" == get($_POST, 'act')) ? "checked" : "")?>> Подробно
			</label>
			<label>
				<input type="radio" name="act" value="edit" <?=((!array_key_exists("act", $_POST) || ("edit" == get($_POST, 'act'))) ? "checked" : "")?>> Просмотр
			</label>
			<label>
				<input type="radio" name="act" value="save" <?=(("save" == get($_POST, 'act')) ? "checked" : "")?>> Сохранение
			</label>
			<button type="submit">Запустить</button>
		</p>
		<p>Каждая запись - отдельная строка
		<div class="table border fields" style="width:1000px;">
			<div class="th">
				<span>Обязат</span>
				<span>Уникальн</span>
				<span>Теги</span>
				<span>СпецСимволы</span>
				<span>Поле</span>
				<span>Условие</span>
			</div>
			<? foreach($FIELDS as $fields): ?>
				<? if(!$name = get($fields, 'Field')): mpre("ОШИБКА определения имени поля") ?>
				<? elseif(!is_string($checked_required = call_user_func(function($name){
						if($required = get($_POST, "required", $name)){ return "checked"; mpre($_POST);
						}elseif($_POST){// mpre("Список чекбоксов не пуст");
						}elseif("name" == $name){ return "checked"; mpre($_POST);
						}else{// mpre($checked);
						} return "";
					}, $name))): mpre("ОШИБКА определения активности уникального поля") ?>
				<? elseif(!is_string($checked_unique = call_user_func(function($name){
						if($checked = get($_POST, "unique", $name)){ return "checked"; mpre($_POST);
						}elseif($_POST){// mpre("Список чекбоксов не пуст");
						}elseif("name" == $name){ return "checked"; mpre($_POST);
						}else{// mpre($checked);
						} return "";
					}, $name))): mpre("ОШИБКА определения активности уникального поля") ?>
				<? elseif(!is_string($checked_chars = call_user_func(function($name){
						if($checked = get($_POST, "chars", $name)){ return "checked"; mpre($_POST);
						}elseif($_POST){// mpre("Список чекбоксов не пуст");
						}elseif("name" == $name){ return "checked"; mpre($_POST);
						}else{// mpre($checked);
						} return "";
					}, $name))): mpre("ОШИБКА определения активности уникального поля") ?>
				<? elseif(!is_string($checked_tags = call_user_func(function($name){
						if($checked = get($_POST, "tags", $name)){ return "checked"; mpre($_POST);
						}elseif($_POST){// mpre("Список чекбоксов не пуст");
						}elseif("name" == $name){ return "checked"; mpre($_POST);
						}else{// mpre($checked);
						} return "";
					}, $name))): mpre("ОШИБКА определения активности уникального поля") ?>
				<? elseif(!$placeholder = ("name" == $name ? "<strong>(.*?)</strong>" : "Регулярное выражение")): mpre("ОШИБКА установки заголовка поля") ?>
				<? elseif(!is_string($value = call_user_func(function($name) use($placeholder){
						if($value = get($_POST, "preg", $name)){ return $value;
						}elseif("name" == $name){ return $placeholder;
						}else{
						} return "";
					}, $name))): mpre("ОШИБКА установки значений из запроса") ?>
				<? else: ?>
					<div>
						<span><input type="checkbox" name="required[<?=$name?>]" <?=$checked_required?>></span>
						<span><input type="checkbox" name="unique[<?=$name?>]" <?=$checked_unique?>></span>
						<span><input type="checkbox" name="tags[<?=$name?>]" <?=$checked_tags?>></span>
						<span><input type="checkbox" name="chars[<?=$name?>]" <?=$checked_chars?>></span>
						<span style="text-align:right;"><?=$name?></span>
						<span><input type="text" name="preg[<?=$name?>]" value="<?=$value?>" placeholder="<?=$placeholder?>" style="width:100%"></span>
					</div>
				<? endif; ?>
			<? endforeach; ?>
		</div>
		<p><textarea name="list" style="height:300px; width:80%;" <?=(empty($table_name) ? "disabled" : "")?>><?=get($_POST, 'list')?></textarea></p>
	</form>

	<? if(!$table_name): mpre("Таблица не задана") ?>
	<?// elseif(!is_string($field_name): mpre("Поле таблицы не задано") ?>
	<? elseif(!$LIST = get($_POST, 'list')): mpre("Список не задан") ?>
	<? elseif(!$_LIST = explode("\n", $LIST)): mpre("Ошибка получения списка элементов") ?>
	<? else:// mpre($_LIST) ?>
		<? foreach($_LIST as $list): mpre("Исходная строка", htmlspecialchars($list)); ?>
			<? if(!$_list = trim($list)): mpre("Строка после удаления закрывающих пробелов оказалась пуста") ?>
			<? elseif(!is_array($field_list = ($_list ? [$field_name=>$_list] : []))): mpre("ОШИБКА формирования одиночного массиива") ?>
			<? elseif(!is_array($values = array_filter($field_name ? $field_list : array_map(function($field) use($list){
					if(!$field_name = get($field, 'Field')){ mpre("ОШИБКА получения имени поля");
					}elseif(!$preg = get($_POST, "preg", $field_name)){// mpre("ОШИБКА регулярное выражение для поля `{$field_name}` не задано");
					}elseif(!preg_match("#{$preg}#", $list, $match)){// mpre("Cовпадения не установлены в поле `{$field_name}`");
					}elseif(!$value = last($match)){ mpre("Последнее совпавшее значение не найдено");
					}elseif(!$value = (get($_POST, "tags", $field_name) ? strip_tags($value) : $value)){ mpre("ОШИБКА удаления спецсимволов");
					}elseif(!$value = (get($_POST, "chars", $field_name) ? htmlspecialchars($value) : $value)){ mpre("ОШИБКА удаления спецсимволов");
					}elseif(!$value = trim($value)){ mpre("ОШИБКА удслаения завершающих пробелов");
					}elseif(!$act = get($_POST, 'act')){ mpre("Действие не задано", $where, $values);
					}elseif("verbose" != $act){ return $value;// mpre("Убираем подробности при сохранении");
					}else{ mpre("Поле `{$field_name}`", "Регулярное вывражение #". htmlspecialchars($preg). "#", $match);
						return $value;
					}
				}, $FIELDS)))): mpre("Значения сохранения не заданы", htmlspecialchars($list)) ?>
			<? elseif(($required = get($_POST, 'required')) && ($required != array_intersect_key($required, $values))): mpre("Не заданы обязательные поля", array_keys($required)) ?>
			<? elseif(($unique = (get($_POST, 'unique')) ?: []) && ($unique != array_intersect_key($unique, $values))): mpre("Не заданы уникальные поля", array_keys($unique)) ?>
			<? elseif(!is_array($where = array_intersect_key($values, $unique))): mpre("ОШИБКА расчета условных полей") ?>
			<? elseif(!$act = get($_POST, 'act')): mpre("Действие не задано", $where, $values) ?>
			<? elseif("save" != $act): mpre("<b>Просмотр</b>", "Условия обновления (уникальные поля)", $where, "Найденные значения (регулярные выражения)", $values) ?>
			<? elseif(!$index = fk($table_name, $where, $values, $values)): mpre("ОШИБКА изменения базы данных") ?>
			<? else: mpre("<b>Сохранение</b> `{$table_name}`", $index) ?>
			<? endif; ?>
			<? /*if(!$index = fk($table_name, null, $w = [$field_name=>trim($list)], $w)): mpre("Ошибка установки значения поля") ?>
			<? else: mpre("{$table_name}", $w); endif;*/ ?>
		<? endforeach; ?>
	<? endif; ?>
<? endif; ?>
