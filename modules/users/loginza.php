<? die;

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
}

if (!empty($_SESSION['loginza']['is_auth'])) { // объект генерации недостаюих полей (если требуется)
	$LoginzaProfile = new LoginzaUserProfile($_SESSION['loginza']['profile']);
	
	// пользователь уже прошел авторизацию
	$avatar = '';
	if (!empty($_SESSION['loginza']['profile']->photo)) {
		$avatar = '<img src="'.$_SESSION['loginza']['profile']->photo.'" height="30" align="top"/> ';
	}
	echo "<h3>Приветствуем Вас:</h3>";
	echo $avatar . $LoginzaProfile->genDisplayName().', <a href="?quit">Выход ('.$LoginzaProfile->genNickname().')</a>';

	echo "<p>";// вывод данных полученных через LoginzaUserProfile
	echo "Ник: ".$LoginzaProfile->genNickname()."<br/>";
	echo "Отображать как: ".$LoginzaProfile->genDisplayName()."<br/>";
	echo "Полное имя: ".$LoginzaProfile->genFullName()."<br/>";
	echo "Сайт: ".$LoginzaProfile->genUserSite()."<br/>";
	echo "</p>";
	
	// выводим переданные данные от Loginza API
//	$LoginzaAPI->debugPrint($_SESSION['loginza']['profile']);
	mpre($_SESSION['loginza']['profile']);
} else {
	// требуетс авторизация, вывод ссылки на Loginza виджет
	echo "<h3>Блок авторизации:</h3>";
	echo '<a href="'.$LoginzaAPI->getWidgetUrl().'" class="loginza">Для авторизации нажмите ссылку</a>';
}

?>