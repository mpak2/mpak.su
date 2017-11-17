<?
	if(tables($table="{$conf['db']['prefix']}data_index")){
		
		if(!ql("SHOW COLUMNS FROM `mp_data_index` WHERE `Field` = 'md5'"))
			qw("ALTER TABLE `mp_data_index` ADD `md5` VARCHAR(255) NOT NULL ;");
		
		if(!ql("SHOW COLUMNS FROM `mp_data_index` WHERE `Field` = 'cat_id_item'"))
			qw("ALTER TABLE `mp_data_index` ADD `cat_id_item` VARCHAR(255) NOT NULL ;");
		
	}
?>
