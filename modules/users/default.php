<? die;

if($_GET['id'] == 0){
	header("Location: /{$arg['modname']}". ($arg['fn'] == 'index' ? "" : ":{$arg['fn']}"). "/". (int)$conf['user']['uid']. ($_GET['theme'] ? "/theme:{$_GET['theme']}" : ''));
}

?>