<? die;

if ($_POST){
//	echo "<pre>"; print_r($_POST); echo "</pre>";
	if (mpql(mpqw("SELECT aid FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_kat WHERE id=".(int)$_POST['kid']), 0, 'aid') > 1){

		$sql = "INSERT INTO {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']} (uid, kid, gid, description) VALUE ({$GLOBALS['conf']['user']['uid']}, ".(int)$_POST['kid'].", ".(int)$_POST['gid'].", '".htmlspecialchars($_POST['text'])."')";
		mpqw($sql); $mysql_insert_id = mysql_insert_id();

		foreach(mpql(mpqw("SELECT p.* FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_pole as p, {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_kpole as kp, {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_kat as k WHERE p.id=kp.pid AND k.id=kp.kid AND k.id=".(int)$_GET['kid'])) as $k=>$v){
			if ($v['type'] == 'checkbox'){
				foreach(mpql(mpqw("SELECT * FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_vpole WHERE pid={$v['id']}")) as $n=>$z){
					if ($_POST["{$v['id']}_{$z['id']}"]){
						$sql = "INSERT INTO {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_dop (oid, pid, vid) VALUES ($mysql_insert_id, {$v['id']}, {$z['id']})";
						mpqw($sql);
					}
				}
			}elseif ($v['type'] == 'img'){ # Загрузка изображений
				foreach(mpql(mpqw("SELECT * FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_vpole WHERE pid={$v['id']}")) as $n=>$z){
					if ($_FILES["{$v['id']}_{$z['id']}"]['error']) continue;
					$ext = array('application/x-httpd-php'=>'php', 'image/png'=>'png', 'image/jpeg'=>'jpg', 'image/jpg'=>'jpg', 'image/gif'=>'gif');
					if ($ext[ $_FILES["{$v['id']}_{$z['id']}"]['type'] ]){
						if(move_uploaded_file($_FILES["{$v['id']}_{$z['id']}"]['tmp_name'], $tmp_file_name = mpopendir('include/images')."/".basename($_FILES["{$v['id']}_{$z['id']}"]['tmp_name']))){
							$file_name = "images/{$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_val_".mysql_insert_id().".".$ext[ $_FILES["{$v['id']}_{$z['id']}"]['type'] ];
							mpqw($sql = "INSERT INTO {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_dop (oid, pid, vid, val) VALUES ($mysql_insert_id, {$v['id']}, {$z['id']}, '$file_name')");
							rename($tmp_file_name, mpopendir('include')."/$file_name");
						}else{
							echo "Ошибка копирования файла. Проверьте права доступа к директории.";
						}
					}else{
						echo "Процедура загрузки вернула код ошибки. Или попытка загрузить файл запрещенного типа ({$_FILES["{$v['id']}_{$z['id']}"]['type']}).";
					}
				}
			}elseif($v['type'] == 'radio' || $v['type'] == 'select'){
				if ($_POST[$v['id']]){
					$sql = "INSERT INTO {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_dop (oid, pid, vid) VALUES ($mysql_insert_id, {$v['id']}, {$_POST[$v['id']]})";
					mpqw($sql);
				}
			}else{
				if ($_POST[$v['id']]){
					$sql = "INSERT INTO {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_dop (oid, pid, val) VALUES ($mysql_insert_id, {$v['id']}, '{$_POST[$v['id']]}')";
					mpqw($sql);
				}
			}
		}
		echo "<div align=center>Ваше обьявление принято. Благодарим на участие. <a href='/?m[obyavlen]&kid={$_GET['kid']}'>Вернуться</div>";
	}else{
		echo "<div align=center><font color=red>В данную категорию запрещена подача объявлений</font>. <a href=>Вернуться</div>";
	}
}else{
	if ($_GET['kid']){
		echo "<p><div align=center>Форма добавления новых обьявлений</div>";
		echo "<p><form method='post' enctype='multipart/form-data'>";
		echo "<table border=0 width=100%><tr><td width=2>";
		echo "Город:</td><td style='width: 150px;'>";
		echo "<select name=gid style='width: 100%;'>";
		$goroda = mpql(mpqw("SELECT * FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_gorod ORDER BY name"));
		foreach($goroda as $k=>$v){
			echo "<option value={$v['id']}>{$v['name']}</option>";
		}
		echo "</select></td>";
		echo "<td width=1>Категория:</td><td>";
		if (!mpql(mpqw("SELECT COUNT(*) as count FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_kat WHERE pid=".(int)$_GET['kid']), 0, 'count')){
			echo "<select name=kid style='width: 100%;'>";
			echo "<option value={$_GET['kid']}>".mpql(mpqw("SELECT name FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_kat WHERE id=".(int)$_GET['kid']), 0, 'name')."</option>";
			echo "</select>";
		}else{
			echo "<select name=kid style='width: 100%;'>";
			$shablon = array(
				'id'=>'id',
				'pid'=>'pid',
				'line'=>array(
					'++'=>'', # Закрытая не последняя директория
					'+-'=>'', # Закрытая последняя директория
					'-+'=>'[3]', # Открытая не последняя директория
					'--'=>'[4]', # Открытая последняя директория
					'+'=>'', # Не последний файл
					'-'=>'', # Последний файл
					'null'=>'[7]' # Верктикальная линия
				),
				'file'=>"<option value={id}>{tmp:prefix}{line}{name}</option>",
				'folder'=>"<option value={id}>{tmp:prefix}{line}{name}</option>{folder}",
				'prefix'=>array(
					'pre'=>'',
					'+'=>'&nbsp;&nbsp;&nbsp;', # Вертикальная полоса
					'-'=>'&nbsp;&nbsp;&nbsp;', # Пробел
					'post'=>'',
				),
				'отображение'=>array('*'=>'none'),
			);
			echo mptree(mpql(mpqw("SELECT CONCAT('0', id) as id, CONCAT('0', pid) as pid, name FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_kat ORDER BY name")), $_GET['kid'] ? $_GET['kid'] : '00', $shablon);
			echo "</select>";
		}
		echo "</td></tr><tr>";
		$table_cols = 3;
		echo "<td colspan=4>";//<table border=1 width=100% cellspacing=0 cellpadding=0><tr>";
		$data = mpql(mpqw("SELECT p.*, kp.row FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_pole as p, {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_kpole as kp, {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_kat as k WHERE p.id=kp.pid AND k.id=kp.kid AND k.id=".(int)$_GET['kid']." ORDER BY kp.row, kp.sort"));
	//	echo "<pre>"; print_r($data); echo "</pre>";
		foreach($data as $k=>$v){
			if ($row != $v['row']){
				echo ($k ? "</tr></table>" : ''). "<table width=100% border=0 cellspacing=0 cellpadding=0><tr>";
				$row = $v['row'];
			}
			echo "<td>";
			echo "<fieldset style='background-color: #eeeeee;'><legend><b>{$v['title']}</b></legend>";
			if ($v['type'] == 'select'){
				echo "<select name={$v['id']}>";
				foreach(mpql(mpqw("SELECT * FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_vpole WHERE pid={$v['id']}")) as $n=>$z){
					echo "<option value={$z['id']}>{$z['val']}</option>";
				}
				echo "</select>";
			}elseif($v['type'] == 'radio'){
				foreach(mpql(mpqw("SELECT * FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_vpole WHERE pid={$v['id']}")) as $n=>$z){
					echo "<input type='radio' value='{$z['id']}' name={$v['id']}> {$z['val']}";
				}
			}elseif($v['type'] == 'checkbox'){
				foreach(mpql(mpqw("SELECT * FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_vpole WHERE pid={$v['id']}")) as $n=>$z){
					echo "<input type='checkbox' name={$v['id']}_{$z['id']} onchange=\"javascript: div.innerHTML = this.value;\"> {$z['val']}";
				}
			}elseif($v['type'] == 'text'){
				echo "<input type='text' name={$v['id']} style='width:100%;'>";
			}elseif($v['type'] == 'textarea'){
				echo "<textarea name={$v['id']} style='width: 100%;'></textarea>";
			}elseif($v['type'] == 'img'){
				foreach(mpql(mpqw("SELECT * FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_vpole WHERE pid={$v['id']}")) as $n=>$z){
					echo "<div>{$z['val']} <input type='file' name={$v['id']}_{$z['id']}></div>";
				}
			}
			echo "</fieldset>";
			echo "</td>";
		} if (count($data)) echo "</tr></table>";
		echo "</td></tr><tr><td colspan=4><fieldset style='background-color: #eeeeee;'><legend><b>Дополнительно</b></legend><textarea name=text style='width: 100%; height: 150;'></textarea></fieldset></td></tr>";
		echo "<tr><td align=right colspan=4><input type=submit></td></tr>";
		echo "</table>";
		echo "</form>";
	}else{
		foreach(mpql(mpqw("SELECT id, name, aid FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_kat ORDER BY pid")) as $k=>$v){
			if ($v['aid'] >= 2){
				echo "<li><a href=/obyavlen:add/kid:02>{$v['name']}<a>";
			}else{
				echo "<li>{$v['name']}";
			}
		}
	}
}

?>