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

//$dat = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} LIMIT 10"));

$w = mpql(mpqw("SELECT w AS name FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE w>0 GROUP BY w ORDER BY w"));
$ot = mpql(mpqw("SELECT ot AS name FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE ot>0 GROUP BY ot ORDER BY ot"));
$diameter = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_diameter ORDER BY name"));
$vendor = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_vendor ORDER BY name"));

?>
		<div class="shina"><img src="img/shina.png" /></div>
		<div class="content">
			<form class="filter-shina" action="/<?=$arg['modname']?>" method="post">
				<div><label>Производитель</label>
					<select id="vendor_id" name="vendor_id">
						<option value="">Выбрать</option>
						<? foreach($vendor as $k=>$v): ?>
							<option value="<?=$v['id']?>" <?=($v['id'] == $_POST['vendor_id'] ? "selected" : "")?>><?=$v['name']?></option>
						<? endforeach; ?>
					</select>
				</div>
				<div><label>Ширина</label>
					<select id="w" name="w">
						<option value="">Выбрать</option>
						<? foreach($w as $k=>$v): ?>
							<option <?=($v['name'] == $_POST['w'] ? "selected" : "")?>><?=$v['name']?></option>
						<? endforeach; ?>
					</select>
				</div>
				<div><label>Высота</label>
					<select id="ot" name="ot">
						<option value="">Выбрать</option>
						<? foreach($ot as $k=>$v): ?>
							<option <?=($v['name'] == $_POST['ot'] ? "selected" : "")?>><?=$v['name']?></option>
						<? endforeach; ?>
					</select>
				</div>
				<div><label>Радиус</label>
					<select id="d" name="diameter_id">
						<option value="">Выбрать</option>
						<? foreach($diameter as $k=>$v): ?>
							<option value="<?=$v['id']?>" <?=($v['id'] == $_POST['diameter_id'] ? "selected" : "")?>><?=$v['name']?></option>
						<? endforeach; ?>
					</select>
				</div>
				<div><label>Сезон</label>
					<select id="d" name="season">
						<option value="">Выбрать</option>
						<option value="0">зима</option>
						<option value="1" <?=($_POST['sezon'] == 1 ? "selected" : "")?>>лето</option>
					</select>
				</div>
				<input type="button" value="Найти" onclick="submit()" />
			</form>
		</div>
