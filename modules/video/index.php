<? die;

$conf['tpl']['video'] = mpqn(mpqw("SELECT * FROM ". ($tn = "{$conf['db']['prefix']}{$arg['modpath']}_files"). mpwr($tn). " ORDER BY id DESC"));

?>