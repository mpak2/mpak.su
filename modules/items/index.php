<? die;

$conf['tpl']['form'] = array( # Свойства формы добавления
	'title'=>array('name'=>'Имя', 'description'=>'Описание', 'img'=>'Изображение', 'type_id'=>'Тип'),
	'hide'=>array('id', 'uid'), //'uid'
	'type' =>array('type_id'=>'select'),
);

/*if($arg['access'] > 1){
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

$conf['tpl'][$arg['fn']] = mpql(mpqw($sql = "SELECT SQL_CALC_FOUND_ROWS * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} WHERE 1=1". ($_GET['type_id'] ? " AND type_id=". (int)$_GET['type_id'] : '').($_GET['stock'] ? " AND stock=". (int)$_GET['stock'] : '').($_GET['hit'] ? " AND hit=". (int)$_GET['hit'] : '').($_GET['id'] ? " AND id=".(int)$_GET['id'] : " ORDER BY id DESC LIMIT ".($_GET['p']*12).",12")));
//$conf['tpl']['type_id'] = spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_type");
$conf['tpl']['mpager'] = mpager($cnt = mpql(mpqw("SELECT FOUND_ROWS()/12 AS cnt"), 0, 'cnt'));
$conf['tpl']['type'] = mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_type WHERE id=". (int)$_GET['tid']), 0);
if ($_GET['tid']) $conf['settings']['mod_title'] = $conf['tpl']['type']['name'];

//mpre($conf['tpl']['type']);

?>