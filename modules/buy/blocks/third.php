<? die; # Нуль

if ((int)$arg['confnum']){
/*	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

echo <<<EOF
	<form method="post">
		<input type="text" name="param" value="$param"> <input type="submit" value="Сохранить">
	</form>
EOF;*/

	return;
}//$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));
//$uid = $_GET['id'] && array_key_exists('users', $_GET['m']) ? $_GET['id'] : $conf['user']['id'];
//if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){};

// $dat = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} LIMIT 10")); //$dat



?>
		<div class="disk"><img src="img/akk.png" /></div>
		<div class="content">
				<form class="filter-shina">
					<div><label>Производитель</label><select><option>Выбрать</option></select></div>
					<div><label>Полярность</label><select><option>Выбрать</option></select></div>
					<div><label>Емкость (А/ч)</label><select><option>Выбрать</option></select></div>
					<div><label>Габариты (ДхШхВ)</label><select><option>Выбрать</option></select></div>
					<div><label>Пусковой ток EN (А)</label><select><option>Выбрать</option></select></div>
					<input type="button" value="Найти" onclick="alert('Раздел в разработке')" />
				</form>
		</div>
