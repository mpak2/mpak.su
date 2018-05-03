<h2>Пересечения</h2>
<? if(!$folder = "phar://index.phar"): mpre("ОШИБКА установки корневой директории") ?>
<? elseif(!$readdir = function($path = "", $folder) use(&$readdir, $conf){ # Получение списка файлов директории
		if(!$dir_name = "{$folder}/{$path}"){ mpre("ОШИБКА получения полного пути до директории");
		}elseif(!$dir = opendir($dir_name)){ mpre("ОШИБКА чтения списка файлов директории `{$folder}`");
		}elseif(!$files = call_user_func(function($dir, $files = []) use($folder){
				while($file = readdir($dir)){ $files[] = $file; } return $files;
			}, $dir)){ mpre("ОШИБКА получения списка файлов директории `{$folder}`");
		}elseif(!is_array($files = array_filter(array_map(function($file) use($folder, $readdir, $path, $conf){ # Получения списка файлов поддиректорий;
				if("." == substr($file, 0, 1)){// mpre("Файл начинается с точки");
				}elseif(!$file_name = ($path ? "{$path}/" : ""). "{$file}"){ mpre("ОШИБКА получения полного пути до файла");
				}elseif(!$intersect = fk('intersect', $w = ['href'=>$file_name], $w += ['hide'=>1, 'admin-access'=>1])){ mpre("ОШИБКА добавления файла в таблицу `{$file_name}`");
				}elseif(!$dir_name = "{$folder}/{$file_name}"){ mpre("ОШИБКА получения имени директории");
				}elseif(is_dir($dir_name)){
					if(!get($intersect, 'admin-access')){ return [$file]; // mpre("Доступ запрещен `{$dir_name}`");
					}else{ return $readdir($file_name, $folder); }
				}elseif(!get($conf, 'settings', 'themes_intersect')){ return $file;
				}else{ return $file; }
			}, $files)))){ mpre("ОШИБКА получения списка файлов поддиректорий");
//		}elseif(true){ die(!mpre($files, $path));
/*		}elseif(!$files = array_filter(array_map(function($file, $key) use($path, $conf){ # Убираем файлы если их нет в файловой системе
				if("array" == gettype($file)){ return $file; // mpre("Поддиректория");
				}elseif("_" == substr($file, 1, 1)){ return null; mpre("Скрытые файлы");
				}elseif(!$file_name = "{$path}/{$file}"){ mpre("ОШИБКА получения относительного пути до файла");
				}elseif(!file_exists($file_name)){ return null;
				}elseif(!get($conf, 'settings', 'intersect')){ return $file;
				}elseif(!$intersect = fk('intersect', $w = ['href'=>$file_name], $w += ['hide'=>1])){ mpre("ОШИБКА добавления файла в таблицу `{$file_name}`");
				}else{ return $file; }
			}, $files, array_keys($files)))){ return $files; mpre("ОШИБКА получения списка файлов");*/
		}elseif(!is_string($basename = (basename($path) ?: ""))){ mpre("ОШИБКА получения директории");
		}elseif(!$files = array_merge([$basename], $files)){ mpre("[0] элементом всегда идет название директории");
		}else{ return $files; }
	}): mpre("ОШИБКА получения списка файлов") ?>
<?// elseif(!$files = $readdir("", $folder)): mpre("ОШИБКА получения списка файлов директории `{$folder}`") ?>
<? elseif(!$_files = $readdir("", ".")): mpre("ОШИБКА получения списка файлов директории `{$folder}`") ?>
<? elseif(!$tree = function($files, $path = "") use(&$tree, $folder){ ?>
		<? if(!$file = (get($files, 0) ?: $folder)): mpre("ОШИБКА поулчения имени директории") ?>
		<?// elseif(!$dir_name = ($path ? "{$path}/" : ""). $file): mpre("ОШИБКА расчета пути до файла относительно корня сайта") ?>
		<? elseif(!is_array($list = array_slice($files, 1))): mpre("ОШИБКА получения списка файлов директории") ?>
		<? elseif(!is_array($intersect = rb("intersect", 'href', "[{$path}]"))): mpre("ОШИБКА выборки данных о файле") ?>
		<? else:// mpre($path, $intersect) ?>
			<ul>
				<li>
					<? if($intersect): ?>
						<a href="/themes:admin/r:intersect?edit&where[id]=<?=get($intersect, 'id')?>" style="inline-block;" title="<?=$path?>">
							<?=$file?>
						</a>
					<? else: ?>
						<strong title="<?=$path?>"><?=$file?></strong>
					<? endif; ?>
				</li>
				<? foreach($list as $file): ?>
					<? if(is_array($file)):// mpre("Директория") ?>
						<? if(!$dir = get($file, 0)): mpre("ОШИБКА получения имени директории") ?>
						<? elseif(!$_path = ($path ? "{$path}/" : ""). $dir): mpre("ОШИБКА формирования пути до директории") ?>
						<? else: $tree($file, $_path) ?>
						<? endif; ?>
					<? elseif(is_string($file)):// mpre("Файл") ?>
						<? if(!$file_name = ($path ? "{$path}/" : ""). $file): mpre("ОШИБКА формирования пути до директории") ?>
						<? elseif(!is_array($intersect = rb("intersect", 'href', "[{$file_name}]"))): mpre("ОШИБКА выборки данных о файле") ?>
						<? elseif(!list($color, $title) = call_user_func(function($intersect) use($file_name){
								if(!$file_phar = "phar://index.phar/{$file_name}"){ mpre("ОШИБКА расчета пхар файла");
								}elseif(!get($intersect, 'hide')){ return ["black", "{$file_name} (Отмечен как видим)"];
								}elseif(!file_exists($file_phar)){ return ["gray", "{$file_name} (Локальный)"];
								}elseif(!$md5_file = md5_file($file_name)){ mpre("ОШИБКА расчета md5 файла `{$file_name}`");
								}elseif(!$md5_phar = md5_file($file_phar)){ mpre("ОШИБКА расчета md5 файла `{$file_phar}`");
								}elseif($md5_phar == $md5_file){ return ["orange", "{$file_name} (Не изменен)"];
								}else{
								} return ["red", "{$file_name} (Изменен)"];
							}, $intersect)): mpre("ОШИБКА расчета цвета файла") ?>
						<? else:// mpre($intersect) ?>
							<li style="display:inline-block; margin:0 10px;">
								<?//=aedit("/themes:admin/r:themes-intersect?edit&where[id]={$intersect['id']}")?>
								<a href="/themes:admin/r:themes-intersect?edit&where[id]=<?=get($intersect, 'id')?>" style="padding-right:20px; color:<?=$color?>;" title="<?=$title?>">
									<?=$file?> <?=(get($intersect, 'name') ? "({$intersect['name']})" : "")?>
								</a>
							</li>
						<? endif; ?>
					<? else: mpre("ОШИБКА не установленный тип элемента директории") ?>
					<? endif; ?>
				<? endforeach; ?>
			</ul>
			<? return $files; ?>
		<? endif; ?>
	<? }): mpre("ОШИБКА создания функции отображения дерева") ?>
<? elseif(!$files = $tree($_files)): mpre("ОШИБКА отображения дерева") ?>
<? else:// mpre($_files) ?>
<? endif; ?>
