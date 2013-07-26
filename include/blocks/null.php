<? die;

$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id=". (int)max($arg['blocknum'], $arg['confnum'])), 0, 'param'));

	if($klesh = array(
		/* ($f = "Ширина")=>($param[ $f ] = $param[ $f ] ?: 200),
			($f = "Высота")=>($param[ $f ] = $param[ $f ] ?: 200),
			"Список"=>array(
			1=>"Одын",
			2=>"Два",
		),
		"Город"=>spisok("SELECT id, name FROM {$conf['db']['prefix']}users_sity ORDER BY name"),*/
		"Тип" => spisok("SELECT id, name FROM {$conf['db']['prefix']}customers_type ORDER BY name"),
	));

	if ((int)$arg['confnum']){

/*		if(array_key_exists("Таблица", $klesh)){ # Если есть таблица то загружаем список таблиц
			foreach(ql("SHOW TABLES") as $v){
				$f = array_shift($v);
				$klesh["Таблица"][ implode("_", array_slice(explode("_", $f), 1)) ] = $f;
			}
		}*/

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

//if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){};

//$tpl['index'] = qn("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}");

################################# верстка ################################# ?>
<div>Верстка здесь</div>