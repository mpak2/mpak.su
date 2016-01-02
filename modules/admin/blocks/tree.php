<? # Нуль

if(array_key_exists('confnum', $arg)){
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST){
		$param = array($_POST['param']=>$_POST['val'])+(array)$param;
		mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	} if(array_key_exists("null", $_GET)) exit;

	$tab = array_keys(mpqn(mpqw("SHOW TABLES"), 'Tables_in_c0e1bd4510d1ed2'));
	$klesh = array(
/*		"Количество символов"=>0,
		"Курс доллара"=>30,*/
		"Структура"=>array(
			""=>"Список",
			1=>"Дерево",
		),
		"Таблица"=>array(""=>"")+array_combine($tab, $tab),
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

$list = mpqn(mpqw("SELECT * FROM ". mpquot($param["Таблица"])), 'catalog_id', 'id');

$get = mpgt($_SERVER['REQUEST_URI']);
$m = $get['m'];

$modpath = array_pop(array_flip($m));
$fn = array_pop($m);

?>
	<script src="/include/jquery/treeview/jquery.treeview.js" type="text/javascript"></script>
	<link rel="stylesheet" href="/include/jquery/treeview/jquery.treeview.css" />
	<script>
		$(function(){
			$(".treeview-gray").treeview();
			$(".treeview a[index_id=<?=(int)$_GET['id']?>]").parents("div").eq(0).css("background-color", "#eee");
		});
	</script>
	<ul class="treeview-gray treeview">
		<? $tree = function($catalog, $tree) use($tpl, $arg, $list, $modpath, $fn){ ?>
			<li index_id="<?=$catalog['id']?>">
				<div style="overflow:hidden;">
					<span style="float:right;">
						<span class="arrow" style="opacity:0">
							<a class="down" href="javascript:" style="display:inline-block; width:15px; height:15px; background-image:url(img/arrow.png);"></a>
							<a class="up" href="javascript:" style="background-repeat:no-repeat; background-position:-15px 0; display:inline-block; width:15px; height:15px; background-image:url(img/arrow.png);"></a>
						</span>
						<a style="display:none;" href="/<?=$arg['modname']?>:<?=$arg['fn']?>/catalog_id:<?=$catalog['id']?>/0" title="Добавить категорию"><img src="img/add.png"></a>
						<a style="display:none;" href="/<?=$arg['modname']?>:<?=$arg['fn']?>/catalog_id:<?=$catalog['id']?>/0" title="Добавить товар"><img src="img/round_add_green.png"></a>
						<a class="del" href="javascript:return false;" title="Удалить"><img src="img/delete.png"></a>
						<a href="/<?=$modpath?>:admin_catalog/<?=$catalog['id']?>" title="Редактировать"><img src="img/edit.png"></a>
						<input type="checkbox">
					</span>
					<span>
						<a index_id="<?=$catalog['id']?>" href="/<?=$modpath?>:<?=$fn?>/<?=$catalog['id']?>">
							<img src="img/folder_yell.png">&nbsp;<?=$catalog['name']?>
						</a>
					</span>
				</div>
				<? if($list[ $catalog['id'] ]): ?>
					<ul>
						<? foreach($list[ $catalog['id'] ] as $k=>$v): ?>
							<?=$tree($v, $tree)?>
						<? endforeach; ?>
					</ul>
				<? endif; ?>
			</li>
		<? }; $tree($list[0][1], $tree); ?>
	</ul>
