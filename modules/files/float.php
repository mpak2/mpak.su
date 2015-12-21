<?

if(array_key_exists("null", $_GET) && $_FILES){
	foreach($_FILES['img']['error'] as $k=>$v){
		$doc_id = mpfdk($tn = "{$conf['db']['prefix']}capcha_img",
			null, array("time"=>time(), "uid"=>$conf['user']['uid'])
		);
		if($fn = mpfn($tn, $k, $doc_id, "img")){
			mpfdk($tn,
				array("id"=>$doc_id), $w = array("name"=>$_FILES['img']['name'][$k], "img"=>$fn), $w
			);
//			mpqw("UPDATE $tn SET name=\"". mpquot($_FILES['img']['name'][$k]). "\", doc=\"". mpquot($fn). "\" WHERE id=". (int)$doc_id);
		}else{
			mpqw("DELETE FROM $tn WHERE id=". (int)$doc_id);
		} 
	} exit("Файлы загружены.");
}

?>
