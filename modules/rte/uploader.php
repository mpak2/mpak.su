<? die;

$file = current($_FILES['img'] = $_FILES);

$arr = array(
	'error' => $file['error'],
	'file' => "{$file['name']}",
	'tmpfile' => $file['tmp_name'],
	'size' => $file['size'],
);

if($file['error'] === 0){
	$tn = "{$conf['db']['prefix']}{$arg['modpath']}_index";
	mpqw("INSERT INTO $tn SET uid=".(int)$conf['user']['uid']);
	if(($id = mysql_insert_id()) && ($fn = mpfn($tn, 'img', $id, array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp', 'image/gif'=>'.gif'), $file))){
		mpqw("UPDATE $tn SET img=\"$fn\" WHERE id=".(int)$id);
		$arr['file'] = "/{$arg['modpath']}:img/tn:index/" .(int)$id ."/null/img.jpg";
	}
}

if(function_exists('json_encode')){
	echo json_encode($arr);
}else{
	$result = array();
	foreach($arr as $key => $val) {
		$val = (is_bool($val)) ? ($val ? 'true' : 'false') : $val;
		$result[] = "'{$key}':'{$val}'";
	}
	echo '{' . implode(',', $result) . '}';
}

?>