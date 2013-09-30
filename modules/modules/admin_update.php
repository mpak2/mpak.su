<? die;

//$conf['tpl']['modules'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}"));

	$tpl['reg'] = qn("SELECT * FROM {$conf['db']['prefix']}blocks_reg");
	mpqw("DELETE FROM mp_blocks_reg_modules");
	foreach($tpl['reg'] as $reg){
		if($reg['mid']){
			mpqw($sql = "INSERT INTO {$conf['db']['prefix']}blocks_reg_modules SET reg_id=". (int)$reg['id']. ", modules_index=". (int)$reg['mid']. ", name='". ($reg['fn'] ?: ""). "'");
			mpqw("UPDATE mp_blocks_reg SET term=1 WHERE id=". (int)$reg['id']);
		}else{
			mpqw("UPDATE mp_blocks_reg SET term=0 WHERE id=". (int)$reg['id']);
		}
	}
	if(empty($tpl['reg'][-1])){
		mpqw("INSERT INTO mp_blocks_reg_modules SET reg_id=-1, theme='zhiraf'");
	} if(empty($tpl['reg'][-2])){
		mpqw("INSERT INTO mp_blocks_reg_modules SET reg_id=-2, theme='zhiraf'");
	}
	mpqw("UPDATE mp_blocks SET rid=-1 WHERE file='admin/blocks/modlist.php'");
	mpqw("UPDATE mp_blocks SET rid=-1 WHERE file='admin/blocks/search.php'");
	mpre($tpl['reg']);


?>