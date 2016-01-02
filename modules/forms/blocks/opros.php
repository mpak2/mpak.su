<? # Нуль

if(array_key_exists('confnum', $arg)){
/*	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

echo <<<EOF
	<form method="post">
		<input type="text" name="param" value="$param"> <input type="submit" value="Сохранить">
	</form>
EOF;*/

	return;
}//$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));

$index = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE uid=". (int)$arg['uid']));
$anket = mpqn(mpqw("SELECT a.*, u.name AS uname FROM {$conf['db']['prefix']}{$arg['modpath']}_anket AS a LEFT JOIN {$conf['db']['prefix']}users AS u ON a.uid=u.id WHERE a.index_id IN (". ($index ? implode(",", array_keys($index)) : "0"). ") AND a.status<2"), 'index_id', 'id');

if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST['anket']['anket_id']){
	mpqw($sql = "UPDATE {$conf['db']['prefix']}{$arg['modpath']}_anket SET status=". (int)$_POST['anket']['status']. " WHERE id=". (int)$_POST['anket']['anket_id']. " AND index_id IN (". ($index ? implode(",", array_keys($index)) : "0"). ")");
	echo $_POST['anket']['anket_id']; exit;
};

?>
<? if($index || ($conf['user']['uid'] == $arg['uid'])): ?>
	<? if($conf['user']['uid'] == $arg['uid']): ?>
		<div style="text-align:right;"><a href="/<?=$arg['modname']?>:edit">Создать заявку</a></div>
	<? endif; ?>
	<script language="javascript">
		$(function(){
			$(".anket_del").click(function(){
				anket_id = $(this).parents("[anket_id]").attr("anket_id");// alert(anket_id);
				$.post("/blocks/<?=$arg['blocknum']?>/null", {anket:{anket_id:anket_id, status:3}}, function(data){
					if(isNaN(data)){
						alert(data);
					}else{
						$("[anket_id="+anket_id+"]").hide(300).destroy();
					}
				});
			});
		});
	</script>
	<? foreach($index as $k=>$v): ?>
		<div style="overflow:hidden;">
			<div>
				<span style="float:right;">
					<? if($conf['user']['uid'] == $arg['uid']): ?><a href="/<?=$arg['modname']?>:edit/<?=$v['id']?>"><img src="/img/edit.png"></a><? endif; ?>
				</span>
				<span><a href="/<?=$arg['modname']?>/<?=$v['id']?>"><?=$v['name']?></a></span>
			</div>
			<? if($conf['user']['uid'] == $arg['uid']): ?>
				<div style="margin-left:10px;">
					<? foreach((array)$anket[ $v['id'] ] as $a): ?>
						<div anket_id="<?=$a['id']?>" style="overflow:hidden;">
							<span style="float:right;">
								<a href="/users/<?=$a['uid']?>"><?=$a['uname']?></a>
								<a href="/" class="anket_del" onClick="javascript: return false;">
									<img src="/img/del.png">
								</a>
							</span>
							<span>
								<a href="/<?=$arg['modname']?>/anket_id:<?=$a['id']?>">#<?=$a['id']?></a>
							</span>
							<span><?=date("Y.m.d H:i:s", $a['time'])?></span>
						</div>
					<? endforeach; ?>
				</div>
			<? endif; ?>
		</div>
	<? endforeach; ?>
<? endif; ?>
