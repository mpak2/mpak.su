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
				<div style="width:200px; float:left; padding:5px; /*text-align:right;*/ font-weight:bold;"><?=$k?> :</div>
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
	if($_POST['level']){
		$level_id = mpfdk("{$conf['db']['prefix']}pay_level", $w = array("name"=>$_POST['level']), $w += array("value"=>$_POST['val']), $w);
		exit($level_id);
	}else{
		$anket_data_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_anket_data", null, $w = array("time"=>time(), "uid"=>$arg['uid'], "anket_id"=>$_POST['anket_id'], "name"=>$_POST['val']));
		exit($anket_data_id);
//		mpre($_POST); exit;
	}
};

$anket = mpqn(mpqw("SELECT a.* FROM {$conf['db']['prefix']}{$arg['modpath']}_anket AS a LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_anket_type AS at ON (a.anket_type_id=at.id) WHERE reg<>1 ORDER BY at.sort, a.sort"), "anket_type_id", "id");// mpre($anket);

$tables = mpqn(mpqw("SHOW TABLES"), "Tables_in_{$conf['db']['name']}");// mpre($tables);
foreach($anket as $anket_type){
	foreach($anket_type as $a){
		if((substr($a['alias'], -3, 3) == "_id") && array_key_exists("{$conf['db']['prefix']}{$arg['modpath']}_". ($alias = substr($a['alias'], 0, -3)), $tables)){
			$select[ $a['alias'] ] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$alias}"));
		}
	}
}// mpre($select);

$anket_type = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_anket_type ORDER BY sort"));

$anket_data = mpqn(mpqw("SELECT d.*
	FROM {$conf['db']['prefix']}{$arg['modpath']}_anket_data AS d
	LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_anket AS a ON (a.id=d.anket_id)
	LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_anket_type AS t ON (a.anket_type_id=t.id) 
	INNER JOIN (SELECT MAX(`id`) AS max, anket_id FROM {$conf['db']['prefix']}{$arg['modpath']}_anket_data WHERE uid=". (int)$arg['uid']. " GROUP BY `anket_id`) AS m ON (m.max=d.id)
	ORDER BY t.sort, a.sort DESC"
), 'anket_id');// mpre($anket_data);

$refer = mpql(mpqw($sql = "SELECT u.* FROM {$conf['db']['prefix']}utree_index AS t LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']} AS u ON (t.uid=u.id) WHERE t.usr=". (int)$arg['uid']), 0);

?>
<style>
	.anket_<?=$arg['blocknum']?> h3 { cursor:pointer; /*border:1px solid blue;*/ margin:3px 0; padding:0 10px; border-radius:20px; /*background-color:#00eeff;*/ }
	.anket_<?=$arg['blocknum']?> div.toggle { /*display:none;*/ }
	.anket_<?=$arg['blocknum']?> div.toggle > div { /*text-align:right;*/ /*display:none; */}
	.anket_<?=$arg['blocknum']?> div.toggle > div > span { display:inline-block; vertical-align:top; text-align:left;}
	.anket_<?=$arg['blocknum']?> div.toggle > div > span:nth-child(1) {text-align:right; /*min-width:50%;*/ padding:4px 3px;}
	.anket_<?=$arg['blocknum']?> div.toggle > div > span:nth-child(2) {min-width:40%}
	.lvl > div {padding:2px;}
</style>
<script src="/include/jquery/my/jquery.klesh.js"></script>
<script src="/include/jquery/jquery-ui/jquery-ui-1.8.23.custom.min.js"></script>
<link rel="stylesheet" href="/include/jquery/jquery-ui/themes/redmond/jquery-ui-1.8.23.custom.css" type="text/css" />
<script>
	$(function(){
		var select = <?=json_encode($select)?>;
		$(".anket_<?=$arg['blocknum']?> .klesh[select=false]").klesh("/blocks/<?=$arg['blocknum']?>/null", function(){
//			$(this).removeClass("hasDatepicker");
		});
		$(".klesh.level").klesh("/blocks/<?=$arg['blocknum']?>/null");
		$.each(select, function(key, val){
			$(".anket_<?=$arg['blocknum']?> .klesh[select=true][alias="+key+"]").klesh("/blocks/<?=$arg['blocknum']?>/null", function(){
			}, val);
		});
		$(".anket_<?=$arg['blocknum']?> h3").click(function(){
			$(this).next("div").slideToggle();
		});
	});
</script>

<div class="anket_<?=$arg['blocknum']?>" style="min-height:20px; overflow:hidden; padding-bottom:20px;">
	<div style="/*float:right;*/ min-width:70%;">
		<? foreach($anket as $anket_type_id=>$anket): $num = 0; ?>
			<div>
				<h3 style="padding:5px 12px; width:70%; text-align:right;">
					<?=$anket_type[ $anket_type_id ]['sort']?><?=($anket_type[ $anket_type_id ] ? "." : "")?>
					<?=$anket_type[ $anket_type_id ]['name']?>
				</h3>
				<div class="toggle">
				<? foreach($anket as $v): ++$num; ?>
					<div style="overflow:hidden; white-space:nowrap;">
						<span>
							<? if($arg['access'] > 3): ?>
								<span>
									<a href="/?m[users]=admin&r=mp_users_anket_data&where[uid]=<?=$arg['uid']?>&where[anket_id]=<?=$v['id']?>">
										<img src="/img/aedit.png">
									</a>
								</span>
							<? endif; ?>
							<?=$anket_type[ $anket_type_id ]['sort']?>.<?=$num?>. <?=$v['name']?>:
						</span>
						<span><div class="klesh" alias="<?=$v['alias']?>" select="<?=$select[ $v['alias'] ] ? "true" : "false"?>" anket_id="<?=$v['id']?>" style="width:100%;"><?=($select[ $v['alias'] ] ? $select[ $v['alias'] ][ $anket_data[ $v['id'] ]['name'] ]['name'] : $anket_data[ $v['id'] ]['name'])?></div></span>
					</div>
				<? endforeach; ?>
				</div>
			</div>
		<? endforeach; ?>
	</div>
</div>
