<?

	$tpl['basket'] = qn("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_basket WHERE basket_order_id=0 AND uid=". (int)$conf['user']['uid']);
	$tpl['items'] = qn("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_items WHERE id IN (". in(rb($tpl['basket'], "items_id")). ")");

	if(array_key_exists("order", $_GET) && $tpl['basket']){
		if($order_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_basket_order", null, $_POST)){
			mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']}_basket SET basket_order_id=". (int)$order_id. " WHERE id IN (". in($tpl['basket']). ")");
		}
	}
