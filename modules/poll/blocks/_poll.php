<? die; # Опрос

if ((int)$arg['confnum']){
	if (isset($_POST['poll'])){
		$sql = "UPDATE {$conf['db']['prefix']}blocks SET param = {$_POST['poll']} WHERE id = {$arg['confnum']}";
		mpqw($sql);
		$param = $_POST['poll'];
	}elseif (count($res = mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}")))){
		$param = $res[0]['param'];
	}

	$polls = spisok("SELECT p.id, p.poll FROM {$conf['db']['prefix']}poll as p, {$conf['db']['prefix']}poll_value as v WHERE p.id = v.pid");
	echo "<form action='{$_SERVER['REQUEST_URI']}' method='post'>";
	echo "Текущий опрос: <font color=blue>{$polls[$param]}</font><p>";
	echo "<select name='poll'><option value='0'></option>";
	foreach($polls as $k=>$v){
		echo "<option value='$k'".($k == $param ? ' selected' : '').">$v</option>";
	}
	echo "</select><input type='submit' value='Применить'></form>";
	return;
}

foreach($conf['user']['gid'] as $k=>$v)
	$uslov .= " OR p.gid = $k";

$uslov = "AND (p.gid = (SELECT id FROM {$conf['db']['prefix']}users_grp WHERE name = '{$conf['settings']['default_grp']}') $uslov)";

# Выборка опроса и его свойств
$poll = mpql(mpqw("SELECT p.id as pid, p.poll, p.mult, p.golos_time, v.result, v.id as vid, v.value, v.color FROM {$conf['db']['prefix']}poll as p, {$conf['db']['prefix']}poll_value as v, {$conf['db']['prefix']}blocks as b WHERE b.id = {$arg['blocknum']} AND p.id = v.pid AND p.id = b.param $uslov"));

if (count($poll)){
	$sql = "SELECT ps.time FROM {$conf['db']['prefix']}poll_post as ps, {$conf['db']['prefix']}poll as p, {$conf['db']['prefix']}blocks as b WHERE ps.poll = p.id AND p.id = b.param AND b.id = {$arg['blocknum']} AND ps.ip = '{$_SERVER['REMOTE_ADDR']}' AND ps.uid = '{$conf['user']['uid']}' AND ps.time + {$poll[0]['golos_time']} > ".time();
	$last = mpql(mpqw($sql));
	if (!count($last) && isset($_POST['poll'])){
		if ($poll[0]['mult']){
			foreach($poll as $k=>$v){
				if (isset($_POST['poll'][ $v['pid'] ][ $v['vid'] ])){
					$sql = "UPDATE {$conf['db']['prefix']}poll_value as v, {$conf['db']['prefix']}poll as p, {$conf['db']['prefix']}blocks as b SET v.result = v.result + 1 WHERE v.pid = p.id AND p.id = b.param AND b.id = {$arg['blocknum']} AND v.id = ".(int)$_POST['poll'][ $v['pid'] ][ $v['vid'] ];
					mpqw($sql);
					$sql = "INSERT INTO {$conf['db']['prefix']}poll_post (uid, time, ip, poll, result) VALUE ('{$conf['user']['uid']}', '".time()."', '{$_SERVER['REMOTE_ADDR']}', '{$poll[0]['pid']}', '".(int)$_POST['poll'][ $v['pid'] ][ $v['vid'] ]."')";
					mpqw($sql);
						$last[] = true;
				}
			}
		}else{
			$sql = "UPDATE {$conf['db']['prefix']}poll_value as v, {$conf['db']['prefix']}poll as p, {$conf['db']['prefix']}blocks as b SET v.result = v.result + 1 WHERE v.pid = p.id AND p.id = b.param AND b.id = {$arg['blocknum']} AND v.id = ".(int)$_POST['poll'][ $poll[0]['pid'] ];
			mpqw($sql);
			$sql = "INSERT INTO {$conf['db']['prefix']}poll_post (uid, time, ip, poll, result) VALUE ('{$conf['user']['uid']}', '".time()."', '{$_SERVER['REMOTE_ADDR']}', '{$poll[0]['pid']}', '".(int)$_POST['poll'][ $poll[0]['pid'] ]."')";
			mpqw($sql);
			$last[] = true;
		}
		$poll = mpql(mpqw("SELECT p.id as pid, p.poll, p.mult, p.golos_time, v.result, v.id as vid, v.value, v.color FROM {$conf['db']['prefix']}poll as p, {$conf['db']['prefix']}poll_value as v, {$conf['db']['prefix']}blocks as b WHERE b.id = {$arg['blocknum']} AND p.id = v.pid AND p.id = b.param"));
	}

	if (count($last)){ # Если уже голосовал
		$res_count = 0;
		foreach($poll as $k=>$v){
			if($v['result'] > $max) $max = $v['result'];
			$res_count += $v['result'];
		}
		foreach($poll as $k=>$v){
			$w = (int)($v['result'] / (!$max ? 1 : $max)  * 100);
			$qw .= "<div style='padding:3px;'>{$v['value']}: {$v['result']} (".(int)($v['result'] / (!$res_count ? 1 : $res_count) * 100)."%)</div>";
			$qw .= "<div style='border:1px solid gray;'><div style='width:".($v['result'] / (!$max ? 1 : $max) * 100)."%; background-color:{$v['color']}; height:15px;'></div></div>";
		}
		echo "<b>{$v['poll']}</b><br>$qw";
	}else{ # Если не голосовал
		foreach($poll as $k=>$v)
			$qw .= "<br><input type='".($v['mult'] ? 'checkbox' : 'radio')."' name='poll[{$v['pid']}]".($v['mult'] ? "[{$v['vid']}]" : '')."' value='{$v['vid']}'> <font color='{$v['color']}'>{$v['value']}</font>";
		echo "<b>{$v['poll']}</b><br><form action='{$_SERVER['REQUEST_URI']}' method='post'>$qw<p><input type='submit' value='Выбрать'></form>";
	}
}else{
	if ($conf['user']['gname'] == $conf['settings']['admin_grp']) echo "Опрос недоступен";
}

?>