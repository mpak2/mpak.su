<?

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

setlocale(LC_CTYPE, 'ru_RU.utf-8'); 
chdir(__DIR__);

/*try{
	if(file_exists($phar = "index.phar")){
		
	}
	
	
} catch (UnexpectedValueException $e) {
    die("Could not open {$phar}");
} catch (BadMethodCallException $e) {
    echo 'technically, this cannot happen';
}*/

if(!$phar = "index.phar"){ mpre("Ошибка установки имени файла");
}else if(file_exists($phar) && !rename($phar, (ini_get('upload_tmp_dir') ?: "/tmp/"). $phar)){ mpre("Ошибка переноса старой копии файла во временную директорию");
}else if(!$p = new Phar($phar, 0, $phar)){ mpre("Ошибка создания архива {$phar}");
}else if(!file_put_contents("phar://{$phar}/version.txt", date("Y.m.d H:i:s"))){ mpre("Ошибка добавления версии движка в архив");
}else{


function apr($folder, $phar){
	if(is_dir($folder)){
		$dir = opendir($folder);
		while($file = readdir($dir)){
			if (($file[0] != '.') && ($f = "$folder/$file")){
				apr($f, $phar);
			}
		}
	}else{
		if($f = "phar://{$phar}/". preg_replace("#(\..*+\.)#i",'.',$folder)){
			echo "copy(\"$folder\", \"$f\")\n";
			copy("$folder", $f);
		}
	}
}

$dolders = array(
	'index.php',
	'include/config.php',
	'include/mpfunc.php',
	'include/install.php',
	'include/mail', # Отправка почты на smtp
	'include/class/simple_html_dom.php',
	'include/idna_convert.class.inc',

	'include/jquery/jquery.iframe-post-form.js',
	'include/jquery/jquery.selection.js',

	'img',
	'modules',
	'themes/zhiraf', 'themes/bootstrap3', 'themes/vk',
	'include/blocks',
	'include/class',
	'include/mail',
	'include/jquery/tiny_mce',
	'include/jquery/inputmask', # <!-- [settings:inputmask] --> Скрипты для маск ввода в формы, в разделе тема создана переменная для ввода всех скриптов
	'include/dhonishow',
	'include/jquery-lightbox-0.5',
);

if(array_key_exists(1, $argv)){
	$dolders = [$argv[1]];
}// print_r($argv); die;

foreach($dolders as $k=>$v){
	echo "\nadded: $v\n\n";
	apr("../$v", $phar);
} if(file_exists($f = "./index.php")){
	echo "$f\n";
	copy($f, "phar://{$phar}/index.php");
}


/*$p['/config/config.php'] = <<<EOF
<?

#date_default_timezone_set('Europe/Moscow');
\$conf['db']['open_basedir'] = dirname(dirname(__FILE__));// echo \$conf['db']['open_basedir']. "<br>";

EOF;*/
$p->setStub('<?php Phar::mapPhar(); include "phar://". __FILE__. "/index.php"; __HALT_COMPILER(); ?>');
/*$p->setStub('<?php Phar::mapPhar(); include "phar://". basename(__FILE__). "/index.php"; __HALT_COMPILER(); ?>');*/ - //не работатает  в cli
/*$p->setStub('<?php Phar::mapPhar(); ini_set("include_path", "phar://". __FILE__); include "index.php"; __HALT_COMPILER(); ?>');*/
$p->stopBuffering();

$dir = opendir($folder = "phar://{$phar}/");
echo "\n\n". $folder;
while($fn = readdir($dir)){
	echo "\n". $fn;
}
}
