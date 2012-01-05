<? die;

define(Auth_OpenID_RAND_SOURCE, null);
$include_path = ini_get('include_path');
ini_set('include_path', 'include/openid-php-openid-782224d/');
require_once "include/openid-php-openid-782224d/Auth/OpenID/Consumer.php";
require_once "include/openid-php-openid-782224d/Auth/OpenID/FileStore.php";
require_once "include/openid-php-openid-782224d/Auth/OpenID/AX.php";
ini_set('include_path', $include_path);

$store = new Auth_OpenID_FileStore("/tmp");
$consumer = new Auth_OpenID_Consumer($store);
$response = $consumer->complete('http://mpak.su/users:openid');

if($response->status == Auth_OpenID_SUCCESS){ // || $_SESSION['openid']['status'] == 'success'
	echo "We now have the tokens! The code samples below will continue <a href=>Ок</a>";
	if(mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_type"), 0, 'auth')){
		$url = parse_url($response->identity_url); $uname = $url['host'];
		$grp = mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_grp WHERE name=\"".mpquot($conf['settings']['user_grp'])."\""), 0);
		if($user = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']} WHERE tid=2 AND name=\"".mpquot($uname)."\""), 0)){
			mpqw($sql = "UPDATE {$conf['db']['prefix']}{$arg['modpath']} SET last_time=".time(). " WHERE id=".(int)$user['id']);
			mpqw($sql = "UPDATE {$conf['db']['prefix']}sess SET uid=".(int)$user['id']." WHERE id=".(int)$conf['user']['sess']['id']);
		}else{
			mpqw($sql = "INSERT INTO {$conf['db']['prefix']}users SET tid=2, name=\"".mpquot($uname)."\", pass=\"nopass\", reg_time=".time().", last_time=".time());
			$conf['user']['uid'] = mysql_insert_id();
			mpqw($sql = "UPDATE {$conf['db']['prefix']}sess SET uid=".(int)$conf['user']['uid']." WHERE id=".(int)$conf['user']['sess']['id']);
			mpqw($sql = "INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_mem SET uid=".(int)$conf['user']['uid'].", gid=".(int)$grp['id']);
		} header("Location: /");
	}else{
		echo "Авторизация запрещена";
	}
}elseif (isset($_POST['submit'])){
	if (!($auth = $consumer->begin($_POST['idx']))) {
		echo "ERROR: Please enter a valid OpenID.";
	}else{
		header('Location: ' . $auth->redirectURL("http://{$_SERVER['HTTP_HOST']}/", "http://{$_SERVER['HTTP_HOST']}/{$arg['modpath']}:openid"));
	}
}

?>