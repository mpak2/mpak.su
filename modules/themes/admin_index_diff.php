<?

if($search = get($_GET, 'search')){
	if($base = dirname(dirname(mpopendir("")))){
		if(file_exists($file = "$base/$search")){
			if($folder = mpopendir("themes")){
				$dir = opendir($folder);
		//		include_once "include/class/diff.php";
				while($fn = readdir($dir)){
					if(substr($fn, 0, 1) == ".") continue;
					if(file_exists($f = "$folder/$fn/$search")){
						$tpl['search'][] = array(
							'file1'=>"$folder/<b>$fn</b>/$search",
							'file2'=>$file,
							'html'=>diff::toTable(diff::compareFiles($file, $f)),
						);
					}
				}
			}else{ pre("Директория /themes не найдена"); }
		}else{ mpre("Файл не найден $base/$search"); }
	}
}
