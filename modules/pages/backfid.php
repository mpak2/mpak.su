<?

if($_POST['text']){
	if($_POST['backfid_id']){
		$answers_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_answers",
			array("id"=>0),
			array("time"=>time(), "uid"=>$conf['user']['uid'], 'backfid_id'=>$_POST['backfid_id'], "text"=>$_POST['text'])
		);
		$conf['tpl']['backfid'][ $_POST['backfid_id'] ] = array("id"=>$_POST['backfid_id']);
		$conf['tpl']['answers'][ $_POST['backfid_id'] ][ $answers_id ] = array(
			"id"=>$answers_id, 'time'=>time(), 'backfid_id'=>$_POST['backfid_id'], "text"=>$_POST['text'], "uid"=>$conf['user']['uid'], "fm"=>$conf['user']['fm'], "im"=>$conf['user']['im'], "ot"=>$conf['user']['ot'],
		);// mpre($conf['tpl']['answers']);
	}else{
		$backfid_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_backfid",
			array("id"=>0),
			$ar = array("time"=>time(), 'index_id'=>(int)$_POST['index_id'], "uid"=>$conf['user']['uid'], "text"=>$_POST['text'])
		); $conf['tpl']['backfid'] = array($backfid_id=>$ar+array("id"=>$backfid_id, "fm"=>$conf['user']['fm'], "im"=>$conf['user']['im']));
	}
}else{
	$conf['tpl']['backfid'] = mpqn(mpqw("SELECT SQL_CALC_FOUND_ROWS b.*, u.fm, u.im, COUNT(DISTINCT a.id) AS cnt FROM {$conf['db']['prefix']}{$arg['modpath']}_backfid AS b LEFT JOIN {$conf['db']['prefix']}users AS u ON (b.uid=u.id) LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_answers AS a ON (b.id=a.backfid_id) WHERE b.index_id=". (int)$_GET['id']. " GROUP BY b.id ORDER BY id DESC LIMIT ". ($_GET['p']*10). ",10"));

	$_SERVER['REQUEST_URI'] = "/{$arg['modpath']}/{$_GET['id']}";
	$conf['tpl']['mpager'] = mpager(mpql(mpqw("SELECT FOUND_ROWS()/10 AS cnt"), 0, 'cnt'));

	$conf['tpl']['answers'] = mpqn(mpqw($sql = "SELECT a.*, u.fm, u.im FROM {$conf['db']['prefix']}{$arg['modpath']}_answers AS a LEFT JOIN {$conf['db']['prefix']}users AS u ON (a.uid=u.id) WHERE backfid_id IN (". implode(",", array_keys($conf['tpl']['backfid'] ?: array(0))). ")"), 'backfid_id', 'id');// echo $sql;
}

$conf['tpl']['page'] = mpql(mpqw("SELECT p.id, p.name, p.uid, u.fm, u.im FROM {$conf['db']['prefix']}pages_index AS p LEFT JOIN {$conf['db']['prefix']}users AS u ON (p.uid=u.id) WHERE p.id=". (int)$_GET['id']), 0);

?>
