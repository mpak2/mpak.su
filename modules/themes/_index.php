<?

if(isset($_GET[''])) $_GET['q'] = $_GET[''];
if(isset($_GET['q'])){
	$keys = array_keys($ar = explode('.', $_GET['q']));
	$ext = $ar[max($keys)];
	$tn = "themes/".basename($_GET['theme'] ? $_GET['theme'] : $conf['settings']['theme']);
	$res_name = $tn ."/".strtr($_GET['q'], array('..'=>''));
	if(!($res = mpopendir($res_name))){
		if(get($conf, 'settings', 'themes_file_not_exists_event')){
			if($themes_resources = $conf['settings']['themes_resources']){
				if(file_exists($dir = mpopendir($tn). "/". dirname($_GET['q'])) || mkdir($dir, 0777, true)){
					if(copy(($dst = "{$themes_resources}/{$_GET['q']}"), $fl = $tn. "/". $_GET['q'])){
						mpevent("Закачка файла из внешнего ресурса", $res_name);
						exit(header("Location: {$_GET['q']}"));
					}else{
						header("HTTP/1.0 404 Not Found");
						exit("Ошибка скачивания {$dst} в {$fl}");
					}
				}else{ mpre("Ошибка создания директории {$dir} в теме {$_GET['theme']}"); }
			}
		} $error = mpevent("Ресурс в теме не найден", preg_replace("#\/(стр|p)\:[0-9]+#", "", first(explode("?", urldecode($_SERVER['REQUEST_URI'])))));
	}else if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && (strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= ($filectime = filectime($res)))){
		exit(header('HTTP/1.0 304 Not Modified'));
	}else if($filectime = filectime($res)){
//		header('Last-Modified: '. gmdate("r", $filectime));
//		header('Expires: '.gmdate('r', time() + 86400*10));
		if(!ob_get_length()){
			include_once(mpopendir('modules/files/defaultmimes.php'));
			header("Content-type: ".(get($conf['defaultmimes'],$ext)?:"text/$ext"));
		} if((array_key_exists("w", $_GET) || array_key_exists("h", $_GET)) && array_search($ext, array(1=>'jpg', 'png', 'gif'))){
			echo mprs($res, (array_key_exists("w", $_GET) ? $_GET['w'] : 0), (array_key_exists("h", $_GET) ? $_GET['h'] : 0), (array_key_exists("c", $_GET) ? $_GET['c'] : 0));
		}else{
			if($f = fopen($res, "rb")){
				while(!feof($f)) {
					echo fread($f, 256);
				}
			}
		}
	}
}
