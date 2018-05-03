<h2>Пересечения</h2>
<? if(!$folder = "phar://index.phar"): mpre("ОШИБКА установки корневой директории") ?>
<? elseif(!$readdir = function($path = "") use(&$readdir, $folder){ # Получение списка файлов директории
		if(!$dir_name = "{$folder}/{$path}"){ mpre("ОШИБКА получения полного пути до директории");
		}elseif(!$dir = opendir($dir_name)){ mpre("ОШИБКА чтения списка файлов директории `{$folder}`");
		}elseif(!$files = call_user_func(function($dir, $files = []) use($folder){
				while($file = readdir($dir)){ $files[] = $file; } return $files;
			}, $dir)){ mpre("ОШИБКА получения списка файлов директории `{$folder}`");
		}elseif(!$files = array_map(function($file) use($folder, $readdir, $path){ # Получения списка файлов поддиректорий;
				if(!$file_name = ($path ? "{$path}/" : ""). "{$file}"){ mpre("ОШИБКА получения полного пути до файла");
				}elseif(!$dir_name = "{$folder}/{$file_name}"){ mpre("ОШИБКА получения имени директории");
				}elseif(is_dir($dir_name)){ return $readdir($file_name);
				}else{ return $file; }
			}, $files)){ mpre("ОШИБКА получения списка файлов поддиректорий");
		}elseif(!$files = array_filter(array_map(function($file, $key) use($path){ # Убираем файлы если их нет в файловой системе
				if("array" == gettype($file)){ return $file; // mpre("Поддиректория");
				}elseif(!$file_name = "{$path}/{$file}"){ mpre("ОШИБКА получения относительного пути до файла");
				}elseif(!file_exists($file_name)){ return null;
				}else{ return $file; }
			}, $files, array_keys($files)))){ return $files; mpre("ОШИБКА получения списка файлов");
		}elseif(!is_string($basename = (basename($path) ?: ""))){ mpre("ОШИБКА получения директории");
		}elseif(!$files = array_merge([$basename], $files)){ mpre("[0] элементом всегда идет название директории");
		}else{ return $files; }
	}): mpre("ОШИБКА получения списка файлов") ?>
<? elseif(!$files = $readdir()): mpre("ОШИБКА получения списка файлов директории `{$folder}`") ?>
<? elseif(!$tree = function($files) use(&$tree, $folder){ ?>
		<? if(!$dir_name = (get($files, 0) ?: $folder)): mpre("ОШИБКА поулчения имени директории") ?>
		<? elseif(!$list = array_slice($files, 1)): mpre("ОШИБКА получения списка файлов директории") ?>
		<? /*elseif(!is_array(array_map(function($file) use($tree){ ?>
				<? if(is_array($file)): $tree($file);  ?>
				<? else: ?>
					<li><?=$file?></li>
				<? endif; ?>
			<? }, $list))): mpre("ОШИБКА отображения списка файлов")*/ ?>
		<? else:// mpre($name) ?>
			<strong><?=$dir_name?></strong>
			<ul>
				<? foreach($list as $file): ?>
					<? if(is_array($file)): $tree($file) ?>
					<? else: ?>
						<li><?=$file?></li>
					<? endif; ?>
				<? endforeach; ?>
			</ul>
			<? return $files; ?>
		<? endif; ?>
	<? }): mpre("ОШИБКА создания функции отображения дерева") ?>
<? elseif(!$files = $tree($files)): mpre("ОШИБКА отображения дерева") ?>
<? else:// mpre($folder, $files) ?>
<? endif; ?>
