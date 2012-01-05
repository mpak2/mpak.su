<? die; # Товар

if ((int)$arg['confnum']){
	# Востановление параметров модуля
	if (count($res = mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0)))
	if(empty($_POST)){
		$param = unserialize($res['param']);
	}else{
		$param = array($_POST['param0'], $_POST['param1'], $_POST['param2']);
	}
	if(!$param) $param = 1;
	$cat = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_obj WHERE id=".(int)$param['0']), 0, 'name');
	echo <<<EOF
	<form method="post">
		Отображать категорию <b>{$cat}</b><br />начиная с: <b>{$param[1]}</b> в количестве: <b>{$param[2]}</b>;
		<p /><select name="param0"><option value="0"></option>
EOF;
foreach(mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_obj ORDER BY name")) as $k=>$v){
	echo "<option value='{$v['id']}'".($param['0'] == $v['id'] ? ' selected' : '').">{$v['name']}</option>";
}
echo <<<EOF
		</select>
		<p />Начиная с <input type="text" name="param1" value="{$param[1]}"><br /> Количество: <input type="text" name="param2" value="{$param[2]}"><p /> <input type="submit" name="submit" value="Сохранить">
	</form>
EOF;
	# Сохранение параметров модуля
	if (count($param)) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	return;
}

if (count($res = mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"))))
	$param = unserialize($res[0]['param']);

$desc = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_desc WHERE disable=0 ORDER BY id".($param[0] ? " WHERE cid=".(int)$param[0] : '')." DESC LIMIT ".(int)$param[1]. ",".(int)$param[2]));
foreach($desc as $k=>$v){
echo <<<EOF
	<div>
		<a href="/{$arg['modpath']}/{$v['id']}">
			<img src="/{$arg['modpath']}:img/{$v['id']}/tn:1/w:150/h:150/null/img.jpg">
		</a>
	</div>
	<div style="float:right; margin-left:10px; margin-top:3px;">{$v['price']} <!-- [settings:onpay_currency] --></div>
	<div style="margin:5px;"><a href="/{$arg['modpath']}/{$v['id']}">{$v['name']}</a></div>
EOF;
}

?>