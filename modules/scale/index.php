<? die;

if ($_POST['id'] && !empty($_POST['mess']) && !empty($_POST['name'])){
	mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_mess SET qwid=".(int)$_POST['id']. ", time=".time().", name='".mpquot($_POST['name'])."', yes=".(int)$_POST['yes']. ", description='". mpquot($_POST['mess'])."'");
}

$conf['tpl']['scale'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_qw".($_GET['id'] ? " WHERE id=".(int)$_GET['id'] : ' WHERE hide=0')));

if ($_GET['id']){
	$conf['tpl']['scale'] = $conf['tpl']['scale'][0];
	$conf['tpl']['yes'] = mpql(mpqw("SELECT SQL_CALC_FOUND_ROWS qw.id, mess.* FROM {$conf['db']['prefix']}{$arg['modpath']}_qw as qw, {$conf['db']['prefix']}{$arg['modpath']}_mess as mess WHERE qw.id=mess.qwid AND mess.yes=1".($conf['tpl']['scale'] ? " AND qw.id={$conf['tpl']['scale']['id']}" : '')." ORDER BY mess.id DESC LIMIT 10"));
	$conf['tpl']['y'] = mpql(mpqw("SELECT FOUND_ROWS() AS count"), 0, 'count');

	$conf['tpl']['no'] = mpql(mpqw("SELECT SQL_CALC_FOUND_ROWS qw.id, mess.* FROM {$conf['db']['prefix']}{$arg['modpath']}_qw as qw, {$conf['db']['prefix']}{$arg['modpath']}_mess as mess WHERE qw.id=mess.qwid AND mess.yes=0".($conf['tpl']['scale'] ? " AND qw.id={$conf['tpl']['scale']['id']}" : '')." ORDER BY mess.id DESC LIMIT 10"));
	$conf['tpl']['n'] = mpql(mpqw("SELECT FOUND_ROWS() AS count"), 0, 'count');
}else{
	$conf['tpl']['cy'] = spisok("SELECT qw.id, COUNT(*) FROM {$conf['db']['prefix']}{$arg['modpath']}_qw as qw, {$conf['db']['prefix']}{$arg['modpath']}_mess as mess WHERE qw.id=mess.qwid AND mess.yes=1 GROUP BY qw.id");

	$conf['tpl']['cn'] = spisok("SELECT qw.id, COUNT(*) FROM {$conf['db']['prefix']}{$arg['modpath']}_qw as qw, {$conf['db']['prefix']}{$arg['modpath']}_mess as mess WHERE qw.id=mess.qwid AND mess.yes=0 GROUP BY qw.id");
}

?>