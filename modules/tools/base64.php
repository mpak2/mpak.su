<? die;

if($_FILES['base64']['error'] === 0){
	$conf['tpl']['base64'] = "\n". chunk_split(base64_encode(file_get_contents($_FILES['base64']['tmp_name'])));
}

?>