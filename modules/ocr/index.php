<? die;

if($_FILES){
	if($fn = mpfn($tn = "{$conf['db']['prefix']}{$arg['modpath']}_index", "img", $id = mpfdk($tn, null, array("uid"=>$conf['user']['uid'])))){
		mpfdk($tn, array("id"=>$id), null, array("img"=>$fn));
	}else{
		mpqw("DELETE FROM $tn WHERE id=". (int)$id);
	} echo $id; exit;
}

?>