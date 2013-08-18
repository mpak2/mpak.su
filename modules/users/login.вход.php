<? die;

if(($_POST['reg'] == 'Аутентификация') && !empty($conf['settings']['users_reg_redirect']) && $conf['user']['uid'] > 0){
	header("Location: ". $conf['settings']['users_reg_redirect']); exit;
}
