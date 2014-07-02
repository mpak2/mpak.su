<?

try {
	$p = new Phar($phar = "create.phar", 0, $phar);
} catch (UnexpectedValueException $e) {
    die("Could not open {$phar}:: {$e}");
} catch (BadMethodCallException $e) {
    echo 'technically, this cannot happen';
}

$p['index.php'] = <<<EOF
<?

echo "work";

EOF;

$p->setStub('<?php Phar::mapPhar(); ini_set("include_path", "phar://". __FILE__); include "index.php"; __HALT_COMPILER(); ?>');
$p->stopBuffering();

$dir = opendir($folder = 'phar://mpak.phar/');
echo "\n\n". $folder;
while($fn = readdir($dir)){
	echo "\n". $fn;
}