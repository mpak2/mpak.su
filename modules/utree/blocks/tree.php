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
//if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){};

$tpl['tree'] = mpqn(mpqw("SELECT id.*, id.usr AS id, u.fm, u.im, u.name AS uname FROM {$conf['db']['prefix']}{$arg['modpath']}_index AS id LEFT JOIN {$conf['db']['prefix']}users AS u ON (id.usr=u.id)"), 'uid', 'id');

$tree = ql($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE usr=". $arg['uid'], 0);

?>
<? if($u = $tpl['tree'][ $tree['uid'] ][ $tree['usr'] ]): ?>
	<link rel="stylesheet" href="/include/jquery/treeview/jquery.treeview.css" />
	<script src="/include/jquery/treeview/jquery.treeview.js" type="text/javascript"></script>
	<script>
		$(function() {
			$("#tree").treeview({
				collapsed: true,
				animated: "medium",
				control:"#sidetreecontrol",
				persist: "location"
			});
		})
	</script>
	<ul id="tree" class="open" style="overflow:hidden;">
		<? $func = function($v = array("id"=>0)) use(&$func, $tpl, $conf, $arg){ ?>
			<li>
				<span>
					<?=$v['fm']?> <?=$v['im']?> (<a href="/users/<?=$v['usr']?>"><?=$v['uname']?></a>)
				</span>
				<? if($tpl['tree'][ $v['id'] ]): ?>
					<ul>
						<? foreach($tpl['tree'][ $v['id'] ] as $v): ?>
							<? $func($v) ?>
						<? endforeach; ?>
					</ul>
				<? endif; ?>
			</li>
		<? }; $func($u); ?>
	</ul>
<? endif; ?>