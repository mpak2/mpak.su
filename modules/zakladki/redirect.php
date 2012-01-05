<? die;

$sql = "SELECT * FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_url WHERE uid={$GLOBALS['conf']['user']['uid']} AND id=".(int)$_GET['id'];
if ($url = mpql(mpqw($sql), 0)){
	header("Location: {$url['name']}");
	mpqw("UPDATE {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_url SET count=count+1, last=".time()." WHERE id={$url['id']}");
}else{
	header("Location: /");
}
?>