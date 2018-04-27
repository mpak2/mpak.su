<?

if(!is_string($folder = call_user_func(function(){
		if($file = get($_GET, 'q')){ return $file;
		}elseif($file = get($_GET, '')){ return $file;
		}elseif(!$get = array_diff_key($_GET, array_flip(['m']))){ mpre("ОШИБКА исключения адресации системы");
		}elseif(first($get)){ mpre("Ожидается первым параметром тема");
		}elseif(!$theme = first(array_keys($get))){ mpre("ОШИБКА получения темы");
		}elseif(!$file = implode('/', array_keys(array_slice($get, 1)))){// mpre("ОШИБКА формирования части пути до файла");
		}else{ return $file;
		} return "";
	}))){ mpre("ОШИБКА получения пути к фалу");
}elseif(!is_string($theme = (array_search('', array_diff_key($_GET, array_flip(['null']))) ?: get($_GET, 'theme')))){ pre("ОШИБКА расчета темы");
}elseif(!$dir = "themes/".basename($theme ?: get($conf, 'settings', 'theme'))){ pre("ОШИБКА определения директории темы");
}elseif(!$res_name = $dir. "/". strtr($folder, array('..'=>''))){ pre("ОШИБКА расчета пути до файла");
}elseif(!$imgs = ['png', 'jpg', 'jpeg', 'gif']){ mpre("ОШИБКА установки расширений для отображения");
}elseif(is_dir($res_name) && !call_user_func(function($res_name) use($dir, $folder, $imgs){// mpre("Директория", $res_name); # Отображение структуры директории ?>
		<strong><?=$res_name?></strong>
		<ul>
			<? foreach(mpreaddir($res_name) as $file): ?>
				<? if(!$path = "/{$dir}/". ($folder ? "{$folder}/" : ""). "{$file}"): mpre("ОШИБКА получения пути до файла") ?>
				<? elseif(!$ext = last(explode('.', $file))): pre("ОШИБКА определения расширения файла") ?>
				<? elseif(call_user_func(function($imgs) use($ext, $dir, $folder, $file){ ?>
						<? if(false === array_search($ext, $imgs)):// mpre("Не отображаем `{$ext}`", $imgs) ?>
						<? elseif(!$path = "/{$dir}/w:50/h:50/c:1/null/". ($folder ? "{$folder}/" : ""). "{$file}"): mpre("ОШИБКА получения пути до файла") ?>
						<? else: ?>
							<img src="<?=$path?>">
						<? endif; ?>
					<? }, $imgs)): mpre("ОШИБКА отображения логотипа файла") ?>
				<? else: ?>
					<li>
						<a href="<?=$path?>"><?=$file?></a>
					</li>
				<? endif; ?>
			<? endforeach; ?>
		</ul>
	<? }, $res_name)){// mpre($res_name, is_file($res_name));
}elseif((!$_GET += ['null'=>false])){ mpre("Выключаем шаблона сайта");
}elseif(!$ext = last(explode('.', $res_name))){ pre("ОШИБКА определения расширения файла");
}elseif(!include_once(mpopendir('modules/files/defaultmimes.php'))){ mpre("ОШИБКА подключения списка типов файлов");
}elseif(!$type = (get($conf['defaultmimes'], $ext) ?: "text/$ext")){ mpre("ОШИБКА подулючения типа файла по расширению `{$ext}`");
}elseif(!$res = mpopendir($res_name)){ mpre("ОШИБКА файл не найден <b>{$res_name}</b>", $_GET);
	header("HTTP/1.0 404 Not Found");
}elseif(!$image = call_user_func(function($res) use($ext, $type, $imgs){
		if(false === array_search($ext, $imgs)){ return true; // mpre("Расширение файла не изображение");
		}elseif(!array_key_exists('null', $_GET)){ return true; // mpre("У файла не найден признак выключения шаблона");
		}elseif(!$width = get($_GET, 'w')){ return true; mpre("ОШИБКА ширина изображения не установлена");
		}elseif(!$height = get($_GET, 'h')){ return true; mpre("ОШИБКА ширина изображения не установлена");
		}elseif(!is_numeric($crop = get($_GET, 'c') ? 1 : 0)){ mpre("ОШИБКА определения кропа картинки");
		}elseif(!$image = mprs($res, $width, $height, $crop)){ mpre("ОШИБКА получения измененного размера изображения");
		}else{ header("Content-type: {$type}");
			exit($image);
		}
	}, $res)){ mpre("ОШИБКА получения изображения");
}elseif(!$f = fopen($res, "rb")){ mpre("ОШИБКА открытия файла на чтение `{$res}`");
	header("HTTP/1.0 404 Not Found");
}else{ header("Content-type: {$type}");
	while(!feof($f)){ echo fread($f, 256); }
	fclose($f); exit();
}
