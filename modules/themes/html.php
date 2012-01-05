<? die;

unset($_GET['null']);
echo file_get_contents(mpopendir("themes/{$conf['settings']['theme']}/".strtr($_GET[''], array('..'=>''))));

?>