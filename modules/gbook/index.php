<? die;

if($_POST['gbook']){
	if ($_COOKIE['captcha_keystring'] == md5($_POST['gbook']['kod'])){
		if($mpdbf = mpdbf($tn = "{$conf['db']['prefix']}{$arg['modpath']}", array('time'=>time(), 'hide'=>$conf['settings']["{$arg['modpath']}_vid_mess"], 'uid'=>$conf['user']['uid'])+$_POST['gbook'])){
			mpqw($sql = "INSERT INTO $tn SET $mpdbf");
			exit();
		}
	}else{
		exit("Код не верен");
	}
}

$conf['tpl']['md5'] = md5(rand(0,1000000).':'.microtime());

$conf['tpl']['mess'] = mpql(mpqw("SELECT g.* FROM {$conf['db']['prefix']}{$arg['modpath']} AS g LEFT JOIN {$conf['db']['prefix']}users as u ON g.uid=u.id WHERE g.hide=0 ORDER BY g.id DESC"));

?>