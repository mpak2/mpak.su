<? die;

		if(($index_id = mysql_insert_id()) && !empty($_FILES[$f = 'img'])){
			foreach($_FILES['img']['name'] as $k=>$v){
				$img_id = mpfdk($tn = "{$conf['db']['prefix']}{$arg['modpath']}_txt_{$f}",
					null, array("time"=>time(), "index_id"=>$index_id, "name"=>$v, 'uid'=>$conf['user']['uid'])
				);
				if($fn = mpfn($tn, $k, $img_id, $f)){
					mpqw("UPDATE {$tn} SET {$f}=\"". mpquot($fn). "\" WHERE id=". (int)$img_id);
				}else{
					mpqw("DELETE FROM {$tn} WHERE id=". (int)$img_id);
				} 
			} exit("Файлы загружены.");
		}

?>