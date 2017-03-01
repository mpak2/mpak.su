<?

function tmpopendir($file_name, $merge=1){
	global $conf;
	$prefix = $merge ? explode('::', strtr(strtr($conf["db"]["open_basedir"], array(":"=>"::")), array("phar:://"=>"phar://"))) : array('./');
	if ($merge < 0) krsort($prefix);
	foreach($prefix as $k=>$v){
		$file = strtr("$v/$file_name", array('/modules/..'=>''));
		if (file_exists($file)){
//			echo "\n==>{$k}<==>{$v}<==>{$file}<==\n";
			return $file; break;
		}
	}
}

if($argv){
	chdir(__DIR__);
	if(file_exists(__DIR__. "/../../../index.phar")){
		$conf["db"]["open_basedir"] = (ini_get("open_basedir") ?: "phar:/". dirname(dirname(dirname(dirname(__FILE__)))). "/index.phar::". dirname(dirname(dirname(dirname(__FILE__)))));
		if($mpfunc = tmpopendir("include/mpfunc.php")){ include $mpfunc; }else{
			include "phar://../../../index.phar/include/mpfunc.php";
		} include "../../../include/config.php";
	}else{
		include "/srv/www/vhosts/mpak.cms/include/parse/simple_html_dom.php";
		include "/srv/www/vhosts/mpak.cms/include/mpfunc.php";
		include "/srv/www/vhosts/mpak.cms/include/func.php";
		include __DIR__. "/../../../include/config.php";
		$conf["db"]["open_basedir"] = (ini_get("open_basedir") ?: dirname(dirname(dirname(dirname(__FILE__)))));
	}// pre($conf["db"]["open_basedir"]);

	$conf['db']['conn'] = new PDO("{$conf['db']['type']}:host={$conf['db']['host']};dbname={$conf['db']['name']};charset=UTF8", $conf['db']['login'], $conf['db']['pass'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC));
	$arg['modpath'] = basename(dirname(dirname(__FILE__)));
	$conf['fs']['path'] = dirname(dirname(dirname(dirname(__FILE__))));

	include "phar://../../../index.phar/include/class/simple_html_dom.php";
	$html = new simple_html_dom();
} chdir(__DIR__); # Изменяем текущую директорию для запуска из крона
$conf['user']['gid'] = array(1=>"Администратор");
unset($conf['db']['pass']); $conf['db']['sql'] = array();

###################################################################################################################################################

if(array_search($cmd["resque"] = "Загрузить информацию об изображениях с директории", $cmd) == get($argv, 1)){
	if(!$table_name = get($argv, 2)){ mpre("Имя таблицы не найдено");
	}elseif(!$FIELDS = fields($table_name)){ mpre("Таблица в БД не найдена", $table_name);
	}elseif(!$INDEX = rb($table_name)){ mpre("Ошибка выборки данных");
	}elseif(!$dir = "../../../"){ mpre("Ошибка имени директории");
	}else{
		foreach($INDEX as $index){
			if(!$glob = "{$dir}/include/images/{$table_name}_img_{$index['id']}.*"){ mpre("Ошибка формирования патерна имен файлов");
			}elseif(!$FILES = glob($glob)){ mpre("Файлы не найдены", $glob);
	//		}elseif(1 != count($FILES)){ mpre("Количество файлов больше одного", $FILES);
			}elseif(!$imgs = last($FILES)){ mpre("Ошибка выборки первого файла");
			}elseif(!$img = implode("/", array_slice(explode("/", $imgs), -2))){ mpre("Ошибка получения пути до файла");
			}elseif($img == $index['img']){ echo("{$index['id']},");
			}elseif(!$index = fk($table_name, ['id'=>$index['id']], null, ['img'=>$img])){ mpre("Ошибка обновления пути к изображению", $index);
			}else{ mpre("Восстановлен: {$img}"); }
		}
	}
}elseif(array_search($cmd["clean"] = "Удалить информацию об изображениях которых нет в фс", $cmd) == get($argv, 1)){
	if(!$table_name = get($argv, 2)){ mpre("Имя таблицы не найдено");
	}elseif(!$INDEX = rb($table_name)){ mpre("Элементов в таблице не найдено");
	}elseif(!$index = first($INDEX)){ mpre("Ошибка выборки первого элемента таблицы");
	}elseif(!get($index, 'img')){ mpre("Поле изображения у элемента не обнаружено");
	}elseif(!$path = mpopendir("include")){
	}else{
		foreach($INDEX as $index){
			if(!$img = get($index, 'img')){// mpre("Изображение не задано {$index['id']}");
			}elseif(!$file_name = "{$path}/{$img}"){ mpre("Ошибка определения имени файла");
			}elseif(!file_exists($file_name)){ mpre("Файл указанный в таблице не найден в файловой системе");
				if(!$index = fk($table_name, ['id'=>$index['id']], null, ['img'=>''])){ mpre("Ошибка обнуления изображения элемента");
				}else{ mpre("{$index['id']}. Удаление информации о файле из таблицы");
				}
			}else{// mpre("{$index['id']}. Файл проверен успешно {$file_name}");
			}
		}
	}
}else{ mpre($cmd); }
