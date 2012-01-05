<? die;

$conf['tpl']['order'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_order WHERE uid=".(int)$conf['user']['uid']. " ORDER BY id DESC LIMIT 5"));
$conf['tpl']['zakaz'] = mpql(mpqw("SELECT i.*, z.order_id, z.count FROM {$conf['db']['prefix']}{$arg['modpath']}_zakaz AS z, {$conf['db']['prefix']}{$arg['modpath']}_index AS i WHERE i.id=z.index_id"));

//mpre($conf['tpl']['zakaz']);

?>