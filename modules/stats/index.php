<?
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

$img = array(
	'browser'=>array(
		'Firefox'=>'firefox.gif',
		'Other'=>'question.gif',
		'MSIE'=>'explorer.gif',
		'Netscape'=>'netscape.gif',
		'Opera'=>'opera.gif',
		'Konqueror'=>'konqueror.gif',
		'Lynx'=>'lynx.gif',
		'Bot'=>'robot.gif',
		'Rambler'=>'Rambler.png',
	),
	'os'=>array(
		'Windows'=>'windows.gif',
		'Linux'=>'linux.gif',
		'Mac'=>'mac.gif',
		'FreeBSD'=>'bsd.gif',
		'SunOS'=>'sun.gif',
		'IRIX'=>'irix.gif',
		'BeOS'=>'be.gif',
		'OS/2'=>'os2.gif',
		'Other'=>'question.gif',
	),
);

$stat = array('modules'=>'Модули', 'browser'=>'Браузер', 'os'=>'Операционная система', 'day_of_week'=>'Дни недели', 'month'=>'Месяцы', 'hours'=>'Часы');
if (array_search($GLOBALS['conf']['settings']['admin_grp'], $GLOBALS['conf']['user']['gid'])) $stat['referer']='Ссылки';

$name = array(
//	'modules' => array(''=>'Стартовая страница') + spisok("SELECT folder, name FROM {$GLOBALS['conf']['db']['prefix']}modules"),
	'day_of_week' => array('1'=>'Понедельник', '2'=>'Вторник', '3'=>'Среда', '4'=>'Четверг', '5'=>'Пятница', '6'=>'Суббота', '0'=>'Воскресенье'),
	'month' => array('1'=>'Январь', '2'=>'Февраль', '3'=>'Март', '4'=>'Апрель', '5'=>'Май', '6'=>'Июнь', '7'=>'Июль', '8'=>'Август', '9'=>'Сентябрь', '10'=>'Октябрь', '11'=>'Ноябрь', '12'=>'Декабрь'),
);

foreach($stat as $k=>$v){
	$sql = "SELECT value, count FROM {$GLOBALS['conf']['db']['prefix']}stats WHERE param = '$k' ORDER BY ".($k == 'day_of_week' || $k == 'month' ? 'value' : 'count DESC')." LIMIT 0, ".max(count($name['modules']), 12);
	$zapros = mpql(mpqw($sql));
	if ($k == 'referer'){
		$sql2 .= "SELECT value, count FROM {$GLOBALS['conf']['db']['prefix']}stats WHERE param = '$k' ORDER BY id DESC LIMIT 0, 3";
		$zapros = array_merge(mpql(mpqw($sql2)), $zapros);
	}
	echo "<p align='center'><b>{$stat[$k]}</b><p>";
	echo "<table width='100%' cellspacing='0' cellpadding='1' border='0'>";
	$bgcolor = 0;
	foreach($zapros as $i=>$line){
		echo "<tr".(++$bgcolor % 2 ? " bgcolor='#eeeeee'" : '' )."><td><table width='100%' border='0'><tr><td align='right' width='48%'>";
		if ($k == 'modules' && $line['value'] != 'admin') echo "<a href='?".($line['value'] ? "m[{$line['value']}]" : '')."'>";
		echo ( isset($name[$k][$line['value']]) ? $name[$k][$line['value']] : $line['value'] );
//		if (array_search($GLOABSL['conf']['settings']['admin_grp'], $GLOBALS['conf']['user']['gid'])) echo "del it";

//		&& $GLOBALS['conf']['modules'][ $GLOBALS['conf']['modules']['stats']['id'] ]['access'] >= 4
		if ($k == 'modules' && $line['value'] != 'admin') echo "</a>";
		echo ( isset($img[$k][$line['value']]) ? "&nbsp;<img src='/modules/stats/img/".$img[$k][$line['value']]."'>" : '' );
		echo "</td><td width='4%'>&nbsp;</td><td>{$line['count']}</td></tr></table></td></tr>";
	}
	echo "</table>";
}

?>
