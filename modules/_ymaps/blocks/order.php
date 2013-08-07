<? die; # Заказы

if ((int)$arg['confnum']){
/*	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

echo <<<EOF
	<form method="post">
		<input type="text" name="param" value="$param"> <input type="submit" value="Сохранить">
	</form>
EOF;*/
	return;
} //$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));

$tn = "{$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}";
if($_POST[$arg['fn']]['del']){
	mpqw("DELETE FROM $tn WHERE (uid = ". (int)$conf['user']['uid']. " OR src=". (int)$conf['user']['uid']. ") AND id=". (int)$_POST[$arg['fn']]['del']);
}elseif($pt = $_POST[$arg['fn']]){
	$user = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}users WHERE id=". (int)($_GET['id'] ?: $conf['user']['uid'])), 0);
	if($mail = $user['email']){
		mpmail($mail, 'Заполнена форма предварительной заявки на сайте http://КартаТакси.РФ', "Для просмотра формы регистрации пройдите по ссылке http://картатакси.рф/users/{$user['id']} предварительно авторизовавшись на сайте.");
	}
	if($mpdbf = mpdbf($tn, array('time'=>time(), 'src'=>$conf['user']['uid'], 'uid'=>(int)$user['id'])+$pt)){
		mpqw("INSERT INTO $tn SET $mpdbf");
	}
	header("Location: ". $_SERVER['REQUEST_URI']);
} $conf['tpl'][ $arg['fn'] ] = mpql(mpqw("SELECT * FROM $tn WHERE uid=". (int)$conf['user']['uid']));

?>
<script>
	$(document).ready(function(){
		$('.del_<?=$arg['modpath']?>').click(function(){
			if(confirm("Вы уверенны?")){
				var id = $(this).attr('id');
				$.post("<?=$_SERVER['REQUEST_URI']?>", {"<?=$arg['modpath']?>[del]":id}, function(data){});
				$('#<?=$arg['modpath']?>_'+id).hide('slow');
			} return false;
		});
	});
</script>
<div>
	<? foreach($conf['tpl'][ $arg['fn'] ] as $k=>$v): ?>
		<div id="<?=$arg['modpath']?>_<?=$v['id']?>">
			<div style="float:right;"><?=date('Y.m.d H:i:s', $v['time'])?></div>
			<span><img id="<?=$v['id']?>" class="del_<?=$arg['modpath']?>" src="/img/del.png"></span>
			<span style="margin:3px; font-weight:bold;"><?=$v['name']?></span>
			<div style="margin:3px; font-style:italic;"><?=$v['text']?></div>
		</div>
	<? endforeach; ?>
</div>
<form method="post">
	<div><input type="text" name="<?=$arg['modpath']?>[name]" style="width:98%;" title="Заголовок"></div>
	<div><textarea name="<?=$arg['modpath']?>[text]" style="width:98%;" title="Описание услуги"></textarea></div>
	<div style="float:right;"><input type="submit"></div>
</form>