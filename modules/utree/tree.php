<? die;

$tpl['index'] = qn("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index");

$tpl['uid'] = qn("SELECT * FROM {$conf['db']['prefix']}users WHERE id IN (". in(rb($tpl['index'], "uid")). ")");
