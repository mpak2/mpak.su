<?

//if(!$file = (get($_GET, '') ?: get($_GET, 'q'))){ pre("Файл шаблона не задан", $_GET);
if(!$file = call_user_func(function(){
		if($file = get($_GET, 'q')){ return $file;
		}elseif($file = get($_GET, '')){ return $file;
		}elseif(!$get = array_diff_key($_GET, array_flip(['m']))){ mpre("ОШИБКА исключения адресации системы");
		}elseif(first($get)){ mpre("Ожидается первым параметром тема");
		}elseif(!$theme = first(array_keys($get))){ mpre("ОШИБКА получения темы");
		}elseif(!$file = implode('/', array_keys(array_slice($get, 1)))){ mpre("ОШИБКА формирования части пути до файла");
		}else{ return $file; }
	})){ mpre("ОШИБКА получения пути к фалу");
}elseif(!is_string($theme = (array_search('', array_diff_key($_GET, array_flip(['null']))) ?: get($_GET, 'theme')))){ pre("ОШИБКА расчета темы");
}elseif(!$dir = "themes/".basename($theme ?: get($conf, 'settings', 'theme'))){ pre("ОШИБКА определения директории темы");
}elseif(!$res_name = $dir ."/".strtr($file, array('..'=>''))){ pre("ОШИБКА расчета пути до файла");
}elseif(!$_GET += ['null'=>false]){ mpre("Выключаем шаблона сайта");
}elseif(!$ext = last(explode('.', $file))){ pre("ОШИБКА определения расширения файла");
}elseif(!include_once(mpopendir('modules/files/defaultmimes.php'))){ mpre("ОШИБКА подключения списка типов файлов");
}elseif(!$type = (get($conf['defaultmimes'], $ext) ?: "text/$ext")){ mpre("ОШИБКА подулючения типа файла по расширению `{$ext}`");
}elseif(!$res = mpopendir($res_name)){ mpre("ОШИБКА расчета полного пути до файла <b>{$res_name}</b>", $_GET);
	header("HTTP/1.0 404 Not Found");
}else{ header("Content-type: {$type}");
			if($f = fopen($res, "rb")){
				while(!feof($f)) {
					echo fread($f, 256);
				}
			} exit();
} header("HTTP/1.0 404 Not Found");
