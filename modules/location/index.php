<? die;

if($location = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE id=". (int)$_GET['id']), 0)){
	mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']}_index SET count=count+1 WHERE id=". (int)$location['id']);
	mpevent("Перенаправление по ссылке", $location['href']);
	header("Location: ". mpidn($location['href'], 1). $_GET['']);
	exit;
}

?>