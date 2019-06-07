<?

if($dump = get($_REQUEST, 'dump')){
	if(get($file = get($_FILES, 'file'), 'name')){
		if($file['error'] == 0){
			exit(mpre(false, $file['tmp_name'], qw(file_get_contents($file['tmp_name']))));
		}else{ mpre("Ошибка загрузки файла", $file); }
	}else if(get($_REQUEST, 'upload')){		
		if($cmd = "mysqldump {$conf['db']['name']} ".implode(" ",array_keys($dump))." -u {$conf['db']['login']} -p{$conf['db']['pass']}"){			
			header("content-disposition: attachment; filename={$conf['db']['name']}".((count($dump)==1)?".".first(array_keys($dump)):"").".sql");			
			exit(passthru($cmd));
		}
	}else{
		$tpl['dump'] = "mysqldump {$conf['db']['name']} ".implode(" ",array_keys($dump))." -u {$conf['db']['login']} -p{$conf['db']['pass']}";
	}
}else if($sql = call_user_func(function() use($conf){ // Создание таблицы
		if(!array_key_exists("null", $_GET)){ //mpre("Не фоновый запрос");
		}else if(!$_POST){ mpre("Пост запрос не задан");
		}else if($sql = get($_POST, 'sql')){// mpre($sql);
			if(!is_array($query = call_user_func(function($query = []) use($conf, $sql){ // Сохранение запроса
					if(!get($conf, "settings", $t = "sql_query")){ mpre("ОШИБКА таблица для сохранения запроса не установлена `{$t}`");
					}else if(!$FIELDS = fields($t = "sql-query")){ mpre("ОШИБКА получения полей таблицы `{$t}`");
					}else if(!$fields = get($FIELDS, "name")){ mpre("ОШИБКА поле для запросов `name` в таблице <a href='/sql:admin/r:sql-query'>{$conf["db"]["prefix"]}sql_query</a> не найдено.");
					}else if(!$query = fk("sql-query", null, ["name"=>$sql])){ mpre("ОШИБКА сохранения запроса в истории");
					}else{ mpre("Сохраняем запрос `{$conf["db"]["prefix"]}sql_query`", $sql);
					} return $query;
				}))){ mpre("ОШИБКА сохранения запроса");
			}elseif(!$microtime = microtime(true)){ mpre("ОШИБКА засечки времени выполнения запроса");
			}elseif(!$result = qw($sql)){ mpre("Ошибка выполнения запроса");
			}elseif(!$microtime = microtime(true)-$microtime){ mpre("ОШИБКА получения времени выполнения запроса");
			}elseif(!is_array($data = mpql($result))){ mpre("ОШИБКА получения списка результатов");
			}elseif(($name = get($data, "name")) && (!$data = $name)){ mpre("Удобный для вывода формат");
			}else{ exit(!mpre("Результат вывода запроса: {$microtime} сек.", ((count($data) == 1) ? first($data) : $data)));
			} exit(mpre("ОШИБКА выполнения запроса"));
		}else if(!is_string($sql = call_user_func(function($sql = ""){ // Удаление таблицы
				if(!$table = get($_POST, 'del')){ //mpre("Запрос на удаление не установлен");
				}else if(!$sql = "DROP TABLE `{$table}`"){ mpre("ОШИБКА составления запроса на удаление");
				}else{ //mpre("Удаление таблицы");
				} return $sql;
			}))){ mpre("ОШИБКА получения запроса на удаление таблицы");
		}else if(!is_string($sql = call_user_func(function($sql = "") use($conf){ // Запрос на создание таблицы
				if(!$table = get($_POST, 'table')){ //mpre("Имя таблицы не задано");
				}else if($conf['db']['type'] == 'sqlite'){ $sql = "CREATE TABLE `{$table}` (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE, time INTEGER, uid INTEGER, name TEXT)";
				}else{ $sql = "CREATE TABLE `$table` (
						id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
						time INT(11),
						uid INT(11),
						name VARCHAR(255)
					) DEFAULT CHARSET=utf8";
				} return $sql;
			}, $sql))){ mpre("ОШИБКА получения запроса на создание таблицы");
		}else if(!qw($sql)){ mpre("ОШИБКА выполения запроса к БД на создание таблицы");
		}else{ return $sql; }
	})){ exit(json_encode(array("sql"=>$sql)));// mpre("ОШИБКА создания таблицы");
}else if(array_key_exists("null", $_GET)){ // Дальне не для фоновых запросов
}elseif(!$table = get($_GET, 'r')){ mpre("ОШИБКА получения имени таблицы");
}elseif(!$FIELDS = fields($table)){ mpre("ОШИБКА определения полей таблицы");
}else if(!$INDEXES = call_user_func(function($_FIELDS) use($conf, $FIELDS){ // Обновления структуры таблицы
		if(!$table = get($_GET, 'r')){ mpre("ОШИБКА таблица для изменений не задана");
		}else if(!$_FIELDS_ = get($_POST, 'f')){ //mpre("Изменений в структуре таблицы не задано");
		}else if(!$_FIELDS = call_user_func(function($_FIELDS) use($FIELDS){ // Список полей + новое поле
				if(!$field = get($_POST, '$')){ //mpre("Новое поле не задано");
				}else if(!$name = get($field, "name")){ //mpre("Имя поля для добавления не задано");
				}else if(!$_FIELDS[$name] = $field){ mpre("ОШИБКА добавления поля");
				}else{ //mpre("Добавленные поле", $FIELDS, $_FIELDS);
				} return $_FIELDS;
			}, $_FIELDS_)){ mpre("ОШИБКА добавления нового поля");
		}else if(!$_FIELDS = array_filter(array_map(function($field) use($_FIELDS_){ // Убираем поля с пустыми именами
				if(!$name = get($field, "name")){ //mpre("Поле отмечено на удаление", $field);
 				}else{ return $field; }
			}, $_FIELDS))){ mpre("ОШИБКА получения списка полей с удалением");
		}else if(!$_FIELDS = call_user_func(function($_FIELDS) use($FIELDS){ // Изменение порядка сортировки полей
				foreach($_FIELDS as $field){ //mpre("По одному полю изменяем порядок сортировки");
					if(!$after = get($field, "after")){ //mpre("Полю не требуется изменение порядка сортировки `{$field}`");
					}else if(!$name = get($field, "name")){ //mpre("Имя поля для добавления не задано");
					}else if(!$_FIELDS_ = array_diff_key($FIELDS, array_flip([$name]))){ mpre("ОШИБКА исключения текущего поля");
					}else if(!$fields_pos = array_flip(array_keys($_FIELDS_))){ mpre("ОШИБКА получения номеров полей в таблице");
					}else if(!$field_prev = get($field, 'after')){ mpre("ОШИБКА предыдущее поле не указано");
					}else if(is_null($field_pos = get($fields_pos, $field_prev))){ mpre("ОШИБКА получения позиции предыдущего поля в таблице");
					}else if(!$field_pos = $field_pos+1){ mpre("ОШИБКА определения позиции текущего поля в списке полей");
					}else if(!$_FIELDS = array_merge(array_slice($_FIELDS_, 0, $field_pos), [$name=>$field], array_slice($_FIELDS_, $field_pos))){ mpre("ОШИБКА добавления нового поля");
					}else{ //mpre("Изменяем порядок сортировки", $field_pos, $field, $_FIELDS_, $_FIELDS);
					}
				} return $_FIELDS;
			}, $_FIELDS)){ mpre("ОШИБКА получения списка полей с удалением");
		}else if(!$INDEX_NAME = array_map(function($fields) use($table, $conf){ // Список имен индексов
				if(!$name = get($fields, "name")){ mpre("ОШИБКА имя поля не указанао");
				}else if(!$tn = substr($table, strlen($conf["db"]["prefix"]))){ mpre("ОШИБКА поулчения короткого имени таблицы");
				}else if(!$index_name = "{$tn}-{$name}"){ mpre("ОШИБКА получения имени ключа поля");
				}else{ return $index_name; }
			}, $FIELDS)){ mpre("ОШИБКА получения имен индексов");
		}else if(!$INDEXES = indexes($table)){ mpre("ОШИБКА получения индексов");
		}else if(!is_array($INDEX_KEY = array_map(function($index, $name) use($_FIELDS_, $INDEXES){ // Удаление ключей
				if(get($_FIELDS_, $index, "index")){ //mpre("Ключ отмечен в списке");
				}else if(!get($INDEXES, $name)){ //mpre("Ключа уже нет в таблице");
				}else if(!$sql = "DROP INDEX `{$name}`"){ mpre("ОШИБКА составления запроса на удаление ключа");
				}else if(!qw($sql)){ mpre("ОШИБКА выполнения запроса к БД на создание ключа");
				}else{ mpre("Удаляем индекса `{$name}`", $sql);
				}
			}, array_keys($INDEX_NAME), $INDEX_NAME))){ mpre("ОШИБКА удаления старых ключей");
		}else if(!is_array($INDEX_KEYDEL = array_map(function($index, $name) use($table, $_FIELDS_, $INDEXES){ // Добавление новых ключей
				if("id" == $index){ //mpre("Не добавляем ключ автоинкрементному полю `{$index}`");
				}else if(!get($_FIELDS_, $index, "index")){ //mpre("Ключ не найден в списке `{$index}`");
				}else if(get($INDEXES, $name)){ //mpre("Ключ уже есть в таблице");
				}else if(!$sql = "CREATE INDEX `{$name}` ON {$table}(`{$index}`)"){ mpre("ОШИБКА составления запроса на создание ключа `{$name}`");
				}else if(!qw($sql)){ mpre("ОШИБКА выполнения запроса к БД на создание ключа");
				}else{ mpre("Добавляем индекса `{$name}`", $sql);
				}
			}, array_keys($INDEX_NAME), $INDEX_NAME))){ mpre("ОШИБКА удаления старых ключей");
		}else if(!is_array($INDEX_UNIQUE = array_map(function($index, $name) use($table, $_FIELDS_, $INDEXES){ // Добавление новых ключей
				if("id" == $index){ //mpre("Не добавляем ключ автоинкрементному полю `{$index}`");
				}else if(!get($_FIELDS_, $index, "unique")){ //mpre("Уникальный ключ не найден в списке `{$index}`");
				}else if(!$_name = "{$name}-unique"){ mpre("ОШИБКА получения имени уникального поля");
				}else if(get($INDEXES, $_name)){ //mpre("Уникальный ключ уже есть в таблице");
				}else if(!$sql = "CREATE UNIQUE INDEX `{$_name}` ON {$table}(`{$index}`)"){ mpre("ОШИБКА составления запроса на создание ключа `{$name}`");
				}else if(!qw($sql)){ mpre("ОШИБКА выполнения запроса к БД на создание ключа");
				}else{ mpre("Добавляем уникальный индекс `{$_name}`", $sql);
				}
			}, array_keys($INDEX_NAME), $INDEX_NAME))){ mpre("ОШИБКА удаления старых ключей");
		}else if(!is_array($INDEX_KEY = array_map(function($index, $name) use($_FIELDS_, $INDEXES){ // Удаление уникальных ключей
				if(get($_FIELDS_, $index, "unique")){ //mpre("Ключ отмечен в списке");
				}else if(!$_name = "{$name}-unique"){ mpre("ОШИБКА получения имени уникального поля");
				}else if(!get($INDEXES, $_name)){ //mpre("Ключа уже нет в таблице");
				}else if(!$sql = "DROP INDEX `{$_name}`"){ mpre("ОШИБКА составления запроса на удаление ключа");
				}else if(!qw($sql)){ mpre("ОШИБКА выполнения запроса к БД на создание ключа");
				}else{ mpre("Удаляем уникального индекса `{$_name}`", $sql);
				}
			}, array_keys($INDEX_NAME), $INDEX_NAME))){ mpre("ОШИБКА удаления уникальных ключей");
		}else if(call_user_func(function() use($_FIELDS, $FIELDS){ // Расчет расхождения полей до и после изменений
				if(!$keys = array_flip(["name", "type"])){
				}else if(!$FIELDS_ = array_map(function($field) use($keys){ // Поля до изменений
						if(!$field = array_replace($keys, $field)){ mpre("ОШИБКА приведению к единому формату");
						}else if(!$field = array_intersect_key($field, $keys)){ mpre("ОШИБКА исключения не сравниваемых полей");
						}else{ //mpre($keys, $field, $_field);
							return $field;
						}
					}, $FIELDS)){ mpre("ОШИБКА получения списка полей до изменений");
				}else if(!$_FIELDS_ = array_map(function($field) use($keys){ // Поля до изменений
						if($after = get($field, "after")){ //mpre("Изменение порядка следования");
						}else if(!$field = array_replace($keys, $field)){ mpre("ОШИБКА приведению к единому формату");
						}else if(!$field = array_intersect_key($field, $keys)){ mpre("ОШИБКА исключения не сравниваемых полей");
						}else{ //mpre($keys, $field, $_field);
							return $field;
						}
					}, $_FIELDS)){ mpre("ОШИБКА получения списка полей до изменений");
				}else if(($FIELDS_ != $_FIELDS_) || (array_keys($FIELDS_) != array_keys($_FIELDS_))){ //mpre("Изменения", $FIELDS_, $_FIELDS_);
				}else{ //mpre("Изменений нет");
					return true;
				}
			})){ mpre("Список изменений структуры таблицы пуст");
		}else if(!$transaction[] = "DROP TABLE IF EXISTS `backup`"){ mpre("ОШИБКА добавления списка запросов");
		}else if(!$transaction[] = call_user_func(function($sql = "") use($_FIELDS){ // Запрос создания временной таблицы
				if(!$_fields = array_filter(array_map(function($field){ // Список полей новой таблицы
						if(!$name = get($field, "name")){ mpre("ОШИБКА имя поля не задано");
						}else if("id" == $name){ //mpre("У поля идентификатора отдельный формат");
						}else if(!is_string($type = get($field, "type"))){ pre("ОШИБКА у поля не задан тип");
						}else if(!$field = "`{$name}` {$type}"){ mpre("ОШИБКА составления формата запроса поля");
						}else{ return $field; }
					}, $_FIELDS))){ mpre("Получение списка имен и типов новых полей");
				}else if(!$sql = "CREATE TABLE backup(`id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE, ". implode(", ", $_fields). ")"){ mptr("ОШИБКА получения запроса");
				}else{ //mpre($_fields, $sql);
				} return $sql;
			})){ mpre("ОШИБКА получение запроса на создание временной таблицы");
		}else if(!$transaction = array_merge($transaction, ["PRAGMA foreign_keys=OFF"])){ mpre("ОШИБКА добавления списка запросов");
		}else if(!$transaction[] = call_user_func(function($sql = "") use($table, $FIELDS){ // Запрос создания обновленнойтаблицы
				if(!$_fields = array_filter(array_map(function($field){ // Список полей новой таблицы
						if(!$name = get($field, "name")){ mpre("ОШИБКА имя поля не задано");
						}else if(!$field = "`{$name}`"){ mpre("ОШИБКА составления формата запроса поля");
						}else{ return $field; }
					}, $FIELDS))){ mpre("Получение списка имен и типов новых полей");
				}else if(!$sql = "INSERT INTO `backup` (". implode(", ", $_fields). ") SELECT ". implode(", ", $_fields). " FROM `{$table}`"){ mpre("ОШИБКА составления запроса на копирование резервных данных");
				}else{ //mpre("Запрос на перенос данных", $sql);
				} return $sql;
			})){ mpre("ОШИБКА добавления запроса на создание обновленной таблицы");
		}else if(!$transaction = array_merge($transaction, ["PRAGMA foreign_keys=ON", "DROP TABLE `{$table}`", "ALTER TABLE `backup` RENAME TO `{$table}`"])){ mpre("ОШИБКА добавления списка запросов");
		}else if(!$FIELDS = call_user_func(function() use($transaction, $table, $INDEXES, $FIELDS, $_FIELDS_){ // Выполение запросов в защищенном режиме
				mpre("Список изменений", $transaction);
				if(!$_transaction = qw("BEGIN TRANSACTION")){ mpre("ОШИБКА начала транзакции");
				}else if(call_user_func(function($nn = 0) use($transaction){ // Выполнение запросов
						do{
							if($response = false){ mpre("ОШИБКА обновления ответа");
							}else if(!$sql = get($transaction, $nn)){ //mpre("ОШИБКА получения следующего запроса");
							//}else if(!mpre("Запрос к БД", $sql)){ mpre("ОШИБКА уведомления");
							}else if($response = qw($sql)){ //mpre("Корректно выполненный запрос", $sql);
							}else if(!qw("ROLLBACK TRANSACTION")){ mpre("ОШИБКА прерывания транзакции");
							}else{ mpre("ОШИБКА выполенния запроса", $sql);
								return true;
							}
						}while($response && ++$nn);
					})){ mpre("ОШИБКА применения запросов к БД");
				}elseif(!$FIELDS = fields($table)){ mpre("ОШИБКА определения полей таблицы");
				}else if(!is_array($SQL = array_filter(array_map(function($index) use($FIELDS, $_FIELDS_, $INDEXES){ // Восстановление индексов
						if(!$name = get($index, "name")){ mpre("ОШИБКА получения имени индекса");
						}else if(!$parts = explode("-", $name, 2)){ mpre("ОШИБКА получения составляющих частей имени индекса");
						}else if(!$_parts = explode("-", get($parts, 1), 2)){ mpre("ОШИБКА получения признака уникального индекса");
						}else if(!$sql = call_user_func(function($sql = "") use($index, $_parts, $FIELDS, $_FIELDS_){ // Получение запроса на восстановление индекса
								if(!$sql = get($index, 'sql') ?: ""){ //mpre("Зпрос на восстановление индекса не задан");
								}else if(!$_name = get($_parts, 0)){ mpre("ОШИБКА получения имени поля");
								}else if(get($FIELDS, $_name)){ //mpre("Поле индекса уже установлено в таблице `{$_name}`", $sql);
								}else if(!$_name_ = get($_FIELDS_, $_name, "name")){ mpre("ОШИБКА поле переименованного поля не указано");
								}else if(!$sql = str_replace($_name, $_name_, $sql)){ mpre("ОШИБКА замены всех вхождений обновленного в запросе");
								}else{ mpre("Запрос на восстановления индекса скорректирован по изменившемуся полю `{$_name}`", $sql);
								} return $sql;
							})){ //mpre("Пустой запрос на восстановление индекса", $index);
						}else if(!qw($sql)){ mpre("ОШИБКА восстановления индекса", $sql, $name, $FIELDS);
						}else{ //mpre("Восстановление идекса", $sql);
							return $sql;
						}
					}, $INDEXES)))){ mpre("ОШИБКА восстановления индексов");
				}elseif(!qw("END TRANSACTION")){ mpre("ОШИБКА завершения транзакции");
				}elseif(!$SQL){ //mpre("Не выводим информацию о восстановлении индексов");
				}else{ mpre("Восстановление индексов", array_values($SQL));
				} return $FIELDS;
			})){ mpre("ОШИБКА применения запросов");
		}else if(!$FIELDS = fields($table)){ mpre("ОШИБКА получения списка обновленных полей");
		}else{ //mpre($table, $transaction);
		} return $FIELDS;
	}, $FIELDS)){ mpre("ОШИБКА обновления структуры таблицы");
}elseif(call_user_func(function(){ // Внешние связи таблицы
		if(!$foreign = get($_POST, 'foreign')){ //mpre("Изменений связей не задано");
		}elseif($conf['db']['type'] == "sqlite"){// die(!mpre($_GET, $_POST));
		}else if(!$table = $_GET['r']){ die(!mpre("Имя таблицы не установлено"));
		}else if(!$FIELDS = fields($table)){ die(!mpre("Ошибка установки свойств таблицы"));
		}elseif('sqlite' == $conf['db']['type']){// die(!mpre("mysql"));
			if(!$field = $_POST['foreign']){ die(!mpre("Ошибка установки поля вторичного ключа"));
			}elseif(!$sql = "PRAGMA foreign_key_list({$_GET['r']});"){ mpre("Ошибка получения информации о вторичных ключах");
			}elseif(!is_array($FOREIGN_KEYS = mpqn(mpqw($sql), "from"))){ mpre("Ошибка выполнения выборки вторичных ключей");
			}elseif(!$tab = substr($table, strlen($conf['db']['prefix']))){ mpre("ОШИБКА получения короткого имени таблицы");
			}elseif(!$modpath = first(explode("_", $tab))){ mpre("ОШИКА определения модуля таблицы");
			}elseif(!$fntab = call_user_func(function() use($conf, $tab, $modpath, $field){ # Если имя ключа содержит тире, то связанная таблица - внешняя
					if("_id" == ($f = substr($field, -3))){ return "{$conf['db']['prefix']}{$modpath}_". substr($field, 0, -3); mpre("Внутренняя связанная таблица");
					}elseif(!$ex = explode('-', $field)){ mpre("ОШИБКА разбивки имени поля по компонентам");
					}elseif(1 == count($ex)){ mpre("Ошибка формата поля внешнего ключа");
					}else{ return "{$conf['db']['prefix']}{$ex[0]}_". implode("_", array_slice($ex, 1));  mpre("Внешняя связаная таблицей"); }
				})){ mpre("ОШИБКА получения имени связанной таблицы по полю `{$field}`");
			}elseif(!$ftab = (0 === strpos($fntab, $conf['db']['prefix']) ? substr($fntab, strlen($conf['db']['prefix'])) : "")){ mpre("ОШИБКА расчета короткого имени связанной таблицы");
			}elseif(!$cmd_create_table = call_user_func(function() use($FOREIGN_KEYS, $FIELDS, $table, $field, $fntab){ # Формируем запрос на новую таблицу
					unset($FOREIGN_KEYS[$field]);
					if(!$fields_list = array_map(function($fld){ # Список полей таблицы
							if('id' == $fld['name']){ return "`{$fld['name']}` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE";
							}else{ return "`{$fld['name']}` {$fld['type']}"; }
						}, $FIELDS)){ mpre("ОШИБКА формирования массива полей новой таблицы");
					}elseif(!is_array($foreign_list = array_map(function($foreign) use($field, $fntab){ # На каждый вторичный ключ создаем инструкцию
							return "FOREIGN KEY(`{$foreign['from']}`) REFERENCES `{$foreign['table']}`({$foreign['to']}) ON UPDATE {$foreign['on_update']} ON DELETE {$foreign['on_delete']}";
						}, $FOREIGN_KEYS))){ mpre("ОШИБКА формирования инструкций связывания вторичных таблиц");
					}elseif(!$sql = "CREATE TABLE `{$table}` (". implode(" ,", $fields_list). ($foreign_list ? ", ". implode(", ", $foreign_list) : ""). ")"){ mpre("ОШИБКА формирования запроса на создание запроса новой таблицы");
					}else{ return $sql; }
				})){ mpre("ОШИБКА получение запроса на создание новой таблицы");
			}elseif($foreign_keys = get($FOREIGN_KEYS, $field)){// mpre("Удаление ключа", $foreign_keys);
//					"База данных sqlite не поддерживает изменение полей, поэтому делаем через промежуточную таблицу",
				mpre($transaction = array(
					"DROP TABLE IF EXISTS `backup`;",
					"CREATE TEMPORARY TABLE `backup` (". implode(",", (array_map(function($f){ return ($f['name'] == "id" ? "`{$f['name']}` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE" : "`{$f['name']}` {$f['type']}"); }, $FIELDS))). ");",
					"INSERT INTO `backup` SELECT * FROM `". mpquot($table). "`;",
					"PRAGMA foreign_keys=0",
					"DROP TABLE `". mpquot($table). "`;",
					$cmd_create_table,
					"PRAGMA foreign_keys=1",
					"INSERT INTO `". mpquot($table). "` (`". implode("`, `", array_keys($FIELDS)). "`) SELECT `". implode("`, `", array_keys($FIELDS)). "` FROM backup;",
					"DROP TABLE backup;",
				));// foreach($transaction as $key=>$sql){ qw($sql); }

				if(!qw("PRAGMA foreign_keys=0")){ mpre("ОШИБКА выключения отслеживания вторичных ключей");
				}elseif(!qw("BEGIN TRANSACTION")){ mpre("ОШИБКА начала транзакции");
				}elseif(!$_transaction = array_map(function($sql){ # Выполняем запросы с проверкой возвращаемых стататусов. Если статус не возвращен прекращаем транзакцию
						if(qw($sql)){ return $sql; // mpre("Успешное выполнение запроса", $sql);
						}else{ mpre("ОШИБКА выполнения запроса изменения таблицы", $sql); }
					}, $transaction)){ mpre("ОШИБКА выполнения запроса на изменение таблицы");
				}elseif($errors = array_diff_key($transaction, array_filter($_transaction))){ mpre("Список запросов выполнен с ошибкой - отмена транзакции", $errors);
					qw("ROLLBACK TRANSACTION");
				}elseif(!qw("END TRANSACTION")){ mpre("ОШИБКА завершения транзакции");
				}elseif(!qw("PRAGMA foreign_keys=1")){ mpre("ОШИБКА включения отслеживания вторичных ключей");
				}else{
				}// exit(json_encode($FIELDS));
				
				mpre("Восстановление индексов", array_column($tpl['indexes'], 'sql', 'name'));
				foreach($tpl['indexes'] as $indexes){ qw($indexes['sql']); }
				
//				}elseif(!$on_update = "ON UPDATE ". $_POST[$w = 'on_update']){ die(!mpre("Не задан `{$w}` контроля вторичного ключа"));
//				}elseif(!$on_delete = "ON DELETE ". $_POST[$w = 'on_delete']){ die(!mpre("Не задан `{$w}` контроля вторичного ключа"));
			}elseif(!$sql = "PRAGMA foreign_key_list({$_GET['r']});"){ mpre("Ошибка получения информации о вторичных ключах");
			}elseif(!is_array($FOREIGN_KEYS = mpqn(mpqw($sql), "from"))){ mpre("Ошибка выполнения выборки вторичных ключей");
			}elseif(!$cmd_create_table = call_user_func(function() use($FOREIGN_KEYS, $FIELDS, $table, $field, $fntab){ # Формируем запрос на новую таблицу
					if(!$fields_list = array_map(function($fld){ # Список полей таблицы
							if('id' == $fld['name']){ return "`{$fld['name']}` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE";
							}else{ return "`{$fld['name']}` {$fld['type']}"; }
						}, $FIELDS)){ mpre("ОШИБКА формирования массива полей новой таблицы");
					}elseif(!$FOREIGN_KEYS[$field] = ["from"=>$field, 'table'=>$fntab, 'to'=>'id', 'on_update'=>get($_POST, 'on_update', $field), 'on_delete'=>get($_POST, 'on_delete', $field)]){ mpre("ОШИБКА добавления нового вторичного ключа к уже созданным");
					}elseif(!is_array($foreign_list = array_map(function($foreign) use($field, $fntab){ # На каждый вторичный ключ создаем инструкцию
							return "FOREIGN KEY(`{$foreign['from']}`) REFERENCES `{$foreign['table']}`({$foreign['to']}) ON UPDATE {$foreign['on_update']} ON DELETE {$foreign['on_delete']}";
						}, $FOREIGN_KEYS))){ mpre("ОШИБКА формирования инструкций связывания вторичных таблиц");
					}elseif(!$sql = "CREATE TABLE `{$table}` (". implode(" ,", $fields_list). ", ". implode(", ", $foreign_list). ")"){ mpre("ОШИБКА формирования запроса на создание запроса новой таблицы");
					}else{ return $sql; }
				})){ mpre("ОШИБКА получение запроса на создание новой таблицы");
			}else{// die(!mpre($cmd_create_table)); // die(!mpre($fntab, $field, $FOREIGN_KEYS, $cmd_create_table));
				mpre($transaction = array(
					"DROP TABLE IF EXISTS `backup`;",
					"PRAGMA foreign_keys=OFF",
					"CREATE TEMPORARY TABLE `backup` (". implode(", ", (array_map(function($f){ return ($f['name'] == "id" ? "`{$f['name']}` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE" : "`{$f['name']}` {$f['type']}"); }, $FIELDS))). ");",
					"INSERT INTO `backup` SELECT * FROM `". mpquot($table). "`;",
					"DROP TABLE `". mpquot($table). "`;",
					$cmd_create_table,
					"PRAGMA foreign_keys=ON",
					"INSERT INTO `". mpquot($table). "` (`". implode("`, `", array_keys($FIELDS)). "`) SELECT `". implode("`, `", array_keys($FIELDS)). "` FROM `backup`;",
					"DROP TABLE `backup`;",
				));// die(!mpre($transaction)); // foreach($transaction as $sql){ qw($sql); }

				if(!qw("PRAGMA foreign_keys=0")){ mpre("ОШИБКА выключения отслеживания вторичных ключей");
				}elseif(!qw("BEGIN TRANSACTION")){ mpre("ОШИБКА начала транзакции");
				}elseif(!$_transaction = array_map(function($sql){ # Выполняем запросы с проверкой возвращаемых стататусов. Если статус не возвращен прекращаем транзакцию
						if(qw($sql)){ return $sql; // mpre("Успешное выполнение запроса", $sql);
						}else{ mpre("ОШИБКА выполнения запроса изменения таблицы", $sql); }
					}, $transaction)){ mpre("ОШИБКА выполнения запроса на изменение таблицы");
				}elseif($errors = array_diff_key($transaction, array_filter($_transaction))){ mpre("Список запросов выполнен с ошибкой - отмена транзакции", $errors);
					qw("ROLLBACK TRANSACTION");
				}elseif(!qw("END TRANSACTION")){ mpre("ОШИБКА завершения транзакции");
				}elseif(!qw("PRAGMA foreign_keys=1")){ mpre("ОШИБКА включения отслеживания вторичных ключей");
				}else{
				}
				
				mpre("Восстановление индексов", array_column($tpl['indexes'], 'sql', 'name'));
				foreach($tpl['indexes'] as $indexes){ qw($indexes['sql']); }
			}
		}elseif('mysql' == $conf['db']['type']){// die(!mpre("mysql"));
			$tpl['key_column_usage'] = ql($sql = "SELECT * FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE (TABLE_NAME='{$_GET['r']}' AND REFERENCED_TABLE_NAME != '') OR REFERENCED_TABLE_NAME = '{$_GET['r']}'");
			if($key_column_usage = rb($tpl['key_column_usage'], "REFERENCED_TABLE_NAME", "REFERENCED_COLUMN_NAME", "[{$_GET['r']}]", $foreign)){ mpre("Внешний ключ", $key_column_usage);
				exit(mpre("ALTER TABLE {$key_column_usage['REFERENCED_TABLE_NAME']} DROP KEY "));
			}elseif($key_column_usage = rb($tpl['key_column_usage'], "TABLE_NAME", "COLUMN_NAME", "[{$_GET['r']}]", $foreign)){// mpre("Внутренний ключ", $key_column_usage);
				qw("ALTER TABLE `{$key_column_usage['TABLE_NAME']}` DROP FOREIGN KEY `{$key_column_usage['CONSTRAINT_NAME']}`");
				exit(json_encode($key_column_usage));
			}elseif("_id" == substr($foreign, -3)){// mpre("Создание ключа", $foreign);
				if($fields = fields($_GET['r'])){
					if(get($fields, $foreign, "Null") == "NO"){
						qw("ALTER TABLE `". mpquot($_GET['r']). "` CHANGE `{$foreign}` `{$foreign}` {$fields[$foreign]['Type']} NULL COMMENT '{$fields[$foreign]['Comment']}'");
						qw("UPDATE `". mpquot($_GET['r']). "` SET `{$foreign}`=NULL WHERE `{$foreign}`=0");
					} qw("ALTER TABLE `{$_GET['r']}` ADD FOREIGN KEY (`". mpquot($foreign). "`) REFERENCES `{$conf['db']['prefix']}". first(explode("_", substr($_GET['r'], strlen($conf['db']['prefix'])))). "_". substr($foreign, 0, -3). "` (`id`) ON DELETE ". mpquot($_POST['reference']));
				}else{ exit(mpre("Ошибка определения структуры таблицы", $_GET['r'])); }
			}else{
			}
		}else{ mpre("Изменение связей таблицы");
		}
	})){ mpre("ОШИБКА установки связей таблицы");
}else{// mpre($tpl['indexes']);
	/*foreach($tpl['tables'] = tables() as $table=>$info){
		$info['id'] = (empty($nn) ? ($nn = 1) : ++$nn);
		$info['modpath'] = get($conf, 'modules', first(array_slice(explode("_", $table), 1, 1)), 'folder');
	}*/
}


