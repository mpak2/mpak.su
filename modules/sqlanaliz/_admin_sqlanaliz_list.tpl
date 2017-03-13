<h2>Вставка списков</h2>
<form method="post">
	<p>
		<textarea name="list" style="width:300px; height:300px;"><?=get($_POST, 'list')?></textarea>
	</p>
	<p>
		<select name="table">
			<option></option>
			<? foreach(tables() as $table): ?>
				<option <?=(get($_POST, 'table') == $table['name'] ? "selected" : "")?>><?=$table['name']?></option>
			<? endforeach; ?>
		</select>
		<button>Загрузить</button>
	</p>
</form>
<? if(!$_POST): mpre("Задайте значения формы"); ?>
<? elseif(!array_filter($LIST = explode("\n", $_POST['list']))): mpre("Не задан список значений"); ?>
<? elseif(!$table = get($_POST, 'table')): mpre("Не задана таблица"); ?>
<? else:// mpre($table, $LIST) ?>
	<? foreach($LIST as $l): ?>
		<? mpre($list = fk($table, $w = ["name"=>$l], $w, $w)) ?>
	<? endforeach; ?>
<? endif; ?>
