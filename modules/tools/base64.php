<?

if($_FILES['base64']['error'] === 0){
	$conf['tpl']['base64'] = base64_encode(file_get_contents($_FILES['base64']['tmp_name']));
}else if($_POST['url']){
	if(strpos($_POST['url'], "http://") === 0){
		$conf['tpl']['base64'] = base64_encode(file_get_contents($_POST['url']));
	}else{
		echo "<span style=color:red;>Не корректный адрес изображения</span>";
	}
}

?>
