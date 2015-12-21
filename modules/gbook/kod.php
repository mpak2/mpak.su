<?

$kod = substr(md5("{$_GET['kod']}:{$GLOBALS['conf']['user']['sess']}"), 0, 5);
header ("Content-type: image/png");
$im = @imagecreate (70, 17)
    or die ("Cannot Initialize new GD image stream");
$background_color = imagecolorallocate ($im, 255, 255, 255);
$text_color = imagecolorallocate ($im, 233, 14, 91);
imagestring ($im, 2, 20, 2,  $kod, $text_color);
imagepng ($im);
die;

?>
