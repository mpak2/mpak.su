<? die;

if(!empty($_GET['tm']) && $arg['access'] > 4){
	mpqw("UPDATE {$conf['db']['prefix']}settings SET value=\"".mpquot($_GET['tm'])."\" WHERE name=\"theme\" LIMIT 1");
	header("Location: /");
}

//mpre(mpreaddir('themes'));
$conf['tpl']['themes'] = mpreaddir('themes', 1);
/*foreach($conf['tpl']['themes'] as $k=>$v){
	if(!file_exists(mpopendir("themes/$v/screen.png"))){
		unset($conf['tpl']['themes'][$k]);
	}
}*/

?>