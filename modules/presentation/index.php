<?

$conf['tpl']['img'] = mpqn(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}_img ORDER BY sort"), 'index_id', 'id');

$conf['tpl']['index'] = mpqn(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}"));

$cur_id = $_GET['img_id'] ?: array_shift(array_keys($conf['tpl']['img'][ $_GET['id'] ] ?: array(0)));

$conf['tpl']['cur'] = (array)$conf['tpl']['img'][ $_GET['id'] ][ $cur_id ];

$conf['tpl']['prev_id'] = (($a = array_search($cur_id-1, $ar = array_keys($conf['tpl']['img'][ $_GET['id'] ] ?: array(0)))) !== false ? $ar[$a] : 0);

$conf['tpl']['next_id'] = (($a = array_search($cur_id+1, $ar = array_keys($conf['tpl']['img'][ $_GET['id'] ] ?: array(0)))) !== false ? $ar[$a] : 0);

?>
