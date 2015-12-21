<? # Нуль

$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id=". (int)max($arg['blocknum'], $arg['confnum'])), 0, 'param'));
if($klesh = array(
		($f = "Количество запросов")=>($param[ $f ] = $param[ $f ] ?: 5),
	/*	($f = "Высота")=>($param[ $f ] = $param[ $f ] ?: 200),
		"Список"=>array(
		1=>"Одын",
		2=>"Два",
	),
	"Город"=>spisok("SELECT id, name FROM {$conf['db']['prefix']}users_sity ORDER BY name"),*/
//	"Таблица" => array(),
));

if ((int)$arg['confnum']){
	if(array_key_exists("Таблица", $klesh)){ # Если есть таблица то загружаем список таблиц
		foreach(ql("SHOW TABLES") as $v){
			$f = array_shift($v);
			$klesh["Таблица"][ implode("_", array_slice(explode("_", $f), 1)) ] = $f;
		}
	}

	if ($_POST){
		$param = array($_POST['param']=>$_POST['val'])+(array)$param;
		mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	} if(array_key_exists("null", $_GET)) exit;

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
<? return;}
################################# php код #################################

//$uid = $_GET['id'] && array_key_exists('users', $_GET['m']) ? $_GET['id'] : $conf['user']['id'];
//if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){};

$list = mpql(mpqw("SELECT i.*, k.id AS keys_id
	FROM {$conf['db']['prefix']}{$arg['modpath']}_index AS i
	INNER JOIN {$conf['db']['prefix']}{$arg['modpath']}_keys AS k ON (k.index_id=i.id)
	GROUP BY i.id
	ORDER BY i.id DESC LIMIT ". (int)$param["Количество запросов"]
));

################################# верстка ################################# ?>
<ul style="border:none; overflow:hidden;">
	<? foreach($list as $v): ?>
		<li style="float:left; font-size:70%; max-width:60px; white-space:nowrap; overflow:hidden;"><a href="/<?=$arg['modname']?>/tabs_id:<?=$v['keys_id']?>/<?=mpue($v['name'])?>"><?=$v['name']?></a></li>
	<? endforeach; ?>
</ul>
