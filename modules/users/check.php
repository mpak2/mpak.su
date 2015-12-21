<?

if(mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}users WHERE name=\"". mpquot($_POST['name']). "\""), 0)){
	exit("true");
}else{
	exit("false");
}


?>
