<?

try {
	$p = new Phar($f = "mpak.phar", 0, $f);
} catch (UnexpectedValueException $e) {
    die("Could not open {$f}");
} catch (BadMethodCallException $e) {
    echo 'technically, this cannot happen';
}

function apr($folder){
	if(is_dir($folder)){
		$dir = opendir($folder);
		while($file = readdir($dir)){
			if ($file[0] != '.') apr("$folder/$file");
		}
	}else{
		copy("$folder", "phar://mpak.phar/$folder/$file");
	}
}

//copy("tmp/index.php", "phar://tmp/test.phar/index.php");
//copy("tmp/mpfunc.php", "phar://tmp/test.phar/include/mpfunc.php");
foreach( $dolders = array(
	'index.php',
	'include/func.php',
	'include/mpfunc.php',
	'include/install.php',

	'img',
	'modules',
	'themes/zhiraf',	'themes/wp20',	'themes/reddy', 'themes/grunge',
	'include/blocks',
	'include/dhonishow',
	'include/image-menu-1',
	'include/jquery',
	'include/jquery-lightbox-0.5',
	'include/jquery.rte',
	'include/openid-php-openid-782224d',
	'include/vkontakte',
) as $k=>$v){
	echo "\nadded: $v";
	apr("../$v");
}


$p['/config/config.php'] = <<<EOF
<?

date_default_timezone_set('Europe/Moscow');
\$conf['fs']['path'] =  substr(dirname(dirname(dirname(__FILE__))), 7). "/:". dirname(dirname(__FILE__));
\$conf['db']['conn'] = null;
\$conf['db']['type'] = 'mysql';
\$conf['db']['prefix'] = 'mp_';
\$conf['db']['host'] = 'localhost';
\$conf['db']['login'] = 'login';
\$conf['db']['name'] = 'name';
\$conf['db']['pass'] = 'pass';

?>
EOF;

$p->setStub('<?php Phar::mapPhar(); ini_set("include_path", "phar://". __FILE__); include "index.php"; __HALT_COMPILER(); ?>');
$p->stopBuffering();

$dir = opendir($folder = 'phar://mpak.phar/');
echo "\n\n". $folder;
while($fn = readdir($dir)){
	echo "\n". $fn;
}

?>