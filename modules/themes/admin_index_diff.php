<?

if(!$search = get($_GET, 'search')){
}elseif(!$base = mpopendir("index.phar")){ mpre("Ошибка базового пути");
}elseif((!file_exists($file = "phar://$base/$search")) &0){ mpre("Файл не найден $base/$search");
}elseif(!$folder = mpopendir("themes")){ mpre("Директория /themes не найдена");
//}elseif(!$dir = opendir($folder)){ mpre("Ошибка чтения директории");
//}elseif(!$DIR = ){ mpre("В темах не найдено директорий");
}elseif(!$DIR = call_user_func(function($DIR) use($folder){
		foreach($DIR as $k=>$dir){
			$DIR[$k] = "{$folder}/{$dir}";
		} $DIR[] = dirname($folder);
		return $DIR;
	}, mpreaddir("themes"))){ mpre("Ошибка получения списка директорий");
}else{// mpre($DIR);
	foreach($DIR as $dir){ // 
		if(!file_exists($f = "{$dir}/{$search}") && ($dir != dirname($folder)) || !empty($exists)){// mpre("Файл не найден `<b>{$f}</b>`");
		
		}else{ $exists = true;
			$tpl['search'][] = array(
				'file1'=>"{$dir}/<b>{$search}</b>",
				'file2'=>"phar://{$base}/<b>{$search}</b>",// strtr($file, ["{$search}"=>"</b>{$search}</b>"]),
				'html'=>diff::toTable(diff::compareFiles($file, $f)),
			);
		}
	}
}
