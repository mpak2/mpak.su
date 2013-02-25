<? die;

if ($_REQUEST['search_block_num'] || empty($_GET['tabs_id'])){
	$index = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE (id=". (int)$_GET['id']. " OR name=\"". mpquot($_REQUEST['search']). "\") AND num=". (int)$_GET['search_block_num']), 0);
	$index_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_index",
		$w = array("id"=>$index['id']),
		$w += array("name"=>$_REQUEST['search'], "time"=>time(), "num"=>$_GET['search_block_num'])
	);
	$keys_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_keys",
		$w = array("index_id"=>$index_id, "name"=>$_GET['search_key']),
		$w += array("time"=>time())
	);
	$tabs_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_keys_tabs",
		$w = array("keys_id"=>$keys_id, "name"=>serialize($_GET['tab'])),
		$w += array("time"=>time(), "uid"=>$conf['user']['uid'])
	); header("Location: /{$arg['modname']}/tabs_id:". (int)$tabs_id. "/". str_replace("%", "%25", $_REQUEST['search']));
}elseif($_GET['tabs_id']){
		$tpl['search'] = mpql(mpqw("SELECT i.*, k.id AS keys_id, k.name as keys_name, kt.name AS tabs_name
			FROM {$conf['db']['prefix']}{$arg['modpath']}_index AS i
			INNER JOIN {$conf['db']['prefix']}{$arg['modpath']}_keys AS k ON (i.id=k.index_id)
			LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_keys_tabs AS kt ON (k.id=kt.keys_id)
			WHERE kt.id=". (int)$_GET['tabs_id']
		), 0);// mpre($tpl['search']);


		$tpl['http_host'] = mpidn($_SERVER['HTTP_HOST']);
		$conf['settings']['title'] .= " : ". $tpl['search']['name'];
		mpevent("Поиск по сайту", $tpl['search']['name'], $conf['user']['uid']);

		$res = mpql(mpqw($sql = "SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = ".(int)$tpl['search']['num']), 0);
		$tpl['param'] = unserialize($res['param']);

		foreach($tpl['param'] as $k=>$v){
			if ($k == 'search_priority' || $k == 'search_query' || $k == 'search_name') continue;
			$tpl['tab'][$k] = $tpl['param']['search_priority'][$k];
		} arsort($tpl['tab']);

		foreach(explode(' ', $tpl['search']['name']) as $k=>$v){
			$str_replace[$v] = "<span style=font-weight:bold;>{$v}</span>";
		}// mpre(explode(' ', $tpl['search']['search']));
		foreach($tpl['tab'] as $k=>$v){
			$tpl['tab'][ $k ] = $tpl['param']['search_name'][ $k ];
			if(!empty($tpl['search']['keys_name'])){
				if(($k != "{$conf['db']['prefix']}{$tpl['search']['keys_name']}")){
					continue;
				}else{
					$ar = array_slice(explode("_", $k), 0, 2);
					foreach($tpl['param'][ $k ] as $key=>$on){
						if(substr($key, -3) == "_id"){
							$tn = implode("_", array_merge($ar, array(substr($key, 0, -3))));
							$tpl['param']['tab'][ $key ] = array(array("id"=>0))+mpqn(mpqw("SELECT * FROM {$tn}"));
						}
					}
				} $tpl['search']['tab_data'] = unserialize($tpl['search']['tabs_name']);// mpre($tpl['search']['tab_data']);

			}else if(empty($tpl['search']['name'])){ continue; }
			$v = $tpl['param'][$k];
			if($pos = strpos($k, '_', strlen($conf['db']['prefix']))){
				$fn = substr($k, $pos+1);
				$mp = substr($k, strpos($k, '_')+1, $pos-strlen($conf['db']['prefix']));
			}else{
				$fn = substr($k, strpos($k, '_')+1);
				$mp = substr($k, strlen($conf['db']['prefix']));
			}
			$desc = mpqn(mpqw("DESC $k"), 'Field');
			preg_match_all("/{(.*?)}/", $tpl['param']['search_query'][$k], $regs);
			$fields = $where = array();
			if($tpl['search']['tab_data']) foreach($tpl['search']['tab_data'] as $t=>$val){
				$tab .= " `{$t}`=". (int)$val. " AND";
			}// mpre($tab);
			foreach($v as $f=>$z){
				$where[] = "($tab `$f` LIKE \"%". implode("%\" AND `$f` LIKE \"%", explode(' ', mpquot($tpl['search']['name']))). "%\")";
				$fields[] = "`$f`";
			} if($desc['uid']) $fields[] = "`uid`";
			mpql(mpqw($sql = "SELECT SQL_CALC_FOUND_ROWS ". implode(', ', $regs[1]).", ". implode(', ', $fields)." FROM $k WHERE 1 AND ". implode(" OR ", $where). " ORDER BY id DESC LIMIT ". ($_GET['p']*5). ",5"));
			$tpl['page'] += mpql(mpqw("SELECT FOUND_ROWS() AS cnt"), 0, 'cnt');// echo $sql;

			foreach(mpql(mpqw($sql)) as $r){
				$lstring = ''; $zamena = array();
				foreach($r as $t=>$f){
					$zamena['{'.$t.'}'] = str_replace("%", "%25", $f);
					if (isset($tpl['param'][$k][$t])){
						$lstring .= ' '. ($t == "time" ? "<b>". date("d.m.Y", $f). "</b>" : strip_tags($f));
					}
				}// mpre($zamena);
				foreach($regs[1] as $t=>$f){
					$zamena['{'.$f.'}'] = str_replace("%", "%25", $r[$f]);
				}// mpre($zamena);
				if($tpl['search']['name']){
					if(($pos = mb_strpos($lstring, $tpl['search']['name']))){
						$lstring = mb_substr($lstring, max($pos-150, 0), 300, 'utf-8');
					}else{
						$lstring = mb_substr($lstring, 0, 300, 'utf-8');
					}
				}
				if (strlen($lstring)){
					$tpl['result'][] = array(
						'time'=>$r['time'],
						'uid'=>$r['uid'] ? ($users[$r['uid']] ?: $users[$r['uid']] = mpql(mpqw("SELECT id, name FROM {$conf['db']['prefix']}users WHERE id=". (int)$r['uid']), 0)) : "",
						'img'=>($desc['img'] ? "/{$mp}:img/{$r['id']}/tn:{$fn}/w:500/h:600/themes/null/img.jpg" : ''),
						'logo'=>($desc['img'] ? "/{$mp}:img/{$r['id']}/tn:{$fn}/w:100/h:100/themes/null/img.jpg" : ''),
						'title' => $tpl['param']['search_name'][$k],
						'link' => $link = ("http://". $tpl['http_host']. strtr($tpl['param']['search_query'][$k], $zamena)),
						'name'=> $r['name'] ?: $link,
						'text'=> strtr($lstring, $str_replace),
					); //$content = str_ireplace(" $search ", "<span style=background:yellow;>{$search}</span>", $content);
				}
			}
		}// mpmc($key, $tpl);
	if($tpl['page']){ # Обновление информации о поиске
		$tpl['mpager'] = mpager($tpl['pages'] = ($tpl['page']/5));
		mpqw("UPDATE {$conf['db']['prefix']}search_index SET count=count+1, pages=". ($tpl['pages'] > 0 ? $tpl['pages']+1 : 0). " WHERE id=". (int)$tpl['search']['id']);
		$tpl['pages'] = ceil($tpl['pages']);
	}
//	mpre($tpl['param']);
} $tpl['search']['num'] = $tpl['search']['num'] ?: (int)$conf['settings']['search_num'];
