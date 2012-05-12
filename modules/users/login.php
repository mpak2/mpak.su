<? die;

if(!empty($conf['settings']['users_reg_redirect']) && $conf['user']['uid'] > 0){
	header("Location: ". $conf['settings']['users_reg_redirect']); exit;
}

?>