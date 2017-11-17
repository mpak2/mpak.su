<?
if(!$uri = implode("/", $ar = array_filter(explode("/", $_SERVER['REQUEST_URI'])))){ mpre("Путь до файла не найден");
}elseif(!$uri = first(explode("?", $uri))){ mpre("Путь отделения динамической части адреса");
}elseif(!$path = mpopendir($uri)){ pre("Ошибка определения пути к файлу <b>{$uri}</b>");
	header("HTTP/1.1 404 Not Found");
}elseif(!$keys = array_keys($ar = explode('.', $path))){ mpre("Ошибка составления массива элементов пути");
}elseif(!$ext = strtolower($ar[max($keys)])){ mpre("Ошибка поиска расширения файла");
}elseif(!array_key_exists($ext, $conf['defaultmimes'])){ mpre("Тип файла не определен");
}elseif(!file_exists($path)){ mpre("Файл не найден");
	header("HTTP/1.1 404 Not Found");
}else{ # Вывод содержимого файла
	if(!ob_get_length()){
		header("Content-type: {$conf['defaultmimes'][$ext]}");
	}

	$f = fopen($path, "rb");
	while (!feof($f)) {
		echo fread($f, 256);
	}
}