<? die;

$conf['tpl']['form'] = array( # Свойства формы добавления
	'title'=>array('name'=>'Комментарий', 'description'=>'Крылатая фраза', 'img'=>'Изображение'),
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
}

$conf['tpl'][$arg['fn']] = mpql(mpqw("SELECT SQL_CALC_FOUND_ROWS * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}".($_GET['id'] ? " WHERE id=".(int)$_GET['id'] : " ORDER BY id DESC LIMIT ".($_GET['p']*10).",10")));
$conf['tpl']['cnt'] = mpql(mpqw("SELECT FOUND_ROWS()/10 AS cnt"), 0, 'cnt');
$conf['tpl']['comments'] = spisok("SELECT u.num, COUNT(*) AS cnt FROM {$conf['db']['prefix']}comments_url AS u, {$conf['db']['prefix']}comments_txt AS t WHERE u.id=t.uid AND u.url LIKE '%/{$arg['modpath']}/%' GROUP BY u.id");

if($_GET['id']){
	$conf['settings']['title'] = substr(strtr($conf['tpl'][$arg['fn']][0]['description'], array('<br />'=>' ')), 0, 1024);
}
//$conf['tpl']['type_id'] = spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_type");

?>