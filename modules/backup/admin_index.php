<?

if($_GET['backup']){
	include(mpopendir("include/config.php"));
	$file_name = "{$conf['db']['name']}-". date("Y.m.d_H:i:s"). ".sql";
	if($error = `mysqldump -u {$conf['db']['login']} -p{$conf['db']['pass']} {$conf['db']['name']} > /tmp/$file_name`){
		echo $error;
	}else{
		header("Content-Disposition: attachment; filename=\"$file_name\"");
		header("Content-Length: ".filesize("/tmp/$file_name"));
		$handle = fopen("/tmp/$file_name", 'rb');
		while (!feof($handle)){
			echo fread($handle, 4096);
			ob_flush();
			flush();
		} fclose($handle);
		unlink("/tmp/$file_name"); exit;
	}
}

?>
