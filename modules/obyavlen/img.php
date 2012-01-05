<? die;

$sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_dop as d, {$conf['db']['prefix']}{$arg['modpath']}_pole as p WHERE p.id=d.pid AND d.id = ".(int)$_GET['id'];

if (count($img = mpql(mpqw($sql))) == 1){
	$ext = substr($img[0]['val'], -4);
	if($ext == '.gif') $src = imagecreatefromgif(mpopendir("include")."/{$img[0]['val']}");
	if ($ext == '.jpg') $src = imagecreatefromjpeg(mpopendir("include")."/{$img[0]['val']}");
	if ($ext == '.png') $src = imagecreatefrompng(mpopendir("include")."/{$img[0]['val']}");
	if ($src){
//		echo "123";
		$max_width = (int)$_GET['w'] ? (int)$_GET['w'] : 100;
		$max_height = (int)$_GET['h'] ? (int)$_GET['h'] : 100;
		list($width, $height) = getimagesize(mpopendir("include")."/{$img[0]['val']}");

//		echo "width = $width; height = $height ".mpopendir("include")."/{$img[0]['img']}";
		$x_ratio = $max_width / $width;
		$y_ratio = $max_height / $height;
		if ( ($width <= $max_width) && ($height <= $max_height) ){
			$tn_width = $width;
			$tn_height = $height;
		}elseif (($x_ratio * $height) < $max_height){
			$tn_height = ceil($x_ratio * $height);
			$tn_width = $max_width;
		}else{
			$tn_width = ceil($y_ratio * $width);
			$tn_height = $max_height;
		}
		$dst = imagecreatetruecolor($tn_width,$tn_height);
		imagecopyresampled($dst, $src, 0, 0, 0, 0,$tn_width,$tn_height,$width,$height);
		header("Content-type: image/png");
		header("Cache-Control: max-age=2592000");
		header("Pragma: no-cache");
		ImagePng($dst, null, -1);
		ImageDestroy($src);
		ImageDestroy($dst);
		exit;
	}else{
		$src = imagecreate (150, 30); /* создание пустого изображения */
		$bgc = imagecolorallocate ($src, 255, 255, 255);
		$tc = imagecolorallocate ($src, 0, 0, 0);
		imagefilledrectangle ($src, 0, 0, 150, 30, $bgc);
		header("Content-type: image/jpeg");
		imagestring ($src, 1, 5, 5, "Error loading", $tc);
		ImageJpeg($src);
		exit;
	}
}



?>