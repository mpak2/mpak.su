<?

if(array_key_exists("null", $_GET) && ($file = $_GET[''])){
	if($dir = mpopendir("modules/{$arg['modpath']}/docs/")){
		if(is_file($f = "{$dir}{$file}")){
			if(true){
				header("Content-disposition: filename='". array_pop(explode("/", $f)). "'");
				header('Content-Type: application/'. array_pop(array_slice(explode(".", $f), -1, 1)));
				exit(readfile(strtr($f, array("//"=>"/"))));
			}else{
//				exit(file_get_contents($f));
				exit(mpre($f));
			}
		}else{ exit(mpre("Файл {$dir}/{$file} не найден")); }
	}else{ exit(mpre("Директория docs не найдена")); }
	exit(mpre($_GET));
}