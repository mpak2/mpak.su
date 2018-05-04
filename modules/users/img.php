<?

if(!is_array($index = (array_key_exists('tn', $_GET) ? rb("users-{$_GET['tn']}", "id", get($_GET, 'id')) : []))){ mpre("ОШИБКА выборки пользователя");
}elseif(!is_numeric($width = (array_key_exists("w", $_GET) ? $_GET['w'] : 0))){ mpre("ОШИБКА расчета ширины изображения");
}elseif(!is_numeric($height = (array_key_exists("h", $_GET) ? $_GET['h'] : 0))){ mpre("ОШИБКА расчета высоты изображения");
}elseif(!is_numeric($crop = (array_key_exists("c", $_GET) ? $_GET['c'] : 0))){ mpre("ОШИБКА расчета кропа");
}elseif(!$file = call_user_func(function($index) use($arg, $conf){ # Имя файла в из возможных вариантов. Прописано у пользователя, картинка гостя, дефолтная картинка
		if($img = get($index, 'img')){ return "include/{$img}"; mpre("Пользователь");
		}elseif(!array_key_exists('id', $_GET) && ($fn = get($_GET, ''))){ return "modules/{$arg['modpath']}/img/{$fn}"; mpre("Изображение");
		}elseif(!$guest = get($conf, 'settings', 'default_usr')){ die(!mpre("ОШИБКА имя гостя в настройках не задано"));
		}elseif(!is_array($index = rb("users-", 'name', "[{$guest}]"))){ die(!mpre("ОШИБКА выборки гостя"));
		}elseif($guest_img = get($index, 'img')){ return "include/{$guest_img}"; mpre("Изображение гостя");
		}else{ return "modules/{$arg['modpath']}/img/unknown.png"; }
	}, $index)){ mpre("ОШИБКА получения имени файла");
//}elseif(true){ die(!mpre($file));
}elseif(!$file_name = mpopendir($file)){ mpre("Полный путь до изображения не найден `{$file}`");
}elseif(!$ext = last(explode('.', $file_name))){ die(!mpre("ОШИБКА расчета расширения"));
}else{ header ("Content-type: image/". last(explode('.', $file_name)));
	echo(mprs($file_name, $width, $height, $crop));
	header("Cache-Control: max-age=". (get($conf, 'settings', "themes_expires") ?: 86400). ", public");
	header('Last-Modified: '. date("r"));
	exit(header("Expires: ". gmdate("r", time()+(get($conf, 'settings', "themes_expires") ?: 86400))));
}

