<? die; # СМеню

if (!function_exists(tree)){ # При отсутствии условия обьявление функции дублируется
	function tree($param, $parent, $id, $s){
		$path = array();
		if (is_array($param)){
			foreach($param as $k=>$v){
				if ($id != $v['parent']) continue;
				$path[$k]['urn'] = $s;
				$path[$k]['name'] = $v['name'];
				$path[$k]['link'] = $v['link'];
				if (count($parent[$k])) # Еели у директории есть потомки
					$path += (array)tree($param, $parent, $k, $s + 1);
			}
		}
		return $path;
	}
}

if ((int)$arg['confnum']){

	# Сохранение и востановление параметров модуля
	if (count($res = mpql(mpqw("SELECT param FROM {$GLOBALS['conf']['db']['prefix']}blocks WHERE id = {$arg['confnum']}"))))
		$param = unserialize($res[0]['param']);

	if ((int)$_GET['del']){
		unset($param[$_GET['del']]);
	}elseif ((int)$_POST['id'] && strlen($_POST['parent']) && strlen($_POST['name'])){
		$param[$_POST['id']]['parent'] = $_POST['parent'];
		$param[$_POST['id']]['name'] = strtr($_POST['name'], array('\"'=>'&quot;', '"'=>'&quot;', "\'"=>'&#039;', "'"=>'&#039;'));
		$param[$_POST['id']]['link'] = strtr($_POST['link'], array('\"'=>'&quot;', '"'=>'&quot;', "\'"=>'&#039;', "'"=>'&#039;'));
	}elseif (strlen($_POST['parent']) && strlen($_POST['name'])){
		$max = 0;
		foreach((array)$param as $k=>$v)
			if ($max <= $k) $max = (int)$k + 1;
		$param[$max]['parent'] = $_POST['parent'];
		$param[$max]['name'] = strtr($_POST['name'], array('\"'=>'&quot;', '"'=>'&quot;', "\'"=>'&#039;', "'"=>'&#039;'));
		$param[$max]['link'] = strtr($_POST['link'], array('\"'=>'&quot;', '"'=>'&quot;', "\'"=>'&#039;', "'"=>'&#039;'));
	}

	foreach((array)$param as $k=>$v)
		$parent[$v['parent']] = array_merge((array)$parent[$v['parent']], array($v['name']=>$v['link']));

	$path = tree($param, $parent, 0, 0);
//	echo "<pre>"; print_r($param); echo "</pre>";

	echo "<a href='?m[blocks]=admin&conf={$arg['confnum']}'>Обновить</a><p>";
	echo "<form action='?m[blocks]=admin&conf={$arg['confnum']}' method='post'>";
	echo "<p><table>";
	echo "<tr><td>Владелец:</td><td>";
	if ((int)$_GET['edit']) echo "<input type='hidden' name='id' value='{$_GET['edit']}'>";
	echo "<select name='parent'><option value='0'></option>";
	foreach($path as $k=>$v){
		echo "<option value='$k'".($param[$_GET['edit']]['parent'] == $k ? ' selected' : '').">".str_repeat('&nbsp;', $v['urn'] * 3)."{$v['name']}</option>";
	}
	echo "</select> <input type='submit' value='Сохранить'></td>";
	echo "<tr><td>Название:</td><td><input type='text' name='name' style='width:200px'".($param[(int)$_GET['edit']] ? " value='{$param[$_GET['edit']]['name']}'" : '')."></td></tr>";
	echo "<tr><td>Ссылка:</td><td><input type='text' name='link' style='width:200px'".($param[(int)$_GET['edit']] ? " value='{$param[$_GET['edit']]['link']}'" : '')."></td></tr>";
	echo "</table>";
	echo "</form>";

//	echo "<pre>"; print_r($path); echo "</pre>";
	echo "<p><table cellspacing='0' cellpadding='3'>";
	foreach($path as $k=>$v)
		echo "<tr><td><a href='?m[blocks]=admin&conf={$arg['confnum']}&del=$k'><img src='/img/del.png' border='0'></a></td><td><a href='?m[blocks]=admin&conf={$arg['confnum']}&edit=$k'><img src='/img/edit.png' border='0'></a></td><td>".(strlen($v['link']) ? "<a href='{$v['link']}'>" : '').str_repeat('&nbsp;', $v['urn'] * 10)."{$v['name']}".(strlen($v['link']) ? "</a>" : '')."</td></tr>";
	//<td><a href='?m[blocks]=admin&conf={$arg['confnum']}&dec=$k'><img src='img/down.gif' border='0'></a></td>
	echo "</table>";

	foreach((array)$param as $k=>$v)
		if (!isset($path[$k])) unset($param[$k]);

	if (count($param)){
//		foreach($param as $k=>$v)
//			$param[$k]['name'] = htmlspecialchars($v['name'], ENT_QUOTES);
		$sql = "UPDATE {$GLOBALS['conf']['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}";
		mpqw($sql);
//		echo "<pre>"; print_r($param); echo "</pre>";
	}
	return;
}


# Выбор параметров
if (count($res = mpql(mpqw("SELECT param FROM {$GLOBALS['conf']['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"))))
	$param = @unserialize($res[0]['param']);


if(is_array($param))
echo "
<script language=JavaScript>
function tree(name){
	if (document.getElementById('div' + name).style.display == 'none'){
		document.getElementById('div' + name).style.display = '';
	}else{
		document.getElementById('div' + name).style.display = 'none';
	}
}
</script>";

$parent = array();
foreach((array)$param as $k=>$v)
	$parent[$v['parent']]++;
$path = tree($param, $parent, 0, 0);

if (!function_exists('tree_menu')){
function tree_menu($param, $parent, $id, $s, $p){
	if(is_array($param))
		foreach($param as $k=>$v){
			if ($v['parent'] != $id) continue;
			$p[$v['parent']]++;
			echo "<div>";
			foreach($p as $n=>$m){
				if ($n) echo $p[$param[$n]['parent']] < $parent[$param[$n]['parent']] ? "<img src='/img/tree_vl.png'>" : "<img src='/img/tree_pvl.png'>";
			}

			if ($parent[$k]){	 # Если директория
				echo "<a href='javascript:tree(\"$k\")'>".
				($p[$param[$k]['parent']] < $parent[$param[$k]['parent']] ? "<img src='/img/tree_plus.png' border='0'>" : "<img src='/img/tree_pplus.png' border='0'>")." <img src='/img/tree_folder.png' border='0'> ";
				if (strlen($v['link'])){
					echo "</a><a href='{$param[$k]['link']}'>";
				}
				echo $param[$k]['name']. "</a>";
				echo "</div><div cellspacing='0' cellpadding='0' id='div$k' name='div$k' style='display:none'>";
				tree_menu($param, $parent, $k, $s + 1, $p);
				echo "</div>";
			}else{
				echo $p[$param[$k]['parent']] < $parent[$param[$k]['parent']] ? "<img src='/img/tree_split.png'>" : "<img src='/img/tree_psplit.png'>";
				echo (strlen($v['link']) ? " <a href='{$v['link']}'>" : '').$v['name'].((strlen($v['link'])) ? "</a>" : '');
				echo "</div>";
			}
		}
}
}

tree_menu($param, $parent, 0, 0, array());

?>