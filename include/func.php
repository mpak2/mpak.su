<?

# Создание пользовательской сессии
function users_sess($sess = null){
	global $conf;
	if(!$guest = ['id'=>0, "uname"=>"гость", "pass"=>"nopass", "reg_time"=>0, "last_time"=>time()]){ pre("Ошибка создания пользователя");
	}elseif(!$hash = ($_COOKIE["sess"] ?: md5("{$_SERVER['REMOTE_ADDR']}:".microtime()))){ pre("ОШИБКА расчета хеша кукисы");
	}elseif(!$url = mpquot(urldecode($_SERVER['REQUEST_URI']))){ pre("ОШИБКА расчета адреса текущей страницы");
	}elseif(!is_string($ref = mpquot(mpidn(urldecode(get($_SERVER, 'HTTP_REFERER')))))){ pre("ОШИБКА расчета реферальной ссылки");
	}elseif(!$_sess = array('id'=>0, 'uid'=>$guest['id'], "refer"=>0, 'last_time'=>time(), 'count'=>0, 'count_time'=>0, 'cnull'=>0, 'sess'=>$hash, 'ref'=>$ref, 'ip'=>mpquot($_SERVER['REMOTE_ADDR']), 'agent'=>mpquot($_SERVER['HTTP_USER_AGENT']), 'url'=>$url)){ pre("Ошибка создания пустой сессии");
	}elseif(!is_numeric($uid = get($conf, 'user', 'uid') > 1 ? $conf['user']['uid'] : $guest['id'])){ pre("ОШИБКА установки идентификтаора пользователя");
	}elseif(!$sql = "SELECT * FROM {$conf['db']['prefix']}users_sess WHERE `ip`='{$_sess['ip']}' AND last_time>=".(time()-86400)." AND `agent`=\"{$_sess['agent']}\" AND ". ($_COOKIE["sess"] ? "sess=\"{$_sess['sess']}\"" : "uid=". $uid)." ORDER BY id DESC"){ mpre("ОШИБКА составления запроса поиска сессии");
	}elseif($sess = (get($_COOKIE, "sess") ? mpql(mpqw($sql), 0) : [])){ return $sess;// pre("ОШИБКА получения сессии");
	}elseif(empty($_POST) && !get($_SERVER, 'HTTP_PRAGMA')){ return $_sess; pre("Пропускаем гостей так как нет запросов и обновлений");
	}elseif(!qw($sql = "INSERT INTO {$conf['db']['prefix']}users_sess (`". implode("`, `", array_keys(array_diff_key($_sess, array_flip(['id'])))). "`) VALUES ('". implode("', '", array_values(array_diff_key($_sess, array_flip(['id'])))). "')", function($error){
			if(strpos($error, "doesn't exist")){ qw("ALTER TABLE mp_sess RENAME mp_users_sess");
			}else{ pre("ОШИБКА не обработана"); }
		})){ pre("ОШИБКА добавления сессии в базу");
	}elseif(!$sess['id'] = $conf['db']['conn']->lastInsertId()){ pre("ОШИБКА определения идентификатора сессии", $sql);
	}elseif(!$sess = rb("users-sess", "id", $sess['id'])){ pre("ОШИБКА выборки установленной сессии");
	
	}else{ setcookie("sess", $sess['sess'], 0, "/");
		return $sess;
	}
}

# Получение алиаса страницы сайта (Используется для формирования адресов СЕО модуля)
function seo_alias($canonical){// mpre($canonical);
		if(!$uri = call_user_func(function($canonical){
				if(is_array($canonical)){ return get($canonical, 'name');
				}elseif(is_string($canonical)){ return $canonical;
				}else{ return $_SERVER['REQUEST_URI']; }
			}, $canonical)){ mpre("ОШИБКА определения адреса");
		}elseif(!is_array($get = mpgt($uri))){ mpre("ОШИБКА получения параметров адресной строки");
		}elseif(!is_array($mod = get($get, 'm'))){ mpre("ОШИБКА получения параметров адреса");
		}elseif(!$modpath = first(array_keys($mod))){ mpre("ОШИБКА получения модуля из адреса");
		}elseif(!$fn = first($mod) ?: "index"){ mpre("ОШИБКА получения модуля из адреса");
		}elseif(!is_array($get = array_diff_key($get, array_flip(['m'])))){ mpre("ОШИБКА получения параметров без адресации");
		}elseif(!is_array($params = array_keys($get))){ mpre("ОШИБКА получения списка имен параметров");
		}elseif(!$alias = "{$modpath}:{$fn}". ($params ? "/". implode("/", $params) : "")){ mpre("ОШИБКА получения алиаса");
		}else{// mpre($alias)
			return $alias;
		}
}

# Автоподгрузка классов
function PHPClassAutoload($CN){
//	if(!$dirname = __DIR__){ mpre("ОШИБКА установки текущей директории")
//	mpre(getcwd());
	foreach(explode("\\",$CN) as $class_name){
		//For example - include/mail/PHPMailerAutoload.php
		$file_project = mpopendir("include/class/$class_name/$class_name.php");
		$file_single  = mpopendir($file = "include/class/$class_name.php");
		$file_mail    = mpopendir("include/class/mail/class.".strtolower($class_name).".php");
		if($file_project){ include_once $file_project;
		}else if($file_single){ include_once $file_single;
		}elseif($file_mail){ include_once $file_mail;
		}elseif(in_array($class_name, array('Memcached'))){// mpre("Имя класса в массиве");
		}else{ mpre("Файл класса не найден {$file}");
		}
	}
}

# Иницилизация автоподгрузки
//if (version_compare(PHP_VERSION, '5.1.2', '>=')) {
    if (version_compare(PHP_VERSION, '5.3.0', '>=')){
        spl_autoload_register('PHPClassAutoload', true, true);
    } else {
        spl_autoload_register('PHPClassAutoload');
    }
//} else { # Функция устарела в версии 7.2
//    function __autoload($classname){
//        PHPClassAutoload($classname);
//    }
//}

# Генерация base64 последовательности изображения из картинги
function base64($img, $w, $h, $c = 0){
	if(!$img){ mpre("Изображение не задано");
	}elseif(!$file_name = mpopendir("include/{$img}")){ mpre("ОШибка получения пути к файлу");
	}elseif(!$ext = last(array_slice(explode(".", $file_name), -1))){ mpre("Ошибка определения расширения");
	}elseif(!$data = mprs($file_name, $w, $h, $c)){ mpre("Ошибка загрузки содержимого файла");
	}else{ return "data:image/{$ext};base64,". base64_encode($data); }
}

# Подключение хранилища по параметрам указанным в конфигурационном файле
function conn($init = null){
	global $conf;
	try{// die(!pre($conf['db']));
		if(!$type = ($init ? first(explode(":", $init)) : $conf['db']['type'])){ pre("Тип подключения не определен");
		}elseif(!$name = ($init ? last(explode(":", $init)) : $conf['db']['name'])){ pre("Файл не установлен");
		}elseif(!$options = [/*PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,*/PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING, PDO::ATTR_PERSISTENT=>false, PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC, PDO::ATTR_TIMEOUT=>1/*, PDO::SQLITE_MAX_EXPR_DEPTH=>0*/]){ mpre("ОШИБКА задания опций подключения");
		}elseif("sqlite" == $type){
			if(!$realpath = realpath($name)){ mpre("Файл с БД не найден `{$name}`");
			}else if(!is_writable($name)){ die(!pre("ОШИБКА файл БД доступен только на чтение", $name));
			}else{// mpre("Реальный путь до файла бд", $name);
				$conf['db']['conn'] = new PDO($init ?: "{$conf['db']['type']}:{$realpath}", null, null, $options);
				$conf['db']['conn']->exec('PRAGMA foreign_keys=ON; PRAGMA journal_mode=MEMORY;');
			}
		}else{// pre($conf['db']);
			if(!is_string($host = (get($conf, 'db', 'host') ? "host={$conf['db']['host']}" : ""))){ pre("ОШИБКА получения хоста из конфигурации");
			}elseif(!is_string($unix_socket = (get($conf, 'db', 'unix_socket') ? "unix_socket={$conf['db']['unix_socket']}" : ""))){ pre("ОШИБКА получения хоста из конфигурации");
			}elseif(!is_string($addr_conf = ($unix_socket ?: $host))){ pre("ОШИБКА получения адреса подключения host/unix_socket");
			}elseif(!$addr_def = (($default_socket = ini_get("pdo_mysql.default_socket")) ? "unix_socket={$default_socket}" : "host=localhost")){ pre("ОШИБКА получения конфигурационного адреса для подключения");
			}elseif(!$addr = $addr_conf ?: $addr_def){ pre("ОШИБКА установки адреса если не указан локальный");
			}elseif(!$init = ($init ?: "{$conf['db']['type']}:{$addr};dbname={$conf['db']['name']};charset=UTF8")){ pre("ОШИБКА составления строки подключения к БД");
			}elseif(!$conf['db']['conn'] = new PDO($init, $conf['db']['login'], $conf['db']['pass'])){ pre("ОШИБКА подключения к базе данных");
			}elseif(!$TABLES = tables()){ pre("ОШИБКА получения списка таблиц");
			}else{// pre($init, $conf['db']['login'], $TABLES);
				$conf['db']['conn']->exec("set names utf8"); # Prior to PHP 5.3.6, the charset option was ignored
			}
		}// return $conf['db']['conn'];
	}catch(Exception $e){ cache(0);
		die(!pre("Ошибка подключения к базе данных {$init}", $conf['db']['login'], str_repeat('*',strlen($conf['db']['pass']))));
	} 
	return $conf['db']['conn'];
}
//компиляция less в css и сжатие css
function MpLessCompile($teme_folder){
	$old_chdir = getcwd();
	
	$files = getDirContents($teme_folder.'styles/less',"#\.less$#iu");
	if(substr(sprintf('%o', fileperms($teme_folder.'styles/css')), -4)!=='0777')
		die("Please set writing permission (0777) o the following folder: [{$teme_folder}styles/css]!");
	foreach($files as $file){
		$path = preg_replace("#styles/less#iu","styles/css",pathinfo($file,PATHINFO_DIRNAME));
		$name = pathinfo($file,PATHINFO_FILENAME);
		$css  = "{$path}/{$name}.css";	
		if(!is_dir($path)){mkdir($path,0777,true);}
		if(!file_exists($css) OR filectime($file)>filectime($css)){
			if(!isset($less)) $less = new lessc;
			try {
				$CssText = trim($less->compileFile(($file)));
				$CssText = preg_replace("#\s+({\n\r?)#iu","$1",$CssText);
				$CssText = preg_replace("#(\n\r?\s+[^:]+:)\s+#iu","$1",$CssText);
				$CssText = preg_replace("#\n(\r?\s+?)?([\w-}@])#iu","$2",$CssText);					
				file_put_contents($css,$CssText);
			} catch (exception $e) {
				pre($e);
				die();
			}
		}
	}
	
	$files = getDirContents($teme_folder.'styles',"#^(?!.*\.(css|less)$).*$#iu");		
	foreach($files as $file){
		if(!is_dir($file)){		
			if(preg_match("#/styles/css/#iu",$file)){
				if(!realpath($file)){
					unlink($file);
				}
			}else if(preg_match("#/styles/less/#iu",$file)){
				$newfile = preg_replace("#/styles/less(/|$)#iu","/styles/css$1",$file);
				if(!in_array($newfile,$files)){
					$path = pathinfo($newfile,PATHINFO_DIRNAME);
					if(!is_dir($path)){mkdir($path,0777,true);}
					chdir($path);
					symlink($file,pathinfo($newfile,PATHINFO_BASENAME));
				}
			}
		}else if(is_dir_empty($file) AND preg_match("#/styles/css/#iu",$file)){		
			rmdir($file);
		}
	}chdir($old_chdir);	
}

function MpJsAutoMini($teme_folder){
	$old_chdir = getcwd();
	
	$files = getDirContents($teme_folder.'js',"#\.js$#iu");
	if(substr(sprintf('%o', fileperms($teme_folder.'js/_min_')), -4)!=='0777')
		die("Please set writing permission (0777) o the following folder: [{$teme_folder}js/_min_]!");//
	foreach($files as $file){
		if(preg_match("#^(?!.*((^/?)|(/))js/_min_/).*\.js$#iu",$file)){
			$path = preg_replace("#/js(/|$)#iu","/js/_min_$1",pathinfo($file,PATHINFO_DIRNAME));
			$name = pathinfo($file,PATHINFO_FILENAME);
			$js  = "{$path}/{$name}.js";	
			if(!is_dir($path)){mkdir($path,0777,true);}
			if(!file_exists($js) OR filectime($file)>filectime($js)){
				file_put_contents($js,JSCompression::minify(file_get_contents($file)));
			}
		}
	}
	
	$files = getDirContents($teme_folder.'js',"#^(?!.*\.js$).*$#iu");		
	foreach($files as $file){
		if(!is_dir($file)){		
			if(preg_match("#/js/_min_/#iu",$file)){
				if(!realpath($file)){
					unlink($file);
				}
			}else{
				$newfile = preg_replace("#/js(/|$)#iu","/js/_min_$1",$file);
				
				if(!in_array($newfile,$files)){
					$path = pathinfo($newfile,PATHINFO_DIRNAME);
					if(!is_dir($path)){mkdir($path,0777,true);}
					if(!file_exists($newfile) and file_exists($file)){
						chdir($path);
						symlink($file,pathinfo($newfile,PATHINFO_BASENAME));
					}
				}
			}
		}else if(is_dir_empty($file) AND preg_match("#/js/_min_/#iu",$file)){		
			rmdir($file);
		}
	}chdir($old_chdir);
}


function is_dir_empty($dir) {
	if (!is_readable($dir)) return NULL; 
	return (count(scandir($dir)) == 2);
}

function ip(){
	return get($_SERVER,'HTTP_X_REAL_IP')?:get($_SERVER,'REMOTE_ADDR');
}

//возвращает содержимое папки, поумолчанию рекурсивно
function getDirContents($dir, $regexp="", $recursive=true, &$results = array()){
	$files = scandir($dir);
	foreach($files as $key => $value){
		$path = realpath($dir.DIRECTORY_SEPARATOR.$value)?:realpath($dir).DIRECTORY_SEPARATOR.$value;
		if(!is_link($path) AND  is_dir($path) AND $recursive AND $value != "." AND $value != "..") 
			getDirContents($path, $regexp, $recursive, $results);			
		if(!$regexp OR ($regexp AND preg_match($regexp,$value)))
			$results[] = $path;
	}
	return $results;
}
function isJSON($string){
   return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
}
# Генерирование паролей из 
function MpGenPassword($max=24){
	$chars="qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";
	$size=StrLen($chars)-1;
	$password=''; 
    while($max--)
		$password.=$chars[rand(0,$size)];
	return $password;
}

# Получение уникальной директории
function MpGenUniquePath($dir="/tmp"){
	$path = $dir ."/" . MpGenPassword();	
	if(file_exists($path))
		$path = MpGenUniquePath();
	return $path;
}

# Кеширование данных. Функция подключается в index.php два раза.
# Первый раз с пустым параметром cache() второй с содержимым страницы в конце. Содержимое страницы кешируется
# Есть еще один режим cache(0) который вызывает если возник таймаут при подключении базы данных
function cache($content = false){
	global $conf;
	if(!array_search("pdo_sqlite", get_loaded_extensions())){// mpre("В списке доступных модулей не sqlite");
	}else if(!$content){// pre("Отдаем кеш из sqlite");
		if(!$cache_dir = !empty($conf['fs']['cache']) ? mpopendir($conf['fs']['cache']) : (ini_get('upload_tmp_dir') ? ini_get('upload_tmp_dir') : "/tmp"). "/cache"){ mpre("Ошибка установки временной директории кеша");
		}elseif(!$cache_log = dirname($cache_dir). "/cache.log"){ print_r("Ошибка формирования пути лог файла кешей");
//		}elseif(true){ mpre($conf["canonical"]);
		}elseif(is_numeric($content)){ # mpre("Ошибка подключения баз данных");
			if(!$conn_file = "{$cache_dir}/{$conf['settings']['http_host']}.sqlite"){ pre("Ошибка составления имени файла");
			}elseif(!$conn = conn("sqlite:{$conn_file}")){// pre("Ошибка создания подключения sqlite");
			}elseif(!$HTTP_HOST = idn_to_utf8($_SERVER['HTTP_HOST'])){ pre("Ошибка определения русскоязычного имени домена");
			}elseif(!$REQUEST_URI = urldecode($_SERVER['REQUEST_URI'])){ mpre("Ошибка определения адреса");
			}elseif(!$RES = mpqw("SELECT * FROM cache WHERE uri='{$REQUEST_URI}' ORDER BY id DESC LIMIT 1", "uri")){ pre("Ошибка создания запроса");
			}elseif(!($sys_getloadavg = array_map(function($avg){ return number_format($avg, 2); }, sys_getloadavg())) /*&& ($sys_getloadavg[0] <= $sys_getloadavg[1]) && ($sys_getloadavg[1] <= $sys_getloadavg[2])*/){ // mpre("Процессор загрузен меньше среднего значения за 10 и 15 минут");
			}else{
//				error_log(implode("/", $sys_getloadavg). " <<! http://{$HTTP_HOST}{$REQUEST_URI}\n", 3, $cache_log);
				foreach(explode("\n", $row['headers']) as $header){
					header($header);
				} header('Content-Encoding: gzip');
				exit($row['content']);
			}
		}elseif(array_key_exists("null", $_GET)){// pre("null");
		}elseif($_COOKIE['sess']){// pre("Зарегистрированный пользователь");

		}elseif(!call_user_func(function($age){
				header("Cache-Control: max-age={$age}");
				header("Expires: ". gmdate('D, d M Y H:i:s T'));
				return true;
			}, (mpsettings("themes_cahce") ?: 86400*10))){ pre("Ошибка установки заговлоков");
		}elseif(!$REQUEST_URI = urldecode($_SERVER['REQUEST_URI'])){ pre("Ошибка определения адреса");
		}elseif(array_search($_SERVER['REQUEST_URI'], [1=>"/admin", "/users:login", "/users:reg", "/sitemap.xml", "/robots.txt"/*, "/favicon.ico",*/])){ // mpre("Не кешируем системные файлы");
		}elseif($_POST || array_key_exists("sess", $_COOKIE)){// pre("Активная сессия");
		}elseif(get($_SERVER, 'HTTP_IF_MODIFIED_SINCE') || (http_response_code() == 304)){ mpre("Кешированная страница. Отдаем только статус");
//			error_log(implode("/", $sys_getloadavg). " <== 301 http://". ($conf['settings']['http_host']. $REQUEST_URI). "\n", 3, $cache_log);
			exit(header('HTTP/1.0 304 Not Modified'));
/*		}elseif(!call_user_func(function() use($conf, $REQUEST_URI, $sys_getloadavg, $cache_log){ # Отображение ранее сохраненной в мемкаше страницы
				if(!class_exists(Memcached)){ mpre("Класс не найден Memcached");
				}elseif(!$Memcached = new Memcached()){ exit(!!pre("Ошибка создания обьекта мемкаш"));
				}elseif(!$Memcached->addServer('localhost', 11211)){ exit(!!pre("Ошибка подключения к сервису мемкаш"));
				}elseif(!$key = "{$conf['settings']['http_host']}{$REQUEST_URI}"){ mpre("Ошибка составления ключа кеша");
				}elseif(!$cache = $Memcached->get($key)){ // pre("Сохраненная страница в мемкаше не найден");
				}else{ header('Content-Encoding: gzip');
					header('Last-Modified: '. gmdate("r"));
					header('Expires: '.gmdate('r', time() + 86400*10));
					error_log(implode("/", $sys_getloadavg). " <~~ ". http_response_code(). " http://{$key}\n", 3, $cache_log);
					exit($cache);
				}
			})){ mpre("Ошибка выборки данных из мемкаша");*/
//			}elseif(($Memcached = new Memcached()) && ($Memcached->addServer('localhost', 11211)) && ($cache = $Memcached->get($REQUEST_URI))){
		}elseif(!$conn_file = "{$cache_dir}/{$conf['settings']['http_host']}.sqlite"){ mpre("Ошибка составления имени файла");
		}elseif(!file_exists($conn_file) && !touch($conn_file) /*&& !chmod($conn_file, 0777)*/){ mpre("Файл бд кеша не найден {$conn_file}");
		}elseif(!$conn = conn("sqlite:{$conn_file}")){ mpre("Ошибка сохдания подключения sqlite");
		}elseif(!$RES = mpqw("SELECT * FROM cache WHERE uri='{$REQUEST_URI}' ORDER BY id DESC LIMIT 1", "uri")){ mpre("Ошибка создания запроса");
		}elseif(!$row = mpql($RES, 0)){ mpre("Ошибка извлечения строк");
		}elseif(empty($row)){ # Пустой результат
		}elseif((time() - get($row, 'time')) > time()){// pre("Кеш устарел", (time() - get($row, 'time')));
			mpqw("DELETE FROM `cache` WHERE id=". (int)$row['id']);
//		}elseif(true){ pre(time() - get($row, 'time'), $conf['themes_cache']);
		}elseif(call_user_func(function($header){ header($header); }, explode("\n", $row['headers']))){ mpre("Ошибка установки заговловков");
		}else{ header('Content-Encoding: gzip');
			exit($row['content']);
		}
	}else{ # Сохраняем кеш в sqlite
		if(!$cache_dir = !empty($conf['fs']['cache']) ? mpopendir($conf['fs']['cache']) : (ini_get('upload_tmp_dir') ? ini_get('upload_tmp_dir') : "/tmp"). "/cache"){ mpre("Ошибка установки временной директории кеша");
		}elseif(!$cache_log = dirname($cache_dir). "/cache.log"){ print_r("Ошибка формирования пути лог файла кешей");
		}elseif(!$conn_file = "{$cache_dir}/{$conf['settings']['http_host']}.sqlite"){ mpre("Ошибка составления имени файла");
		}elseif(!($sys_getloadavg = array_map(function($avg){ return number_format($avg, 2); }, sys_getloadavg())) /*&& ($sys_getloadavg[0] <= $sys_getloadavg[1]) && ($sys_getloadavg[1] <= $sys_getloadavg[2]) && (rand(0, $sys_getloadavg[0]) <= 1)*/){// mpre("Процессор загрузен меньше среднего значения за 10 и 15 минут");
		}elseif(!$REQUEST_URI = urldecode($_SERVER['REQUEST_URI'])){// mpre("Ошибка определения адреса {$_SERVER['REQUEST_URI']}");
		}elseif(!$gzen = gzencode($content)){ mpre("Ошибка архивирования кода страницы");
		}elseif(array_search($_SERVER['REQUEST_URI'], [1=>"/admin", "/users:login", "/users:reg", "/sitemap.xml", "/robots.txt"/*, "/favicon.ico",*/])){ // mpre("Не кешируем системные файлы");
		}elseif(!is_array($seo_cat = rb("seo-cat", "id", get($conf, "settings", "canonical", "cat_id")))){ mpre("ОШИБКА получения СЕО категории страницы");
		}elseif(!is_numeric($themes_cache = (get($conf, 'themes_cache') ?: 600))){ mpre("ОШИБКА получения времени кеша темы");
		}elseif(!is_numeric($seo_cache = get($seo_cat, "cache") ?: $themes_cache)){ mpre("ОШИБКА получения времени кеширования");
		}elseif(get($_COOKIE, 'sess')){// pre("Зарегистрированный пользователь");
			if(class_exists("Memcached")){ // mpre("Класс Мемкаш не доступен");
				if(!$Memcached = new Memcached()){ mpre("Ошибка создания обьекта мемкаш");
				}elseif(!$Memcached->addServer('localhost', 11211)){ mpre("Ошибка подключения к серверу мемкаш");
				}else{ $Memcached->delete("{$_SERVER['HTTP_HOST']}{$REQUEST_URI}"); }
			}
		}elseif((class_exists("Memcached")) && ($Memcached = new Memcached()) && ($Memcached->addServer('localhost', 11211)) && ($cache = $Memcached->set("{$_SERVER['HTTP_HOST']}{$REQUEST_URI}", $gzen))){
			header('Content-Encoding: gzip');
//			error_log(implode("/", $sys_getloadavg). " ~~> ". http_response_code(). " http://". ($conf['settings']['http_host']. $REQUEST_URI). "\n", 3, $cache_log);
			exit($gzen);
		}elseif(!file_exists($conn_file) && !touch($conn_file) && !mkdir(dirname($conn_file))){ mpre("Файл бд кеша не найден {$conn_file}");
		}elseif(!$conn = conn("sqlite:{$conn_file}")){
		}elseif(!($TABLES = qn("SELECT * FROM sqlite_master WHERE type='table'", "name")) && !($TABLES = call_user_func(function() use($conf, $conn){
				if(!(qw($sql = "CREATE TABLE cache(id INTEGER PRIMARY KEY, time INTEGER, uri TEXT, headers TEXT, content BLOB)", "Создание таблицы кешей", null, null, $conn)) &0){ mpre("Ошибка создания таблицы кешей {$sql}");
				}elseif(qw($sql = "CREATE INDEX `cache-uri` ON `cache` (`uri`);", "Создание ключей", null, null, $conn) &0){ mpre("Ошибка создания ключей таблицы {$sql}");
				}elseif(!$TABLES = qn("SELECT * FROM sqlite_master WHERE type='table'", "name")){ mpre("Ошибка проверки таблицы стилей");
				}else{ return $TABLES; }
			}))){ mpre("Параметры таблицы не определены");
		}elseif(http_response_code() != 200){// pre("Кешируем только корректно отдаваемые страницы");
			$conn->query("DELETE FROM `cache` WHERE `uri`='{$REQUEST_URI}'");
//			error_log(implode("/", $sys_getloadavg). " <<< ". http_response_code(). " http://". ($conf['settings']['http_host']. $REQUEST_URI). "\n", 3, $cache_log);	
		}elseif($conf['user']['sess']['uid']){// mpre("Сохранение действует только для гостей");
			$conn->query("DELETE FROM `cache` WHERE `uri`='{$REQUEST_URI}'");
//			error_log(implode("/", $sys_getloadavg). " xxx ". ($conf['user']['sess']['uid'] <= 0 ? "{$guest['uname']}{$conf['user']['sess']['id']}" : $conf['user']['uname']). " http://". ($conf['settings']['http_host']. $REQUEST_URI). "\n", 3, $cache_log);
		}elseif(!$cache_exists = call_user_func(function($PARAMS) use($conf, $conn){
				try{
					if(!$uri = rb($PARAMS, 'name', '[uri]', 'value')){ pre("Ошибка получения адреса страницы", $PARAMS);
					}elseif(!$result = $conn->query("SELECT * FROM `cache` WHERE `uri`='{$uri}' ORDER BY `id` DESC LIMIT 1")){ pre("Ошибка создания запроса");
					}elseif($cache = $result->fetch(PDO::FETCH_ASSOC)){// pre("Обновление страницы");
						$TYPES = ['id'=>PDO::PARAM_INT, 'time'=>PDO::PARAM_INT, 'headers'=>PDO::PARAM_STR, 'content'=>PDO::PARAM_LOB];
						$result = $conn->prepare("UPDATE `cache` SET `time`=:time, `headers`=:headers, `content`=:content WHERE `id`=:id");
						foreach($PARAMS as $params){
							$result->bindValue($params['name'], $params['value'], $params['type']);
						} $result->execute();
						return $cache['id'];
					}else{ # Добавление новой записи
						$result = $conn->prepare("INSERT INTO cache (time, uri, headers, content) VALUES (:time, :uri, :headers, :content)");
						foreach($PARAMS as $params){
							$result->bindValue($params['name'], $params['value'], $params['type']);
						} $result->execute();
						return -$conn->lastInsertId();
					}
				}catch(Exception $e){ mpre($e); return false; }
			},[['name'=>'time', 'value'=>time()+$seo_cache, 'type'=>PDO::PARAM_INT], ['name'=>'uri', 'value'=>$REQUEST_URI, 'type'=>PDO::PARAM_STR], ['name'=>'headers', 'value'=>implode("\n", headers_list()), 'type'=>PDO::PARAM_STR], ['name'=>'content', 'value'=>$gzen, 'type'=>PDO::PARAM_LOB],])){ mpre("Ошибка установки запроса");
		}else{// return error_log(implode("/", $sys_getloadavg). " ". ($cache_exists > 0 ? "<=>" : ">>>"). " http://". ($conf['settings']['http_host']. $REQUEST_URI). "\n", 3, $cache_log);
		}
	}
}


# Изменяет мета информацию страницы записывая ее в раздел seo
# Первый аргумент массив - array(внутренний, внешний адреса) или строка - внутренний адрес на сайте $_SERVER['REQUEST_URI'], второй параметр - установка метаинформации
function meta($where, $meta = null){
	global $conf;
	if(!$where = (is_array($where) ? $where : [$where])){ mpre("Ошибка установки адреса страницы. Если строкой один адрес то он внутренний");
	}elseif(!$index = get($where, 1)){ mpre("Ошибка внешний адрес не задан");
	}elseif("/" != substr($index, 0, 1)){ mpre("Ошибка. Формат внешнего адреса `{$index}` задан не верно ожидается первый слеш");
	}elseif(!$seo_index = fk('seo-index', $w = ['name'=>$index], $w += $meta)){ mpre("Ошибка установки внешнего адреса `{$name}`");
	}elseif(!$location = get($where, 0)){ mpre("Ошибка не задан внутренний адрес");
	}elseif("/" != substr($location, 0, 1)){ mpre("Ошибка. Формат внутреннего адреса `{$location}` задан не верно ожидается первый слеш");
	}elseif(!$seo_location = fk("seo-location", $w = ['name'=>$location], $w += $meta)){ mpre("Ошибка установки внутреннего адреса `{$location}`");
	}elseif(!$themes_index = get($conf, 'themes', 'index')){// mpre("Многосайтовый режим не установлен");
		if(!$seo_index = fk('seo-index', $w= ['id'=>$seo_index['id']], $w+= ['location_id'=>$seo_location['id']]+$meta, $w)){ mpre("Ошибка добавления внешнего");
		}elseif(!$seo_location = fk('seo-location', $w= ['id'=>$seo_location['id']], $w+= ['index_id'=>$seo_index['id']], $w)){ mpre("Ошибка установки <a href='/seo:admin/r:mp_seo_location_themes?&where[location_id]={$seo_location['id']}&where[themes_index]={$themes_index['id']}'>переадресации</a> `{$seo_location['name']}` на `{$seo_index['name']}`", $w);
		}else{ return $where+$seo_index; }
	}elseif(!$seo_index_themes = fk('seo-index_themes', $w= ['index_id'=>$seo_index['id'], 'themes_index'=>$themes_index['id']], $w+= ['location_id'=>$seo_location['id']]+$meta, $w)){ mpre("Ошибка добавления адресации");
	}elseif(!$seo_location_themes = fk('seo-location_themes', $w= ['location_id'=>$seo_location['id'], 'themes_index'=>$themes_index['id']], $w+= ['index_id'=>$seo_index['id']], $w)){ mpre("Ошибка установки <a href='/seo:admin/r:mp_seo_location_themes?&where[location_id]={$seo_location['id']}&where[themes_index]={$themes_index['id']}'>переадресации</a> `{$seo_location['name']}` на `{$seo_index['name']}`", $w);
	}else{// mpre($seo_index, $seo_location, $where, $meta);
		return $where+$seo_index_themes;
	}
}

//функция скачки файла (чтение файла идет по 5метров)
function file_download ($file,$filename,$mimetype='application/octet-stream',$end=true) {
   if(!$filename) $filename = preg_replace("#.*\/([^\/]+)$#iu",'$1',$file);
   if (file_exists ($file)) {
     header ($_SERVER["SERVER_PROTOCOL"] . ' 200 OK');
     header ('Content-Type: ' . $mimetype);
     header ('Last-Modified: ' . gmdate ('r', filemtime ($file)));
     header ('ETag: ' . sprintf ('%x-%x-%x', fileinode ($file), filesize ($file), filemtime ($file)));
     header ('Content-Length: ' . (filesize ($file)));
     header ('Connection: close');
     header ('Content-Disposition: attachment; filename="'.$filename.'";');
 // Открываем искомый файл
     $f=fopen ($file, 'r');
     while (!feof ($f)) { // Читаем килобайтный блок, отдаем его в вывод и сбрасываем в буфер
       echo fread ($f, 5120);
       flush ();
     }
 // Закрываем файл
     fclose ($f);
   } else {
     header ($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
     header ('Status: 404 Not Found');
   }
if($end)
	exit();
}//функция скачки файла (чтение файла идет по 5метров)
 
# Проверка вхождения тегов в коде и их корректная вложенность друг в друга
# Если вложенность тегов верная возвращается false иначе список незакрытых тегов в форме массива
# Если тегов не найдено, возвращается null
function nesting($text, $tags = array("\? if", "\? endif", "\? foreach", "\? endforeach", "html", "div", "span", "table", "ul", /*"li",*/ "tr", "td", "form", "label", "button", "script", "noscript", /*"p",*/ "a")){
	if(preg_match_all($str = "#<(\/?)(". implode("|", $tags). ")(\s.*?)?>#si", $text, $match)){
		$nesting = $tags = array();// mpre($str, array_slice($match, 1));
		foreach($match[2] as $n=>$tag_name){
			$tn = last($nesting);
			if(($sl = get($match, 1, $n)) && ($tag_name == $tn)){
				$tn = array_pop($nesting);
			}elseif(($tn == "? if") && ($tag_name == "? endif")){
				$tn = array_pop($nesting);
			}elseif(($tn == "? foreach") && ($tag_name == "? endforeach")){
				$tn = array_pop($nesting);
			}else{
				$nesting[$n] = $tag_name; //array_push($nesting, $tag_name);
				$tags[$n] = "&lt;". ($sl ? "/" : ""). $match[2][$n]. $match[3][$n]. "&gt;"; //array_push($tags, "&lt;". $match[2][$n]. $match[3][$n]. "&gt;");
			}
		} return empty($nesting) ? false : array_intersect_key($tags, $nesting);
		// return empty($nesting) ? false : $nesting;
	}else{ return null; }
}

function get($ar){
	foreach(array_slice(func_get_args(), 1) as $key){
		if(!empty($ar) && is_array($ar) && (is_string($key) || is_numeric($key)) && array_key_exists($key, $ar)){
			$ar = $ar[ $key ];
		}else{ return null; }
	} return $ar;
} 
function &get_link(&$ar){
	foreach(array_slice(func_get_args(), 1) as $key){
		if(!empty($ar) && is_array($ar) && (is_string($key) || is_numeric($key)) && array_key_exists($key, $ar)){
			$ar = &$ar[ $key ];
		}else{ return false; }
	} return $ar;
}
function first($ar, $cur = null){
	if(empty($ar)){// mpre("Заданный массив пуст");
	}elseif(!is_array($ar)){// mpre("Заданный массив не является массивом");
	}elseif($cur !== null){// mpre("Выборка следующего элемента за текущим");
		if(!$keys = array_keys($ar)){ mpre("Ошибка составления массива ключей");
		}elseif(is_null($key = array_search($cur, $keys))){ mpre("Ошибка нахождения текущего элемента", $cur, $keys);
		}elseif(!is_numeric($nxt = get($keys, $key-1))){ return []; // mpre("Ошибка нахождения предыдущего элемента");
		}elseif(is_null($next = get($ar, $nxt))){ mpre("Ошибка нахождения предыдущего значения");
		}else{// mpre($next);
			return $next;
		}
	}elseif($keys = array_keys($ar)){
		return get($ar, array_shift($keys));
	}else{ return null; }
} function last($ar, $cur = null){
	if(empty($ar)){// mpre("Заданный массив пуст");
	}elseif(!is_array($ar)){// mpre("Заданный массив не является массивом");
	}elseif($cur !== null){// mpre("Выборка следующего элемента за текущим");
		if(!$keys = array_keys($ar)){ mpre("Ошибка составления массива ключей");
		}elseif(is_null($key = array_search($cur, $keys))){ mpre("Ошибка нахождения текущего элемента", $cur, $keys);
		}elseif(!is_numeric($nxt = get($keys, $key+1))){ return []; // mpre("Ошибка нахождения следующего элемента");
		}elseif(is_null($next = get($ar, $nxt))){ mpre("Ошибка нахождения следующего значения");
		}else{// mpre($next);
			return $next;
		}
	}elseif($keys = array_keys($ar)){
		return get($ar, array_pop($keys));
	}else{ return null; }
}


function tables($table = null){
	global $conf;
	if($conf['db']['type'] == "sqlite"){
		$tpl['tables'] = qn("SELECT * FROM sqlite_master WHERE type='table'", "name");
	}else{
		$tpl['tables'] = qn("SHOW TABLES", "Tables_in_{$conf['db']['name']}");
	}// pre($tpl['tables']);

	if($table){ return get($tpl,'tables',$table); }else{
		ksort($tpl['tables']);// pre(array_keys($tpl['tables']));
		return $tpl['tables'];
	}
} function fields($tab, $type = false){
	global $conf;
	if(!$table = call_user_func(function($tab) use($conf){ # Формирование полного имени таблицы
			if(0 === strpos($tab, $conf['db']['prefix'])){ return $tab; mpre("Адрес таблицы указан полностью");
			}elseif(!strpos($tab, '-')){ return "{$conf['db']['prefix']}{$tab}"; mpre("Адрес таблицы указан полностью");
			}elseif(!$ex = explode('-', $tab)){ mpre("Ошибка разбивки таблицы на составные части");
			}elseif(!$tab = "{$conf['db']['prefix']}{$ex[0]}_{$ex[1]}"){ mpre("Ошибка составления полного имени таблицы");
			}else{// mpre($tab);
				return $tab;
			}
		}, $tab)){ mpre("Ошибка формирования полного имени таблицы");
	}elseif($conf['db']['type'] == "sqlite"){
		$tpl['fields'] = qn("pragma table_info ('". $table. "')", "name");
		if($type){
			$tpl['fields'] = array_column($tpl['fields'], "type", "name");
		}
	}else{
		$tpl['fields'] = qn("SHOW FULL COLUMNS FROM `". $table. "`", "Field");
	} return $tpl['fields'];
}

function indexes($table_name){
	global $conf;
	if(!$table = call_user_func(function($table_name) use($conf){
			if(!strpos($table_name, '-')){ return $table_name;
			}elseif(!$EX = explode("-", $table_name)){ mpre("ОШИБКА парсинга составных частей имени таблицы");
			}elseif(!$table = "{$conf['db']['prefix']}{$EX[0]}_{$EX[1]}"){ mpre("ОШИБКА составления полного имени таблицы");
			}else{ return $table; }
		}, $table_name)){ mpre("ОШИБКА получения имени таблицы");
	}else if($conf['db']['type'] == "sqlite"){
//		mpre($sql = "SELECT * FROM sqlite_master WHERE type='index' AND tbl_name='". mpquot($table). "'", qn($sql));
		return qn("SELECT * FROM sqlite_master WHERE type='index' AND tbl_name='". mpquot($table). "'", "name");
	}else if($conf['db']['type'] == "mysql"){
		return qn("SHOW INDEXES IN {$table}", "Column_name");
	}
}
# Подключение страницы
/*function inc($file_name, $variables = [], $req = false){
	global $conf; extract($variables);
	if(preg_match("#(.*)(\.php|\.tpl|\.html)$#", $file_name, $match)){
		global $tpl;
		if($f = mpopendir($file_name)){
			$_arg = $GLOBALS['arg'];
			if(!array_key_exists('arg', $variables)){ # Если не переопределяем список аргументов
				if(($path = explode("/", $file_name)) && ($path[0] == "modules")){
					if($mod = get($conf, 'modules', $path[1])){
						$GLOBALS['arg'] = $arg = array("modpath"=>$path[1], 'modname'=>$mod['modname'], "admin_access"=>$mod['admin_access'], "fn"=>first(explode(".", $path[2])));
					}
				}
			} if(array_search("Администратор", get($conf, 'user', 'gid'))){
				ob_start();
					call_user_func_array(function($f, $variables, $req) use(&$conf, &$arg, &$tpl,&$return){ extract($variables); ($req ? require($f) : include($f)); }, [$f, $variables, $req]);
					$content = ob_get_clean();
				if((".tpl" == get($match, 2))){
					echo strtr(get($conf, 'settings', 'modules_start'), array('{path}'=>$f));
					if($nesting = nesting($content)){
						mpre("Ошибка верстки. Нарушена структура вложенности тегов.", $f, $nesting);
					}
				} echo $content;
				if((".tpl" == get($match, 2))){
					echo strtr(get($conf, 'settings', 'modules_stop'), array('{path}'=>$f));
				}
				$GLOBALS['arg'] = $_arg;
				return true;
			}else{
				if($req){ require $f; }else{ include $f; }
				$GLOBALS['arg'] = $_arg;
				return true;
			}
			$GLOBALS['arg'] = $_arg;
			return false;
		}else if(!array_key_exists('arg', $variables)){ # Установленная переменная arg признак не выводить ошибку
			mpre("Подключаемый файл не найден", $file_name);
		}
	}else{
		$php = inc("{$file_name}.php", $variables, $req, $return);
		if($return){// mpre("Ошибочный возвращенный статус 1");	
			return 0;
		}else{
			$tpl = inc("{$file_name}.tpl", $variables, $req);
			if($return){	// mpre("Ошибочный возвращенный статус 1");
				return 0;
			}
		}
		return ($php || $tpl);
	} return false;
}*/

# Подключение фала. Путь должен быть от корня сайта /modules/pages/index Можно указать расширение .tpl .php В случае успешного подключения возвращается ноль. На ошибке выполнение прекращается
function inc($file_name, $variables = [], $req = false){ global $conf, $tpl;
	if(extract($variables) &&0){ mpre("Ошибка восстановления переданных значений");
//	}elseif((strpos($file_name, 'admin.tpl')) && mpre($file_name, debug_backtrace())){
	}elseif(!preg_match("#(.*)(\.php|\.tpl|\.html)$#", $file_name, $match)){// mpre("Расширение не указано подключаем оба формата `{$file_name}`");
		/*if((strpos($file_name, 'admin.tpl')) && !mpre($file_name, debug_backtrace())){
		}else*/if($php = inc("{$file_name}.php", $variables, $req, $tpl)){// mpre("Вернулась ошибка - не запускаем шаблон");
			return $php;
		}else{// mpre("При запуске скрипта ошибок не возникло - отображаем шаблон");
			$tpl = inc("{$file_name}.tpl", $variables, $req, $tpl);
			return ($tpl ?: $php);
		}
	}elseif(!$file = mpopendir($file_name)){// mpre("Файл в файловой системе не найден `{$file_name}`");
//	}elseif((strpos($file_name, 'admin.tpl')) && !mpre($file_name, $file)){
	}elseif(($_arg = $GLOBALS['arg']) &&0){ mpre("Ошибка сохранения вышестоящих аргументов");
	}elseif(!$path = explode("/", $file_name)){ mpre("Ошибка разбивки директорий разделителем");
	}elseif(!$path[0] && (!$path = array_slice($path, 1))){ mpre("Ошибка правки смещения при первом левом слеше");
	}elseif(!$path[0] == "modules"){ mpre("Файл не из директории с модулями `{$file_name}`", $path);
	}elseif(($mod = get($conf, 'modules', $path[1])) &&0){ mpre("Директория раздела не установлена", $path);
	}elseif(!$arg = array("modpath"=>$path[1], 'modname'=>$mod['modname'], "admin_access"=>$mod['admin_access'], "fn"=>first(explode(".", get($path, 2))))){ mpre("Ошибка установки аргументов файла");
	}elseif($_RETURN = false){ mpre("Установка значения возврата");
	}elseif(!is_string($content = call_user_func(function($file, $content = '') use(&$conf, &$tpl, &$_RETURN, $arg, $file_name, $match, $variables, $req){
			if(($modules_start = get($conf, 'settings', 'modules_start')) && (!$modules_start = strtr($modules_start, ['{path}'=>$file]))){ mpre("Установка путь до файла в подсказку");
			}elseif(($modules_stop = get($conf, 'settings', 'modules_stop')) && (!$modules_stop = strtr($modules_stop, ['{path}'=>$file]))){ mpre("Установка путь до файла в подсказку");
			}elseif(($GLOBALS['arg'] = $arg) &&0){ mpre("Установка глобального параметра для использования в запросах блоков");
			}elseif(($content = call_user_func(function($file) use(&$conf, $variables, &$tpl, $req, &$arg, &$_RETURN){
					ob_start(); extract($variables);
					($req ? require($file) : include($file));
					return ob_get_clean();
				}, $file)) &&0){ mpre("Ошибка получения вывода файла");
			}elseif(!array_search("Администратор", get($conf, 'user', 'gid'))){ return $content; mpre("Не администраторам не доступны подсказки");
			}elseif(!preg_match("#(.*)(\.tpl|\.html)$#", $file_name, $match)){ return $content; mpre("Только шаблоны оборачиваются подсказками");
			}elseif(!$content = "{$modules_start}{$content}{$modules_stop}"){ mpre("Ошибка добавления тегов подсказок администратору");
			}else{ return $content; }
		}, $file))){ mpre("Ошибка получения содержимого файла `{$file}`");
	}elseif(($GLOBALS['arg'] = $_arg) &&0){ mpre("Ошибка возврата сохраненных значений аргумента");
	}else{ echo $content;
		return $_RETURN;
	}
}

# Функция определения seo вдреса страницы. Если адрес не определен в таблице seo_redirect то false
# Параметр return определяет возвращать ли ссылку обратно если переадресация не найдена
function seo($href, $return = true){
	global $conf;
	if($seo_location = rb("seo-location", "name", "id", (is_string($href) ? "[$href]" : true), (is_numeric(get($href, "id")) ? $href['id'] : true))){
		if(array_key_exists("index_id", $seo_location) && $seo_location['index_id']){ # Односайтовый режим
			if(!$index = rb("seo-index", 'id', $seo_location['index_id'])){ return $href;
			}else{ return $index['name']; }
		}elseif($themes_index = get($conf, 'user', 'sess', 'themes_index')){ # МногоСайтов
			if(!$SEO_INDEX_THEMES = rb("seo-index_themes", "location_id", "themes_index", "id", $seo_location['id'], $themes_index['id'])){ return $href;
			}elseif(!$tpl['index'] = rb("seo-index", "id", "id", rb($SEO_INDEX_THEMES, "index_id"))){ return $href;
			}elseif(count($tpl['index']) == 1){ return get(first($tpl['index']), 'name');
			}elseif($themes_index = last($tpl['index'])){ return $themes_index['name'];
			}else{ mpre("Внешний адрес дублируется <a href='/seo:admin/r:mp_seo_index_themes?&where[location_id]={$seo_location['id']}&where[themes_index]={$themes_index['id']}'>не найден</a>", $SEO_INDEX_THEMES); }
		}else{ return $href; }
	}else{ return $href; }
}

if (!function_exists('modules')){
	function modules($content){ # Загрузка содержимого модуля
		global $conf, $arg, $tpl;
		foreach($_GET['m'] as $k=>$v){ $k = urldecode($k);
			if(!$mod = (get($conf, 'modules', $k) ?: rb(get($conf, 'modules'), "modname", "[{$k}]"))){ pre("Модуль `{$k}` недоступен");
			}elseif(!$mod['link'] = (is_link($f = mpopendir("modules/{$mod['folder']}")) ? readlink($f) : $mod['folder'])){ pre("Ошибка определения ссылки на раздел");
			}elseif(!ini_set("include_path" ,mpopendir("modules/{$mod['link']}"). ":./modules/{$mod['link']}:". ini_get("include_path"))){ pre("Сбой добавления локального пути до скриптов");
			}elseif((!$MODULES_INDEX_UACCESS = mpqn(mpqw("SELECT *, admin_access AS admin_access FROM `{$conf['db']['prefix']}modules_index_uaccess` WHERE `mid`=". (int)$mod['id']. " AND `uid`=". (int)$conf['user']['uid'], "Запрос прав доступа пользователя к разделу", function($error) use($conf){
					if(!strpos($error, "Unknown column 'admin_access'")){ pre("Неопределенная ошибка", $error);
					}else{ qw(mpre("ALTER TABLE `{$conf['db']['prefix']}modules_index_uaccess` CHANGE `access` `admin_access` int(11) NOT NULL")); }
				}))) &0){ mpre("Разрешения для группы");
			}elseif((!$MODULES_INDEX_GACCESS = mpqn(mpqw("SELECT *, admin_access AS admin_access FROM `{$conf['db']['prefix']}modules_index_gaccess` WHERE `mid`=". (int)$mod['id']. " AND `gid` IN (". in($conf['user']['gid']). ")", "Запрос прав доступа групп пользователя", function($error) use($conf){
					if(!strpos($error, "Unknown column 'admin_access'")){ pre("Неопределенная ошибка", $error);
					}else{ qw(mpre("ALTER TABLE `{$conf['db']['prefix']}modules_index_gaccess` CHANGE `access` `admin_access` int(11) NOT NULL")); }
				}))) &0){ mpre("Разрешения для группы");
//		}elseif(!is_numeric($access = (("admin" == $mod['folder'] || (strpos($v, "admin") === 0)) ? 4 : 1))){ mpre("Ошибка подсчета дефолтного доступа к разделам");
			}elseif(!is_numeric($access = call_user_func(function($v) use($mod){
					if("admin" === $v){ return 5;
					}elseif("admin" == $mod['folder']){ return 4;
					}elseif(strpos($v, "admin") === 0){ return 4;
					}else{ return 1; }
				}, $v))){
			}elseif(!$gmax = ($MODULES_INDEX_GACCESS ? max(array_column($MODULES_INDEX_GACCESS, 'admin_access')) : 1)){ mpre("Ошибка максимального разрешения для группы");
			}elseif(!$umax = ($MODULES_INDEX_UACCESS ? max(array_column($MODULES_INDEX_UACCESS, 'admin_access')) : 1)){ mpre("Ошибка максимального разрешения для пользователя");
			}elseif(!is_numeric(array_search($conf['user']['uname'], explode(',', $conf['settings']['admin_usr']))) && (max($umax, $gmax) < $access)){// mpre("Недостаточно прав доступа к разделу", $umax, $gmax, $access);
				if(!mpopendir($f = "modules/admin/deny.tpl")){ pre("Не найдена страница запрета доступа");
					header("HTTP/1.0 403 admin_access Denied");
					exit(header("Location: /users:login"));
				}else{
					$conf["deny"] = true;
					ob_start();
						inc("modules/admin/deny");				
					$content = ob_get_clean();
				}
			}elseif(!$v = $v != 'del' && $v != 'init' && $v != 'sql' && strlen($v) ? $v : 'index'){ pre("Ошибка определения имени скрипта");
			}elseif(!$conf['db']['info'] = "Модуль '". ($name = $mod['name']). "'"){ pre("Ошибка установки описания запрос к БД");
//			}elseif(!$g = (preg_match("/[a-z]/", $v) ? "/{$v}.*.php" : "/*.{$v}.php")){ pre("Ошибка определения шаблона запроса к файловой системе");
			}elseif(!$g = "/{$v}.*"){ pre("Ошибка определения шаблона запроса к файловой системе");
			}elseif((!$glob = glob($gd = mpopendir("modules/{$mod['link']}"). $g)) && (!$glob = glob($gd = "modules/{$mod['link']}". $g)) &0){ pre("Список файлов раздела {$gd}");
			}elseif(!empty($glob) && (!$glob = basename(array_pop($glob))) && (!$g = explode(".", $glob)) && ($v = array_shift($g))){ pre("Ошибка определения имен файлов");
//			}elseif(!$fe = ((strpos($_SERVER['HTTP_HOST'], "xn--") !== false) && (count($g) > 1)) ? array_shift($g) : $v){ mpre("Ошибка определения русскоязычного названия имени");
			}elseif(!$arg = array('modpath'=>$mod['folder'], 'modname'=>$mod['modname'], 'fn'=>$v, "fe"=>"", 'admin_access'=>$mod['admin_access'])){ pre("Ошибка формирования аргументов страницы");
			}elseif($v == "admin"){
				ob_start();
					if(!is_null($return = inc("modules/{$mod['link']}/admin", array('arg'=>array('modpath'=>$mod['link'], 'fn'=>'admin'))))){// mpre("Успешный запуск админстраницы", $return);
					}else{// mpre("return", var_dump($return));
						inc("modules/admin/admin", array('arg'=>array('modpath'=>$mod['link'], 'fn'=>'admin')));
					}
				$content .= ob_get_contents(); ob_end_clean();
			}else{// mpre($_SERVER['REQUEST_URI']);
					if(!get($conf, 'settings', 'seo_meta')){ mpre("Обработкич мета информации страницы выключен");
					}elseif($get = []){ mpre("Задание параметров запроса");
					}elseif(!$uri = get($canonical = get($conf, 'settings', 'canonical'), 'name') ? get($canonical, 'name') : $_SERVER['REQUEST_URI']){ mpre("Ошибка формирования адреса страницы");
					}elseif(!$get = mpgt($uri)){// mpre("Параметры в адресе не заданы");
					}elseif(array_key_exists("null", $get)){// mpre("Аякс запрос");
					}elseif(array_key_exists("p", $get)){// mpre("Установлена пагинация старницы");
					}elseif($conf['settings']['theme/*:admin'] == $conf['settings']['theme']){// mpre("Админ страница");
					}elseif(array_search($arg['fn'], ['', 'ajax', 'json', '404'])){// mpre("Список исключенных для СЕО старниц");
					}else{// mpre($canonical);
						inc("modules/seo/admin_meta.php", array('arg'=>$arg, "uri"=>$uri, "get"=>$get, "canonical"=>$canonical));
					}
					ob_start();
						if(is_null($return = inc("modules/{$mod['link']}/{$v}", array('arg'=>$arg)))){ # Если не создано скриптов и шаблона для страницы запускаетм общую (аля 404 для модуля)
							inc("modules/{$mod['link']}/default.tpl", array('arg'=>$arg));
						}
					$content .= ob_get_contents(); ob_end_clean();
			}
		} return $content;
	}
}

if(!function_exists('blocks')){
	function blocks($bid = null){# Загружаем список блоков и прав доступа
		global $conf, $arg;
		$result = [];// mpre($bid);
		if(!$conf['db']['info'] = "Выборка шаблонов блоков"){ pre("Установка описания запросам");
		}elseif(!$BLOCKS = mpql(mpqw($sql = "SELECT *, `admin_access` as admin_access FROM {$conf['db']['prefix']}blocks_index WHERE hide=0". ($bid ? " AND id=". (int)$bid : " ORDER BY sort"), "Запрос списка блоков", function($error) use($conf){
				if(strpos($error, "Unknown column 'admin_access'")){
					qw(pre("ALTER TABLE `mp_blocks_index` CHANGE `access` `admin_access` smallint(6) NOT NULL COMMENT ''"));
					qw(pre("ALTER TABLE `mp_blocks_index` ADD INDEX (`admin_access`)"));
				}elseif(strpos($error, "Unknown column 'hide' in 'where clause'")){
					qw(pre("ALTER TABLE {$conf['db']['prefix']}blocks_index CHANGE `enabled` `hide` smallint(6) NOT NULL", $error));
					qw("UPDATE {$conf['db']['prefix']}blocks_index SET hide=-1 WHERE hide=1; UPDATE {$conf['db']['prefix']}blocks_index SET hide=1 WHERE hide=0; UPDATE {$conf['db']['prefix']}blocks_index SET hide=0 WHERE hide=-1");
				}
			}))){ pre("Список блоков не найден");
//		}elseif(true){ mpre($BLOCKS);
		}elseif(!$BLOCKS_REG = mpqn(mpqw("SELECT *, `name` as name FROM {$conf['db']['prefix']}blocks_reg", "Запрос списка регионов", function($error, $conf){
				if(strpos($error, "Unknown column 'name' in 'field list'")){
					qw(pre("ALTER TABLE {$conf['db']['prefix']}blocks_reg CHANGE `description` `name` varchar(255)", $error));
				}else{ mpre("Ошибка не определена", $error); }
			}))){ pre("Список регионов блоков не задан");
		}elseif((!$BLOCKS_INDEX_UACCESS = mpqn(mpqw("SELECT *, admin_access AS admin_access FROM `{$conf['db']['prefix']}blocks_index_uaccess` WHERE `uid`=". (int)get($conf, 'user', 'uid'), "Запрос прав доступа пользователя к разделу", function($error) use($conf){
				if(!strpos($error, "Unknown column 'admin_access'")){ pre("Неопределенная ошибка", $error);
				}else{ qw(mpre("ALTER TABLE `{$conf['db']['prefix']}blocks_index_uaccess` CHANGE `access` `admin_access` int(11) NOT NULL")); }
			}))) &0){ mpre("Разрешения для пользователя");
		}elseif((!$BLOCKS_INDEX_GACCESS = mpqn(mpqw("SELECT *, admin_access AS admin_access FROM `{$conf['db']['prefix']}blocks_index_gaccess` WHERE `gid` IN (". in($conf['user']['gid']). ")", "Запрос прав доступа групп пользователя", function($error) use($conf){
				if(!strpos($error, "Unknown column 'admin_access'")){ pre("Неопределенная ошибка", $error);
				}else{ qw(mpre("ALTER TABLE `{$conf['db']['prefix']}blocks_index_gaccess` CHANGE `access` `admin_access` int(11) NOT NULL")); }
		}))) &0){ mpre("Разрешения для группы");
		}else{// mpre($BLOCKS);
			foreach($BLOCKS as $k=>$block){
				if(!$theme = ((substr($block['theme'], 0, 1) == "!") && ($conf['settings']['theme'] != substr($block['theme'], 1)) ? $conf['settings']['theme'] : $block['theme'])){ mpre("Ошибка расчета темы с учетом отрицания {$block['theme']}");
				}elseif(($conf['settings']['theme'] != $block['theme']) && ($conf['settings']['theme'] != $theme)){// mpre("У блока отмечен другой шаблон", $theme);
				}elseif(!$conf['db']['info'] = "Блок '{$block['name']}'"){ pre("Описание к запросам блока");
				}elseif(!$mod = get($conf, 'modules', basename(dirname(dirname($block['src'])))) ?: array("folder"=>'')){ mpre("Ошибка определения модуля");
				}elseif(!$arg = array('blocknum'=>$block['id'], 'modpath'=>$mod['folder'], 'modname'=>(get($mod, 'modname') ?: ""), 'fn'=>basename(first(explode('.', $block['src']))), 'uid'=>0, 'admin_access'=>$block['admin_access'])){ pre("Ошибка формирования аргументов блока");
				}elseif(!is_numeric($access = $block['admin_access'])){ mpre("Разрешения для блока");
				}elseif((!$_BLOCKS_INDEX_UACCESS = rb($BLOCKS_INDEX_UACCESS, "index_id", "id", $block['id'])) &0){ mpre("Разрешения пользователя для конкретного блока");
				}elseif((!$_BLOCKS_INDEX_GACCESS = rb($BLOCKS_INDEX_GACCESS, "index_id", "id", $block['id'])) &0){ mpre("Разрешения группы для конкретного блока");
				}elseif(!is_numeric($gmax = ($_BLOCKS_INDEX_GACCESS ? max(array_column($_BLOCKS_INDEX_GACCESS, 'admin_access')) : $access))){ mpre("Ошибка максимального разрешения для группы");
				}elseif(!is_numeric($umax = ($_BLOCKS_INDEX_UACCESS ? max(array_column($_BLOCKS_INDEX_UACCESS, 'admin_access')) : $access))){ mpre("Ошибка максимального разрешения для пользователя");
				}elseif(!is_numeric(array_search($conf['user']['uname'], explode(',', $conf['settings']['admin_usr']))) && (max($umax, $gmax) < 1)){// mpre("Недостаточно прав доступа к разделу");
				}elseif($conf["settings"]["bid"] = $bid){ pre("Блок к которому мы обращаемся в параметрах блока");// $result = $cb;
				}elseif(!is_string($cb = call_user_func(function($block) use($arg){ # Получения содержимого блока
						ob_start();
							if(inc("modules/{$block['src']}", array('arg'=>$arg))){ mpre("ОШИБКА подключения блока `{$block['src']}`");
							}else{// mpre(123);
							} $cb = ob_get_contents();
						ob_end_clean();
						return $cb;
					}, $block))){ mpre("ОШИБКА получения содержимого блока", $block);
				}elseif(!$shablon[ $block['shablon'] ] = call_user_func(function($block) use($conf){ # Получение шаблона блока
						if(is_numeric($block['shablon'])){// mpre("Номер в имени файла шаблона блока");
						}elseif(!get($block, 'shablon')){// mpre("ШАБЛОН НЕ задан");
						}elseif(!$file = "themes/{$conf['settings']['theme']}/{$block['shablon']}"){ mpre("ОШИБКА получения короткого пути до файла шаблона блока");
						}elseif(!$file_name = mpopendir($file)){ mpre("Полный путь до шаблона указанного в блоке не найден `{$file}` блок `{$block['name']}`");
						}else{ return $file_name;
						} return "<!-- [block:content] -->";
					}, $block)){ mpre("ОШИБКА получения шаблона блока");
				}elseif(!is_string($cb = strtr($shablon[ $block['shablon'] ], $w = array( # Замена элементов в шаблоне
						'<!-- [block:content] -->'=>$cb,
						'<!-- [block:id] -->'=>$block['id'],
						'<!-- [block:name] -->'=>$block['name'],
						'<!-- [block:modpath] -->'=>$arg['modpath'],
						'<!-- [block:fn] -->'=>$arg['fn'],
						'<!-- [block:title] -->'=>$block['name']
					)))){ mpre("ОШИБКА получения обработанного блока", $w);
				}elseif(!$section = array("{modpath}"=>$arg['modpath'],"{modname}"=>$arg['modname'], "{name}"=>$block['name'], "{fn}"=>$arg['fn'], "{id}"=>$block['id'])){ print_r("Ошибка создания массива замены");
				}elseif(($blocks_start = get($conf, 'settings', 'blocks_start')) &&0){ print_r("Начальна строка замены не найдена");
				}elseif(($blocks_stop = get($conf, 'settings', 'blocks_stop')) &&0){ print_r("Конечная строка замены не установлена");
				}elseif(!$key = "<!-- [block:{$block['id']}] -->"){ print_r("Ошибка вычисления троки замены блока");
				}elseif(!$block_text = "{$blocks_start}{$cb}{$blocks_stop}"){// print_r("Ошибка расчета содержимого блока");
				}elseif(!$result[$key] = strtr($block_text, $section)){ print_r("Ошибка установки содержимого блока");
				}elseif(get($block, 'alias') && ($n = "<!-- [block:{$block['alias']}] -->") && (!$result[$n] = get($result, $n). $result["<!-- [block:{$block['id']}] -->"])){ # Ошибка установки содержимого по алиасу
				}elseif(($n = "<!-- [blocks:". $block['reg_id'] . "] -->") && (!$result[$n] = get($result, $n). $result["<!-- [block:{$block['id']}] -->"])){ mpre("Ошибка добавления блока по номеру группы");
				}elseif(!is_string($section_start = strtr(get($conf, 'settings', $t = 'blocks_start'), $section))){ mpre("ОШИБКА замены тега `{$t}`");
				}elseif(!is_string($section_stop = strtr(get($conf, 'settings', $t = 'blocks_stop'), $section))){ mpre("ОШИБКА замены тега `{$t}`");
				}else{// mpre($section);
					$result[$key] = $section_start. $cb. $section_stop;
				}
			} return $result;
		}
	}
}

function gvk($array = array(), $field=false){ 
	return isset($array[$field]) ? $array[$field] : FALSE;
}
//проверка на ассоциативность массива
function mp_is_assoc($array){	
	if(key($array)===0){
		$keys = array_keys($array);
		return array_keys(array_keys($array)) !== array_keys($array);
	}else{
		return true;
	}
}
//проверка на одномерность массива
function mp_array_is_simple($array){
	return count($array, COUNT_NORMAL)===count($array, COUNT_RECURSIVE);
}
//форматирование массива - приведение двухмерного массива к нужному формату
function mp_array_format($array,$array_format){
	$buf = array();
	if(is_array($array) AND (is_array($array_format) OR is_string($array_format))){
		foreach($array as $key => $value){
			if(is_array($array_format)){
				if(!isset($buf[$key])) $buf[$key] = array();				
				foreach($array_format as $key_from => $key_to){						
					if(is_string($key_from)){	
						if(array_key_exists((string)$key_from,$value))
							$buf[$key][(string)$key_to] = $value[(string)$key_from];
					}else{
						if(array_key_exists((string)$key_to,$value))
							$buf[$key][(string)$key_to] = $value[(string)$key_to];
					}					
				}
			}else if(is_string($array_format)){				
				if(!isset($buf[$key])) $buf[$key] = array();					
				if(array_key_exists((string)$array_format,$value))
					$buf[$key][(string)$array_format] = $value[(string)$array_format];				
			}
		}
	}
	return $buf?:$array;
}

set_error_handler(function ($errno, $errmsg, $filename, $linenum, $vars){
	global $conf;
	if(!$errortype = array (
			1   =>  "Ошибка",
			2   =>  "Предупреждение",
			4   =>  "Ошибка синтаксического анализа",
			8   =>  "Замечание",
			16  =>  "Ошибка ядра",
			32  =>  "Предупреждение ядра",
			64  =>  "Ошибка компиляции",
			128 =>  "Предупреждение компиляции",
			256 =>  "Ошибка пользователя",
			512 =>  "Предупреждение пользователя",
			1024=>  "Замечание пользователя",
			2048=> "Обратная совместимость",
		)){ mpre("ОШИБКА установки массива ошибок");
	}elseif(!$file_info = "{$filename}:{$linenum}"){ mpre("ОШИБКА получения информаци о файле и строке");
	}elseif(!$type_num = (($type = get($errortype, $errno)) ? "{$type} ({$errno})" : "Неустановленный тип ошибки ({$errno})")){ mpre("Тип ошибки не установлен");
	}elseif(!$pdo = (0 === strpos($errmsg, 'PDO::query():'))){ mpre($file_info, $type_num, $errmsg);
	}elseif(!$conn = $conf['db']['conn']){ mpre("ОШИБКА определения соединения с базой данных");
	}elseif(!$info = last($conf['db']['sql'])){ mpre("ОШИБКА получения запроса", $conf['db']);
	}elseif(!$error = (last($conn->errorInfo()) ?: $errmsg)){ mpre("Текст ошибки не установлен", $info['sql']);
	}else{ mpre($file_info, $type_num, $error, $info['sql']);
		mpevent($type, $error, ["Файл"=>$file_info, "Номер ошибки"=>$type_num, "Ошибка"=>$error, "Запрос"=>$info['sql']]);
	}
});
function mpzam($ar, $name = null, $prefix = "{", $postfix = "}", $separator = ":"){ # Создание из много мерного массиива - одномерного. Применяется для подставки в текстах отправляемых писем данных из массивов
	$f = function($ar, $prx = "") use(&$f, $prefix, $postfix, $separator, $name){
		$r = array();
		foreach($ar as $k=>$v){
			$pr = ($prx ? $prx.":".$k : $k);
			if(is_array($v)){
				$r += $f($v, $pr);
			}else{
				$r[$prefix. ($name ? "{$name}". ($separator ?: ":") : ""). $pr. $postfix] = $v;
			}
		} return $r;
	}; return $f($ar);
}
function in($ar, $flip = false){ # Формирует из массива строку с перечисляемыми ключами для подставки в запрос
	if(!is_array($ar) || empty($ar)){
		$ar = array(0);
	}else if($flip){
		 $ar = array_flip($ar);
	} return implode(",", array_map(function($key){
		return (is_numeric($key) || ($key == "NULL")) ? $key : "\"". mpquot($key). "\"";
	}, array_keys($ar)));
}
function aedit($href, $echo = true, $title = null){ # Установка на пользовательскую старницу ссылки в административные разделы. В качестве аргумента передается ссылка, выводится исходя из прав пользователя на сайте
	global $arg, $conf;
	if(!$append = preg_match("#\?#iu",$href) ? "&" : "?"){ mpre("ОШИБКА установки разделителя");
	}elseif(get($conf, 'settings', 'admin_go_to_save') && (!$href .= "{$append}go_to_save=".urlencode($_SERVER['REQUEST_URI']))){ mpre("ОШИБКА добавления адреса сохранения");
	}elseif(!$echo){ return $href;
	}elseif(!array_search("Администратор", $conf['user']['gid'])){ mpre("Выводим только администраторам");
	}else{
			echo "<div class=\"aedit\" style=\"position:relative; left:-20px; z-index:700; float:right;\"><span style=\"float:right; margin-left:5px; position:absolute;\"><a href=\"{$href}\" title=\"". $title. "\" ><img src=\"/img/aedit.png\" style='max-width:10px; max-height:10px; width:10px; height:10px;'></a></span></div>";
	}
}



function mptс($time = null, $format = 0){ # Приведение временных данных у удобочитаемую человеческую форму. Обычно для вывода на пользовательские страницы
	if($time === null) $time = time();
	$time = time()-$time;
	$month = explode(",", $conf['settings']['themes_month']);
	$days = floor($time/86400);
	$hours = floor($time/3600)%60;
	$minutes = floor($time/60);
	if($format == 1){
		return ($time > 86400 ? str_pad($days, 2, '0', STR_PAD_LEFT). ":" : "")
				. str_pad($hours%24, 2, '0', STR_PAD_LEFT). ":"
				. str_pad($minutes%60, 2, '0', STR_PAD_LEFT). ":"
				. str_pad($time%60, 2, '0', STR_PAD_LEFT);
	}else{
		return ($days ? " {$days} ". mpfm($days, "день", "дня", "дней") : "").
				($hours ? " ". ($hours%24). " ". mpfm($hours, "час", "часа", "часов") : "").
				($minutes ? " ". ($minutes%60). " ". mpfm($minutes, "минута", "минуты", "минут")  : "");
	}
}
if(!function_exists("mb_ord")){
	function mb_ord($char){
			list(, $ord) = unpack('N', mb_convert_encoding($char, 'UCS-4BE', 'UTF-8'));
			return $ord;
	}
} if(!function_exists("mb_ord")){
	function mb_chr($string){
			return html_entity_decode('&#' . intval($string) . ';', ENT_COMPAT, 'UTF-8');
	}
}
# Вызов библиотеки curl Для хранения файла кукисов используется текущая директория. Первым параметром передается адрес запрос, вторым пост если требуется
function mpcurl($href, $post = null, $temp = "cookie.txt", $referer = null, $headers = array(), $proxy = null){
	$ch = curl_init();
	if($proxy){
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		curl_setopt($ch, CURLOPT_PROXY, $proxy); //если нужен прокси
	}
//	curl_setopt ($ch , CURLOPT_FOLLOWLOCATION , true);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $temp);//tempnam(ini_get('upload_tmp_dir'), "curl_cookie_")
	curl_setopt($ch, CURLOPT_COOKIEJAR, $temp); //В какой файл записывать
	curl_setopt($ch, CURLOPT_URL, $href); //куда шлем
	if($post){
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post /*, iconv('utf-8', 'cp1251', $post)*/);
	}
	if ($referer) curl_setopt($ch, CURLOPT_REFERER, $referer);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0; MyIE2; .NET CLR 1.1.4322)");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_NOBODY, 0);
	$result=curl_exec ($ch);
	curl_close ($ch);
	return $result;
}
# единственные двойственные и множественные числительные. Пример использования mpfm($n, 'письмо', 'письма', 'писем');
function mpfm($n, $form1, $form2, $form5){
    $n = abs($n) % 100;
    $n1 = $n % 10;
    if ($n > 10 && $n < 20) return $form5;
    if ($n1 > 1 && $n1 < 5) return $form2;
    if ($n1 == 1) return $form1;
    return $form5;
}
# Кеширование данных в memcache
function mc($key, $function, $force = false){
	if($force !== false) mpre($key);
	if(!($tmp = mpmc($key)) || $force){
		mpmc($key, $tmp = $function($key));
		if($force !== false) mpre($tmp);
	} return $tmp;
}
function mp_is_html($string){
  return preg_match("/<[^<]+>/",$string,$m) != 0;
}
function normalize_files_array($files = []) {
	if(!$files)
		$files = $_FILES;
	$normalized_array = [];
	foreach($files as $index => $file) {
		if (!is_array($file['name'])) {
			$normalized_array[$index][] = $file;
			continue;
		}
		foreach($file['name'] as $idx => $name) {
			$normalized_array[$index][$idx] = [
				'name' => $name,
				'type' => $file['type'][$idx],
				'tmp_name' => $file['tmp_name'][$idx],
				'error' => $file['error'][$idx],
				'size' => $file['size'][$idx]
			];
		}
	}
	return $normalized_array;
}

function mpue($name){
	return str_replace('%', '%25', trim($name));
} 

function mpmc($key, $data = null, $compress = 1, $limit = 1000, $event = false){
	global $conf;



	if(!get($conf, 'settings', 'sql_memcache_disable') && function_exists('memcache_connect')){
		if($memcache = memcache_connect("localhost")){
			if($data){
				memcache_set($memcache, $key, $data, $compress, $limit);
				if($event) mpevent($conf['settings']['users_event_memcache_set'], $key, $conf['user']['uid']);
			}else{
				$mc = memcache_get($memcache, $key);
				if($event) mpevent($conf['settings']['users_event_memcache_get'], $key, $conf['user']['uid']);
			} return $mc;
		}else{ return false; }
	}else{ return false; }
}

# Пересборка данных массива. Исходный массив должен находится в первой форме
#	[0]  = (array)|(string)			массив|название тавлицы
#	[1] ?= (int) \d+				пагинатор
#	[2] ?= (string) 'id|name_id'	другой id
#	[.] ?= (mixed)					параметры выборки
#
#	erb('table',10,'id|virtuemart_category_id',........ПАРАМЕТРЫ ВЫБОКИ..........);
#	erb('table','id|virtuemart_category_id',........ПАРАМЕТРЫ ВЫБОКИ..........);
#	erb('table',10,........ПАРАМЕТРЫ ВЫБОКИ..........);
#	erb('table',........ПАРАМЕТРЫ ВЫБОКИ..........);
#####################################################################################

function erb($src, $key = null){
	global $arg, $conf, $tpl;
	if((!$func_get_args = array_slice(func_get_args(), 1)) &&0){ mpre("Ошибка получения списка параметров функции");
	}elseif(is_numeric(!$limit = (is_numeric($key) ? array_shift($func_get_args) : null))){ mpre("Определяем лимит сообщений");
	}elseif(empty($func_get_args) && (!$func_get_args = ['id'])){ mpre("Задание дефолтного значения");
	}elseif(!is_numeric($line = call_user_func(function() use($func_get_args){
			foreach($func_get_args as $key=>$val){
				if(is_numeric($val)){ return $key; mpre("Числовое значение");
				}elseif(is_array($val)){ return $key; mpre("Массив значений");
				}elseif(is_null($val)){ return $key; mpre("Нуль");
				}elseif(is_bool($val)){ return $key; mpre("Логические типы");
				}elseif(!is_string($val)){ mpre("Неустановленное значение поля", gettype($val), $val);
				}elseif((substr($val, 0, 1) == "[") && (substr($val, -1, 1) == "]")){ return $key;
				}elseif($val == ""){ return $key;
				}else{ // mpre("Параметр определен как имя поле");
				}
			} return count($func_get_args);
		}))){ mpre("Ошибка определения границы значений", $func_get_args);
	}elseif(!$FIELDS = array_slice($func_get_args, 0, $line)){ mpre("Ошибка определения массива полей");
	}elseif((!$VALUE = array_slice($func_get_args, $line)) &&0){ mpre("Ошибка определения массива значений");
	}elseif((!$VALUES = array_map(function($val){
			if(!is_string($val)){ return $val;
			}elseif((substr($val, 0, 1) == "[") && (substr($val, -1, 1) == "]")){// mpre("Парсинг значений со специальными ограничителями");
				return array_flip(explode(",", substr($val, 1, -1)));
			}else{ return $val; }
		}, $VALUE)) &0){ mpre("Ошибка определения списка значений");
	}elseif(!is_numeric($min = min(count($FIELDS), count($VALUES)))){ mpre("Ошибка получения минимального значения");
	}elseif(!is_array($_FIELDS = array_slice($FIELDS, 0, $min))){ mpre("Ошибка урезание полей до количетсва значений");
	}elseif(call_user_func(function() use($conf, $_FIELDS, $src){ # Проверка таблицы на существование ключей если нет то добавляем
			if(gettype($src) != "string"){// mpre("Проверяем ключи только у таблиц физически присутствующих в базе (Сроковой тип имени таблицы)");
			}elseif(!$_FIELDS){// mpre("Поля не указаны");
			}elseif(rand(0, 1000)%100){// mpre("Один процент на проверку наличия ключей условия выборки в таблице");
			}elseif(!$table = call_user_func(function($table_name) use($conf){
					if(strpos($table_name, $conf['db']['prefix']) === 0){ return $table_name;
					}elseif(!strpos($table_name, '-')){ return "{$conf['db']['prefix']}{$table_name}";
					}elseif(!$EX = explode("-", $table_name)){ mpre("ОШИБКА парсинга составных частей имени таблицы");
					}elseif(!$table = "{$conf['db']['prefix']}{$EX[0]}_{$EX[1]}"){ mpre("ОШИБКА составления полного имени таблицы");
					}else{ return $table; }
				}, $src)){ mpre("ОШИБКА получения имени таблицы `{$table}`");
			}elseif(!is_array($INDEXES = indexes($table))){ mpre("ОШИБКА получения индексов таблицы");
			}elseif(!is_array($INDEXES = array_map(function($indexes) use($conf, $INDEXES, $table){
					if('mysql' == $conf['db']['type']){ return ($indexes += ['field'=>get($indexes, 'Column_name')]);
					}elseif(!$name = get($indexes, 'name')){ mpre("ОШИБКА получения имени индекса", $indexes);
					}elseif(!$explode = explode('-', $name)){ mpre("ОШИБКА разбивки имени индекса на составляющие");
					}elseif(!$field = ((1 == count($explode)) ? $name : implode('-', array_slice($explode, 1)))){ mpre("ОШИБКА получения имени поля `{$name}`");
					}elseif(!$indexes += ['field'=>$field]){ mpre("ОШИБКА добавления имени поля к данным об индекса");
					}else{ return $indexes; }
				}, $INDEXES))){ mpre("ОШИБКА получения имен полей индексов из названий");
			}elseif(!is_array($_indexes = array_flip(array_column($INDEXES, 'field')))){ mpre("ОШИБКА получения массива типов ключей");
			}elseif(!$_fields = array_flip($_FIELDS)){ mpre("ОШИБКА получения массива с именами в ключах", $_FIELDS);
			}elseif(!$keys = array_diff_key($_fields, $_indexes)){// mpre("Ключей для добавления не найдено");
//			}elseif(true){ mpre($table, $_fields, $_indexes, $INDEXES);
			}elseif(!$FIELDS = fields($table)){ mpre("ОШИБКА получения списка полей таблицы");
			}elseif(!$SQL = array_filter(array_map(function($key) use($table, $conf, $_fields, $FIELDS){
					if(!$tab = substr($table, strlen($conf['db']['prefix']))){ mpre("ОШИБКА формирования префикса таблицы для имени ключа");
					}elseif(!$name = "{$tab}-{$key}"){ mpre("ОШИБКА формирования имени ключа");
					}elseif("sqlite" == $conf['db']['type']){// mpre("Запрос для создания ключа БД sqlite");
						if(!$TABLES = tables()){ mpre("ОШИБКА получения списка таблиц базы данных");
						}elseif(!get($TABLES, $table)){// mpre("Таблица `{$table}` не найдена в основной БД возможно она с подключенных для чтения");
						}else{ mpre("Создание ключа", $table, $name);
							return "CREATE INDEX `{$name}` ON `{$table}` (`{$key}`);";
						}
//					}elseif(true){ mpre($key, $FIELDS);
					}elseif(!$field = get($FIELDS, $key)){ mpre("ОШИБКА поулчения параметров поля");
					}elseif("text" == get($field, 'Type')){// mpre("Запрещенный для индексирования тип поля");
					}elseif("mysql" == $conf['db']['type']){// mpre("Запрос для создания ключа БД mysql");
						return "ALTER TABLE `{$table}` ADD INDEX (`{$key}`)";
					}else{ mpre("ОШИБКА создания ключа (База данных не определена)"); }
				}, array_keys($keys)))){// mpre("Список запросов на добавление ключа - пуст (уже устанволены)");
			}elseif(!$RESULT = array_map(function($sql){
					if(!$result = qw($sql)){ mpre("ОШИБКА выполнения запроса добавления ключа поиска");
					}else{ mpevent("Добавление ключа к таблице", $sql);
						return $result;
					}
				}, $SQL)){ mpre("ОШИБКА запуска запросов на создание ключей", $SQL);
			}else{ mpre("Добавление ключей таблицы", $SQL); }
		})){ mpre("ОШИБКА проверки наличия ключа в таблице");
	}elseif(!is_array($_VALUES = array_slice($VALUES, 0, $min))){ mpre("Ошибка выборки значений");
	}elseif(!is_array($SRC = (is_array($src) ? array_filter(array_map(function($src) use($min, $conf, $_FIELDS, $_VALUES){
			if(!$_VALUES){ return $src;
			}else{// mpre($_VALUES, $src, array_diff_assoc($_VALUES_, $src));
				foreach($_VALUES as $key=>$value){ # Фильтрация массива по условиям
					if(!$field = get($_FIELDS, $key)){ return null;
					}elseif(is_numeric($value) && ((int)get($src, $field) !== (int)$value)){ return null;
					}elseif(is_array($value) && !call_user_func(function($src) use($field, $value){
							if(($val = get($src, $field)) &&0){ mpre("Значение массива");
							}elseif(is_null($val) && array_key_exists("NULL", $value)){ return $src;
							}elseif(!array_key_exists($val, $value)){ return null;
							}else{ return $src; }
						}, $src)){ return null;
					}elseif(is_string($value) && (get($src, $field) != $value)){ return null;
					}elseif(is_bool($value) && !$value){ return null;
					}elseif(is_null($value) && (get($src, $field) != $value)){ return null;
					}else{// mpre($src);
					}
				} return $src;
			}
		}, $src)) : call_user_func(function($src) use(&$tpl, $min, $conf, $_FIELDS, $_VALUES, $limit, $arg, $func_get_args){ # Выборка данных из БД по условиям
			if(!is_array($WHERE = array_filter(array_map(function($field, $value) use($_FIELDS, $_VALUES, $func_get_args){
					if(is_numeric($value)){ return "`{$field}`=". (int)$value;
					}elseif(is_string($value)){ return "`{$field}`=\"{$value}\"";
					}elseif($value === true){ return null;
					}elseif(is_null($value)){ return "`{$field}` IS NULL";
					}elseif(is_bool($value)){ return ($value ? "TRUE" : "FALSE");
					}elseif(!is_array($value)){ mpre("Ошибочный тип данных в значении", gettype($value));
					}elseif(empty($value)){ return "NULL"; // mpre("Пустой массив");
					}elseif(!is_array($IN = array_map(function($val) use($field, $func_get_args){
							if(is_numeric($val)){ return "`{$field}`={$val}";
							}elseif("NULL" === $val){ return "`{$field}` IS {$val}";
							}else{ return "`{$field}` = \"{$val}\""; }
						}, array_keys($value)))){ mpre("Ошибка обработки значений массива", $_FIELDS, $_VALUES, $func_get_args);
					}elseif(!$IN){ return null;
					}else{ return "(". implode(" OR ", $IN). ")"; }
				}, $_FIELDS, $_VALUES)))){ mpre("Получения условий WHERE");
//			}elseif(true){ mpre($WHERE);
			}elseif(!is_string($where = implode(" AND ", $WHERE))){ mpre("Ошибка составления всех условий в строку");
			}elseif(!$tab = call_user_func(function($src) use($conf, $arg){
					if($conf['db']['prefix'] == substr($src, 0, strlen($conf['db']['prefix']))){// mpre("Полное имя таблицы вместе с префиксом");
						return $src;
					}elseif(!strpos($src, "-")){// mpre("Короткое имя таблицы без модуля", $src);
						return "{$conf['db']['prefix']}{$arg['modpath']}{$src}";
					}elseif(!$MOD = explode("-", $src, 2)){ mpre("Ошибка парсинга модуля имени таблицы");
					}elseif(!$tab = "{$conf['db']['prefix']}{$MOD[0]}_{$MOD[1]}"){
					}else{ return $tab; }
				}, $src)){ mpre("Ошибка получения имени таблицы запроса");
			}elseif(!$debug_backtrace = debug_backtrace()){ mpre("Ошибка получения списка функций");
			}elseif(!$func = rb($debug_backtrace, 'function', '[rb]')){ mpre("ОШИБКА выборки информации о функции вызвавшей постраничное отображение");
			}elseif(!$pager_id = crc32("{$func['file']}:{$func['line']}")){ mpre("ОШИБКА формирования идентификатора постраничного отображения");
			}elseif(!$p = ((strpos(get($_SERVER, 'HTTP_HOST'), "xn--") === 0) && ($arg['fn'] != "admin")) ? "стр" : "p"){ mpre("ОШИБКА формирования переменной с номером пагинатора");
			}elseif(!$p = "{$p}{$pager_id}"){ mpre("ОШИБКА формирования имени переменной в адресе");

			}elseif((!$LIMIT = ($limit ? " LIMIT ". (get($_GET, $p)*$limit). ",". abs($limit) : "")) &0){ mpre("Условия лимита");
			}elseif(!$modp = substr($tab, strlen($conf['db']['prefix']))){ mpre("Ошибка вычисления строки модуля");
			}elseif(!is_string($order = get($conf, 'settings', "{$modp}=>order") ?: "")){ mpre("Расчет поля сортировки");
			}elseif(!$sql = "SELECT * FROM `{$tab}`". ($where ? " WHERE {$where}" : ""). ($order ? " ORDER BY {$order}" : ""). $LIMIT){ mpre("Ошибка составления запроса к базе");
//			}elseif(true){ mpre($sql);
			}elseif(is_numeric($limit) && ($limit <= 0) && !mpre($sql)){ mpre("Отображение запроса");
			}elseif(!is_array($SRC = qn($sql))){ mpre("Ошибка выполнения запроса", $sql);
//			}elseif(strpos($sql, "news_index") && !mpre($p, $sql, $SRC)){
			}elseif(!is_string($tpl['pager'] = call_user_func(function() use($limit, $sql, $pager_id, $tpl, $tab, $where, $order){ # Получаем описание на постраничные переходы
					if(!$limit){ return (get($tpl, 'pager') ?: "");
					}elseif(!$sql = $sql = "SELECT COUNT(*) AS cnt FROM `{$tab}`". ($where ? " WHERE {$where}" : ""). ($order ? " ORDER BY {$order}" : "")){ mpre("ОШИБКА формирования адреса для постраничной выборки");
					}elseif(!$res = ql($sql)){ mpre("ОШИБКА выполнения запроса на выборку количества записей");
					}elseif(!is_numeric($count = get($res, 0, "cnt"))){ mpre("Ошибка подсчета количества страниц в пагинаторе", $res);
					}elseif(!is_numeric($cnt = $cnt = $count/$limit)){ mpre("ОШИБКА получения номера страницы постраничного отображения");
//			}elseif(strpos($sql, "news_index") && !mpre($sql, $pager_id)){
					}elseif($pager = mpager($cnt, $pager_id)){ return $pager;
					}else{ return ""; }
				}))){ mpre("ОШИБКА формирования списка ссылок на постраничные переходы");
			}elseif(is_numeric($limit) && ($limit<0) && mpre($sql)){ mpre("Отображение запроса к базе данных");
			}else{ return $SRC; }
		}, $src)))){ mpre("Нулевой результат выборки данных `{$src}`");
	}elseif(empty($SRC)){ return [];
	}elseif(!$_FIELDS = array_slice($FIELDS, $min)){ # Если значений больше чем полей
		if(!$_VALUES = array_slice($VALUES, $min)){ return last($SRC); # Возвращаем последний массив
		}else{ array_unshift($_VALUES, last($SRC));
			return call_user_func_array("get", $_VALUES); # Поле по последнему ключу
		}
	}elseif(!$SRC = call_user_func(function($SRC, $_FIELDS, $_SRC = []) use($func_get_args){
//			if((1 == count($_FIELDS)) && ("id" == get($_FIELDS, 0))){ return $SRC;
			if(call_user_func(function($_FIELDS) use($SRC){
					if(1 != count($_FIELDS)){// mpre("Количество полей не равно одному");
					}elseif("id" != get($_FIELDS, 0)){// mpre("Первое значение не равно `id`");
					}elseif(!$src = first($SRC)){// mpre("ОШИБКА получения первого значения списка");
					}elseif(!$key = first(array_keys($SRC))){// mpre("ОШИБКА получения ключа первого значения");
					}elseif(get($src, 'id') != $key){// mpre("Ключи не совпадают перебираем весь массив");
					}else{ return true;
					}
				}, $_FIELDS)){ return $SRC;// mpre("Возвращаем значения без обработки");
			}elseif(array_search("", $_FIELDS)){ exit(mpre("Пустое значение в списке полей", $_FIELDS/*, debug_backtrace()*/));
			}else{
				foreach($SRC as $src){
					$TMP = &$_SRC;
					foreach($_FIELDS as $_fields){
						if(!array_key_exists($_fields, $src)){ mpre("Значение поля `{$_fields}` не установлено", $src);
						}elseif(!array_key_exists($src[$_fields], $TMP)){ $TMP[ $src[$_fields] ] = [];
						} $TMP = &$TMP[ $src[$_fields] ];
					} $TMP = $src;
				} return $_SRC;
			}
		}, ($limit ? array_slice($SRC, 0, $limit, true) : $SRC), $_FIELDS)){// mpre("Ошибка формирования ключей по дополнительным полям");
		return [];
	}else{ return $SRC; }
} function rb($src, $key = 'id'){
	global $conf, $arg, $tpl;
	$func_get_args = func_get_args();
//	echo "<pre>"; print_r($func_get_args); echo "</pre>"; exit;
	if(is_string($src)){
		if(strpos($func_get_args[0], '-')){ # Разделитель  - (тире) считается разделителем для раздела
			$func_get_args[0] = $conf['db']['prefix']. implode("_", array_filter(explode("-", $func_get_args[0])));
		}else if(!preg_match("#^{$conf['db']['prefix']}.*#iu",$func_get_args[0])){ # Если имя таблицы начинается с префика
			$func_get_args[0] = "{$conf['db']['prefix']}{$arg['modpath']}_{$func_get_args[0]}";
		} //проверка полное или коротное название таблицы
	} return call_user_func_array('erb', $func_get_args);
}

function arb($index,$params,$return=null){
	$buff = array($index);
	foreach($params as $key => $param){
		if(!is_int($key)){array_push($buff,$key);}
		else{array_push($buff,$param);}
	}
	foreach($params as $key => $param){
		if(!is_int($key)){array_push($buff,$param);}
	}
	if(is_string($return)){array_push($buff,$return);}
	return call_user_func_array('rb',$buff);
}

# Автоматическое определение кодировки строки и приведение ее в нужную форму.
function mpde($string) { 
	ini_set('mbstring.substitute_character', "none");
	foreach(array('utf-8', 'windows-1251') as $item) {
//		$sample = iconv($item, $item. "//IGNORE", $string); # Начиная с этой версии, функция возвращает FALSE на некорректных символах, только если в выходной кодировке не указан //IGNORE. До этого, функция возвращала часть строки
		$sample = mb_convert_encoding($string, $item, $item);
		if(md5($sample) == md5($string))
			return iconv($item, "utf-8", $string);
	} return null;
}

function mpdbf($tn, $post = null, $and = false){
	global $conf;
	$fields = $f = array();
	if(!isset($post)) $post = $_POST;
	/*
		Отключаем обработку html тегов
		$conf['settings']['html_mpquot_disable'] = true;
	*/
	$html_mpquot = get($conf,'settings','html_mpquot_disable') ? [] : ["<"=>"&lt;", ">"=>"&gt;"];
//	foreach(mpql(mpqw("SHOW COLUMNS FROM `$tn`")) as $k=>$v){
	foreach(fields($tn) as $name=>$field){
		$fields[$name] = (get($field, 'Type') ?: $field['type']);
	} foreach((array)$post AS $k=>$v){
		if(!empty($conf['settings']['analizsql_autofields']) && $conf['settings']['analizsql_autofields'] && !array_key_exists($k, $fields) && array_search($conf['user']['uname'], explode(',', $conf['settings']['admin_usr'])) !== false){
			mpqw($sql = "ALTER TABLE `$tn` ADD `$k` ". (is_numeric($v) ? "INT" : "varchar(255)"). " NOT NULL"); echo "\n<br>". $sql;
			$f[] = "`$k`=\"". mpquot(strtr($v, $html_mpquot)). "\"";
		}elseif(array_key_exists($k, $fields)){
			if(is_array($v)){
				if(mp_is_assoc($v)){
					$f[] = "`$k` IN (". mpquot(strtr(implode(",", $v), $html_mpquot)). ")";
				}else{
					$f[] = "`$k`=\"". mpquot(strtr(implode(",", $v), $html_mpquot)). "\"";
				}
			}else{
				if($v === null){
					$f[] = ($and ? "`$k` IS NULL" : "`$k`=NULL");
				}elseif(is_int($v) || ($v == "NULL")){
					$f[] = "`$k`=". $v;
				}else{
					$f[] = "`$k`=\"". mpquot(strtr($v, $html_mpquot)). "\"";
				}
			}
		}
	} /*mpre($post, implode(($and ? " AND " : ', '), (array)$f));*/ return implode(($and ? " AND " : ', '), (array)$f);
} function mpfdk($tn, $find, $insert = array(), $update = array(), $log = false){
	global $conf, $arg;
	if($find && ($fnd = mpdbf($tn, $find, 1)) &&
		($sel = qn($sql = "SELECT `id` FROM `". mpquot($tn). "` WHERE ". $fnd))
	){
		if((count($sel) == 1) && ($s = array_shift($sel))){
			if($update && ($upd = mpdbf($tn, $update))){
				qw($sql = "UPDATE `". mpquot($tn). "` SET {$upd} WHERE `id`=". (int)$s['id']);
			} return $s['id'];
		}else{ mpre("Множественные изменения запрещены `{$tn}`", $find); # Множественное обновление. Если в качестве условия используется несколько элементов
/*			if($update && ($upd = mpdbf($tn, $update))){
				qw($sql = "UPDATE `". mpquot($tn). "` SET {$upd} WHERE `id` IN (". in($sel). ")");
			} return $sel;*/
		}
	}elseif($insert){
		if(!$fields = fields($tn)){ mpre("ОШИБКА получения полей таблицы `{$tn}`");
		}elseif(!$mpdbf = $insert+array("time"=>time(), "uid"=>get($conf, 'user', 'uid'), 'sid'=>get($conf, 'user', 'sess', 'id'))){ mpre("ОШИБКА добавления дефолтных значений");
		}elseif(!$values = array_map(function($val){ return mpquot($val); }, array_intersect_key($mpdbf, $fields))){ mpre("ОШИБКА составления значений запроса");
		}else{// pre($conf['user']['sess']);
			qw("INSERT INTO `". mpquot($tn). "` (`". implode("`, `", array_keys($values)). "`) VALUES (\"". implode("\", \"", array_values($values)). "\")");
			return $sel['id'] = $conf['db']['conn']->lastInsertId();
		}// qw($sql = "INSERT INTO `". mpquot($tn). "` SET ". mpdbf($tn, $insert+array("time"=>time(), "uid"=>(!empty($conf['user']['uid']) ? $conf['user']['uid'] : 0))));
	}
}

function fdk(&$tn, $find, $insert = array(), $update = array(), $log = false){
	global $conf;
	if(is_array($tn)){
		$func_get_args = array_merge([$tn], array_keys($find), ['id'], array_values($find));
		if($update){
			if($tlist = call_user_func_array('rb', $func_get_args)){
				foreach($tlist as $k=>&$ln){
					$tlist[$k] = $ln = array_replace_recursive($ln, $update);
				} $tn = array_replace_recursive($tn, $tlist); return count($tlist) ? first($tlist) : $tlist;
			}else{ mpre("Результат для изменений не найдн"); }
		}elseif($insert){
			$tn[] = array();
			$insert['id'] = last(array_keys($tn));
			return ($tn[ $insert['id'] ] = $insert);
		}else{ return false; }
	}elseif($index_id = mpfdk($tn, $find, $insert, $update, $log)){
		if($line = qn("SELECT * FROM `$tn` WHERE id IN (". (is_numeric($index_id) ? $index_id : in($index_id)). ")")){
			if(1 == count($line)){
				return first($line);
			}elseif(!$mn = explode("_", substr($tn, strlen($conf['db']['prefix'])), 2)){ mpre("Ошибка парсинга адреса таблицы");
			}else{// mpre("Количество элементов подходящих под условие больше одного", $mn, $tn, $find, $line);
				mpre("Дублирующийся элемент", implode("<br />\t", array_map(function($l) use($mn){
					return "<a href='/seo:admin/r:{$mn[0]}-{$mn[1]}?&where[id]={$l['id']}'>{$l['name']} ({$l['id']})</a>";
				}, $line)));
				return $line;
			}
		}else{ mpre("sql:", $sql); return false; }
	}
} function fk($t, $find, $insert = array(), $update = array(), $key = false, $log = false){
	global $conf, $arg;
	if(strpos($t, '-')){ //проверка полное или коротное название таблицы
		$t = $conf['db']['prefix']. implode("_", array_filter(explode("-", $t)));
	}elseif(!preg_match("#^{$conf['db']['prefix']}.*#iu",$t)){
		$t = "{$conf['db']['prefix']}{$arg['modpath']}_{$t}";	
	}
	if($index = fdk($t, $find, $insert, $update, $log)){
		return $key ? $index[$key] : $index;
	}else{ return []; }
}
function mpdk($tn, $insert, $update = array()){
	global $conf, $arg;
	if($ins = mpdbf($tn, $insert)){
		$upd = mpdbf($tn, $update);
//		foreach(mpql(mpqw("SHOW COLUMNS FROM $tn")) as $k=>$v){
		foreach(fields($tn) as $name=>$field){
			$fields[$name] = ($field['Type'] ?: $field['type']);
		} if("SELECT id FROM `". mpquot($tn). "` WHERE "){
			mpqw("INSERT INTO `". mpquot($tn). "` SET $ins ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)". ($update ? ", $upd" : ""));
		} return $conf['db']['conn']->lastInsertId();
	}
}
function mpevent($name, $description = null){ # Сохранение информации о событии
	global $conf, $argv;
	if(!$name){ mpre("Имя события не указано");
	}elseif(!$debug_backtrace = debug_backtrace()){ mpre("Ошибка создания списка вызовов функций");
	}elseif(!$users_event = fk("users-event", $w = array("name"=>$name), $w += array("hide"=>1, "up"=>time()))){ mpre("Ошибка добавления события в базу событий");
	}elseif(get($users_event, 'hide')){ return []; mpre("Событие выключено");
	}elseif(!$event_logs = fk("users-event_logs", null, ["event_id"=>$users_event['id'], "themes-index"=>get($conf, "themes", "index", "id"), 'description'=>(is_string($description) ? $description : "")])){// mpre("ОШИБКА добавления события");
	}elseif(!$settings_name = 'users_event_values'){ mpre("Название параметра для значений логов");
	}elseif(!get($conf, 'settings', 'users_event_values')){ mpre("Создание таблиц событий");
		if(!mpsettings('users_event_values', "Значения")){ mpre("ОШИБКА установки названия таблицы значений");
		}elseif("mysql" == $conf['db']['type']){ mpre("Создаем таблицы событий для {$conf['db']['type']}");
			qw("CREATE TABLE `{$conf['db']['prefix']}users_event_params` (`id` int(11) NOT NULL AUTO_INCREMENT, `time` int(11) DEFAULT NULL, `uid` int(11) DEFAULT NULL, `name` varchar(255) DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8");
			qw("CREATE TABLE `{$conf['db']['prefix']}users_event_value` (`id` int(11) NOT NULL AUTO_INCREMENT, `time` int(11) DEFAULT NULL, `uid` int(11) DEFAULT NULL, `name` varchar(255) DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8");
			qw("CREATE TABLE `{$conf['db']['prefix']}users_event_values` (`id` int(11) NOT NULL AUTO_INCREMENT, `time` int(11) DEFAULT NULL, `uid` int(11) DEFAULT NULL, `event_logs_id` int(11) DEFAULT NULL, `event_params_id` int(11) DEFAULT NULL, `event_value_id` int(11) DEFAULT NULL, PRIMARY KEY (`id`), KEY `event_params_id` (`event_params_id`), KEY `event_value_id` (`event_value_id`), KEY `event_logs_id` (`event_logs_id`), CONSTRAINT `mp_users_event_values_ibfk_1` FOREIGN KEY (`event_logs_id`) REFERENCES `mp_users_event_logs` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8");
		}elseif("sqlite" == $conf['db']['type']){ mpre("Создаем таблицы событий для {$conf['db']['type']}");
			qw("CREATE TABLE `{$conf['db']['prefix']}users_event_params`(`id` INTEGER PRIMARY KEY,`time` INTEGER,`uid` INTEGER,`name` TEXT)");
			qw("CREATE TABLE `{$conf['db']['prefix']}users_event_value`(`id` INTEGER PRIMARY KEY,`time` INTEGER,`uid` INTEGER,`name` TEXT)");
			qw("CREATE TABLE `{$conf['db']['prefix']}users_event_values`(`id` INTEGER PRIMARY KEY,`time` INTEGER,`uid` INTEGER,`hide` INTEGER,`event_logs_id` INTEGER,`event_params_id` INTEGER,`event_value_id` INTEGER)");
		}else{ mpre("База данных не установлена"); }
	}elseif(!$values = ['Источник'=>get($_SERVER, 'HTTP_REFERER'), 'Адрес'=>$_SERVER['REQUEST_URI']]){ mpre("ОШИБКА получения параметров события");
	}elseif(!is_array($func_get_args = func_get_args())){ mpre("ОШИБКА получения списка параметров");
	}elseif(!is_array($_VALUES = array_filter(array_map(function($nn, $args){ # Все принятые функцией массивы
			if(!is_array($args)){// mpre("Только массивы");
			}else{ return $args; }
		}, array_keys($func_get_args), $func_get_args)))){ mpre("ОШИБКА получения масса параметра");
	}elseif(!is_array($_values = array_filter(array_map(function($args){ # Список строковых входящих значений
			if(!is_string($args)){// mpre("ОШИБКА ожидается строка");
			}else{ return $args; }
		}, array_slice($func_get_args, 2))))){ mpre("ОШИБКА получения строковых значений");
	}elseif(!$VALUES = array_merge([$values, $_values]+ $_VALUES)){
	}elseif(!$EVENT_VALUES = array_map(function($array, $EVENT_VALUES = []) use($event_logs){
			foreach($array as $param=>$value){
				if(!$event_params = fk('users-event_params', $w = ['name'=>$param], $w)){ mpre("ОШИБКА добалвения параметра события");
				}elseif(!is_array($event_value = ($value ? fk('users-event_value', $w = ['name'=>$value], $w) : []))){ mpre("ОШИБКА добавления нового значения");
				}elseif(!$event_values = fk('users-event_values', $w = ['event_logs_id'=>$event_logs['id'], 'event_params_id'=>$event_params['id'], 'event_value_id'=>get($event_value, 'id')], $w)){ mpre("ОШИБКА добавления значения запроса");
				}elseif(!$EVENT_VALUES[$event_values['id']] = $event_values){ mpre("Формирование списка значений");
				}else{// mpre($event_values);
				}
			} return $EVENT_VALUES;
		}, $VALUES)){ mpre("ОШИБКА сохранения значений");
	}else{// mpre($values, $_values, $_VALUES, $VALUES, $event_logs);
		return $event_logs;
	}
}

function mpidn($value, $enc = 0){
	/*if(!class_exists('idna_convert') && require_once(mpopendir('include/idna_convert.class.inc'))){ mpre("Ошибка подключения класса");
	}else*/if(!$IDN = new idna_convert()){ mpre("Ошибка создания экземпляра класса");
	}elseif($enc){ return $IDN->encode($value);
	}else{ return $IDN->decode($value); }
}
function mpsettings($name, $value = null, $aid = 4, $description = ""){
	global $conf, $arg;
	if($value === null){
		return mpql(mpqw($sql = "SELECT value FROM {$conf['db']['prefix']}settings WHERE name=\"". mpquot($name). "\""), 0, "value");
	}elseif(!empty($value)){
		if(get($conf, 'settings', $name) != $value){
			return get($settings = fk("{$conf['db']['prefix']}settings", $w = array("name"=>$name), $w += array("modpath"=>first(explode("_", $name)), "value"=>$value, "aid"=>$aid, "description"=>$description), $w), "value");
		}else{ return $value; }
	} return null;
}

# Разбор адресной строки на параметры для использования в $_GET массиве
function mpgt($REQUEST_URI, $get = array()){
	if(strpos($REQUEST_URI, "?")){
		$keys = array_keys($ar = explode('?', $REQUEST_URI));
		$part = explode('//', str_replace("/null/", "//", $ar[min($keys)]), 2);// mpre($part); exit;
	}else{
		$part = explode('//', str_replace("/null/", "//", $REQUEST_URI), 2);
	} if(!empty($part[1])){
		$param = explode(':', $part[1], 2);// mpre($param);
		$val = array_pop($param);// mpre($val); exit;
		$get += array(urldecode(array_shift($param))=>urldecode($val));
		$get['null'] = '';
	}
	$part = explode('/', $part[0], 3);
	$mod = array_key_exists(1, $part) ? explode(':', $part[1]) : array();
	if(!empty($mod[0])){
		$get['m'] = array(urldecode(array_key_exists(0, $mod) ? $mod[0] : "")=>urldecode(array_key_exists(1, $mod) ? $mod[1] : ""));
		if($mod[0] == 'include' || urldecode($mod[0]) == 'img') $get['null'] = '';
	}
	if(!empty($part[2]) && $part[2] != ''){
		foreach($tpl = explode('/', $part[2]) as $k=>$v){
			if($param = explode(':', $v, 2)){
				if(!empty($param[0]) && !is_numeric($param[0])){
					$get = $get + array(urldecode(array_key_exists(0, $param) ? $param[0] : "")=>urldecode(array_key_exists(1, $param) ? $param[1] : ""));
				}elseif(is_numeric($param[0])){
					$get = array('id'=>$param[0]) + $get;
				}
			}
		}
	} if(!empty($get['стр']) && $get['стр']) $get['p'] = $get['стр'];
	return $get;
}
function mpwr($tn, $get = null, $prefix = null){ mpre("Устаревшая функция. Если вы ее используете удалите из кода. Скоро она перестанет быть доступной");
	global $conf;
	if(empty($prefix)) $where = ' WHERE 1=1';
	$f = mpqn(mpqw("DESC {$tn}"), 'Field');
	foreach($get !== null ? $get : $_GET as $k=>$v){
		$buf = explode('.', $k);
		$n = array_pop($buf);unset($buf );
		if((substr($k, 0, 1) == '!') && ($f[substr($k, 1)] || $f[$n])){
			$where .= " AND {$prefix}`". mpquot(substr($k, 1)). "`<>\"". mpquot($v). "\"";
		}elseif(is_numeric($v) && (substr($k, 0, 1) == '+') && ($f[substr($k, 1)] || $f[$n])){
			$where .= " AND {$prefix}`". mpquot(substr($k, 1)). "`>". (int)$v;
		}elseif(is_numeric($v) && (substr($k, 0, 1) == '-') && ($f[substr($k, 1)] || $f[$n])){
			$where .= " AND {$prefix}`". mpquot(substr($k, 1)). "`<". (int)$v;
		}elseif(($v !== "") && get($f,$n) && gettype($v) == "string"){
			$where .= " AND {$prefix}`". mpquot($k). "`=\"". mpquot($v). "\"";
		}
	} return $where;
}

function mpsmtp($to, $subj="", $text="", $from = null, $files = array(), $login = null){ # Отправка письмо по SMTP протоколу
	global $conf;
	$old_chdir = realpath('.');
	$emailRegex = "[\w_\-\.]+@[\w_\-\.]+\.\w+";	
	if(is_array($first=func_get_args()[0])){		
		$defaults=[
			'to'=>'required',
			'subj'=>'',
			'text'=>'',
			'from'=>null,
			'files'=>array(),
			'file_size_limit'=>10,//MB
			'file_accept'=>["jpg","jpeg","gif","png","doc","docx","xls","xlsx","pdf","txt","csv"],
			'file_accept_add'=>[],
			'login'=>null
		];
		foreach($defaults as $keyParam => $valueParam){
			${$keyParam} = get($first,$keyParam)?:$valueParam;
			if(${$keyParam}==='required'){
				ob_clean();
				die("mpsmtp:{$keyParam} - is required");
			}
		}
		$file_accept = array_merge($file_accept,$file_accept_add);
	}else{
		$file_accept = [];
		$file_size_limit = 10;
	}
	
	$mail = new PHPMailer;
	$Providers = array(
		'smtp.mail.ru'=>array('port'=>465,'host'=>'mail.ru'),
		'smtp.yandex.ru'=>array('port'=>465,'host'=>'yandex.ru'),
		'smtp.gmail.com'=>array('port'=>465,'host'=>'gmail.com'),
	);	
	$param = explode("@", $login ? $login : $conf['settings']['smtp']);
	$host = explode(":", array_pop($param));
	$auth = explode(":", implode("@", $param));
//	mpre($param, $host, $auth);
	if(!$from OR !($mail_match=preg_match("#{$emailRegex}#iu",trim($from)))){
		if (filter_var($auth[0], FILTER_VALIDATE_EMAIL)) {
			//берем из логина в случае если это емайл
			$from_ = $auth[0];
		}else if(isset($Providers[$host[0]])){
			//берем из уже известных нам провайдеров
			$from_ = $auth[0] . "@" . $Providers[$host[0]]['host'];
		}else{
			//пытаемся угадать сами
			$from_ = $auth[0] . "@" . trim(preg_replace("#smtp\.#iu","",$host[0]));
		}
		if(isset($mail_match) and !$mail_match){
			$from = "{$from} <{$from_}>";
		}else{
			$from = $from_;
		}
	}//	mpre($Providers[$host[0]], $from, $from_);
	$mail->isSMTP();
	$mail->SMTPAuth = true;
	$mail->Host = $host[0];
	$mail->Username = $auth[0];
	$mail->Password = $auth[1];
	$mail->isHTML(mp_is_html($text));
	$mail->setLanguage('ru'); 
	$mail->CharSet = 'UTF-8';
	if(isset($Providers[$host[0]])){
		$mail->SMTPSecure = 'ssl';
		$mail->Port	= $Providers[$host[0]]['port'];
	}else{
		if(isset($host[1]) and in_array(intval(trim($host[1])),array(465,587))){
			$mail->SMTPSecure = 'ssl';
			$mail->Port = intval(trim($host[1]));
		}else{
			$mail->SMTPSecure = 'tls';
			$mail->Port = isset($host[1]) ? intval(trim($host[1])) : 25;
		}
	}	
	if(preg_match("#(.+)\s+?\<($emailRegex)\>#iu",trim($from),$from_))
		$mail->setFrom($from_[2], $from_[1]);
	else
		$mail->setFrom($from);
	foreach(explode(',',$to) as $recipient){
		if(preg_match("#(.+)\s+?\<($emailRegex)\>#iu",trim($recipient),$recipient_)){
			$mail->addAddress($recipient_[2], $recipient_[1]);
		}else{
			$mail->addAddress($recipient);
		}
	}	
	if($files===TRUE){
		foreach(normalize_files_array() as $key => $input){
			foreach($input as $i_file => $file){
				if(
					!$file['error']
						AND
					$file['size']/1024/1024 <= $file_size_limit
						AND
					(!$file_accept OR in_array(mb_strtolower(preg_replace("#^.*\.([^\.]+)$#iu","$1",$file['name'])),$file_accept))
				){
					$mail->addAttachment($file['tmp_name'],"{$key}_{$i_file}_{$file['name']}");
				}
			}
		}	
	}else{
		if(is_string($files))
			$files = array($files);
		foreach($files as $key => $filepath){
			if(file_exists($filepath)){
				if(is_int($key)){
					$mail->addAttachment($filepath);
				}else{
					$mail->addAttachment($filepath,$key);
				}
			}
		}
	}
	$mail->Subject = $subj;
	$mail->Body    = $text;
	if(!$mail->send()) {
		$return = 'Mailer Error: ' . $mail->ErrorInfo;
	} else {
		$return = 0;
	} 
	chdir($old_chdir);
	return $return;
}


# Отправка информации на почту Параметры устанавливаются либо параметром при вызове функции, либо берутся из настроек системы
function mpmail(){
	global $conf;
	$fArgs = func_get_args();
	if($conf['settings']['smtp'] OR isset($func_get_args[5])){		
		return call_user_func_array('mpsmtp', $fArgs);
	} 
	mpevent("Отправка сообщения", $fArgs[0], $conf['user']['uid'], debug_backtrace());
	if(empty($to)){ return false; }else{
		$header = "Content-type:text/html; charset=UTF-8;". ($fArgs[3] ? " From: {$fArgs[3]};" : "");
		call_user_func_array('mail',$fArgs);
		mpevent($conf['settings']['users_event_mail'], $fArgs[0], $conf['user']['uid'], $fArgs[1], $fArgs[2]);
		return true;
	}
}
function hid($tn, $href, $id = false, $fn = "img", $exts = array('image/png'=>'.png', 'image/pjpeg'=>'.jpeg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')){
	global $conf, $arg;
	if(!$data = file_get_contents($href)){ pre("Ошибка загрузки файла", $href);
	}elseif(!($ext = '.'. preg_replace("/[\W]+.*/", '', preg_replace("/.*?\./", '', $href))) && (array_search(strtolower($ext), $exts) || isset($exts['*']))){ pre("Запрещенное к загрузке расширение", $ext);
	}elseif(!$el = fk($tn, $w = ($id ? ["id"=>$id] : null), $w = ['id'=>NULL])){ mpre("Ошибка получения идентификатора элемента {$tn}");
	}elseif(!$f = "{$tn}-{$fn}_". (int)$el['id']. $ext){ mpre("Ошибка формирования имени файла");
	}elseif((!$ufn = mpopendir('include/images')) && (!$ufn = realpath('include/images'))){ mpre("Директория с изображениями не определена");
	}elseif(!file_put_contents("$ufn/$f", $data)){ mpre("Ошибка сохранения файла");
	}elseif(!$el = fk($tn, array("id"=>$el['id']), null, array($fn=>"images/$f"))){ mpre("Ошибка занесения имени файла в таблицу");
	}else{ return $el; }
}
function fid($tn, $fn, $id = 0, $prefix = null, $exts = array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')){// pre($fn, $prefix, normalize_files_array());
	global $conf;
	if(!$file = get(normalize_files_array(),$fn,intval($prefix))){ pre("ОШИБКА получения имени закачанного файла", ['fn'=>$fn, 'prefix'=>$prefix]);
	}elseif($file['error']){ mpre("ОШИБКА загрузки файла");
	}elseif(!$folder = preg_match_all("#^image/\w+$#iu",$file['type']) ? 'images' : 'files'){ pre("ОШИБКА выбора имени директории для хранения файла");
	}elseif((!$ext = get($exts, $file['type'])) && !get($exts, '*')){ pre("Тип загрузаемого файла `{$file['type']}` не найден среди разрешенных", $ext);
	}elseif(!strlen($ext) && (!$ext = '.'. last(explode('.', $file['name'])))){ mpre("ОШИБКА расчета расширения");
	}elseif(!$img = fk($tn, $w = ($id ? ["id"=>$id] : []), $w += ['time'=>time(), 'uid'=>$conf['user']['uid']])){ mpre("ОШИБКА выборки записи по идентификатору");
	}elseif(!$file_name = "{$tn}-{$fn}_{$img['id']}{$ext}"){ mpre("ОШИБКА расчета имени файла");
	}elseif(!$ufn = mpopendir("include/{$folder}")){ mpre("ОШИБКА получения пути к загружаемой директории");
	}elseif(!move_uploaded_file($file['tmp_name'], "$ufn/$file_name")){ mpre("ОШИБКА перемещения файла с временной директории в директорию системы");
	}elseif(!array_key_exists($fn, $img)){ pre("Поле `{$fn}` не найдено среди полей таблицы `$tn`", $img);
	/* Если загружать одновременно одно и еще несколько изображений приходится разделять их имена img и imgs но тем не менее грузить нужно в поле img таблиц index b imdex_imgs */
	}elseif(!$img = fk($tn, $w = ['id'=>$img['id']], $w += [$fn=>"{$folder}/{$file_name}"], $w)){ mpre("ОШИБКА обновления имени файла в записи изображения");
//	}elseif(($img['id'] != $id) && mpqw("DELETE FROM {$tn} WHERE id=". (int)$img_id)){ mpre("ОШИБКА удаления файла если загрузка не удалась");
	}else{// mpre($img);
		return $img;
	}
}
function mpfn($tn, $fn, $id = 0, $prefix = null, $exts = array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')){
	global $conf;
	mpevent("Устаревшая функция", "mpfn", $conf['users']['uid']);
	if($prefix === null){
		$file = $_FILES[$fn];
	}else{
		$file = array(
			'name'=>$_FILES[$fn]['name'][$prefix],
			'type'=>$_FILES[$fn]['type'][$prefix],
			'tmp_name'=>$_FILES[$fn]['tmp_name'][$prefix],
			'error'=>$_FILES[$fn]['error'][$prefix],
			'size'=>$_FILES[$fn]['size'][$prefix],
		);
	}// mpre($_FILES[$fn]); mpre($file);
	if($file['error'] === 0){
		if ($exts[ $file['type'] ] || isset($exts['*'])){
			if(!($ext = $exts[ $file['type'] ])){
				$ext = '.'. array_pop(explode('.', $file['name']));
			} $f = "{$tn}_{$fn}_". (int)($img_id = mpfdk($tn, array("id"=>$id), array("time"=>time(), "uid"=>$conf['user']['uid']))). $ext;
			if(($ufn = mpopendir('include/images')) && move_uploaded_file($file['tmp_name'], "$ufn/$f")){
				/*if($img_id != $id) */mpqw($sql = "UPDATE {$tn} SET `". mpquot($fn). "`=\"". mpquot($return = "images/$f"). "\" WHERE id=". (int)$img_id);
			}else{
				if($img_id != $id){
					mpqw("DELETE FROM {$tn} WHERE id=". (int)$img_id);
				}
			} return "images/$f";
		}else{
			echo " <span style='color:red;'>{$file['type']}</span>";
		} mpevent("Загрузка файла", $_SERVER['REQUEST_URI'], $conf['user']['uid'], $file);
		return $return ? $return : false;
	}elseif(empty($file)){
		return "error not null";
	} return null;
}

function mpager($count, $id = null, $null=null, $cur=null /* Номер пагинатора */){ # Формируем пагинатор по номеру пагинатора
	global $conf, $arg;// mpre("mpager");
	if(!$p = ((strpos(get($_SERVER, 'HTTP_HOST'), "xn--") === 0) && ($arg['fn'] != "admin")) ? "стр" : "p"){ mpre("ОШИБКА формирования переменной с номером пагинатора");
	}elseif(!$p = "{$p}". (empty($id) ? "" : $id)){ mpre("ОШИБКА формирования имени переменной в адресе");
	}elseif(!is_numeric($cur = (array_key_exists($p, $_GET) ? $_GET[$p] : 0))){ mpre("ОШИБКА получения текущей страницы пагинатора");
	}elseif(!$REQUEST_URI = get($_SERVER, 'REQUEST_URI')){// mpre("Не найден адрес");
	}elseif(!$url = call_user_func(function() use($p, $conf, $REQUEST_URI){ # Формирование адреса
			if(is_string($canonical = get($conf, 'settings', 'canonical'))){ return $canonical;
			}elseif(!is_array($canonical)){ return urldecode($REQUEST_URI);
			}elseif(!$url = get($canonical, 'name')){ mpre("ОШИБКА выборки адреса страницы из canonical");
			}else{ return $url; }
		})){ mpre("ОШИБКА формирования адреса пагинатора");
	}elseif(!$url = seo($url)){ mpre("ОШИБКА получения СЕО адреса страницы пагинатора");
	}elseif(!$uri = urldecode($url)){ mpre("ОШИБКА приведения адреса в нужный формат");
	}elseif(!$url = get($_GET, $p) ? strtr($uri, array("/{$p}:{$_GET[$p]}"=>'', "&{$p}={$_GET[$p]}"=>'')) : $url){ mpre("ОШИБКА замены в адресной строке номера текущей страницы");
	}elseif(!$url = call_user_func(function() use($url, $uri, $null){ # Формирование адреса исходя из усорвмя отображения шаблона
			if($null){ return str_replace($uri, $uri. (strpos($url, '&') || strpos($url, '?') ? "&null" : "/null"), $url);
			}else if($null === false){ return strtr($url, array("/null"=>"", "&null"=>"", "?null"=>""));
			}else{ return $url; }
		})){ mpre("ОШИБКА изменения адреса страницы для стрниц с выключенны отображением шаблона");
	}elseif(!$url = (strpos($url, "?") ? $url : $url. "?")){ mpre("ОШИБКА добавления вопроса");
	}else{// mpre($url);
	}
	
	if(2 > $count = ceil($count)) return;
	$return = "<script>$(function(){ $(\".pager\").find(\"a[href='". urldecode($REQUEST_URI). "']\").addClass(\"active\").css(\"font-weight\", \"bold\"); })</script>";
	$return .=  "<div class=\"pager\">";
	$mpager['first'] = $url;

//	mpre($url);
	
	$return .= "<a rel=\"prev\" href=\"$url".($cur > 1 ? "/{$p}:".($cur-1) : '')."\">&#8592; назад</a>";
	$mpager['prev'] = $url. ($cur > 1 ? (strpos($url, '&') || strpos($url, '?') ? "&{$p}=".($cur-1) : "/{$p}:".($cur-1)) : '');
	for($i = max(0, min($cur-5, $count-10)); $i < ($max = min($count, max($cur+5, 10))); $i++){
		$mpager[ $i+1 ] = $url. ($i ? (strpos($url, '&') || strpos($url, '?') ? "&{$p}=$i" : (substr($url, -1, 1) == "/" ? "" : "/"). "{$p}:$i") : '');
		$return .=  '&nbsp;'. ("<a href=\"$url".($i ? (strpos($url, '&') || strpos($url, '?') ? "&{$p}=$i" : (substr($url, -1, 1) == "/" ? "" : "/"). "{$p}:$i") : ''). "\">".($i+1)."</a>");
	}
	$return .=  '&nbsp;';
	$return .=  "<a rel=\"next\" href=\"$url".($i ? (strpos($url, '&') || strpos($url, '?') ? "&{$p}=".($cur+1) : "/{$p}:".($cur+1)) : '')."\">вперед &#8594;</a>";
	$mpager['next'] = $url. ($i ? (strpos($url, '&') || strpos($url, '?') ? "&{$p}=".($cur+1) : (substr($url, -1, 1) == "/" ? "" : "/"). "{$p}:". min($max-1, $cur+1)) : '');
	$mpager['last'] = $url. ($i ? (strpos($url, '&') || strpos($url, '?') ? "&{$p}=".($count-1) : (substr($url, -1, 1) == "/" ? "" : "/"). "{$p}:". ($count-1)) : '');
	$return .= "</div>";
	if((($theme = get($conf, 'settings', 'theme')) && ($fn = mpopendir("themes/{$theme}/mpager.tpl"))) || ($fn = mpopendir("themes/zhiraf/mpager.tpl"))){
		ob_start();
		include($fn);
		$return = ob_get_contents();
		ob_end_clean();
		return $return;
	}else{ return $return; }
}

function mphash($user, $pass){
	return md5("$user:".md5($pass));
}
function mpget($name, $value = null){
	$param = "$name".(strlen($value) ? "=$value" : '');
	if (isset($_GET[$name])){
		return str_replace("$name={$_GET[$name]}", $param, $_SERVER['REQUEST_URI']);
	}else{
		return $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'], '?') ? '&' : '?').$param;
	}
}
function mpct($file_name, $arg = array(), $vr = 1){
	global $conf, $tpl;
	foreach(explode('::', $conf["db"]["open_basedir"]) as $k=>$v)
		if (file_exists($file = "$v/$file_name")) break;
	if (!file_exists($file = "$v/$file_name")) return false;
	$conf['settings']['data-file'] = $file;
	$func_name = create_function('$arg', "global \$conf, \$tpl;\n". strtr(file_get_contents($file), $vr ? array('<? die;'=>'', '<?'=>'', '?>'=>'') : array()));
	ob_start(); $func_name($arg);
	$content = ob_get_contents(); ob_end_clean();
	return $content;
}
function mpeval($file_name, $arg = array(), $vr = 1){
	global $conf;
	foreach(explode('::', $conf["db"]["open_basedir"]) as $k=>$v)
		if (file_exists($file = "$v/$file_name")) break;
	if (!file_exists($file = "$v/$file_name")) return "<div style=\"margin-top:100px; text-align:center;\"><span style=color:red;>Ошибка доступа к файлу</span> $v/$file_name</div>";
	ob_start();
	$conf['settings']['data-file'] = $file;
	eval('?>'. strtr(file_get_contents($file), array('<? die;'=>'<?', '<?php die;'=>'<?php')));
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}
function mpreaddir($file_name, $merge=0){
	global $conf;
	$itog = array();
	$prefix = $merge ? explode('::', $conf["db"]["open_basedir"]) : array('./');
	if ($merge < 0) krsort($prefix);
	foreach($prefix as $k=>$v){
		if (!is_dir("$v/$file_name")) continue;
		$dir = opendir("$v/$file_name");
		$files = array();
		while($file = readdir($dir)){
			if (substr($file, 0, 1) == '.') continue;
			$files[] = $file;
		}
		$itog = array_merge_recursive($itog, $files);
	}
	return $itog;
}
function mpopendir($file_name, $merge=1){
//	mpre(__DIR__);
	global $conf;
	if(!$prefix = $merge ? explode('::', get($conf, "db", "open_basedir")) : array('./')){ mpre("Ошибка нахождения префиксов настройки системы");
	}elseif(($merge < 0) && krsort($prefix)){ mpre("Ошибка сортировки префиксов");
	}else{
		foreach($prefix as $k=>$v){
			$file = strtr(/*mpre*/("$v/$file_name"), array('/modules/..'=>''));
			if(file_exists($file)){
				return $file; break;
			}
		}
	}
}
function mpql($dbres, $ln = null, $fd = null){
	$result = array();
	if($dbres){
		try{
			$result = $dbres->fetchAll();
			if($ln !== null && $result){
				$result = $result[$ln];
				if($fd){
					$result = $result[$fd];
				}
			}
		} catch(Exception $e){
			mpre($e->getMessage());
		}
	} return $result;
} function ql($sql, $ln = null, $fd = null){ # Выполнение запроса к базе данных. В случае превышения лимита времени кеширование результата
	$microtime = microtime(true);
	if(!($r = mpmc($key = "ql:". md5($sql)))){
		if($mpqw = mpqw($sql)){
			$r = mpql($mpqw, $ln, $fd);
			if(($mt = (microtime(true) - $microtime)) > .3){
				mpevent("Кеширование списка", $sql);
				mpmc($key, $r);
			}
		} return $r;
	}
}

function mpqn($dbres, $x = "id", $y = null, $n = null, $z = null){
	$result = array();
	if($dbres){
		while($line = $dbres->fetch(PDO::FETCH_ASSOC)){
			if($z){
				$result[ $line[$x] ][ $line[$y] ][ $line[$n] ][ $line[$z] ] = $line;
			}elseif($n){
				$result[ $line[$x] ][ $line[$y] ][ $line[$n] ] = $line;
			}elseif($y){
				$result[ $line[$x] ][ $line[$y] ] = $line;
			}else{
				$result[ $line[$x] ] = $line;
			}
		}
	}
	return $result;
} function qn($sql){ # Выполнение запроса к базе данных. В случае превышения лимита времени кеширование результата. Возвращается список записей в нормальной форме
	$microtime = microtime(true);
	if(!($r = mpmc($key = "qn:". md5($sql)))){
		$func_get_args = func_get_args();
		$func_get_args[0] = qw($sql);
		$r = call_user_func_array('mpqn', $func_get_args);
		if(($mt = microtime(true) - $microtime) > .3){
//			mpevent("Кеширование нумерованного списка", $sql);
			mpmc($key, $r);
		}
	} return $r;
}

# Функция выполнения запросов. Определяет параметры по типу. Принимает функцию - обработку ошибки, массив - замены [":name"=>"name"] и строку - описание запроса
function mpqw($sql){ global $conf; # Все аргументы разбираются по типам
	if(!is_array($ARGS = array_map(function($arg){
			if(!$type = (is_callable($arg) ? "function" : gettype($arg))){ mpre("ОШИБКА получения типа аргумента");
			}else{ return ['type'=>$type, 'arg'=>$arg]; }
		}, array_slice(func_get_args(), 1)))){ mpre("ОШИБКА выборки параметров");
	}elseif(!$mt = microtime(true)){ mpre("ОШИБКА установки времени начала запроса");
	}elseif(!$conn = ((rb($ARGS, 'type', '[object]', 'arg')) ?: $conf['db']['conn'])){ mpre("ОШИБКА определения соединения");
	}elseif(!$result = call_user_func(function($ARGS) use($sql, $conn, &$conf){// mpre($ARGS);
			if(!$params = rb($ARGS, 'type', '[array]', 'arg')){// mpre("Параметры не заданы");
				if(!$conf['db']['sql'][] = array('info'=>'', 'time'=>0, 'sql'=>$sql)){ mpre("ОШИБКА добавления информации о запросе");
				}elseif($result = $conn->query($sql)){ return $result;
				}elseif(!$callback = rb($ARGS, 'type', '[function]', 'arg')){ return $result;
				}elseif(!$info = $conn->errorInfo()){ mpre("ОШИБКА получения информации о запросе");
				}elseif(!$error = get($info, 2)){ mpre("Не удалось получить текст ошибки запроса");
				}elseif(!$result = call_user_func($callback, $error)){ return $result;
				}else{ mpre("Запрос не дал результата", $error);
				}
			}elseif(!$result = $conn->prepare($sql)){ mpre("Подготовка к запросу");
			}elseif(!array_map(function($name, $value) use(&$result){
					return $result->bindValue(":{$name}", $value);
				}, array_keys($params), $params)){ mpre("ОШИБКА устанвоки параметров");
			}elseif(!is_bool($result->execute())){ mpre("ОШИБКА выбополнения запроса");
			}else{ return $result; }
		}, $ARGS)){// mpre("ОШИБКА получения результата выполнения запроса");
	}elseif(call_user_func(function($mt) use($conf, $sql, $ARGS){
			if(!get($conf, 'settings', 'analizsql_log')){// mpre("Лог выполнения отключен");
			}elseif(!$microtime = microtime(true)-$mt){ mpre("ОШИБКА расчета времени выполнения");
			}elseif(!$info = (rb($ARGS, 'type', '[string]', 'arg') ?: get($conf, 'db', 'info'))){ mpre("Описание запроса не задано");
			}elseif(!$conf['db']['sql'][] = $mess = array('info'=>$info, 'time'=>$microtime, 'sql'=>$sql)){ mpre("ОШИБКА добавления информации в лог");
			}elseif(!get($conf, 'settings', 'sqlanaliz_time_log')){// pre("Лимиты выполнения запроса не заданы");
			}elseif($microtime <= $conf['settings']['sqlanaliz_time_log']){// mpre("Время запроса в пределах нормы");
			}else{ mpre("Долгий запрос к базе данных", $sql, $mess); }
		}, $mt)){ mpre("ОШИБКА сохранения информации о времени выполнения запроса");
	}elseif(!is_numeric($count = $result->rowCount())){ mpre("ОШИБКА получения количества изменений");
	}else{ return $result; }
} function qw($sql, $info = null, $callback = null, $params = null, $conn = null){
	return call_user_func("mpqw", $sql, $info, $callback, $params, $conn);
}
function mpfile($filename, $description = null){
//	$file_name = strtr($file_name, array('../'=>'', '/./'=>'/', '//'=>'/'));
	$file_name = mpopendir("include/$filename");
	if (file_exists($file_name)){
		$ext = explode('.', $file_name); $ext = $ext[count($ext) - 1];
//		header("Content-Type:	 text/html; charset=windows-1251");
//		header("Content-Type: application/force-download; name=\"".($description ? "/$description/". (substr($description, strlen($ext)*-1)) : basename($file_name))."\"");
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: ".filesize("$file_name"));
		header("Content-Disposition: attachment; filename=\"".($description ? "$description". (substr($description, strlen($ext)*-1) == $ext ? "" : ".". $ext) : basename($file_name))."\"");
//		header('Cache-Control: max-age=28800');
//		header("Cache-Control: max-age=3600, must-revalidate");
//		header("Pragma: no-cache");
//		readfile($file_name); exit;
		$handle = fopen($file_name, 'rb');
		while (!feof($handle)){
			echo fread($handle, 4096);
			ob_flush();
			flush();
		} fclose($handle); exit;
	}else{
		return '';
	}
}
function mpgc($value, $param = null){
	if ($param) unset($value[$param]);
	ob_start();
	var_dump($value);
	$str = ob_get_contents();
	ob_end_clean();
	return $str;
}
function mpwysiwyg($name, $content = null, $tpl = ""){
	global $conf;
	if(!isset($conf['settings']['wysiwyg']) OR empty($conf['settings']['wysiwyg'])){
		$conf['settings']['wysiwyg'] = 'tinymce';		
		fk(
			"{$conf['db']['prefix']}settings",
			$w=['name'=>'wysiwyg'],
			$w+['modpath'=>'settings','value'=>$conf['settings']['wysiwyg']],
			['value'=>$conf['settings']['wysiwyg']]
		);
	}
	if(isset($conf['modules'][$conf['settings']['wysiwyg']]) AND !empty($conf['modules'][$conf['settings']['wysiwyg']]['admin_access'])){
		$conf['settings'][$conf['settings']['wysiwyg'].'_name'] = $name;
		$conf['settings'][$conf['settings']['wysiwyg'].'_text'] = $content;
		if($tpl && $fn = mpopendir("modules/{$conf['settings']['wysiwyg']}/". basename($tpl))){
			include $fn;
		}else{
			include mpopendir("modules/{$conf['settings']['wysiwyg']}/wysiwyg.tpl");
		}
	}else{
		return "<textarea name='$name' style='width:100%; height:200px;'>$content</textarea>";
	}
}
function mpmenu($m = array()){
	global $conf, $arg;
	# Скрываем меню в админке для администраторов
	if($conf['settings']['admin_mpmenu_hide'] && $arg['admin_access'] < 5) return;
	if(array_key_exists("null", $_GET)) return false;
	$tab = (int)$_GET['r'];
	if($_GET['r']){
		echo <<<EOF
			<script>
				$(function(){
					$('.tabs li.{$tab}').add('.tabs li.{$_GET['r']}').addClass('act');
				});
			</script>
EOF;
	}
	if(empty($conf['settings']['admin_help_hide'])){
		echo '<div style="float:right; margin:5px;"><a target=blank href="//mpak.su/help/modpath:'. $arg['modpath']. "/fn:". $arg['fn']. '/r:'. $_GET['r']. '">Помощь</a></div>';
	}
	if($modname = array_search('admin', $_GET['m'])){
		$modname_id = mpfdk("{$conf['db']['prefix']}modules_index",
			array("folder"=>$modname), null, array("priority"=>time())
		);
	}
	echo '<ul class="nl tabs">';
	foreach($m as $k=>$v){
		if (($v[0] == '.') && ($_GET['r'] != $k)) continue;
		echo "<li class=\"$k\"><a href=\"/{$modname}:admin". ($k ? "/r:$k" : ''). "\">$v</a></li>";
	}
	echo '</ul>';
	if(!empty($m) && empty($_GET['r'])){
		if(!is_numeric($r = array_shift(array_keys($m))) && (strpos($_SERVER['REQUEST_URI'], "?") !== false)){
			header("Location: /admin:{$arg['modname']}/r:". array_shift(array_keys($m)));
		}
	}
}

function pre(){
	global $conf;
	if(!$debug_backtrace = debug_backtrace()){ mpre("Ошибка получения списка функций");
	}elseif(!is_numeric($func = ('mpre' == get($debug_backtrace, 1, 'function') ? 1 : 0))){ mpre("Ошибка получения аргументов функции");
	}elseif(!is_numeric($func = ('mpre' == get($debug_backtrace, 2, 'function') ? 2 : $func))){ mpre("Ошибка получения аргументов функции");
	}elseif(!$pre = get($debug_backtrace, $func)){ print_r("Ошибка получения фукнции инициатора pre[{$num}]");
	}elseif(!$args = get($pre, 'args')){ mpre("Ошибка выборки аргументов");
	}else{// echo "<pre>"; print_r($debug_backtrace); echo "</pre>";
		echo "<fieldset class='pre' style=\"z-index:". ($conf['settings']['themes-z-index'] = ($z_index = get($conf, "settings", 'themes-z-index')) ? --$z_index : 999999). "\"><legend> ". get($pre, 'file'). ":". get($pre, 'line'). " <b>{$pre['function']}</b> ()</legend>";
		foreach($args as $n=>$z){
			echo "<pre>\t\n\t"; print_r($z); echo "\n</pre>";
		} echo "</fieldset>\n";
	} return get(func_get_args(), 0);
} function mpre(){// print_r(func_get_args());
	global $conf, $arg;
	if((!$gid = get($conf, 'user', 'gid')) || (!array_search("Администратор", $gid))){ return first(func_get_args()); // print_r("Отображение доступно только администраторам");
	}else{// mpre(debug_backtrace());
		return call_user_func_array("pre", func_get_args());
	}
}
function mpquot($data){ # экранирование символов при использовании в запросах к базе данных
	global $conf;
	if(ini_get('magic_quotes_gpc')){ # Волшебные кавычки для входных данных GET/POST/Cookie. magic_quotes_gpc = On
		$data = stripslashes($data);
	}
	
	$data = str_replace("\\", "\\\\", $data); 
	$data = str_replace("\x00", "\\x00", $data); 
	$data = str_replace("\x1a", "\\x1a", $data); 
	if(!$data){// mpre("Содержимое не задано");
	}elseif($conf['db']['type'] == 'sqlite'){ # sqlite
		$data = strtr($data, ["'"=>"''", '"'=>'""']);
	}else{ # mysql
		$data = str_replace("'", "\'", $data); 
		$data = str_replace('"', '\"', $data); 
		$data = str_replace("\r", "\\r", $data); 
		$data = str_replace("\n", "\\n", $data); 
	} 
	
	return $data;
}

# Изменение размеров изображения. ($max_width и $max_height) высота и ширина. Параметр $crop это способ обработки. Обрезать или вписать в размер
function mprs($file_name, $max_width=0, $max_height=0, $crop=0){
	global $conf;
	$func = array(
		'jpg' => 'imagejpeg',
		'jpeg' => 'imagejpeg',
		'png' => 'imagepng',
		'gif' => 'imagegif',
	);
	$keys = array_keys($ar = explode('.', $file_name));
	$ext = $ar[max($keys)];
	$cache_name = (ini_get('upload_tmp_dir') ? ini_get('upload_tmp_dir') : "/tmp"). "/images";
	$host_name = strpos('www.', $_SERVER['SERVER_NAME']) === 0 ? substr($_SERVER['SERVER_NAME'], 4) : $_SERVER['SERVER_NAME'];
	$fl_name = (int)$max_width. "x". (int)$max_height. "x". (int)$crop. "_" .basename($file_name);
	$prx = basename(dirname($file_name));
	if(!array_key_exists('nologo', $_GET) && (strtotime(get($_SERVER, 'HTTP_IF_MODIFIED_SINCE')) >= filectime($file_name))){
		exit(header('HTTP/1.0 304 Not Modified'));
	}else if((get($_SERVER, 'HTTP_PRAGMA') != "no-cache") && file_exists("$cache_name/$host_name/$prx/$fl_name") && (($filectime = filectime("$cache_name/$host_name/$prx/$fl_name")) > ($sfilectime = filectime($file_name)))){
//		header('Last-Modified: '. date("r", $filectime));
//		header("Expires: ".gmdate("r", time() + 86400*10));
		return file_get_contents("$cache_name/$host_name/$prx/$fl_name");
	}else if($src = imagecreatefromstring(file_get_contents($file_name))){
//		header('Last-Modified: '. date("r", filectime($file_name)));
//		header("Expires: ".gmdate("r", time() + 86400*10));
		$width = imagesx($src);
		$height = imagesy($src);
		if(!array_key_exists('water', $_GET) && (empty($max_width) || empty($max_height) || (($width <= $max_width) && ($height <= $max_height)))){
			$content = file_get_contents($file_name);
		}else{
			if($crop){
				$cdst = array($max_width, $max_height);
				$max = max($max_width/$width, $max_height/$height);
				$irs = array(4=>($width-$max_width/$max)/2, ($height-$max_height/$max)/2, $max_width, $max_height, ($max_width/$max), ($max_height/$max),);
			}else{
				$x_ratio = $max_width / $width;
				$y_ratio = $max_height / $height;
				if ( ($width <= $max_width) && ($height <= $max_height) ){
					$tn_width = $width;
					$tn_height = $height;
				}elseif (($x_ratio * $height) < $max_height){
					$tn_height = ceil($x_ratio * $height);
					$tn_width = $max_width;
				}else{
					$tn_width = ceil($y_ratio * $width);
					$tn_height = $max_height;
				}
				$irs = array(4=>0, 5=>0, $tn_width, $tn_height, $width, $height,);
				$cdst = array($tn_width, $tn_height);
			}
			$dst = imagecreatetruecolor($cdst[0], $cdst[1]);
			imagealphablending($dst, false);
			imagesavealpha($dst, true);
			imagecopyresampled($dst, $src, 0, 0, $irs[4], $irs[5], $irs[6], $irs[7], $irs[8], $irs[9]);
			if (
				!array_key_exists('nowater', $_GET) &&
				!empty($conf['settings']['theme_logo']) &&
				(imagesx($dst) >= 200) &&
				(imagesy($dst) >= 200) &&
				!isset($_GET['m']['themes']) &&
				($lg = explode(':', $conf['settings']['theme_logo'])) &&
				($f = mpopendir("themes/{$conf['settings']['theme']}/". array_shift($lg))) &&
				$logo = imagecreatefromstring(file_get_contents($f))
			){
				imagealphablending($dst, true);
				$w = array_shift($lg); $h = array_shift($lg);
				imagecopyresampled($dst, $logo, ($w < 0 ? imagesx($dst)-imagesx($logo)+$w : $w), ($h < 0 ? imagesy($dst)-imagesy($logo)+$h : $h), 0, 0, imagesx($logo), imagesy($logo), imagesx($logo), imagesy($logo));
			}
			if(!$f = $func[ strtolower($ar[max($keys)]) ]){ mpre("Ошибка получения функции сжатия изображения");
			}elseif(!$q = (get($_GET, 'q') ?: 80)){ mpre("Ошибка получения качества изображения");
			}elseif(($f == "imagepng") && (!$q = (int)$q/10)){ mpre("Ошибка изменения параметра качетсва для функции png");
			}else{
				ob_start();
					$keys = array_keys($ar = explode('.', $file_name));
					$f($dst, null, $q);
					$content = ob_get_contents();
				ob_end_clean();
			} ImageDestroy($src); ImageDestroy($dst);
		}
		if(!file_exists("$cache_name/$host_name/$prx")){
			if($idna = mpopendir('include/idna_convert.class.inc')){
				require_once($idna);
			}
			$IDN = new idna_convert();
			if(is_writeable("$cache_name/$host_name")){
				mkdir("$cache_name/$host_name/$prx", 0755, 1);
				if($host_name != $IDN->decode($host_name) && !file_exists("$cache_name/". $IDN->decode($host_name))){
					symlink("$cache_name/$host_name", "$cache_name/". $IDN->decode($host_name));
				}
			}
		}
		if(is_writeable("$cache_name/$host_name/$prx")){
			file_put_contents("$cache_name/$host_name/$prx/$fl_name", $content);
		}
		if(function_exists("mpevent")){
			mpevent("Формирование изображения", $fl_name, $conf['user']['uid']);
		} return $content;
	}else{
		$src = imagecreate (65, 65);
		$bgc = imagecolorallocate ($src, 255, 255, 255);
		$tc = imagecolorallocate ($src, 0, 0, 0);
		imagefilledrectangle ($src, 0, 0, 150, 30, $bgc);
		header("Content-type: image/jpeg");
		header('Last-Modified: '. date("r"));
		mpevent("Ошибка открытия изображения", $file_name, $conf['user']['uid']);
		imagestring($src, 1, 5, 30, (file_exists($file_name) ? "GD Error" : "HeTKapmuHku"), $tc);
		return ImageJpeg($src);
	}
}

if(!function_exists("array_column")){
	function array_column(array $input, $columnKey, $indexKey = null){
		$result = array();
		if(null === $indexKey){
			if(null === $columnKey){
				$result = array_values($input);
			}else{
				foreach($input as $row){
					$result[] = get($row, $columnKey);
				}
			}
		}else{
			if(null === $columnKey){
				foreach($input as $row){
					$result[$row[$indexKey]] = $row;
				}
			}else{
				foreach($input as $row){
					$result[$row[$indexKey]] = $row[$columnKey];
				}
			}
		} return $result;
	}
}
