<? die;

mpqw("CREATE VIEW `mp_abysstat_igramid` AS select `mp_abysstat_igra`.`pid` AS `pid`,max(`mp_abysstat_igra`.`id`) AS `mid` from `mp_abysstat_igra` group by `mp_abysstat_igra`.`pid`");

?>