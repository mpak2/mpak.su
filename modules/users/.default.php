<? die;

if(array_key_exists('id', $_GET) && $_GET['id'] == 0){
	header("Location: /{$arg['modname']}". ($arg['fn'] == 'index' ? "" : ":{$arg['fn']}"). "/". (int)$conf['user']['uid']. ($_GET['theme'] ? "/theme:{$_GET['theme']}" : ''));
}

?>