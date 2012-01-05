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

if ($_GET['f']){
	$table_name = "{$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}";
	list($k, $v) = each($_GET['f']);
	mpfile(mpql(mpqw("SELECT ".addslashes($k)." FROM $table_name WHERE id=".(int)$v), 0, $k));
}

echo "<table cellspacing='0' cellpadding='10' border='0'>";
$sql = "SELECT id, name, link FROM {$GLOBALS['conf']['db']['prefix']}kabinet";
foreach(mpql(mpqw($sql)) as $k=>$v)
	echo ($k % 6 == 0 ? "<tr>" : '')."<td align='center' width='".(100 / 6)."%'><a href='{$v['link']}'><img src='?m[{$arg['modpath']}]&f[img]={$v['id']}' alt='{$v['name']}' border='0'><br>{$v['name']}</a></td>".(++$k % 6 == 0 ? "</tr>" : '');
echo "</table>";


?>