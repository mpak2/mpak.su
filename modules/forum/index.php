<? die;

$conf['tpl']['vetka'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_vetki WHERE id=".(int)$_GET['vetki_id']), 0);// mpre($conf['tpl']['vetka']);
//$conf['settings']['title'] = $conf['tpl']['vetka']['name'];

$conf['tpl']['parent'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_vetki WHERE id=". (int)$conf['tpl']['vetka']['vetki_id']), 0);// mpre($conf['tpl']['parent']);

$conf['settings']['title'] = $conf['tpl']['vetka']['name']. " : ". ($conf['tpl']['parent']['name'] ?: "Форум");

foreach(mpql(mpqw("SELECT id, vetki_id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_vetki WHERE aid>0")) as $k=>$v){
	$id[ $v['id'] ] = $v;
	$pid[ $v['vetki_id'] ][] = $v['id'];
}

$vetka['vetki_id'] = (int)$_GET['vetki_id'];
while($vetka = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_vetki WHERE id=". (int)$vetka['vetki_id']), 0)){
	$conf['tpl']['forums'][] = $vetka;
}// mpre($conf['tpl']['forums']);
if(gettype($conf['tpl']['forums']) == "array"){
	asort($conf['tpl']['forums']);
}

if ($_POST['text'] && $conf['tpl']['vetka']['aid'] > 1){ # Добавление новых сообщений пользователем
	if ((int)$_POST['id']){
		$mess = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_mess WHERE id=".(int)$_POST['id']), 0);
		if ($conf['user']['uname'] != $conf['settings']['default_usr'] && $mess['uid'] == $conf['user']['uid'] || $conf['user']['uname'] == $conf['settings']['admin_usr']){
			mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']}_mess SET text='".mpquot($_POST['text'])."' WHERE id=".(int)$_POST['id']);
		}else{
			echo "<div align=center>Недостаточно прав для редактирования сообщения</div>";
		}
	}else{
		mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_mess (time, uid, vetki_id, text) VALUE (".time().", {$conf['user']['uid']}, ".(int)$_GET['vetki_id'].", '".mpquot($_POST['text'])."')");
	}
}elseif((int)$_GET['edit']){ # Редактирование сообщений
	$conf['tpl']['edit'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_mess WHERE id=".(int)$_GET['edit']), 0);
}elseif((int)$_GET['del']){ # Удаление сообщений
	$mess = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_mess WHERE id=".(int)$_GET['del']), 0);
	if (($mess['uid'] == $conf['user']['uid']) || ($arg['access'] >= 4)){
		mpqw("DELETE FROM {$conf['db']['prefix']}{$arg['modpath']}_mess WHERE id=".(int)$_GET['del']);
	}else{
		echo "<div align=center>Недостаточно прав для удаления сообщения</div>";
	}
}

//$conf['tpl']['count'] = spisok("SELECT v.id as id, CONCAT('[', COUNT(*), ']') as count FROM {$conf['db']['prefix']}{$arg['modpath']}_vetki as v, {$conf['db']['prefix']}{$arg['modpath']}_mess as m WHERE v.vetki_id=".(int)$_GET['vetki_id']." AND aid>0 AND v.id=m.vetki_id GROUP BY v.id");

$conf['tpl']['vetki'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_vetki WHERE aid>0 AND vetki_id=".(int)$_GET['vetki_id']));// mpre($conf['tpl']['vetki']);

//$conf['tpl']['mess'] = mpql(mpqw("SELECT c.count as count, u.name as uname, u.reg_time as reg_time, m.* FROM {$conf['db']['prefix']}{$arg['modpath']}_cmess as c, {$conf['db']['prefix']}{$arg['modpath']}_mess as m, {$conf['db']['prefix']}users as u WHERE vetki_id=".(int)$_GET['vetki_id']." AND m.uid=u.id AND c.uid=u.id ORDER BY time"));

$conf['tpl']['mess'] = mpql(mpqw("SELECT m.*, u.name AS uname, u.reg_time, c.count FROM {$conf['db']['prefix']}{$arg['modpath']}_mess as m LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_cmess as c ON c.uid=m.uid LEFT JOIN {$conf['db']['prefix']}users as u ON m.uid=u.id WHERE m.vetki_id=".(int)$_GET['vetki_id']));

//mpre($conf['tpl']['mess']);

if(!$_GET['vetki_id']){
	$conf['tpl']['last'] = mpql(mpqw("SELECT m.*, u.name AS uname FROM {$conf['db']['prefix']}{$arg['modpath']}_mess AS m LEFT JOIN {$conf['db']['prefix']}users AS u ON m.uid=u.id ORDER BY m.id DESC LIMIT 10"));
}

foreach($conf['tpl']['vetki'] as $k=>$v){
	$sum += $v['count'];
}// echo $sum;
if($conf['tpl']['vetka']['count'] != ($sum += count($conf['tpl']['mess']))){
	mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']}_vetki SET count=". (int)$sum. " WHERE id=". (int)$conf['tpl']['vetka']['id']);// echo $sum;
}// echo $sum;

?>