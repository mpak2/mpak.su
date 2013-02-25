<? die;

if($_GET['tn']){
	$tn = array(
		($f = 'tasks_comments')=>"_{$f}",
	);
	$sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}{$tn[$_GET['tn']]} WHERE id=".(int)$_GET['id'];
	$f = mpql(mpqw($sql), 0);
}else{
	$file_name = mpopendir("modules/{$arg['modpath']}/img/". basename($_GET['']));
} echo mpfile($f[ ($_GET['fn'] ?: "file") ], $f['name']);

?>