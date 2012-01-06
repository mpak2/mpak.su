<? die;

$refer = "/". implode('/', array_slice(explode('/', urldecode($_SERVER['HTTP_REFERER'])), 3));
if(array_key_exists('null', $_GET) && $_POST){
	if($_POST['fav_id']){
		echo mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_index",
			$wr = array("uid"=>$conf['user']['uid'], "id"=>$_POST['fav_id']), null,
			array("time"=>time(), "fav"=>0)
		);
	}else{
		$url_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_url",
			$wr = array("name"=>$refer), $wr
		);
		echo mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_index",
			$wr = array("uid"=>$conf['user']['uid'], "url_id"=>$url_id),
			$wr + array("time"=>time(), "fav"=>$_POST['fav']),
			array("time"=>time(), "fav"=>$_POST['fav'])
		);
	} exit;
}

$conf['tpl']['index'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index AS id LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_url AS u ON id.url_id=u.id WHERE u.name=\"". mpquot($refer). "\" AND id.uid=". (int)$conf['user']['uid']), 0);

?>