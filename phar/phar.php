<?

setlocale(LC_CTYPE, 'ru_RU.utf-8'); 
chdir(__DIR__);

try{
	rename($phar = "index.phar", (ini_get('upload_tmp_dir') ?: "/tmp/"). $phar);
	$p = new Phar($phar, 0, $phar);
	file_put_contents("phar://{$phar}/version.txt", date("Y.m.d H:i:s"));
} catch (UnexpectedValueException $e) {
    die("Could not open {$phar}");
} catch (BadMethodCallException $e) {
    echo 'technically, this cannot happen';
}

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
