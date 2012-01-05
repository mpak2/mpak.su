<? die;

// ----------------------------------------------------------------------
// mpak Content Management System
// Copyright (C) 2007 by the mpak.
// (Link: http://mp.s86.ru)
// ----------------------------------------------------------------------
// LICENSE and CREDITS
// This program is free software and it's released under the terms of the
// GNU General Public License(GPL) - (Link: http://www.gnu.org/licenses/gpl.html)http://www.gnu.org/licenses/gpl.html
// Please READ carefully the Docs/License.txt file for further details
// Please READ the Docs/credits.txt file for complete credits list
// ----------------------------------------------------------------------
// Original Author of file: Krivoshlykov Evgeniy (mpak) +7 3462 634132
// Purpose of file:
// ----------------------------------------------------------------------

if ($_GET['img']){
	if ($img = mpql(mpqw("SELECT img FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_clan WHERE id=".(int)$_GET['img']), 0, 'img')){
		mpfile($img);
	} die;
}

if (file_exists($file_name = "themes/{$GLOBALS['conf']['settings']['theme']}/modules/{$arg['modpath']}/".(int)$_GET['pid'].".html")){
	$content = file_get_contents($file_name);
}elseif (file_exists($file_name = "themes/{$GLOBALS['conf']['settings']['theme']}/modules/{$arg['modpath']}/*.html")){
	$content = file_get_contents($file_name);
}else{
	$content = "<div><!-- pages:text --></div>";
}
	$iname = mpql(mpqw("SELECT name FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_pers WHERE id=".(int)$_GET['pid']), 0, 'name');
	$GLOBALS['conf']['settings']['title'] = "$iname : ". $GLOBALS['conf']['settings']['title'];
	echo "<a href='/?m[{$arg['modpath']}]'>Список кланов</a><p>";
	echo "Статистика <b>$iname</b><p>";
	if ($_GET['pid']){
		$bgcolor = array(
		''=>'#ffffff',
	);

	$bg = $bgcolor[''];
	$m = array('0'=>array('bg'=>$bg));

	$time = time();
	$period = 20;
	$start = mktime (0, 0, 0, date('m', time()-60*60*24*$period), date('d', time()-60*60*24*$period), date('Y', time()-60*60*24*$period));
	foreach(mpql(mpqw("SELECT * FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_igra WHERE pid=".(int)$_GET['pid']." ORDER BY time")) as $k=>$v){
		$tm = $v['time']-$start;
		$h = (int)($tm-$tm%3600)/3600;
		$m[ $h ]['title'] .= " ". date('H:i', $v['time'])." ";
		if ($v['igra'] == 0){
		    $m[ $h ]['title'] .= "Профа [{$v['profa']}]";
		}elseif ($v['igra'] == 1){
		    $m[ $h ]['title'] .= "Вошел [{$v['profa']}]";
		}else{
		    $m[ $h ]['title'] .= "Вышел из игры";
		}
		if ($v['profa'] && !$bgcolor[$v['profa']]){
			$b = decbin(count($bgcolor));
			$bgcolor[$v['profa']] = '#'.strtr(str_repeat('0', 3-strlen($b)).$b, array('0'=>'66', '1'=>'ff'));
		}
		if ($v['profa']) $m[ $h ]['bg'] = $bgcolor[ $v['profa'] ];
		$m[ $h ]['profa'] = $v['profa'];
		$m[ $h+1 ]['bg'] = $bgcolor[$v['profa']];
	}

	echo "<table border=0 cellspacing=2 cellpadding=2 bgcolor=#dddddd>";
	for($i = 0; $i<=(time()-$start)/3600; $i++){
		if ($m[$i]['bg']){
		    $bg = $m[$i]['bg'];
		}else{
		    $m[$i]['bg'] = $bg;
		}
		if ($i%24 == 0) echo "<tr height=15px;><td bgcolor=#ffffff><b>".date('Y.m.d', $start+$i*3600)."</b></td>";
		echo "<td".($m[$i]['bg'] ? " bgcolor={$m[$i]['bg']}" : '').($m[$i]['title'] ? " title='{$m[$i]['title']}' style=' font-weight: bold;'" : '').">".date('H:i', $start+$i*3600)."</td>";
		if (($i+1)%24 == 0) echo "</tr>";
	}
	echo "</table><p>";

	echo "<table>";
	foreach($bgcolor as $k=>$v){
		echo "<tr><td>$k</td><td width=100 bgcolor=$v></td>";
	}
	echo "</table>";
}else{
	$GLOBALS['conf']['settings']['title'] = 'Список кланов'. " : ". $GLOBALS['conf']['settings']['title'];
	echo "В базе ".mpql(mpqw("SELECT COUNT(*) as count FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_pers"), 0, 'count')." игрока ";
	if ($_GET['display']){
		$_GET['display'] = '';
//		echo "<a href=/?m[abysstat]>Свернуть</a>";
	}else{
		$_GET['display'] = $GLOBALS['conf']['settings']['abysstat_display'];
//		echo "<a href=/?m[abysstat]&display=none>Раскрыть</a>";
	}
	$folder = file_exists($file_name = 'themes/sf.ugratel.ru/dmenu_folder.html') ? file_get_contents($file_name) : "<a href='{id}' onClick=\"javascript: obj=document.getElementById('div_{id}'); if(obj.style.display==''){obj.style.display='none'}else{obj.style.display=''}; return false;\">{line}<img src={логоклана} border=0>  [ {вигре}/{количество} ] {name}</a> {описание}";// /img/tree_folder.png
	$file = file_exists($file_name = 'themes/sf.ugratel.ru/dmenu_link.html') ? file_get_contents($file_name) : "{line}<a href='/{$arg['modpath']}/pid:{id}'><img src=http://mpak.su/?m[files]&id={лого} border=0> {name}</a> {профа}";

	$shablon = array(
        	'id'=>'id',
        	'pid'=>'pid',
        	'поля'=>array('*'=>'0'),
        	'line'=>array(
                	'++'=>'<img src=/img/tree_plus.png border=0>', # Закрытая не последняя директория
                        '+-'=>'<img src=/img/tree_pplus.png border=0>', # Закрытая последняя директория
                        '-+'=>'[3]', # Открытая не последняя директория
                        '--'=>'[4]', # Открытая последняя директория
                        '+'=>'<img src=/img/tree_split.png border=0>', # Не последний файл
                        '-'=>'<img src=/img/tree_psplit.png border=0>', # Последний файл
                        'null'=>'[7]' # Верктикальная линия
        	),
        	'file'=>"<table cellspacing='0' cellpadding='0' border='{поля}' width=100%>
                	                <tr valign=center>
                        	                <td width=1>{tmp:prefix}</td>
                                	        <td>$file</td>
                                	</tr>
                        	</table>",
        	'folder'=>"<table cellspacing='0' cellpadding='0' border='{поля}' width=100%>
	                                <tr valign=center>
        	                                <td width=1>{tmp:prefix}</td>
                	                        <td>$folder</td>
                        	        </tr>
	                        </table><div cellspacing='0' cellpadding='0' id='div_{id}' style='display:{display};'>{folder}</div>",
	        'prefix'=>array(
			'+'=>'<img src=/img/tree_vl.png>', # Вертикальная полоса
			'-'=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' # Пробел
        	),
        	'display'=>array('*'=>$_GET['display']),
		'лого'=>array('*'=>'21'),
		'логоклана'=>array('*'=>'/img/tree_folder.png')+spisok("SELECT CONCAT('0', id), CONCAT('/{$arg['modpath']}/img:', id) as img FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_clan WHERE img<>''"),
	);

	$online = mpql(mpqw("SELECT p.id, p.kid, CONCAT('(', i.profa, ')') as profa FROM {$GLOBALS['conf']['db']['prefix']}abysstat_igra as i, {$GLOBALS['conf']['db']['prefix']}abysstat_igramid AS m, {$GLOBALS['conf']['db']['prefix']}abysstat_pers AS p WHERE i.igra>=0 AND p.id=m.pid AND m.mid=i.id"));
	foreach($online as $k=>$v) {
		$shablon['профа'][$v['id']] = $v['profa'];
		$shablon['лого'][$v['id']] = '22';
		$shablon['вигре']['0'.$v['kid']]+=1;
	}

//	print_r($shablon['вигре']);

//	$shablon['вигре'] = spisok("SELECT CONCAT('0', c.id), CONCAT('[', COUNT(*), ']') FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_clan as c, {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_pers as p WHERE c.id=p.kid GROUP BY c.id");

	$shablon['количество'] = spisok("SELECT CONCAT('0', c.id), COUNT(*) FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_clan as c, {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_pers as p WHERE c.id=p.kid GROUP BY c.id");

	$shablon['описание'] = spisok("SELECT CONCAT('0', id) as id, CONCAT('# ', description) FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_clan WHERE description<>''");

	$data = mptree(mpql(mpqw("(SELECT CONCAT('0', id) as id, '00' as pid, name FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_clan) UNION (SELECT id, CONCAT('0', kid) as pid, name FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_pers) ORDER BY name")), '00', $shablon);

	echo "<div cellspacing='0' cellpadding='0'>$data</div>";
}

//$profa = spisok("select p.id, i.profa from {$GLOBALS['conf']['db']['prefix']}abysstat_igra as i JOIN (SELECT MAX(id) AS maxid FROM {$GLOBALS['conf']['db']['prefix']}abysstat_igra GROUP BY pid) AS i2 ON i.id=i2.maxid JOIN (SELECT id, kid, name FROM {$GLOBALS['conf']['db']['prefix']}abysstat_pers) AS p ON p.id=i.pid AND i.igra=1");
//echo "<pre>"; print_r($profa); echo "</pre>";


?>