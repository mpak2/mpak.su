<? die;

$conf['tpl'][ $arg['fn'] ] = mpqn(mpqw("SELECT * FROM ". ($tn = "{$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}"). mpwr($tn)));

?>