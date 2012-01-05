<? die;

if($post = $_POST['sumit']){
	if($mpdbf = mpdbf($tn = "{$conf['db']['prefix']}{$arg['modpath']}_index", array('uid'=>$conf['user']['uid'])+$post)){
		mpqw("INSERT INTO $tn SET $mpdbf");
		if(($id = mysql_insert_id()) && ($fn = mpfn($tn, 'img', $id, 'sumit'))){
			mpqw($sql = "UPDATE $tn SET img=\"". mpquot($fn). "\" WHERE id=". (int)$id);
		}
	}
}

$conf['tpl']['sumit'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index"));

?>