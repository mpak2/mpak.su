<? die;

$tpl['works'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_projects_works"));

foreach($tpl['works'] as $v){
	$period_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_period", $w = array("year"=>date("Y", $v['time']), "month"=>date("m", $v['time']), "week"=>date("W", $v['time'])), $w);
	$projects_works_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_projects_works", array("id"=>$v['id']), null, array("period_id"=>$period_id));
}
