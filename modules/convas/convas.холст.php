<? die;

if(array_key_exists("null", $_GET) && $_POST){
	if($_POST['base64']){
		if($data = base64_decode(array_pop(explode(",", $_POST['base64'])))){
			if($img_id = mpfdk($tn = "{$conf['db']['prefix']}{$arg['modpath']}_index", array("id"=>$_POST['index_id'], "uid"=>$conf['user']['uid']), array("time"=>time(), "uid"=>$conf['user']['uid']))){
				$fn = "images/{$conf['db']['prefix']}{$arg['modpath']}_index_img_img_". (int)$img_id. ".png";
				if(file_put_contents(mpopendir("include/"). $fn, $data)){
					$img_id = mpfdk($tn, array("id"=>$img_id), null, array("img"=>$fn));
				}
			}
		} exit(''. $img_id);
	}else if($_FILES && $prx = explode("_", $fn = mpfn("{$conf['db']['prefix']}{$arg['modpath']}_index_img", "img"))){
		exit("". (int)$prx[5]);
	}else{
		exit($fn);
	}
}else{
	$tpl['index'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE uid=". (int)$conf['user']['uid']. " ORDER BY id DESC"));
}

?>