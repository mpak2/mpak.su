<?// if(!$themes_index = rb("index", "id", 9962)): mpre("Тестовый элемент не найден") ?>

<? if(!$INDEX_THEME = qn("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index_theme ORDER BY id")): mpre("Ошибка получения списка тем") ?>
<? elseif(!$_INDEX_THEME = array_values(rb($INDEX_THEME, "hide", "id", 0))): mpre("Ошибка получения нумерованного списка тем") ?>
<? elseif(!$cnt = count($_INDEX_THEME)): mpre("Ошибка вычисления количества тем") ?>
<? elseif(!empty($themes_index)): mpre("Устанавливаем шаблон для нового сайта"); ?>
	<? if(!$crc32 = crc32($themes_index['name'])): pre("Ошибка формирования хеша") ?>
	<? elseif(!$VALUES = array_column($_INDEX_THEME, "id")): pre("Ошибка формирования массива ключей"); ?>
	<? elseif(($n = $crc32%$cnt) &0): pre("Ошибка вычисления порядкового номера шаблона") ?>
	<? elseif(!$index_theme_id = get($VALUES, $n)): pre("Не найден шаблон по порядковому номеру") ?>
	<? elseif(!$themes_index = fk("index", ['id'=>$themes_index['id']], null, ['index_theme_id'=>$index_theme_id])): pre("Ошибка обновления темы нового сайта"); ?>
	<? else: mpre($themes_index) ?>
	<? endif; ?>
<? elseif(!$INDEX = rb("index")): mpre("Ошибка выборки хостов") ?>
<? elseif(!$_INDEX = rb($INDEX, 'index_theme_id', "id")): mpre("Ошибка получения нумерованного списка тем") ?>
<? else:// mpre($_INDEX) ?>
	<h1>Установка тем сайтов</h1>
	<form method="post">
		<select name="index_theme_id">
			<option value="0"></option>
			<? foreach($INDEX_THEME as $index_theme): ?>
				<? if(($nn = array_search($index_theme['id'], array_column($_INDEX_THEME, "id"))) === false): mpre("Ошибка определения номера элемента") ?>
				<? endif; ?>
					<option value="<?=$index_theme['id']?>" <?=($index_theme['id'] == get($_POST, 'index_theme_id') ? "selected" : "")?> <?=(get($index_theme, 'hide') ? "disabled" : "")?>>
						<?=$nn?>. <?=$index_theme['name']?> <?=$index_theme['path']?> (<?=count(get($_INDEX, $index_theme['id']) ?: [])?>)
					</option>
			<? endforeach; ?>
		</select>
		<button>Пересчитать</button>
	</form>
	<? if(!$index_theme_id = get($_POST, 'index_theme_id')): mpre("Тема не задана") ?>
	<? elseif(!$index_theme = rb($INDEX_THEME, "id", $index_theme_id)): mpre("Ошибка получения выбранного элемента") ?>
	<? elseif(($nn = array_search($index_theme_id, array_column($_INDEX_THEME, "id"))) === false): mpre("Ошибка определения номера элемента") ?>
	<? else: mpre(array_intersect_key($index_theme, array_flip(['id', 'name', 'path']))); mpre("Значение элемента: {$nn}", "Количество активных тем: {$cnt}"); ?>
		<? foreach($INDEX as $index): ?>
			<? if(strlen($index['theme'])): mpre("У темы установлена тема вручную") ?>
			<? elseif(!$crc32 = crc32($index['name'])):// mpre("Ошибка формирования хеша") ?>
			<? elseif($nn != ($n = $crc32%$cnt)):// mpre("{$index['id']} Тему не устанавливаем {$n}") ?>
			<? elseif($index['index_theme_id'] == $index_theme['id']):// mpre("{$index['id']} Тема равна расчетной {$n}") ?>
			<? elseif(!$index = fk("index", array("id"=>$index['id']), null, array('index_theme_id'=>$index_theme['id']))): mpre("Ошибка сохранения темы в хост") ?>
			<? else: mpre(array_intersect_key($index, array_flip(['id', 'name', 'index_theme_id']))) ?>
			<? endif; ?>
		<? endforeach; ?>
	<? endif; ?>
<? endif; ?>