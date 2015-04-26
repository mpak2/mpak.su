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

$p->setStub('<?php Phar::webPhar("", "index.php"); ini_set("include_path", "phar://". __FILE__); include phar://index.php"; __HALT_COMPILER(); ?>');
$p->stopBuffering();

$dir = opendir($folder = "phar://{$phar}/");
echo "\n\n". $folder;
while($fn = readdir($dir)){
	echo "\n". $fn;
}