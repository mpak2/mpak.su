<?

setlocale(LC_CTYPE, 'ru_RU.utf-8'); 

try {
	rename($phar = "index.phar", (ini_get('upload_tmp_dir') ?: "/tmp/"). $phar);
	$p = new Phar($phar, 0, $phar);
} catch (UnexpectedValueException $e) {
    die("Could not open {$phar}");
} catch (BadMethodCallException $e) {
    echo 'technically, this cannot happen';
}

function apr($folder){
	if(is_dir($folder)){
		$dir = opendir($folder);
		while($file = readdir($dir)){
			if (($file[0] != '.') && ($f = "$folder/$file")){
				apr($f);
			}
		}
	}else{
		if($f = "phar://index.phar/". preg_replace("#(\..*+\.)#i",'.',$folder)){

			echo "copy(\"$folder\", \"$f\")\n";
			copy("$folder", $f);
		}
	}
}

foreach( $dolders = array(
	'index.php',
//	'phpinfo.php',
	'include/func.php',
	'include/mpfunc.php',
	'include/install.php',
	'include/parse/simple_html_dom.php',
	'include/idna_convert.class.inc',
	'include/jquery/jquery.iframe-post-form.js',

	'img',
	'modules',
	'themes/zhiraf', 'themes/bootstrap3', 'themes/vk',
	'include/blocks',
	'include/class',
	'include/mail',
	'include/jquery/tiny_mce',
	'include/jquery/tiny_mce/inputmask', # <!-- [settings:inputmask] --> Скрипты для маск ввода в формы, в разделе тема создана переменная для ввода всех скриптов
	'include/dhonishow',
//	'include/image-menu-1',
	'include/jquery-lightbox-0.5',
//	'include/jquery.rte',
//	'include/openid-php-openid-782224d',
//	'include/vkontakte',
) as $k=>$v){
	echo "\nadded: $v\n\n";
	apr("../$v");
} if(file_exists($f = "./index.php")){
	echo "$f\n";
	copy($f, "phar://index.phar/index.php");
}


/*$p['/config/config.php'] = <<<EOF
<?

#date_default_timezone_set('Europe/Moscow');
\$conf['db']['open_basedir'] = dirname(dirname(__FILE__));// echo \$conf['db']['open_basedir']. "<br>";

EOF;*/
$p->setStub('<?php Phar::mapPhar(); ini_set("include_path", "phar://". __FILE__); include "index.php"; __HALT_COMPILER(); ?>');
/*$p->setStub('<?php Phar::mapPhar(); ini_set("include_path", "phar://". __FILE__); include "index.php"; __HALT_COMPILER(); ?>');*/
$p->stopBuffering();

$dir = opendir($folder = 'phar://index.phar/');
echo "\n\n". $folder;
while($fn = readdir($dir)){
	echo "\n". $fn;
}
