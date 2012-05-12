<? die;

if(array_search($_GET['tn'] ?: $_POST['tn'], explode(',', $conf['settings'][$arg['modpath']. "_img_tn"])) === false) exit("Ошибка доступа к данным");

if($_FILES){
	if(!empty($_FILES[$f = 'img'])){
		foreach($_FILES['img']['name'] as $k=>$v){
			$img_id = mpfdk($tn = "{$conf['db']['prefix']}". mpquot($_POST['tn']),
				null, array("time"=>time(), "index_id"=>$index_id, "name"=>$v, 'uid'=>$conf['user']['uid'])
			);
			if($fn = mpfn($tn, $f, $img_id, $k)){
				mpqw($sql = "UPDATE {$tn} SET {$f}=\"". mpquot($fn). "\" WHERE id=". (int)$img_id);
				$tpl['img'] = array($img_id=>array("id"=>$img_id, "time"=>time()));
			}else{
				mpqw($sql = "DELETE FROM {$tn} WHERE id=". (int)$img_id);
			}
		}
	} $tpl['arg'] = explode("_", $_POST['tn']);
}elseif($_POST['del']){
	mpqw("DELETE FROM {$conf['db']['prefix']}". mpquot($_GET['tn']). " WHERE uid=". (int)$conf['user']['uid']. " AND id=". (int)$_POST['del']);
	exit($_POST['del']);
}else{
	$tpl['img'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}". mpquot($_POST['tn']. " WHERE uid=". (int)$conf['user']['uid'])));
	$tpl['arg'] = explode("_", $_POST['tn']);
}

?>