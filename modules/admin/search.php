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

//include_once("include/idna_convert.class.inc");
//$IDN = new idna_convert();

$sql = "SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = ".(int)$_REQUEST['search_block_num'];
$res = mpql(mpqw($sql));
$param = unserialize($res['0']['param']);

$conf['tpl']['http_host'] = $_SERVER['HTTP_HOST'];

# Сохраняем историю поиска
if ((int)$_REQUEST['search_block_num']){
	mpqw("INSERT INTO {$conf['db']['prefix']}search_index (uid, time, num, name, ip) VALUE ('{$conf['user']['uid']}', '". time(). "', '".(int)$_REQUEST['search_block_num']."', \"".mpquot($_REQUEST['search'])."\", '{$_SERVER['REMOTE_ADDR']}')");
}

$search_result = array();
if (strlen(trim($_REQUEST['search']))){
	$_REQUEST['search'] = htmlspecialchars($_REQUEST['search']);
	foreach((array)$param as $k=>$v){
		if ($k == 'search_query' || $k == 'search_name') continue;
		if(preg_match_all("/{(.*?)}/", $param['search_query'][$k], $regs)){
		}
		$fields = '';$where = '';
		foreach($v as $f=>$z){
			$where .= " OR `$f` LIKE \"%".mpquot($_REQUEST['search'])."%\"";
			$fields .= ", `$f`";
		}
		$search = mpql(mpqw($sql = "SELECT SQL_CALC_FOUND_ROWS ".implode(', ', $regs[1]).", ".substr($fields, 2)." FROM $k WHERE ".substr($where, 3). " LIMIT ". ($_GET['p']*10). ",10"));
		$conf['tpl']['cnt'] +=mpql(mpqw("SELECT FOUND_ROWS()/10 AS cnt"), 0, 'cnt');
		foreach(mpql(mpqw($sql)) as $ns=>$r){
			$lstring = ''; $zamena = array();
			foreach($r as $t=>$f){
				$zamena['{'.$t.'}'] = $f;
				if (isset($param[$k][$t]) && strlen($lstring) < 100)
					$lstring .= ' '.substr(preg_replace ("/(<\/?)(\w+)([^>]*>)/e", " ", $f), 0, 200 - strlen($lstring));
			}
			foreach($regs[1] as $t=>$f)
				$zamena['{'.$f.'}'] = $r[$f];
 			if (strlen($lstring)){
				$conf['tpl']['result'][] = array(
					'name' => $param['search_name'][$k],
					'text' => $lstring,
					'link' => /*$conf['tpl']['http_host']. */strtr($param['search_query'][$k], $zamena),
				);
			}
		}
	} $conf['tpl']['mpager'] = mpager($conf['tpl']['cnt']);	
}

?>