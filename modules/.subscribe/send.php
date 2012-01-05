<? die;

if($arg['access'] >= 5){
	if($_POST['uid']){
		if($_POST['subject'] && $_POST['subject']){
			$user = mpql(mpqw("SELECT id, email FROM {$conf['db']['prefix']}users WHERE id=". (int)$_POST['uid']), 0);
			mpmail($user['email'], $_POST['subject'], $_POST['text']);
			echo $user['id'];
		} exit;
	}

	$conf['tpl']['users'] = mpql(mpqw("SELECT id, name FROM {$conf['db']['prefix']}users"));
}else{
	exit;
}

?>