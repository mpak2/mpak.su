<?

session_start();

require_once 'include/loginza/LoginzaAPI.class.php';
require_once 'include/loginza/LoginzaUserProfile.class.php';

$LoginzaAPI = new LoginzaAPI();

if (!empty($_POST['token'])) { // получаем профиль авторизованного пользователя
	$UserProfile = $LoginzaAPI->getAuthInfo($_POST['token']);
	
	if (!empty($UserProfile->error_type)) { // есть ошибки, выводим их
		mpre($UserProfile->error_type.": ".$UserProfile->error_message);
	} elseif (empty($UserProfile)) { // прочие ошибки
		mpre('Temporary error.');
	} else { // ошибок нет запоминаем пользователя как авторизованного
		$_SESSION['loginza']['is_auth'] = 1;
		// запоминаем профиль пользователя в сессию или создаем локальную учетную запись пользователя в БД
		$_SESSION['loginza']['profile'] = $UserProfile;
	}
} elseif (isset($_GET['quit'])) { // выход пользователя
	unset($_SESSION['loginza']);
	mpfdk("{$conf['db']['prefix']}sess", array("id"=>$conf['user']['sess']['id']), null, array("uid"=>"-{$conf['user']['uid']}"));
}

if (!empty($_SESSION['loginza']['is_auth'])) { // объект генерации недостаюих полей (если требуется)
	$LoginzaProfile = new LoginzaUserProfile($_SESSION['loginza']['profile']);
	
	// пользователь уже прошел авторизацию
	$avatar = '';
	if (!empty($_SESSION['loginza']['profile']->photo)) {
		$avatar = '<img src="'.$_SESSION['loginza']['profile']->photo.'" height="30" align="top"/> ';
	}
	echo "<h3>Приветствуем Вас:</h3>";
	echo $avatar . $LoginzaProfile->genDisplayName().", <a href=\"/{$arg['modname']}:{$arg['fn']}/quit\">Выход (".$LoginzaProfile->genNickname().")</a>";

/*	echo "<p>";// вывод данных полученных через LoginzaUserProfile
	echo "Ник: ".$LoginzaProfile->genNickname()."<br/>";
	echo "Отображать как: ".$LoginzaProfile->genDisplayName()."<br/>";
	echo "Полное имя: ".$LoginzaProfile->genFullName()."<br/>";
	echo "Сайт: ".$LoginzaProfile->genUserSite()."<br/>";
	echo "</p>";*/

	// выводим переданные данные от Loginza API
//	$LoginzaAPI->debugPrint($_SESSION['loginza']['profile']);
	mpre($_SESSION['loginza']['profile']);
	if($_POST){
		$type_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_type",
			$w = array("name"=>$_SESSION['loginza']['profile']->provider), $w
		);
		$grp_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_grp", array("name"=>$conf['settings']['user_grp']));
		$uid = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}",
			$w = array("name"=>($_SESSION['loginza']['profile']->nickname ?: $_SESSION['loginza']['profile']->email), "type_id"=>$type_id),
			$w += array("reg_time"=>time(), "last_time"=>time(), "pass"=>"loginza", "email"=>$_SESSION['loginza']['profile']->email)
		); mpfdk("{$conf['db']['prefix']}sess", array("id"=>$conf['user']['sess']['id']), null, array("uid"=>$uid));
		$mem_id = array("{$conf['db']['prefix']}{$arg['modpath']}_mem", $w = array("uid"=>$uid, "grp_id"=>$grp_id), $w);
		if($conf['settings']['users_reg_redirect']){ # Перенаправление на страницу с настроек
	//		header("Location: {$conf['settings']['users_reg_redirect']}");
		}else{
	//		header("Location: /");
		} header("Location: /{$arg['modname']}:{$arg['fn']}");
	}
} else {
	// требуетс авторизация, вывод ссылки на Loginza виджет
	echo "<h3>Блок авторизации:</h3>";
}

?>
