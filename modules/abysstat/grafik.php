<? die;

if ($_GET['cid']){
	$title = array('h'=>'График час', 'd'=>'График день', 'm'=>'График месяц');
	$tm = array(
		'h'=>array('i'=>60, 'r'=>60*60, 'f'=>'i'),
		'd'=>array('i'=>60*60, 'r'=>60*60*24, 'f'=>'H:i'),
//		'w'=>array('i'=>60*60*24, 'r'=>60*60*24*7, 'f'=>'m.d'),
		'm'=>array('i'=>60*60*24, 'r'=>60*60*24*30, 'f'=>'m.d'),
		'y'=>array('i'=>60*60*24*30, 'r'=>60*60*24*365, 'f'=>'Y.m'),
	);
	$tp = array(
		'h'=>array('n'=>'d'),
		'd'=>array('p'=>'h', 'n'=>'m'),
		'm'=>array('p'=>'d'),
		'y'=>array('p'=>'m')
	);

	$stime = $_GET['s'] ? $_GET['s'] : time();
	$stime = $stime-($stime%$tm[$_GET['t']]['r']);
//	if ($_GET['t'] != 'm') $stime -= 60*60*4;
	$etime = $stime+$tm[$_GET['t']]['r'];

	echo "Период: с <font color=blue>".date('Y.m.d H:i:s', $stime)."</font> по <font color=blue>".date('Y.m.d H:i:s', $etime)."</font> <b>".mpql(mpqw("SELECT name FROM {$GLOBALS['conf']['db']['prefix']}abysstat_clan WHERE id=".(int)$_GET['cid']), 0, 'name')."</b><p>";

	foreach($title as $k=>$v){
		echo " <a href=/?m[{$arg['modpath']}]=grafik&cid={$_GET['cid']}&t=$k>".($_GET['t'] == $k ? "<font color=blue>$v</font>" : $v)."</a>";
	}

	echo "<p><a href=/?m[abysstat]=grafik&cid={$_GET['cid']}&t={$_GET['t']}&s=".($stime-$tm[$_GET['t']]['r']).">Назад</a>";
	if ($tp[$_GET['t']]['n']){
		if ($tp[$_GET['t']]['n'] == 'm'){
			$ntime = mktime(0, 0, 0, date('m', $stime), 1, date('Y', $stime));
		}else{
			$ntime = $stime-($stime%$tm[ $tp[$_GET['t']]['n'] ]['r']);
		}
		for($i = 0; $i < $tm[ $tp[$_GET['t']]['n'] ]['r']/$tm[ $tp[$_GET['t']]['n'] ]['i']; $i++){
			$ttime = $ntime+$i*$tm[ $tp[$_GET['t']]['n'] ]['i'];
			$z = str_repeat('0', 2-strlen($i)).$i;
			$z = $stime == $ttime ? "<font color=blue>$z</font>" : $z;
//			$z = $stime == $ttime ? "<font color=blue>".date('Y.m.d H:i:s', $ttime)."</font>" : date('Y.m.d H:i:s', $ttime);
			echo " <a href=/?m[{$arg['modpath']}]=grafik&cid={$_GET['cid']}&t={$_GET['t']}&s=$ttime>$z</a>";
		}
	}
	echo " <a href=/?m[abysstat]=grafik&cid={$_GET['cid']}&t={$_GET['t']}&s=".($stime+$tm[$_GET['t']]['r']).">Дальше</a>";

	$sql = "SELECT * FROM {$GLOBALS['conf']['db']['prefix']}abysstat_igra as i JOIN (SELECT MAX(id) AS maxid FROM {$GLOBALS['conf']['db']['prefix']}abysstat_igra WHERE time<$stime GROUP BY pid) AS i2 JOIN (SELECT name, id FROM {$GLOBALS['conf']['db']['prefix']}abysstat_pers WHERE kid=".(int)$_GET['cid'].") AS p ON i.igra>=0 AND i.id=i2.maxid AND p.id=i.pid";

	$sc = mpql(mpqw($sql));
	$max = $graf[-1]['min'] = $graf[-1]['max'] = $count = count($sc);
	for($i = 0; $i < $tm[$_GET['t']]['r']/$tm[$_GET['t']]['i']; $i++){
		$graf[$i]['time'] = $stime + $i * $tm[$_GET['t']]['i'];
		if ($graf[$i]['time'] > time()) continue;
		$graf[$i]['min'] = $graf[$i]['max'] = $count;
		$izmen = mpql(mpqw("SELECT p.name, i.* FROM {$GLOBALS['conf']['db']['prefix']}abysstat_igra as i, {$GLOBALS['conf']['db']['prefix']}abysstat_pers as p WHERE p.id=i.pid AND p.kid=".(int)$_GET['cid']." AND i.time>=".($stime+$i*$tm[$_GET['t']]['i'])." AND i.time<".($stime+($i+1)*$tm[$_GET['t']]['i'])." ORDER BY i.time"));
		foreach($izmen as $k=>$v){
			$count += $v['igra'];
			if ($v['igra'] > 0) $graf[$i]['title'] .= " +{$v['name']}(".date('H:i:s', $v['time']).")";
			if ($v['igra'] < 0) $graf[$i]['title'] .= " -{$v['name']}(".date('H:i:s', $v['time']).")";
			$graf[$i]['min'] = min($graf[$i]['min'], $count);
			$graf[$i]['max'] = max($graf[$i]['max'], $count);
			$max = max($max, $graf[$i]['max']);
		}
	}
	$tw = 900; $th = 500;
//	echo " max=$max";
	echo "<p><table border=0 height=$th cellspacing=0 cellpadding=1><tr valign=bottom>";
	for($k = 0; $k < $tm[$_GET['t']]['r']/$tm[$_GET['t']]['i']; $k++){
		echo "<td width=".($tw/($tm[$_GET['t']]['r']/$tm[$_GET['t']]['i']))."><div".($tp[$_GET['t']]['p'] ? " onClick=\"location.href='/?m[abysstat]=grafik&cid={$_GET['cid']}&t=".$tp[$_GET['t']]['p']."&s={$graf[$k]['time']}';\"" : '')."><div title='{$graf[$k]['title']}' style='background-color:#cccccc; height:".@($th/$max*($graf[$k]['max']-$graf[$k]['min'])).";' align=center>".($graf[$k]['max']-$graf[$k]['min'] ? $graf[$k]['max'] : '')."</div>";
		echo "<div title='{$graf[$k]['title']}' style='background-color:#888888; height:".@($th/$max*$graf[$k]['min']).";' align=center>".($graf[$k]['min'] ? $graf[$k]['min'] : '')."</div></div>";
		echo "<div style='height:25;'><div style='position: absolute; border: 1px solid black;'>".date($tm[$_GET['t']]['f'], $stime+$k*$tm[$_GET['t']]['i'])."</div></div></td>";
	}
	echo "</tr></table>";
}else{
	echo "<table border=1 cellspacing=0 cellpadding=2>";
	$graf = array('h'=>'График час', 'd'=>'График день', 'w'=>'График неделя', 'm'=>'График месяц', 'y'=>'График год');
	foreach(mpql(mpqw("SELECT * FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_clan")) as $k=>$v){
		if ($k == 0){
			echo "<tr align=center><td><b>Клан<b></td>";
			foreach($graf as $n=>$z){
				echo "<td><b>$z</b></td>";
			}
			echo "</tr>";
		}
		echo "<tr><td>{$v['name']}</td>";
		foreach($graf as $n=>$z){
			echo "<td><a href=/?m[abysstat]=grafik&cid={$v['id']}&t=$n>График</a></td>";
		}
		echo "</tr>";
	}
	echo "</table>";
}

?>