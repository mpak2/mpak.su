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
		),*/
		"Форма"=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_index ORDER BY name"),
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

$form = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE id=". (int)$param["Форма"]), 0);
if($tn = $form['tn']){
	if($fields = mpqn(mpqw("SHOW FIELDS FROM `{$tn}`"), 'Field')){
		$data = mpqn(mpqw("SELECT * FROM `{$tn}` WHERE 1". ($fields['hide'] ? " AND hide=0" : ""). " ORDER BY id DESC LIMIT 10"));
	}
}

if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){
	if(array_key_exists("hide", $data[ $_POST['hide'] ])){
		if($arg['access'] > 3){
			mpqw($sql = "UPDATE `{$tn}` SET hide=1 WHERE id=". (int)$_POST['hide']);
		}else if($data[ $_POST['hide'] ]['uid'] == $conf['user']['uid']){
			mpqw($sql = "UPDATE `{$tn}` SET hide=1 WHERE uid=". (int)$conf['user']['uid']. " AND id=". (int)$_POST['hide']);
		}else{
			exit("Недостаточно прав доступа");
		} exit($_POST['hide']);
	}else{
		exit("Ошибка данных");
	}
};

?>
<? if($data): ?>
	<div style="font-weight:bold"><?=$form['name']?></div>
	<script>
		$(function(){
			$(".alert_<?=$arg['blocknum']?> a.del").click(function(){
				form_id = $(this).parents("[form_id]").attr("form_id"); console.log("form_id:"+form_id);
				$.post("/blocks/<?=$arg['blocknum']?>/null", {hide:form_id}, function(data){
					if(isNaN(data)){ alert(data); }else{
						$(".alert_<?=$arg['blocknum']?> li[form_id="+form_id+"]").remove();
					}
				});
			});
		});
	</script>
	<ul class="alert_<?=$arg['blocknum']?>">
		<? foreach($data as $v): ?>
			<li form_id="<?=$v['id']?>" style="overflow:hidden;">
				<span style="float:right;">
					<?=date("Y.m.d H:i:s", $v['time'])?>
					<? if(array_key_exists('hide', $v)): ?>
						<a class="del" href="javascript:"><img src="/img/del.png"></a>
					<? endif; ?>
				</span>
				<span title="<?=$v['description']?>"><?=$v['name']?></span>
			</li>
		<? endforeach; ?>
	</ul>
<? endif; ?>