<? die;

$shablon = array(
	'car'=>array(
		'cvet'=>array('rgb'=>'<span style="background-color:{f:{f}};color:gray;padding:0 20px;">{f:{f}}</span>',),
		'mark'=>array('site'=>'<a target=_blank href="http://{f:{f}}">{f:{f}}</a>'),
		'index'=>array('uid'=>'<a href="/users/{f:{f}}">Кабинет</a>'),
	),
);

function zam($line, $k){
	$sh["{f}"] = $k;
	foreach($line as $c=>$z){
		$sh["{f:$c}"] = $line[$c];
	} return $sh;
}

foreach(mpqn(mpqw($sql = "DESC ". ($tn = "{$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}")), 'Field') as $k=>$v){
//	if(mb_substr($conf['settings'][$f = "{$arg['modpath']}_{$arg['fn']}_{$k}"], 0, 1, 'utf-8') == '.') continue;
	if(array_search($k, array('href')) != null) continue;
	$conf['tpl']['th'][$k] = ($conf['settings'][$f = "{$arg['modpath']}_{$arg['fn']}_{$k}"] ?: $f);
} $fields = mpquot(implode(', ', array_keys($conf['tpl']['th'])));

if($_GET['id']){
	$conf['tpl'][$arg['fn']] = mpql(mpqw($sql = "SELECT $fields FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} WHERE id=".(int)$_GET['id']), 0);
	$conf['settings']['title'] = $conf['settings']["{$arg['modpath']}_{$arg['fn']}"]. " : ". $conf['tpl'][$arg['fn']]['name'];
	foreach($conf['tpl'][$arg['fn']] as $k=>$v){
		if($s = $shablon[ $arg['modpath'] ][ $arg['fn'] ][ $k ]) $sh = zam($conf['tpl'][$arg['fn']], $k);
		if(substr($k, -3) == '_id' && $v){
			$val = mpql(mpqw($sql = "SELECT name FROM {$conf['db']['prefix']}{$arg['modpath']}_". mpquot($f = substr($k, 0, strlen($k)-3)). " WHERE id=". (int)$v), 0, 'name');
			if($shablon[ $arg['modpath'] ][ $arg['fn'] ][ $k ]) $val = strtr(strtr($s, $sh), $sh);
			$conf['tpl'][$arg['fn']][ $k ] = "<a href=\"/{$arg['modpath']}:{$f}/". (int)$v. "\">{$val}</a>";
		}elseif($shablon[ $arg['modpath'] ][ $arg['fn'] ][ $k ]){
			$conf['tpl'][$arg['fn']][ $k ] = strtr(strtr($s, $sh), $sh);
		}
	}

	$sql = "SELECT * FROM information_schema.KEY_COLUMN_USAGE WHERE `TABLE_SCHEMA`=\"{$conf['db']['name']}\" AND `POSITION_IN_UNIQUE_CONSTRAINT`=1 AND `REFERENCED_TABLE_NAME`=\"{$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}\"";
	foreach(mpql(mpqw($sql)) as $z){
		$tn = substr($z['TABLE_NAME'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"));

		$conf['tpl']['fk'][ $tn ] = mpql(mpqw($sql = "SELECT SQL_CALC_FOUND_ROWS * FROM {$z['TABLE_NAME']} WHERE {$z['COLUMN_NAME']}=". (int)$_GET['id']. " LIMIT ". ($_GET['p']*30). ", 30"));
		$conf['tpl']['mpager'][ $tn ] = mpager(($conf['tpl']['cnt'][ $tn ] = mpql(mpqw("SELECT FOUND_ROWS() AS cnt"), 0, 'cnt'))/30);
		foreach($conf['tpl']['fk'][ $tn ] as $n=>$line){ # Заменяем ключи реальными значениями
			foreach($line as $k=>$v){
				if(substr($k, -3) == '_id'){
					$val = mpql(mpqw($sql = "SELECT name FROM {$conf['db']['prefix']}{$arg['modpath']}_". mpquot($f = substr($k, 0, strlen($k)-3)). " WHERE id=". (int)$v), 0, 'name');
					$conf['tpl']['fk'][ $tn ][ $n ][ $k ] = ($f == $arg['fn'] ? $val : "<a href=\"/{$arg['modpath']}:{$f}/". (int)$v. "\">{$val}</a>");
				}elseif($k == 'id'){
					$conf['tpl']['fk'][ $tn ][ $n ][ $k ] = "<a href=\"/{$arg['modpath']}:{$tn}/{$v}\">{$v}</a>";
				}
			}
		}
	}
}else{
	$conf['tpl'][$arg['fn']] = mpql(mpqw("SELECT SQL_CALC_FOUND_ROWS $fields FROM $tn". mpwr($tn). " LIMIT ". ($_GET['p']*20). ", 20"));
	$conf['tpl']['mpager'] = mpager(($conf['tpl']['cnt'][$arg['fn']] = mpql(mpqw("SELECT FOUND_ROWS() AS cnt"), 0, 'cnt'))/20);

	foreach($conf['tpl'][$arg['fn']] as $n=>$line){ # Заменяем ключи реальными значениями
		foreach($line as $k=>$v){
			if($s = $shablon[ $arg['modpath'] ][ $arg['fn'] ][ $k ]) $sh = zam($line, $k);
			if(substr($k, -3) == '_id' && $v){
				$val = mpql(mpqw($sql = "SELECT name FROM {$conf['db']['prefix']}{$arg['modpath']}_". mpquot($f = substr($k, 0, strlen($k)-3)). " WHERE id=". (int)$v), 0, 'name');
				if($shablon[ $arg['modpath'] ][ $arg['fn'] ][ $k ]) $val = strtr(strtr($s, $sh), $sh);
				$conf['tpl'][$arg['fn']][ $n ][ $k ] = "<a href=\"/{$arg['modpath']}:{$f}/". (int)$v. "\">{$val}</a>";
			}elseif($k == 'id'){
				$val = $shablon[ $arg['modpath'] ][ $arg['fn'] ][ $k ] ? strtr(strtr($s, $sh), $sh) : $v;
				$conf['tpl'][$arg['fn']][ $n ][ $k ] = "<a href=\"/{$arg['modpath']}:{$arg['fn']}/{$v}\">{$val}</a>";
			}elseif($shablon[ $arg['modpath'] ][ $arg['fn'] ][ $k ]){
				$conf['tpl'][$arg['fn']][ $n ][ $k ] = strtr(strtr($s, $sh), $sh);
			}
		}
	}
}

?>