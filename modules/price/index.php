<? die;

if($_POST['submit']){
	$ar = array('time'=>time(), 'uid'=>$conf['user']['uid'], 'description'=>$_POST['description']);
	if($mpdbf = mpdbf($tn = "{$conf['db']['prefix']}{$arg['modpath']}_order", $ar)){
		mpqw($sql = "INSERT INTO $tn SET $mpdbf");
		if($id = (int)mysql_insert_id()){
			foreach($_POST['check'] as $n=>$v){
				mpqw($sql = "INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_zakaz SET order_id=". (int)$id. ", index_id=".(int)$n. ", count=". (int)$_POST['count'][$n]);
			}
		}
	}
}

/*$conf['tpl']['form'] = array( # Свойства формы добавления
	'title'=>array('name'=>'Имя', 'description'=>'Описание', 'img'=>'Изображение', 'type_id'=>'Тип'),
	'hide'=>array('id', 'uid'), //'uid'
	'type' =>array('type_id'=>'select'),
);

if($arg['access'] > 1){
	$tn = "{$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}";
	if($_POST['submit']){
		if($arg['access'] == 2) unset($_GET['id']);
		if($mpdbf = mpdbf($tn, array_merge($_POST, array('uid'=>$conf['user']['uid'])))){
			mpqw($sql = "INSERT INTO $tn SET $mpdbf". ($arg['access'] > 2 ? " ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), $mpdbf" : ''));
			if(($id = (int)mysql_insert_id()) && $fn = mpfn($tn, 'img', $id)){
				mpqw($sql = "UPDATE $tn SET img=\"$fn\" WHERE id=".(int)$id);
				foreach (glob(mpopendir('cache'). "/{$_SERVER['SERVER_NAME']}/*_{$tn}_img_{$id}*") as $fn) unlink($fn);
			}
		}
		header("Location: /{$arg['modpath']}". ($arg['fn'] == 'index' ? '' : ":{$arg['fn']}"));
	}elseif($arg['access'] > 2){
		if($_GET['del']){
			mpqw($sql = "DELETE FROM $tn WHERE id=".(int)$_GET['del']);
			header("Location: /{$arg['modpath']}". ($arg['fn'] == 'index' ? '' : ":{$arg['fn']}"));
		}
		if($_GET['edit']) $conf['tpl']['form']['edit'] = mpql(mpqw($sql = "SELECT * FROM $tn WHERE id=".(int)$_GET['edit']), 0);
	}
}*/

$conf['tpl']['type'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_type"));
$conf['tpl']['index'] = mpql(mpqw("SELECT SQL_CALC_FOUND_ROWS * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} WHERE hide=0"));

//$conf['tpl']['type_id'] = spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_type");
//$conf['tpl']['cnt'] = mpql(mpqw("SELECT FOUND_ROWS()/10 AS cnt"), 0, 'cnt');
//$conf['tpl']['comments'] = spisok("SELECT u.num, COUNT(*) AS cnt FROM {$conf['db']['prefix']}comments_url AS u, {$conf['db']['prefix']}comments_txt AS t WHERE u.id=t.uid AND u.url LIKE '%/{$arg['modpath']}/%' GROUP BY u.id");

?>