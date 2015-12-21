<?

if($_POST['reg'] && $_POST['text'] && array_key_exists('null', $_GET)){
	preg_match($_POST['reg'], $_POST['text'], $reg);
	print_r($reg); exit;
}

?>
