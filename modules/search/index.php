<? die;

if (strlen(trim($_REQUEST['search'])) && $_REQUEST['search_block_num']){
	$search = mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}search_index WHERE search=\"". mpquot($_REQUEST['search']). "\" AND num=". (int)$_REQUEST['search_block_num']), 0);
	if($search){
		mpqw("UPDATE {$conf['db']['prefix']}search_index SET time=". time(). " WHERE id=". (int)$search['id']);
	}else{
		mpqw("INSERT INTO {$conf['db']['prefix']}search_index (uid, time, num, search, ip) VALUE (". (int)$conf['user']['uid']. ", '". time(). "', '".(int)$_REQUEST['search_block_num']."', \"".mpquot(htmlspecialchars($_REQUEST['search']))."\", '". mpquot($_SERVER['REMOTE_ADDR']). "')");
		$search['id'] = mysql_insert_id();
	} header("Location: /{$arg['modname']}/". (int)$search['id']. "/". str_replace("%", "%25", $_REQUEST['search']));
//	echo ("Location: /{$arg['modname']}/". (int)$search['id']);
}elseif($_GET['id']){

//	if(!($conf['tpl'] = mpmc($key = "/{$arg['modname']}:{$arg['fn']}/result:{$_GET['id']}/p:{$_GET['p']}"))){

		$conf['tpl']['search'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}search_index WHERE id=". (int)$_GET['id']), 0);

		$conf['tpl']['http_host'] = mpidn($_SERVER['HTTP_HOST']);
		$conf['settings']['title'] .= " : ". $conf['tpl']['search']['search'];
		mpevent("Поиск по сайту", $conf['tpl']['search']['search'], $conf['user']['uid']);

		$res = mpql(mpqw($sql = "SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = ".(int)$conf['tpl']['search']['num']), 0); $param = unserialize($res['param']);

		foreach($param as $k=>$v){
			if ($k == 'search_priority' || $k == 'search_query' || $k == 'search_name') continue;
			$tab[$k] = $param['search_priority'][$k];
		} arsort($tab);// mpre($tab);

		foreach(explode(' ', $conf['tpl']['search']['search']) as $k=>$v){
			$str_replace[$v] = "<span style=font-weight:bold;>{$v}</span>";
		}// mpre(explode(' ', $conf['tpl']['search']['search']));

		foreach($tab as $k=>$v){
			$v = $param[$k];
			if($pos = strpos($k, '_', strlen($conf['db']['prefix']))){
				$fn = substr($k, $pos+1);
				$mp = substr($k, strpos($k, '_')+1, $pos-strlen($conf['db']['prefix']));
			}else{
				$fn = substr($k, strpos($k, '_')+1);
				$mp = substr($k, strlen($conf['db']['prefix']));
			}
			$desc = mpqn(mpqw("DESC $k"), 'Field');
			preg_match_all("/{(.*?)}/", $param['search_query'][$k], $regs);
			$fields = $where = array();
			foreach($v as $f=>$z){
				$where[] = "(`$f` LIKE \"%". implode("%\" AND `$f` LIKE \"%", explode(' ', mpquot($conf['tpl']['search']['search']))). "%\")";
				$fields[] = "`$f`";
			} if($desc['uid']) $fields[] = "`uid`";
			mpql(mpqw($sql = "SELECT SQL_CALC_FOUND_ROWS ". implode(', ', $regs[1]).", ". implode(', ', $fields)." FROM $k WHERE ".implode(' OR ', $where). " ORDER BY id DESC LIMIT ". ($_GET['p']*5). ",5"));
			$conf['tpl']['page'] += mpql(mpqw("SELECT FOUND_ROWS() AS cnt"), 0, 'cnt');

			foreach(mpql(mpqw($sql)) as $r){
				$lstring = ''; $zamena = array();
				foreach($r as $t=>$f){
					$zamena['{'.$t.'}'] = str_replace("%", "%25", $f);
					if (isset($param[$k][$t])){
						$lstring .= ' '. strip_tags($f);
					}
				}// mpre($zamena);
				foreach($regs[1] as $t=>$f){
					$zamena['{'.$f.'}'] = str_replace("%", "%25", $r[$f]);
				}// mpre($zamena);
				if($pos = mb_strpos($lstring, $conf['tpl']['search']['search'])){
					$lstring = mb_substr($lstring, max($pos-150, 0), 300, 'utf-8');
				}else{
					$lstring = mb_substr($lstring, 0, 300, 'utf-8');
				}
				if (strlen($lstring)){
					$conf['tpl']['result'][] = array(
						'uid'=>$r['uid'] ? ($users[$r['uid']] ?: $users[$r['uid']] = mpql(mpqw("SELECT id, name FROM {$conf['db']['prefix']}users WHERE id=". (int)$r['uid']), 0)) : "",
						'img'=>($desc['img'] ? "/{$mp}:img/{$r['id']}/tn:{$fn}/w:500/h:600/themes/null/img.jpg" : ''),
						'logo'=>($desc['img'] ? "/{$mp}:img/{$r['id']}/tn:{$fn}/w:100/h:100/themes/null/img.jpg" : ''),
						'title' => $param['search_name'][$k],
						'link' => $link = ("http://". $conf['tpl']['http_host']. strtr($param['search_query'][$k], $zamena)),
						'name'=> $r['name'] ?: $link,
						'text'=> strtr($lstring, $str_replace),
					); //$content = str_ireplace(" $search ", "<span style=background:yellow;>{$search}</span>", $content);
				}
			}
		}// mpmc($key, $conf['tpl']);
//	}
	if($conf['tpl']['page']){ # Обновление информации о поиске
		$conf['tpl']['mpager'] = mpager($conf['tpl']['pages'] = ($conf['tpl']['page']/5));
		mpqw("UPDATE {$conf['db']['prefix']}search_index SET count=count+1, pages=". ($conf['tpl']['pages'] > 0 ? $conf['tpl']['pages']+1 : 0). " WHERE id=". (int)$conf['tpl']['search']['id']);
		$conf['tpl']['pages'] = ceil($conf['tpl']['pages']);
	}
} $conf['tpl']['search']['num'] = $conf['tpl']['search']['num'] ?: (int)$conf['settings']['search_num'];

?>