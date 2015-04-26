<?

try {
	$p = new Phar($phar = "mpak.phar", 0, $phar);
} catch (UnexpectedValueException $e) {
    die("Could not open {$phar}");
} catch (BadMethodCallException $e) {
    echo 'technically, this cannot happen';
}

$p['index.php'] = <<<EOF
<?

echo phpinfo();
EOF;

$p->setStub('<?php Phar::mapPhar(); ini_set("include_path", "phar://". __FILE__); include "index.php"; __HALT_COMPILER(); ?>');
$p->stopBuffering();

