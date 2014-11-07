<? die; 

//$tpl['index:mpager'] = qn($sql = "SELECT SQL_CALC_FOUND_ROWS id,name FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE 1". ($_GET['id'] ? " AND id=". (int)$_GET['id'] : " ORDER BY id DESC LIMIT ". ($_GET['p']*10). ",10"));
//$tpl['mpager:index'] = mpager(ql("SELECT FOUND_ROWS()/10 AS cnt", 0, 'cnt'));

//$tpl[$i = "index"] = qn("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$i} WHERE id IN (". in($tpl['index:mpager']).")");

# Проверка данных на присутствие в запросе $_POST данных. Также проверяется чтобы запрос приходил с этой же страницы
//if($_POST && (array_pop(explode($_SERVER['HTTP_HOST'], $_SERVER['HTTP_REFERER'])) == $_SERVER['REDIRECT_URL'])){ exit(mpre($_SERVER)); }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//   Функция находит все таблици текущего раздела и сохраняет их в массиве для возможности использование в шаблоне
//   Все данные сохраняются в массиве $tpl ключами которой являются имена таблиц значениями - данные таблиц
//   Данные таблиц хранятся в основной фотме в качестве ключей записи используется поле id
//   В скрипте все данные можно посмотреть функцией mpre($tpl['index']) где index это таблица раздела
//
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

foreach(mpql(mpqw("SHOW TABLES WHERE Tables_in_{$conf['db']['name']} LIKE \"{$conf['db']['prefix']}{$arg['modpath']}\_%\"")) as $k=>$v){
	$t = implode("_", array_slice(explode("_", $v["Tables_in_{$conf['db']['name']}"]), 2));
	if((empty($conf['settings']["{$arg['modpath']}_tpl_exceptions"]) && !array_key_exists($t, (array)$tpl)) ||
		((!empty($conf['settings']["{$arg['modpath']}_tpl_exceptions"]) && (array_search($t, explode(",", $conf['settings']["{$arg['modpath']}_tpl_exceptions"])) === false)) && !array_key_exists($t, (array)$tpl))){
		$tpl[ $t ] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$t}". (($order = $conf['settings']["{$arg['modpath']}_{$t}=>order"]) ? " ORDER BY {$order}" : "")));
	}
}// mpre(array_keys($tpl));

//$tpl['uid'] = qn("SELECT * FROM {$conf['db']['prefix']}users WHERE id IN (". in(rb($tpl['index'], "uid")). ")");
