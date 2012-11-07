<? die; # Нуль

if ((int)$arg['confnum']){
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST){
		$param = array($_POST['param']=>$_POST['val'])+(array)$param;
		mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	} if(array_key_exists("null", $_GET)) exit;

	$klesh = array(
/*		"Количество анкет"=>1,
		"Курс доллара"=>30,
		"Список"=>array(
			1=>"Одын",
			2=>"Два",
		),
		"Город"=>spisok("SELECT id, name FROM {$conf['db']['prefix']}users_sity ORDER BY name"),*/
	);

?>
	<script src="/include/jquery/my/jquery.klesh.select.js"></script>
	<script>
		$(function(){
			<? foreach($klesh as $k=>$v): ?>
				<? if(gettype($v) == 'array'): ?>
					$(".klesh_<?=strtr(base64_encode($k), array("="=>''))?>").klesh("/?m[blocks]=admin&r=mp_blocks&null&conf=<?=$arg['confnum']?>", function(){
					}, <?=json_encode($v)?>);
				<? else: ?>
					$(".klesh_<?=strtr(base64_encode($k), array("="=>''))?>").klesh("/?m[blocks]=admin&r=mp_blocks&null&conf=<?=$arg['confnum']?>");
				<? endif; ?>
			<? endforeach; ?>
		});
	</script>
	<div style="margin-top:10px;">
		<? foreach($klesh as $k=>$v): ?>
			<div style="overflow:hidden;">
				<div style="width:200px; float:left; padding:5px; text-align:right; font-weight:bold;"><?=$k?> :</div>
				<? if(gettype($v) == 'array'): ?>
					<div class="klesh_<?=strtr(base64_encode($k), array("="=>''))?>" param="<?=$k?>"><?=$v[ $param[$k] ]?></div>
				<? else: ?>
					<div class="klesh_<?=strtr(base64_encode($k), array("="=>''))?>" param="<?=$k?>"><?=($param[$k] ?: $v)?></div>
				<? endif; ?>
			</div>
		<? endforeach; ?>
	</div>
<? return;

}//$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));
//$uid = $_GET['id'] && array_key_exists('users', $_GET['m']) ? $_GET['id'] : $conf['user']['id'];

$fields = mpqn(mpqw("SHOW FIELDS FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}"), 'Field');

if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum'])){
	if($fields[ $_POST['fn'] ]){
		echo $id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}",
			array("id"=>$_POST['anket_id'], "uid"=>$conf['user']['uid']),
			array("uid"=>$conf['user']['uid'], "time"=>time(), $_POST['fn']=>$_POST['val']),
			array($_POST['fn']=>$_POST['val'])
		); exit;
	}elseif(array_key_exists("del", $_GET) && $_POST['anket_id']){
		mpqw("DELETE FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} WHERE id=". (int)$_POST['anket_id']. " AND uid=". (int)$conf['user']['uid']);
	}elseif(array_key_exists("add", $_GET)){
		mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} SET time=". time(). ", uid=". (int)$conf['user']['uid']);
/*		mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}", null,
			array("uid"=>$conf['user']['uid'], "time"=>time())
		);*/
	}
};

$anket = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} WHERE uid=". (int)$conf['user']['uid']. " ORDER BY id DESC"));

foreach($fields as $k=>$v){
	if(substr($k, -3, 3) == "_id"){
		$users[$k] = spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_". substr($k, 0, strlen($k)-3));
	}
}

?>
<? if(!array_key_exists("null", $_GET)): ?>
	<script>
		$(function(){
			$(".add_<?=$arg['blocknum']?>").click(function(){
				$.post("/blocks/<?=$arg['blocknum']?>/add/null", {add:1}, function(data){
//					if(isNaN(data)){// alert(data) }else{
						$("#data_<?=$arg['blocknum']?>").html(data);
//					}
				});
			});
			$(".del_<?=$arg['blocknum']?>").live("click", function(){
				anket_id = $(this).parents("[anket_id]").attr("anket_id");// alert(anket_id);
				$.post("/blocks/<?=$arg['blocknum']?>/del/null", {anket_id:anket_id}, function(data){
					$("#data_<?=$arg['blocknum']?>").html(data);
				});
			});
		});
	</script>
	<div style="text-align:right;"><a class="add_<?=$arg['blocknum']?>" href="javascript: return false;">Добавить анкету</a></div>
<? endif; ?>
<div id="data_<?=$arg['blocknum']?>">
	<script src="/include/jquery/my/jquery.klesh.select.js"></script>
	<script>
		$(function(){
			<? foreach($fields as $k=>$v): ?>
				<? if($users[$k]): ?>
					$(".klesh[fn=<?=$k?>]").klesh("/blocks/<?=$arg['blocknum']?>/null", function(){
					}, <?=json_encode($users[$k])?>)
				<? else: ?>
					$(".klesh[fn=<?=$k?>]").not("[fn=id]").klesh("/blocks/<?=$arg['blocknum']?>/null");
				<? endif; ?>
			<? endforeach; ?>
		})
	</script>
	<div>
		<? foreach($anket as $a): ?>
			<div anket_id="<?=$a['id']?>">
				<div>
					<span style="float:right;"><a class="del_<?=$arg['blocknum']?>" href="javascript: return false;">Удалить</a></span>
					<h2>Анкета #<?=$a['id']?></h2>
				</div>
				<? foreach(array_diff_key($a, array_flip(array('id', 'time', 'uid'))) as $k=>$v): ?>
					<div style="overflow:hidden;">
						<div style="float:left; width:150px; padding:6px;"><?=($conf['settings'][$f = "{$arg['modpath']}_{$arg['fn']}_$k"] ? : $f)?></div>
						<div style="float:left;" class="klesh" anket_id="<?=$a['id']?>" fn="<?=$k?>"><?=($users[$k] ? $users[$k][$v] : $v)?></div>
					</div>
				<? endforeach; ?>
			</div>
		<? endforeach; ?>
	</div>
</div>