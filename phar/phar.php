<?php

if(!chdir(__DIR__)){ pre("ОШИБКА установки текущей директории");
}elseif(!include($f = "../include/func.php")){ print_r("Не найден файл $f");
}elseif(!$conf['user']['gid'] = array(1=>"Администратор")){ pre("Устанавливаем администратора");
}elseif(!setlocale(LC_CTYPE, 'ru_RU.utf-8')){ pre("ОШИБКА Установка нужной для сборки локали");
}elseif(ini_get('phar.readonly') === '1'){ pre("Установите параметр php.ini phar.readonly On");
}elseif(!$phar = "index.phar"){ pre("Ошибка установки имени файла");
}elseif(file_exists($phar) && !rename($phar, (ini_get('upload_tmp_dir') ?: "/tmp/"). $phar)){ pre("Ошибка переноса старой копии файла во временную директорию");
}elseif(!$p = new Phar($phar, 0, $phar)){ pre("Ошибка создания архива {$phar}");
}elseif(!$tags = system("git describe --tags")){ mpre("ОШИБКА получения тега последней версии");
}elseif(!preg_match("#v(\d+)\.?(\d+)?-(\d+).*#", $tags, $match)){ mpre("ОШИБКА парсинга версии тега");
}elseif(!$version = "v{$match[1]}.{$match[2]}.{$match[3]}"){ mpre("ОШИБКА получения полной версии движка");
}elseif(!$date = date("Y.m.d H:i:s")){ mpre("ОШИБКА получения времени сборки phar архива");
}elseif(!file_put_contents("phar://{$phar}/version.txt", "{$version} ({$date})")){ pre("Ошибка добавления версии движка в архив");
}elseif(!$apr = function($folder, $phar, $p) use(&$apr){ # Создание функции упаковки файлов в архив
		if(is_dir($folder)){// pre("Читаем директорию пофайлово `{$folder}` и добавляем в архив");
			if(!$dir = opendir($folder)){ die(!pre("ОШИБКА открытия директории `{$folder}`"));
			}else{
				while($file = readdir($dir)){
					if($file[0] == '.'){// pre("Пропускаем директории начинающиеся с точки");
					}elseif(!$f = "$folder/$file"){ die(pre("ОШИБКА формирования полного имени к файлу"));
					}else{ $apr($f, $phar, $p); }
				}
			}
		}elseif(!$file = substr($folder, 3)){ pre("ОШИБКА получения пути до файла");
		}elseif(!$phar_file = "phar://{$phar}/". preg_replace("#(\..*+\.)#i", '.', $folder)){ pre("ОШИБКА получения имени файла внутри архива");
		}else{ echo "copy(\"$folder\", \"$phar_file\")\n";
			$p->addFile($folder, $file);
		}
	}){ pre("ОШИБКА создания функции добавления файлов/директорий в архив");
}elseif(!$dolders = array( # Список файлов для загрузки в архив
		'index.php',
		'include/config.php',
		'include/func.php',
		'include/install.php',
//		'include/mail', # Отправка почты на smtp
		'include/class/simple_html_dom.php',
//		'include/idna_convert.class.inc',

		'include/jquery/jquery.iframe-post-form.js',
		'include/jquery/jquery.selection.js',

		'img',
		'modules',
		'themes/zhiraf', 'themes/bootstrap3', 'themes/vk',
//		'include/blocks',
		'include/class',
//		'include/mail',
		'include/jquery/tiny_mce',
		'include/jquery/inputmask', # <!-- [settings:inputmask] --> Скрипты для маск ввода в формы, в разделе тема создана переменная для ввода всех скриптов
//		'include/dhonishow',
		'include/jquery/jquery-lightbox-0.5',
	)){ pre("ОШИБКА сохдания списка загружаемы файлов");
}elseif(array_key_exists(1, $argv) && (!$dolders = [$argv[1]])){ print_r("ОШИБКА добавления дополнительного файла в архив");
}elseif(!array_map(function($file) use($apr, $phar, $p){
		$apr("../{$file}", $phar, $p);
	}, $dolders)){ pre("ОШИБКА перебора всех файлов массива");
}elseif(file_exists($f = "./index.php") && !copy($f, "phar://{$phar}/index.php")){ pre("ОШИБКА добавления файла `{$f}`");
}elseif(!$p->setStub('<?php Phar::mapPhar(); include "phar://". __FILE__. "/index.php"; __HALT_COMPILER(); ?>')){ pre("ОШИБКА установка заголовков архива");
}else{ $p->stopBuffering();
	pre("Архив успешно создан");
}
