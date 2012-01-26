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

$conf['tpl']['img'] = mpql(mpqw("SELECT SQL_CALC_FOUND_ROWS * FROM {$conf['db']['prefix']}{$arg['modpath']}_img WHERE 1". ($_GET['uid'] ? " AND uid=". (int)$_GET['uid'] : ""). ($_GET['cat'] ? " AND kid=".(int)$_GET['cat'] : ' ORDER BY id DESC'). " LIMIT ". ($_GET['p']*25). ", 25"));

$conf['tpl']['mpager'] = mpager(mpql(mpqw("SELECT FOUND_ROWS()/25"), 0, 'cnt'));
$conf['tpl']['cat'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_cat"));

?>