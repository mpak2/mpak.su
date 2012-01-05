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

if(array_key_exists('id', $_GET) && $_GET['id'] == 0){
	header("Location: /{$arg['modname']}/". (int)$conf['user']['uid']. ($_GET['theme'] ? "/theme:{$_GET['theme']}" : ''));
}elseif(!$_GET['id']){
	$conf['tpl']['users'] = mpql(mpqw("SELECT SQL_CALC_FOUND_ROWS id, name, reg_time, last_time, email FROM {$conf['db']['prefix']}{$arg['modpath']} ORDER BY id LIMIT ".((int)$_GET['p']*28).", 28"));
	$conf['tpl']['mpager'] = mpager(mpql(mpqw("SELECT FOUND_ROWS()/28 AS cnt"), 0, 'cnt'));

	foreach($conf['tpl']['users'] as $k=>$v){
		foreach($v as $n=>$z){
			if($n == 'reg_time' || $n == 'last_time'){
				$conf['tpl']['users'][$k][$n] = date('Y.m.d', $z);
			}elseif($n == 'email'){
				$conf['tpl']['users'][$k][$n] = strtr($z, array('ch'=>'ч', 'sh'=>'ш', 'zh'=>'ж','q'=>'ку', 'w'=>'в', 'e'=>'е', 'r'=>'р', 't'=>'т', 'y'=>'ай', 'u'=>'у', 'i'=>'и', 'o'=>'о', 'p'=>'п', 'a'=>'а', 's'=>'с', 'd'=>'д', 'f'=>'ф', 'g'=>'г', 'h'=>'х', 'j'=>'й', 'k'=>'к', 'l'=>'л', 'z'=>'з', 'x'=>'кс', 'c'=>'с', 'v'=>'в', 'b'=>'б', 'n'=>'н', 'm'=>'м'));
			}
		}
	}
}

?>