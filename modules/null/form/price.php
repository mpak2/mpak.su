<? die;

if(array_key_exists('null', $_GET) && $_POST){
	if($_FILES){
		$fs = array('time'=>time(), 'uid'=>$conf['user']['uid'], 'name'=>$_FILES[$f = 'file']['name']);
		if($mpdbf = mpdbf($tn = "{$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}", $fs+$_POST)){
			mpqw($sql = "INSERT INTO $tn SET $mpdbf");// echo $sql;
			if(($id = mysql_insert_id()) && $fn = mpfn($tn, $f, $id, '', array('text/csv'=>'.csv'))){
				mpqw($sql = "UPDATE $tn SET `$f`=\"". mpquot($fn). "\" WHERE id=". (int)$id);// echo $sql;
			}
			$conf['tpl']['doc'] = array($id=>array('id'=>$id)+$fs+$_POST);
		}
	}elseif($_POST['del_id']){
		mpqw("DELETE FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} WHERE uid=". (int)$conf['user']['uid']. " AND id=". (int)$_POST['del_id']);
		echo 0; exit;
	}
}else{
	$conf['tpl']['doc'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} WHERE uid=". (int)$conf['user']['uid']));
}

?>