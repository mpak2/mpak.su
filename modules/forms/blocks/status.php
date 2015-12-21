<? # Нуль

if ((int)$arg['confnum']){
/*	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

echo <<<EOF
	<form method="post">
		<input type="text" name="param" value="$param"> <input type="submit" value="Сохранить">
	</form>
EOF;*/

	return;
}//$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));
//$uid = $_GET['id'] && array_key_exists('users', $_GET['m']) ? $_GET['id'] : $conf['user']['id'];
//if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){};

$anket = mpql(mpqw("SELECT a.*, id.name FROM {$conf['db']['prefix']}{$arg['modpath']}_anket AS a LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_index AS id ON a.index_id=id.id WHERE a.uid=". (int)$arg['uid']));

$status = array('0'=>'новый', '1'=>'обработка', '2'=>'отменен', '3'=>'выполнен');



?>
<? if($status): ?>
	<script src="/include/jquery/my/jquery.klesh.select.js"></script>
	<script>
/*		$(function(){
			$(".klesh_<?=$arg['blocknum']?>").klesh("/blocks/<?=$arg['blocknum']?>/null");
		});*/
	</script>
	<div>
		<? foreach($anket as $k=>$v): ?>
			<div>
				<span style="float:right;">
					<span class="klesh_<?=$arg['blocknum']?>"><?=$status[ $v['status'] ]?></span>
					<span><?=date('Y.m.d', $v['time'])?></span>
				</span>
				<span>
					<span><a href="/?m[<?=$arg['modpath']?>]=admin&r=mp_opros2_anket&where[id]=<?=$v['id']?>"><img src="/img/edit.png"></a></span>
					<span><?=$v['name']?></span>
				</span>
			</div>
		<? endforeach; ?>
	</div>
<? endif; ?>
