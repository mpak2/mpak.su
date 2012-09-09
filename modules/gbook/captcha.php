<? die;

ini_set('display_errors','Off');
include "include/kcaptcha/index.php";
$captcha = new KCAPTCHA();
setcookie("captcha_keystring", md5($captcha->getKeyString()), 0, '/');
exit;

?>