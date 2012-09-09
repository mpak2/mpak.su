<? die; # Нуль

if ((int)$arg['confnum']){
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST){
		$param = array($_POST['param']=>$_POST['val'])+(array)$param;
		mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	} if(array_key_exists("null", $_GET)) exit;

	$klesh = array(
/*		"Количество символов"=>0,
		"Курс доллара"=>30,
		"Список"=>array(
			1=>"Одын",
			2=>"Два",
		),
		"Город"=>spisok("SELECT id, name FROM {$conf['db']['prefix']}users_sity ORDER BY name"),*/
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

}//$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));
//$uid = $_GET['id'] && array_key_exists('users', $_GET['m']) ? $_GET['id'] : $conf['user']['id'];
if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){
	mpqw($sql = "INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_subscribe SET time=". (int)time(). ", uid=". (int)$conf['user']['uid']. ", name=\"". mpquot($_POST['name']). "\"");// echo $sql;
	exit("Электронный ящик сохранен.");
};

$news = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_post ORDER BY time DESC LIMIT 3"));

?>
<script src="/include/jquery/toggleformtext.js"></script>
<script src="/include/jquery/jquery.iframe-post-form.js"></script>
<script>
	$(function(){
		$("form.news_subscribe_<?=$arg['blocknum']?>").iframePostForm({
			complete:function(data){
				if(isNaN(data)){ alert(data) }else{
					alert("Электронный адрес добавлен в базу рассылки");
				}
			}
		});
	});
</script>
<ul>
	<? foreach($news as $v): ?>
		<li>
			<?=date("d.m.Y", $v['time'])?>
			<h4><a href="/<?=$arg['modname']?>/<?=$v['name']?>"><?=$v['tema']?></a></h4>
		</li>
	<? endforeach ?>
</ul>
<div><a href="/<?=$arg['modname']?>">Смотреть все</a></div>
<h4>Подписаться на новости</h4>
<form class="news_subscribe_<?=$arg['blocknum']?>" method="post" action="/blocks/<?=$arg['blocknum']?>/null">
	<input type="text" name="name" style="width:70%; float:left:" title="Ваш электронный ящик">
	<input type="submit" value="ОК">
</form>
<div>
	<img src="data:image/jpeg;base64,

iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJ
bWFnZVJlYWR5ccllPAAABVxJREFUWMPFlwlMFFcYx53hWFCqYtKkjU3aRtKkadPUNKnSxmqtrSG1
1dajVgXvG7etR41H1WKDmkrRWM+qrdoSBc94EC0eQBGWZTmlHqsUEEFYjmWXnZ29+Pd74zIweYgk
anzhF4Z53/v+v327b4AeAHo8S9SLovh+kcRVAk8ZlhHJCRT+FG6uO6CD1zRZwXdjNXxliY/GvLFb
tPVlGZRVxgnkx4XDQwUeUzRcmcMgX3wD8uV3IKe/BzlzOOSsKMg5YyDnfgU5LxqyaSbkgnmQC2Mh
F+kfiat0FdymaUoGy+IETD+Gw60ED4Tz7Itwpr4M5/nX4Ex7C85L70K+MoRERpDIKMjZYyEbJkE2
TiOR2ZDz55PMIkLfNaa5SgbL4gSMa/pCTo2AdCwU0ok+QGsrffn8eNHq88DnaoDXWgpPXTrc5Yeo
4UI4c6bAmTsDTuMcONnP+fouYRksixMwrO4LxxGRCIAjJYRutfISfhFFxucmXIqQ6+ZWEomB0zCL
RObBmbeIZPSdwjJYFieQvbIPWpJElfbR2ulutEsQXhc8DXkU8B2k7OmQcuZAyl0AyajnYL1ZFieQ
tbw3bIdEFe3o5m647ZBv/ArHP9FwXJ0JR/ZcOAyxcOTqVVhvlsUJZC7rjeY/RJX0de1kxOmQ/1sk
ig9FoSo7EXJzZYfd8HK7IZsPoOXKJLRkTEVL1mySmQ9Hjl6B9WZZnED64ufQtE/slMa9Iqp3iri7
MxjXE0ORs6EPSg6PQ0tt8UPfFql0G+wXJ8B+ZQrsGTNgz5wDe9ZCpR/L4gQufxOGxj1iNwggoRBU
7giDcVM/lKWtoVft5t8Wj0ShC2C78CVsaV/DdikG9vRZSg+WxQmkxYahnl5lt9kVhPo9obiZ2Bsl
SePhddm43XBbzbCe+4z4HM3nx6H570nKWpbFCVyY3wuWbaJK2/BJdfDcN0DK2wjr8Q81NZZtAbBs
1+G/xF4oOTgGrV4P9yF1XNuLpuPD0XQqCtYzY5R1LIsTSJ3bC7VbRJWHDXf1VTQeeV9TW7s1EHd+
DoH57ArutHikBjSkDEVD8hA0Hhuh1LMsTuDsrJ6oSRBUuhzU2Ja5QlPPKIkLQnOViTu2NmMCLAff
hiVpsFLHsjiB09NDUb1JUOnOsKWv0KxhFO8eyT07XHRsa3dHoHbfm0oNy+IETsaEoipeUDm38AHn
6cgYt0ehrjS1U4n65FGaddfXCrCWG7iHWH3KaNRsf0WpYVmcwPHJIbi7XuCoiKOmqwVkfEvfjy6h
4+XSCLjqzdRUp1lz+8QS7klaf3k97iU8r8yzLE7g6EQdKtYJXWJaKqBwfwy3C5aUiZq6f38ZyNU0
XjuJyvgwZZ5lcQLJE3Qo/0FQub9vGFw1BQrsuu1+AUnUFp3RNG/K/0uz9sYqnfKI7jgcNaUoXxuk
zLMsTuDw2GCUrRRU3E0V7UePrjvOFW75SNNcqjNr5u8QzsYqTY1HsqrzLIsTSPoiCHeWCyodG7Dr
jnPXvu/JNe84z3BYyrSfAtqRtjmWxQn8OToIdzcPwO1lgkJuIv3pZa1RMCREqfcZt5ayT3I7p6cK
mnnGuenamlMxD+6zDJbFCRwcFYjKTQNgXiwo5NMRPB39gIJYQb3/uLAMlsUJHPg0kH7lRqJy8+u4
RUfuaVAR/6qSwbI4gd+jAs1ZM0Tc2xGJ8g0DUL1nKCzJ458IrBfryXqzDMri/y/Y/XHAB3s/CTDu
HxmApwnLYFmcAA2BCCb6ERHEQGIQMfgxGeTvFeHvzTKEzgTYEIkQIpx4gehPvPSY9Pf3Cvf3Zhk9
HibQJhHoN9U9IYL9PcW2EE7gWfE/B9bQ36s4hzUAAAAASUVORK5CYII=">
	<a href="/<?=$arg['modname']?>:rss/null/news.rss">RSS лента</a>
</div>
