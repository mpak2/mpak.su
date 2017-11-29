<?

if(($download = get($_GET, 'download')) && (!call_user_func(function($download){
		if(!$data = file_get_contents($download)){ mpre("Ошибка получения содержимого файла `{$download}`");
		}elseif(!$basename = basename($download)){ mpre("Ошибка определения имени файла");
		}else{
			header('Content-Type: application/force-download');
			header("Content-Disposition: attachment; filename=\"{$basename}\"");
			header('Content-Transfer-Encoding: binary');
			exit($data);
		}
	}, $download))){ mpre("ОШибка скачивания файла");
}elseif(!$search = get($_GET, 'search')){
}elseif(!$base = mpopendir("index.phar")){ mpre("Ошибка базового пути");
}elseif((!file_exists($file = "phar://$base/$search")) &0){ mpre("Файл не найден $base/$search");
}elseif(!$folder = mpopendir("themes")){ mpre("Директория /themes не найдена");
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
				'path1'=>"{$dir}/{$search}",
				'file2'=>"phar://{$base}/<b>{$search}</b>",// strtr($file, ["{$search}"=>"</b>{$search}</b>"]),
				'path2'=>"phar://{$base}/{$search}",
				'html'=>diff::toTable(diff::compareFiles($file, $f)),
			);
		}
	}
}
