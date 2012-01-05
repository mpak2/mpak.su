<? if($_POST['submit']): ?>
	<div>Ваше обьявление добавлено!!! <a href="/<?=$arg['modpath']?>">На главную</a></div>
<? else: ?>
	<h1>Добавить объявления</h1>
	<form method="post" enctype="multipart/form-data">
		<div>Заголовок: <input type="text" name="name"></div>
		<div>Цена:<input type="text" name="price"></div>
		<div>Контакт: <input type="text" name="contact"></div>
		<div>
			<select>
				<? foreach($conf['tpl']['relations'] as $k=>$v): ?>
					<option value="<?=$v['id']?>"><?=$v['name']?></option>
				<? endforeach; ?>
			</select>
		</div>
		<div>Регион:
			<select name="region_id">
			<? foreach($conf['tpl']['region'] as $n=>$z): ?>
				<option value="<?=$n?>"><?=$z?></option>
			<? endforeach; ?>
			</select>
		</div>
		<div>Описание: <textarea name="description"></textarea></div>
		<div><input type="file" name="img"></div>
		<div><input type="submit" name="submit"></div>
	</form>
<? endif; ?>