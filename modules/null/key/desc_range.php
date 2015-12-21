<?

if(array_key_exists('null', $_GET) && $_POST['desc_id'] && $_POST['range_id']){
	$tn = "{$conf['db']['prefix']}{$arg['modpath']}_desc_range";
	if($_POST['checked'] == 'true'){
		$mpdbf = mpdbf($tn, $_POST);
		mpqw("INSERT INTO $tn SET $mpdbf");
		echo mysql_insert_id();
	}else{
		mpqw("DELETE FROM $tn WHERE desc_id=". (int)$_POST['desc_id']. " AND range_id=". (int)$_POST['range_id']);
		echo 0;
	} exit;
}

$conf['tpl']['range'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_range"));

$conf['tpl']['desc'] = mpqn(mpqw("SELECT SQL_CALC_FOUND_ROWS * FROM {$conf['db']['prefix']}{$arg['modpath']}_desc LIMIT ". ($_GET['p']*10). ",10"));

$conf['tpl']['mpager'] = mpager(mpql(mpqw("SELECT FOUND_ROWS()/10 AS cnt"), 0, 'cnt'));

$conf['tpl']['desc_range'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_desc_range WHERE desc_id IN(". implode(',', array_keys($conf['tpl']['desc'])). ")"), 'desc_id', 'range_id');

?>
