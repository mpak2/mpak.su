<?

if(empty($conf) && !($include = call_user_func(function(){
		if(file_exists($INCLUDE[] = "modules/sqlanaliz/sh/cli.php") && !print_r("Подключаем", include ($include = array_pop($INCLUDE)))){ print_r("Ошибка подключения {$include}");
		}elseif(file_exists($INCLUDE[] = "modules/null/sh/cli.php") && !print_r("Подключаем", include ($include = array_pop($INCLUDE)))){ print_r("Ошибка подключения {$include}");
		}elseif(file_exists($INCLUDE[] = "../../../modules/null/sh/cli.php") && !print_r("Подключаем", include ($include = array_pop($INCLUDE)))){ print_r("Ошибка подключения {$include}");
		}elseif(file_exists($INCLUDE[] = "phar://../../../index.phar/modules/null/sh/cli.php") && !print_r("Подключаем", include ($include = array_pop($INCLUDE)))){ print_r("Ошибка подключения {$include}");
		}elseif(file_exists($INCLUDE[] = "phar://../../../mpak.cms/phar/index.phar/modules/null/sh/cli.php") && !print_r("Подключаем", include ($include = array_pop($INCLUDE)))){ print_r("Ошибка подключения {$include}");
		}elseif(empty($include) && !call_user_func(function(){
				if(!$dir = opendir($folder = ".")){ print_r("Ошибка открытия текущей директории");
				}elseif(!$DIR = call_user_func(function($DIR = []) use($dir){ while($file = readdir($dir)){ $DIR[] = $file; } return $DIR; })){ print_r("Ошибка чтения текущей директории");
				}else{ print_r("Ошибка подключения файлов\n"); echo "<pre>"; print_r($INCLUDE); echo "</pre>"; print_r("Библиотека консоли не найдена `{$folder}`\n"); echo "<pre>"; print_r($DIR); echo "</pre>"; }
			})){ print_r("\n\tОшибка отображения списка файлов текущей директории");
		}else{ return $include; }
	}))){ print_r("\n\tОшибка подключения консоли");
}elseif(!$conf['user']['gid'] = array(1=>"Администратор")){ mpre("Устанавливаем администратора");
}elseif(!$modpath = (isset($argv) ? basename(dirname(dirname(__FILE__))) : basename(dirname(__FILE__)))){ mpre("Ошибка вычисления имени модуля");
}elseif(!$arg = ['modpath'=>$modpath, "fn"=>implode(".", array_slice(explode(".", basename(__FILE__)), 0, -1))]){ mpre("Установка аргументов");
}elseif(!isset($argv)){ pre("Запуск из веб интерфейса");
}elseif(!chdir(dirname(dirname(__DIR__)))){
}elseif(file_exists($config = "../include/config.php") && !include($config)){ mpre("Ошибка подключения параметров БД");
}elseif(!chdir(dirname(dirname(dirname(__DIR__))))){ mpre("Ошибка установки текущей директории");
}elseif(!$conf['db']['conn'] = new PDO("{$conf['db']['type']}:host={$conf['db']['host']};dbname={$conf['db']['name']};charset=UTF8", $conf['db']['login'], $conf['db']['pass'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC))){ mpre("Ошибка подключения БД");
}elseif(!$conf['user']['gid'] = array(1=>"Администратор")){ mpre("Устанавливаем администратора");
}elseif(array_search($cmd["sqlite"] = "Преобразовать базу данных из mysql в sqlite", $cmd) == get($argv, 1)){// pre("Метод", get($argv, 1));

	if(!$strtr = array("varchar(255)"=>"TEXT", "int(11)"=>"INTEGER", "smallint(6)"=>"INTEGER", "text"=>"TEXT")){ mpre("Ошибка создания типов данных");
	}elseif(!$conf['db']['type'] == "mysql"){ mpre("Данный скрипт доступен только при работе с БД mysql");
	}elseif(!file_exists($db = "modules/{$arg['modpath']}/sh/.htdb2")){ mpre("База для выгрузки не найдена", $db);
		if(call_user_func(function(){
				if(!$dir = opendir($folder = ".")){ print_r("Ошибка открытия текущей директории");
				}elseif(!$DIR = call_user_func(function($DIR = []) use($dir){ while($file = readdir($dir)){ $DIR[] = $file; } return $DIR; })){ print_r("Ошибка чтения текущей директории");
				}else{ print_r("Ошибка подключения файлов\n"); echo "<pre>"; print_r($INCLUDE); echo "</pre>"; print_r("Библиотека консоли не найдена `{$folder}`\n"); echo "<pre>"; print_r($DIR); echo "</pre>"; }
			})){ print_r("\n\tОшибка отображения списка файлов текущей директории");
		}
	}elseif(!is_writable($db)){ mpre("Файл {$db} недоступен для изменений");
	}elseif(!$conn = new PDO($info = "sqlite:$db")){
	}elseif(get($argv, 2) && file_put_contents($db, "")){ mpre("Ошибка обнуления БД");
	}else{ mpre("<a href='/{$arg['modname']}:{$arg['fn']}/drop'>drop</a>");
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		foreach(tables() as $table){
			$fields = array_map(function($name, $type) use($strtr){
				return "  `{$name}` ". ($strtr[$type] ?: "[$type]"). ($name == "id" ? " PRIMARY KEY" : "");
			}, array_keys($fild = array_column(fields($tab = first($table)), "Type", "Field")), array_values($fild));

			mpre($create = "CREATE TABLE {$tab} (\n". implode(",\n", $fields). "\n)");
			$conn->exec($create);
			if(($INDEXES = qn("SHOW INDEXES IN `{$tab}`", "Key_name")) && ($INDEXES = array_diff_key($INDEXES, array_flip(['PRIMARY'])))){
				foreach(rb($INDEXES, "Index_type", "Key_name", "[BTREE]") as $indexes){
					if(!$index_name = substr($tab, strlen($conf['db']['prefix'])). "-{$indexes['Column_name']}"){ mpre("Ошибка формирования имени ключа");
					}elseif(!$sql = "CREATE INDEX IF NOT EXISTS `{$index_name}` ON `{$tab}` (`{$indexes['Column_name']}`);"){ mpre("Ошибка формирования запроса к БД");
					}else{ mpre($sql);
						$conn->exec($sql);
					}
				}// exit(mpre($INDEXES));
			}

				# Добавить на случае создания sqlite
//			CREATE UNIQUE INDEX alias ON mp_seo_cat(alias)
//			CREATE UNIQUE INDEX events ON mp_users_events(name)

			foreach(rb($tab) as $t){
				$t = array_map(function($val){
					return strtr(SQLite3::escapeString($val), array("?"=>"??", "'"=>"''", "\""=>"\"\"", "\n"=>"\\n"));
				}, $t);

				if(($tab == "mp_users") || ($tab == "mp_users_mem")){ mpre("Не добавляем пользователей", $t);
				}elseif(($tab == "mp_settings") && ($t['name'] == "admin_usr")){ mpre("Пропускаем администратора", $t);
				}elseif(!$sql = "INSERT INTO `{$tab}` (`". implode("`, `", array_keys($t)). "`) VALUES (\"". implode("\", \"", array_values($t)). "\")"){ mpre("Ошибка формирования запроса");
//				}elseif(sleep(0.8)){ mpre("Делаем паузу между запросами");
				}else{ mpre($sql);
					$conn->exec($sql);
				}
			}
		}
	}

}else{ mpre($cmd);}