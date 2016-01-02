<?

try {
	$p = new Phar($phar = "test.phar", 0, $phar);
} catch (UnexpectedValueException $e) {
    die("Could not open {$phar}:: {$e}");
} catch (BadMethodCallException $e) {
    echo 'technically, this cannot happen';
}

$p['index.php'] = <<<EOF
<?php

phpinfo();

EOF;

$p->setStub('<?php Phar::mapPhar(); include "phar://". basename(__FILE__). "/index.php"; __HALT_COMPILER(); ?>');
$p->stopBuffering();

$dir = opendir($folder = "phar://{$phar}/");
echo "\n\n". $folder;
while($fn = readdir($dir)){
	echo "\n". $fn;
}
