<?

header("Content-Type: text/html; charset=utf-8");

require 'vkapi.class.php';

$api_id = $conf['settings']['vkontakte_api_id']; // Insert here id of your application
$secret_key = $conf['settings']['vkontakte_app_secret_key']; // Insert here secret key of your application

$VK = new vkapi($api_id, $secret_key);

//$resp = $VK->api('getProfiles', array('uids'=>'1,6492,64142510','3770553'));
//mpre($resp);

?>
