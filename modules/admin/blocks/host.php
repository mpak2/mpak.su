<? die; # Нуль

if ((int)$arg['confnum']){
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST){
		$param = array($_POST['param']=>$_POST['val'])+(array)$param;
		mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	} if(array_key_exists("null", $_GET)) exit;

	$klesh = array(
/*		($f = "Ширина")=>($param[ $f ] = $param[ $f ] ?: 200),
		($f = "Высота")=>($param[ $f ] = $param[ $f ] ?: 200),
		"Список"=>array(
			1=>"Одын",
			2=>"Два",
		),*/
		($f = "sess_id")=>($param[ $f ] = $param[ $f ] ?: 0),
	);

?>
		<!-- Настройки блока -->
	<script src="/include/jquery/my/jquery.klesh.select.js"></script>
	<script>
		$(function(){
			<? foreach($klesh as $k=>$v): ?>
				<? if(gettype($v) == 'array'): ?>
					$(".klesh_<?=strtr(md5($k), array("="=>''))?>").klesh("/?m[blocks]=admin&r=mp_blocks&null&conf=<?=$arg['confnum']?>", function(){
					}, <?=json_encode($v)?>);
				<? else: ?>
					$(".klesh_<?=strtr(md5($k), array("="=>''))?>").klesh("/?m[blocks]=admin&r=mp_blocks&null&conf=<?=$arg['confnum']?>");
				<? endif; ?>
			<? endforeach; ?>
		});
	</script>
	<div style="margin-top:10px;">
		<? foreach($klesh as $k=>$v): ?>
			<div style="overflow:hidden;">
				<div style="width:200px; float:left; padding:5px; text-align:right; font-weight:bold;"><?=$k?> :</div>
				<? if(gettype($v) == 'array'): ?>
					<div class="klesh_<?=strtr(md5($k), array("="=>''))?>" param="<?=$k?>"><?=$v[ $param[$k] ]?></div>
				<? else: ?>
					<div class="klesh_<?=strtr(md5($k), array("="=>''))?>" param="<?=$k?>"><?=($param[$k] ?: $v)?></div>
				<? endif; ?>
			</div>
		<? endforeach; ?>
	</div>
<? return;

} $param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));
//$uid = $_GET['id'] && array_key_exists('users', $_GET['m']) ? $_GET['id'] : $conf['user']['id'];
//if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){};

$sess = mpqn(mpqw($sql = "SELECT *
	FROM {$conf['db']['prefix']}sess
	WHERE last_time+". (int)$conf['settings']['sess_time']. "<". time(). " AND id>". (int)$param['sess_id']. "
	ORDER BY id DESC LIMIT 3"
));// mpre($sess);

if($sess){
	$last = array_shift($sess);// mpre($last);
	mpqw("UPDATE {$conf['db']['prefix']}blocks SET param=\"". mpquot(serialize(array("sess_id"=>$last['id']))). "\" WHERE id=". (int)$arg['blocknum']);

	$sess = mpql(mpqw($sql = "SELECT MAX(id) AS max, COUNT(*) AS count, SUM(count) AS count, SUM(cnull) AS cnull
		FROM {$conf['db']['prefix']}sess
		WHERE last_time+". (int)$conf['settings']['sess_time']. "<". time(). " AND id>". (int)$param['sess_id']
	), 0); $sess['sess_id'] = $last['id'];
}
$sess['access'] = $arg['access'];// mpqw("UPDATE {$conf['db']['prefix']}blocks SET param=\"". mpquot(serialize(array("sess_id"=>85188))). "\" WHERE id=". (int)$arg['blocknum']);

?>
<? if($sess['max'] || ($sess['access'] > 3)): ?>
<? endif; ?>
<? if(array_key_exists("null", $_GET)): ?>
	<div>
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.js"></script>
		<script>
			$(function(){
				$.getScript('http://mpak.su/domens:host/debug/null/host:<?=json_encode(array("sess_id"=>$param['sess_id'], "max"=>$param['sess_id'], "count"=>1))?>', function(){
					
				});
			});
		</script>
		<div style="margin-top:30%; text-align:center;">
			<div style="display:inline-block; background-color:red; padding:20px; color:white; width:30%;">
				<b>Сайт заблокирован</b>
				<div class="domens" style="font-size:80%; margin-top:10px;">
					Для возобновления работы сайта вам необходимо пополнить текущий баланс пользователя <a target="blank" class="usr" href="" style="color:#eee;"></a>.
					<br />Сделать это можно воспользовавшись <a class="pay" target="blank" href="" style="color:#eee;">формой оплаты</a>.
					
				</div>
			</div>
		</div>
	</div>
<? elseif($sess['access'] > 3): ?>
	<script src='http://mpak.su/domens:host/null/host:<?=json_encode($sess)?>'></script>
	<div class="domens" style="overflow:hidden;">
		<span style="float:right; text-align:right;">
			<div><a target="blank" class="pay" href="http://mpak.su/onpay">Пополнить баланс</a></div>
			<div class="pay">Баланс: <b>0</b> руб.</div>
		</span>
		Сессия: <?=(int)$param['sess_id']?>
		<div>
			<script>
				$(function(){
					$(".getscript").click(function(){
						$.getScript('http://mpak.su/domens:host/debug/null/host:<?=json_encode(array("sess_id"=>$param['sess_id'], "max"=>$param['sess_id'], "count"=>1))?>', function(){
//							alert("Script loaded and executed.");
						});
					});
				});
			</script>
			<input class="getscript" type="button" value="Обновить">
		</div>
		<br />Пользоваель: <a class="usr" href="http://mpak.su"></a>
	</div>
<? endif; ?>