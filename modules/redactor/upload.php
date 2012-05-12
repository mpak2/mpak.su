<? die;

if($_FILES){
	if($tpl['upload']['id'] = mpfdk($tn = "{$conf['db']['prefix']}{$arg['modpath']}_index", null, array("time"=>time(), "uid"=>$conf['user']['uid']))){
		if($fn = mpfn($tn, "file", $tpl['upload']['id'])){
			mpfdk($tn = "{$conf['db']['prefix']}{$arg['modpath']}_index", array("id"=>$tpl['upload']['id']), null, array("file"=>$fn, 'name'=>$_FILES['file']['name']));
		}else{
			mpqw("DELETE FROM {$tn} WHERE id=". (int)$tpl['upload']['id']);
		}
	}
}

?>