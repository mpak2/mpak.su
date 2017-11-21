<br/><h1>Просмотр содержимого Cron</h1><br/>
<?
	$hash = hash('crc32',$_SERVER['DOCUMENT_ROOT']);	
	$cronText = shell_exec("crontab -l");	
	preg_match_all("@.*#MPCron-{$hash}-\d+-\w+\s[^\n]+@iu",$cronText,$matches);
	echo "<pre>";
	foreach(get($matches,0)?:[] as $f){
		echo $f;
		echo "<br/>";
		echo "<br/>";
	}
	echo "</pre>";
?>