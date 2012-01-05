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
	if ($img = mpql(mpqw("SELECT img FROM {$conf['db']['prefix']}{$arg['modpath']}_clan WHERE id=".(int)$_GET['img']), 0, 'img')){
		mpfile($img);
	} die;
}

if (file_exists($file_name = "themes/{$conf['settings']['theme']}/modules/{$arg['modpath']}/".(int)$_GET['pid'].".html")){
	$content = file_get_contents($file_name);
}elseif (file_exists($file_name = "themes/{$conf['settings']['theme']}/modules/{$arg['modpath']}/*.html")){
	$content = file_get_contents($file_name);
}else{
	$content = "<div><!-- pages:text --></div>";
}

if (!$conf['user']['sess']['ref']){
	$conf['settings']['title'] =  $conf['settings']['title'] .= " : =GM= Zarja Кусок говна";
}

if ($_GET['pid']){
	$pers = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_pers WHERE id=".(int)$_GET['pid']), 0);
	if (!$pers){
		header("HTTP/1.0 404 Not Found");
		echo "<div align=center>Страница не найдена.</div>";
	}else{
		$clan = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_clan WHERE id={$pers['kid']}"), 0);
		$clans = mpql(mpqw("SELECT c.name as cname, p.id, p.name as pname FROM {$conf['db']['prefix']}{$arg['modpath']}_clan as c LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_pers as p ON c.id=p.kid WHERE p.name='{$pers['name']}'"));
		echo "<li><a href=/{$arg['modpath']}>Опа опа</a> > <a href=/{$arg['modpath']}/cid:{$clan['id']}>{$clan['name']}</a> > <a href=/{$arg['modpath']}/pid:{$pers['id']} style='color:blue;'>{$pers['name']}</a>";
		if (count($clans) > 1){
			echo " (";
			foreach($clans as $k=>$v){
				if ($k) echo ", ";
				echo "<a href=/abysstat/pid:{$v['id']}>{$v['cname']}</a>";
			}
			echo ")";
		}
		$conf['settings']['title'] = "{$clan['name']} : ". $conf['settings']['title'];
		$conf['settings']['title'] = "{$pers['name']} : ". $conf['settings']['title'];

		$bgcolor = array(''=>'#ffffff',);
		$bg = $bgcolor[''];
		$m = array('0'=>array('bg'=>$bg));

		$time = time();
		$period = 20;
		$start = mktime (0, 0, 0, date('m', time()-60*60*24*$period), date('d', time()-60*60*24*$period), date('Y', time()-60*60*24*$period));
		foreach(mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_igra WHERE pid=".(int)$_GET['pid']." ORDER BY time")) as $k=>$v){
			$tm = $v['time']-$start;
			$h = (int)($tm-$tm%3600)/3600;
			$m[ $h ]['title'] .= " ". date('H:i', $v['time'])." ";
			if ($v['igra'] == 0){
			$m[ $h ]['title'] .= "Сменил профессию [{$v['profa']}]";
			}elseif ($v['igra'] == 1){
			$m[ $h ]['title'] .= "Зашел в игру [{$v['profa']}]";
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

		echo "<p><table border=0 cellspacing=2 cellpadding=2 bgcolor=#dddddd>";
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
	}
}elseif($_GET['cid']){

	$online = mpql(mpqw("SELECT p.id, p.kid, CONCAT('(', i.profa, ')') as profa FROM {$conf['db']['prefix']}abysstat_igra as i, {$conf['db']['prefix']}abysstat_igramid AS m, {$conf['db']['prefix']}abysstat_pers AS p WHERE i.igra>=0 AND p.id=m.pid AND m.mid=i.id"));
	foreach($online as $k=>$v) {
		$shablon['пїЅпїЅпїЅпїЅпїЅ'][$v['id']] = $v['profa'];
		$shablon['пїЅпїЅпїЅпїЅ'][$v['id']] = '22';
	}

	foreach(mpql(mpqw("SELECT p.* FROM {$conf['db']['prefix']}abysstat_pers AS p LEFT JOIN {$conf['db']['prefix']}abysstat_igra AS i ON p.id=i.pid WHERE p.kid=".(int)$_GET['cid']." AND i.time>".(time()-60*60*24*10)." GROUP BY p.id")) as $k=>$v){
		$igra[ $v['id'] ] = $v['name'];
	}
	$clan = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_clan WHERE id=".(int)$_GET['cid']), 0);
	$conf['settings']['title'] = "{$clan['name']} : ". $conf['settings']['title'];

	echo "<li><a href=/{$arg['modpath']}>Блин блин</a> > <a href=/{$arg['modpath']}/cid:{$clan['id']} style='color:blue;'>{$clan['name']}</a><p>";
	foreach(mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_pers WHERE kid=".(int)$_GET['cid']." ORDER BY name")) as $k=>$v){
		echo " | <nobr><img src=http://mpak.su/files/".($shablon['пїЅпїЅпїЅпїЅ'][$v['id']] ? $shablon['пїЅпїЅпїЅпїЅ'][$v['id']] : '21')."/null>&nbsp;<a href=/{$arg['modpath']}/pid:{$v['id']}".(!$igra[$v['id']] ? " style='color:red;'" : '').">{$v['name']}</a></nobr>";
	}

}else{
	echo "<table border=0 width=100%><tr valign=top><td width=50%>";
	$online = mpql(mpqw("SELECT p.id, p.kid, CONCAT('(', i.profa, ')') as profa FROM {$conf['db']['prefix']}abysstat_igra as i, {$conf['db']['prefix']}abysstat_igramid AS m, {$conf['db']['prefix']}abysstat_pers AS p WHERE i.igra>=0 AND p.id=m.pid AND m.mid=i.id"));
	foreach($online as $k=>$v) {
		$count[$v['kid']]++;
	}

	if (mpopendir("modules/{$arg['modpath']}/insert.php")) echo "<li> <a href=/{$arg['modpath']}:insert>Добавить клан</a><p>";
	echo "В клане: ". mpql(mpqw("SELECT COUNT( * ) AS count FROM `mp_abysstat_igramid` WHERE 1"), 0, 'count'). " персонажей.<p />";

	echo "<table><tr>";
	foreach(mpql(mpqw("SELECT c.*, s.name as sname, COUNT(*) as count FROM {$conf['db']['prefix']}{$arg['modpath']}_clan as c, {$conf['db']['prefix']}{$arg['modpath']}_serv as s, {$conf['db']['prefix']}{$arg['modpath']}_pers as p WHERE s.id=c.sid AND c.id=p.kid GROUP BY c.id ORDER BY s.name, c.name")) as $k=>$v){

		echo "<tr>";
		echo "<td>{$v['sname']}</td>";
		echo "<td>".($v['img'] ? "<img src=/{$arg['modpath']}/img:{$v['id']}>" : '')."</td>";
		echo "<td align=right>[{$count[$v['id']]} / {$v['count']}]</td>";
		echo "<td><a href=/{$arg['modpath']}/cid:{$v['id']}>{$v['name']}</a></td>";
		echo "</tr>";
	}	
	echo "</table>";
	echo "</td><td><li><a href=/{$arg['modpath']}:mess>Нацарапать</a>";
		foreach(mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_mess ORDER BY id DESC LIMIT 10")) as $k=>$v){
			echo "<div style='padding:3px; border: 1px solid #bbbbbb; margin:5px;'><div style='margin:5px;'>{$v['mess']}</div><div align=right>{$v['kontakt']}</div></div>";
		}
	echo "</td></tr></table>";
}

	if (!empty($conf['modules']['comments'])){
echo <<<EOF
<script src='../../include/jquery/jquery.js'></script>
<div id='comments' style='border:0px solid #000; padding:2px;'></div>
<script> $('#comments').load('/comments/null'); </script>
EOF;
	}

?>
