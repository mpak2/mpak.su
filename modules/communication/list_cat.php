<? die;

foreach($conf['tpl']['relations'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_relations")) as $v){
	if($list = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index AS id INNER JOIN {$conf['db']['prefix']}{$arg['modpath']}_catrel AS cr ON id.catrel_id=cr.id WHERE cr.relations_id=". (int)$v['id']))){
		$conf['tpl']['index'][ $v['id'] ] = $list;
	}
} mpre($conf['tpl']['relations']);

?>