<? die; # Нуль

$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id=". (int)max($arg['blocknum'], $arg['confnum'])), 0, 'param'));
if($klesh = array(
	/* ($f = "Ширина")=>($param[ $f ] = $param[ $f ] ?: 200),
		($f = "Высота")=>($param[ $f ] = $param[ $f ] ?: 200),
		"Список"=>array(
		1=>"Одын",
		2=>"Два",
	),*/
	"Опрос"=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_index ORDER BY id DESC"),
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
<? return;}// mpre($param);
################################# php код #################################

//if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){};

$index = ql("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE id=". (int)$param['Опрос'], 0);

$value = qn("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index_value WHERE index_id=". (int)$index['id']);

$voice = qn("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index_voice WHERE index_value_id IN (". implode(",", array_keys($value) ?: array(0)). ") AND uid=". (int)$conf['user']['uid'], "index_value_id");

################################# верстка ################################# ?>
<div class="blocknum_<?=$arg['blocknum']?>">
	<script>
		$(function(){
			<? if($voice): ?>
				$(".blocknum_<?=$arg['blocknum']?> input[type=radio]").attr("disabled", true);
			<? endif; ?>
			$(".blocknum_<?=$arg['blocknum']?> input[type=button]").click(function(){
				(post = {}).index_value_id = $(".blocknum_<?=$arg['blocknum']?> ul li input[type=radio]:checked").attr("index_value_id");
				post.index_id = $(".blocknum_<?=$arg['blocknum']?> [index_id]").attr("index_id");
console.log(post);
				$.post("/<?=$arg['modpath']?>:ajax/class:index_voice", post, function(data){
					if(isNaN(data)){ alert(data) }else{
						$(".blocknum_<?=$arg['blocknum']?> [type=radio]").attr("disabled", true);
						alert("Спасибо за участие в опросе");
					}
				});
			});
		});
	</script>
	<h4><?=$index['name']?></h4>
	<ul index_id="<?=$index['id']?>">
		<? foreach($value as $v): ?>
			<li>
				<span><input type="radio" name="value[<?=$index['id']?>]" index_value_id="<?=$v['id']?>" value="<?=$v['id']?>" <?=(!empty($voice[ $v['id'] ]) ? "checked" : "")?>></span>
				<span><?=$v['name']?></span>
				<div style="color:gray;"><?=$v['description']?></div>
			</li>
		<? endforeach; ?>
	</ul>
	<div><input type="button" value="Голосовать"></div>
</form>