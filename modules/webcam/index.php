<?php die;
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

$sql = "SELECT id, name, link FROM {$GLOBALS['conf']['db']['prefix']}webcam";
$res = mpql(mpqw($sql));

$cam = (int)$_GET['cam'] ? (int)$_GET['cam'] : $res[0]['id'];
echo "<table align='center' width='70%' cellspacing='0' cellpadding='10'><tr><td><font color=red>Внимание: </font>для просмотра данной страницы необходимо высокоскоростное подключение к Интернет.</td></tr><tr align='right'><td>";
echo "<select id='num_cam' name='num_cam' onchange=\"document.location = '?m[webcam]".(isset($_GET['null']) ? '&null' : '')."&cam=' + options[selectedIndex].value;\">";
//echo "<pre>"; print_r($res); echo "</pre>";

foreach($res as $k=>$v){
	echo "<option value='{$v['id']}'".($cam == $v['id'] ? ' selected' : '').">{$v['name']}</option>";
	$links[$v['id']] = $v['link'];
}
echo "</select>";
echo "<p></td></tr><tr><td><img id='chartimg' name='chartimg' src='{$links[$cam]}' border='1' width='100%'></td></tr></table>";

echo "
	<script language=javascript>

	setInterval( setcam, 1000 );
	var sess = 1;
	function setcam(){
		chartimg.src='{$links[$cam]}?' + sess++;
	}
</script>";

?>