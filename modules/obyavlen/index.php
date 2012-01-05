<? die;

$kat = mpql(mpqw("SELECT * FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_kat WHERE id=".(int)$_GET['kid']), 0);
$par = mpql(mpqw("SELECT * FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_kat WHERE id=".$kat['pid']), 0);
$GLOBALS['conf']['settings']['title'] .= " : ". $kat['name']." : ".$par['name'];

echo "<h1>{$GLOBALS['conf']['settings']['title']}</h1>";
echo "<a href=/?m[{$arg['modpath']}]=add&kid=0{$_GET['kid']}>Добавить объявление</a><p>";
echo "<a href=/?m[{$arg['modpath']}]=derevo&kid=0{$par['id']}>{$par['name']}</a> > <b>{$kat['name']}</b><p>";
$pole = mpql(mpqw("SELECT p.*, kp.row FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_pole as p, {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_kpole as kp, {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_kat as k WHERE p.id=kp.pid AND k.id=kp.kid AND k.id=".(int)$_GET['kid']." ORDER BY row,sort"));

$uslov = "WHERE kid=".(int)$_GET['kid'].($_GET['tid'] ? " AND tid=".(int)$_GET['tid'] : '').($_GET['gid'] ? " AND gid=".(int)$_GET['gid'] : '');
$count = mpql(mpqw("SELECT COUNT(*) as count FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']} $uslov"), 0, 'count');

$link_count = 20;
if ($count){
	if ((int)$_GET['p'] > $link_count) echo "<a href=/?m[{$arg['modpath']}]&kid=".(int)$_GET['kid'].">1</a> ...";
	for($i=max(0, min((int)$_GET['p']-$link_count, (int)($count/$link_count)-$link_count*2)); $i<=min($count/$link_count, max((int)$_GET['p']+$link_count, $link_count*2)); $i++){
		echo " <a href=/?m[{$arg['modpath']}]&kid=".(int)$_GET['kid'].($i ? "&p=$i" : '').($_GET['p'] == $i ? " style='color: blue;'" : '').">".($i+1)."</a>";
	}
	if ((int)$_GET['p']+$link_count < (int)$count/$link_count) echo " ... <a href=/?m[{$arg['modpath']}]&kid=".(int)$_GET['kid']."&p=".((int)($count/$link_count)).">".(int)($count/$link_count)."</a>";
}

echo "<table border=0 width=100% cellspacing=5 cellpadding=5><tr><td>";
foreach(mpql(mpqw("SELECT * FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']} $uslov ORDER BY id DESC LIMIT ".($_GET['p'] * $link_count).", $link_count")) as $i=>$o){
	echo "<table width=100% border=0 cellspacing=0 cellpadding=0><tr><td><b>Город</b>: <i>".mpql(mpqw("SELECT name FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_gorod WHERE id=".(int)$o['gid']), 0, 'name')."</i></td></tr>";
	foreach($pole as $k=>$v){
		if ($row != $v['row']){
			echo "</tr></table><table width=100% border=0 cellspacing=0 cellpadding=0><tr>";
			$row = $v['row'];
		}
		if ($v['type'] == 'checkbox'){
			if (count($val = mpql(mpqw("SELECT v.* FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_vpole as v, {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_dop as d WHERE d.oid={$o['id']} AND d.pid={$v['id']} AND v.id=d.vid")))){
				echo "<td><b>{$v['title']}</b>: ";
				foreach($val as $n=>$z){
					echo "<i>#{$z['val']}</i> ";
				} echo "<td>";
			}
		}elseif ($v['type'] == 'img'){
			if (count($val = mpql(mpqw("SELECT v.*, d.id as did FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_vpole as v, {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_dop as d WHERE d.oid={$o['id']} AND d.pid={$v['id']} AND v.id=d.vid")))){
				echo "<td><b>{$v['title']}</b>: ";
				foreach($val as $n=>$z){
					echo "<div style='text-align: center; margin:2px; padding: 10px; border: solid 0px; width: 100px; height: 110px; float: left;'><i>{$z['val']}</i><br><img src='/?m[obyavlen]=img&id={$z['did']}'></div>";
				} echo "<td>";
			}
		}elseif($v['type'] == 'radio' || $v['type'] == 'select'){
			if ($val = mpql(mpqw("SELECT v.val FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_vpole as v, {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_dop as d WHERE v.pid={$v['id']} AND d.oid={$o['id']} AND v.id=d.vid"), 0, 'val')){
				echo "<td><b>{$v['title']}</b>: <i>$val</i></td>";
			}
		}else{
			if ($val = mpql(mpqw("SELECT val FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_dop WHERE pid={$v['id']} AND oid={$o['id']}"), 0, 'val')){
				echo "<td><b>{$v['title']}</b>: <i>$val</i></td>";
			}
		}
	} echo "</tr></table>";

	if ($o['description']) echo "<table width=100% border=0 cellspacing=0 cellpadding=0><tr><td><b>Дополнительно</b>: <i>{$o['description']}</i></td></tr></table>";
	echo "<hr>";
}
echo "</td></tr></table>";

?>