<? die;

if($_GET['id']){
	$conf['tpl'][$arg['fn']] = mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE id=".(int)$_GET['id']. ($arg['access'] < 5 ? " AND uid=".(int)$conf['user']['uid'] : '')), 0);
}elseif($_FILES){
	mpqw("INSERT INTO ". ($tn = "{$conf['db']['prefix']}{$arg['modpath']}_index"). " SET time=". time(). ", uid=".(int)$conf['user']['uid']);
	if($fn = mpfn($tn, 'img', $id = mysql_insert_id())){
		mpqw("UPDATE $tn SET img=\"". mpquot($fn). "\" WHERE id=".(int)$id);
		header("Location: /{$arg['modpath']}:{$arg['fn']}/$id");
	}else{
		mpqw("DELETE FROM $tn WHERE id=".(int)$_GET['id']);
	}
}else{
	header("Location: /{$arg['modpath']}:edit/30");
}

?>