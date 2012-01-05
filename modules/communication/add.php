<? die;

if($_POST['submit'] && $mpdbf = mpdbf($tn = "{$conf['db']['prefix']}{$arg['modpath']}_index", array('uid'=>$conf['user']['uid'])+$_POST)){
	if(mpqw("INSERT INTO $tn SET $mpdbf") &&
			($id = mysql_insert_id()) &&
			$fn = mpuf('img', $tn, 'img', $id, array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp'))
	){
		mpqw("UPDATE $tn SET img='". mpquot($fn). "' WHERE id=". (int)$id);
	}
}

$conf['tpl']['region'] = spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_region");

$conf['tpl']['relations'] = mpql(mpqw("SELECT c.id, CONCAT(r.name, ' / ', cat.name) AS name FROM {$conf['db']['prefix']}{$arg['modpath']}_relations AS r INNER JOIN {$conf['db']['prefix']}{$arg['modpath']}_catrel AS c ON r.id=c.relations_id INNER JOIN {$conf['db']['prefix']}{$arg['modpath']}_cat AS cat ON c.cat_id=cat.id"));

//mpre($conf['tpl']['relations']);

?>