<? die;

$conf['tpl'][ $arg['fn'] ] = mpqn(mpqw("SELECT * FROM ". ($tn = "{$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}"). mpwr($tn). " ORDER BY id DESC"));

//$conf['tpl'][$f = "cat"] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$f}"));// mpre($conf['tpl'][ $f ]);

?>