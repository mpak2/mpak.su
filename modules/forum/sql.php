<? die;

mpqw("CREATE SQL SECURITY DEFINER VIEW `{$conf['db']['prefix']}{$arg['modpath']}_cmess` AS select `{$conf['db']['prefix']}{$arg['modpath']}_mess`.`uid` AS `uid`,count(0) AS `count` from `{$conf['db']['prefix']}{$arg['modpath']}_mess` group by `{$conf['db']['prefix']}{$arg['modpath']}_mess`.`uid`");

?>