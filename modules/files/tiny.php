<? die;

if($_REQUEST['prev']){
	$file = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_files WHERE id<". (int)$_REQUEST['prev']. " ORDER BY id DESC LIMIT 1"), 0);
}else if(array_key_exists('next', $_REQUEST)){
	$file = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_files WHERE id>". (int)$_REQUEST['next']. " ORDER BY id LIMIT 1"), 0);
} echo json_encode($file);