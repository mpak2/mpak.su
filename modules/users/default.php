<? die;

if($_GET['id'] == 0){
	if($conf['user']['uid'] > 0){
		header("Location: /{$arg['modname']}". ($arg['fn'] == 'index' ? "" : ":{$arg['fn']}"). "/". (int)$conf['user']['uid']. ($_GET['theme'] ? "/theme:{$_GET['theme']}" : ''));
	}else{
		exit(header("Location:/{$arg['modname']}:login"));
	}
}
