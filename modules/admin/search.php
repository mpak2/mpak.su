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

/*if($_REQUEST['search_block_num']){ 
	mpqw("INSERT INTO {$conf['db']['prefix']}search_index (uid, time, num, name, ip) VALUE ('{$conf['user']['uid']}', '". time(). "', '".(int)$_REQUEST['search_block_num']."', \"".mpquot(get($_REQUEST, 'search'))."\", '{$_SERVER['REMOTE_ADDR']}')");
}*/

if(!$block = rb("blocks-index", "id", $_REQUEST['search_block_num'])){ mpre("ОШИБКА выборки блока");
}elseif(!$tpl['param'] = $param = json_decode($param = $block['param'], true)){ mpre("ОШИБКА получения параметров поиска");
}elseif(!strlen(trim(get($_REQUEST, 'search')))){ mpre("Запрос на поиск не задан");
}elseif(!$tpl['search'] = htmlspecialchars(get($_REQUEST, 'search'))){ mpre("ОШИБКА строка запроса не найдена");
}elseif(!$tpl['result'] = array_map(function($table) use($tpl, $param){
		if(!$pm = get($param, $table)){ mpre("ОШИБКА получения параметров таблицы");
		}elseif(!$fields = get($pm, 'fields')){ mpre("ОШИБКА списко полей таблицы не найден");
		}elseif(!$where = implode(" OR ", array_map(function($field) use($tpl){ # Получения условий полей
				return "`$field` LIKE \"%".mpquot($tpl['search'])."%\"";
			}, array_keys($fields)))){ mpre("ОШИБКА получения условий на выборку");
		}elseif(!$field = implode(",", array_keys($fields))){ mpre("ОШИБКА получения списка полей таблицы");
		}elseif(!$sql = "SELECT id,{$field} FROM `$table` WHERE ". $where/*. " LIMIT ". (get($_GET, 'p')*5). ",10"*/){ mpre("ОШИБКА составления запроса на выборку результата");
		}elseif(!is_array($LIST = ql($sql))){ mpre("ОШИБКА получения списка результатов");
		}elseif(!$sql = "SELECT COUNT(*) AS `cnt` FROM `$table` WHERE ". $where){ mpre("ОШИБКА составления запроса на выборку результата");
		}elseif(!is_numeric($count = ql($sql, 0, 'cnt'))){ mpre("ОШИБКА получения количества элементов");
		}elseif(!$result = $pm + ['list'=>$LIST, 'cnt'=>$count]){ mpre("Параметры таблицы");
		}else{// mpre($sql, $LIST, $count);
			return $result;
		}
	}, array_combine(array_keys($param), array_keys($param)))){ mpre("ОШИБКА получения результатов поиска");
}else{// mpre($param, $tpl);
}

