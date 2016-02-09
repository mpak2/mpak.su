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

$tpl['param'] = json_decode($param = ql("SELECT param FROM {$conf['db']['prefix']}blocks_index WHERE id = ".(int)$_REQUEST['search_block_num'], 0, 'param'), true);

# Сохраняем историю поиска
if($_REQUEST['search_block_num']){
	mpqw("INSERT INTO {$conf['db']['prefix']}search_index (uid, time, num, name, ip) VALUE ('{$conf['user']['uid']}', '". time(). "', '".(int)$_REQUEST['search_block_num']."', \"".mpquot($_REQUEST['search'])."\", '{$_SERVER['REMOTE_ADDR']}')");
}

$search_result = array();
if (strlen(trim($_REQUEST['search']))){
	$tpl['search'] = htmlspecialchars($_REQUEST['search']);
	foreach($tpl['param'] as $table=>$v){
		$fields = '`id`';$where = '';

		foreach($v['fields'] as $f=>$z){
			$where .= " OR `$f` LIKE \"%".mpquot($tpl['search'])."%\"";
			$fields .= ", `$f`";
		}

		if($list = ql($sql = "SELECT SQL_CALC_FOUND_ROWS {$fields} FROM `$table` WHERE ".substr($where, 3). " LIMIT ". (get($_GET, 'p')*10). ",10")){
			$tpl['result'][$table] = array(
				'list'=>$list,
				'cnt'=>ql("SELECT FOUND_ROWS()/10 AS cnt", 0, 'cnt'),
			); $tpl['counter'] = get($tpl, 'counter') + count($list);
		}else{ /*mpre($sql);*/ }
	}
}

