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
} //$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));

$arg['uid'] = $_GET['id'] && array_key_exists($conf['modules']['users']['modname'], $_GET['m']) ? $_GET['id'] : $conf['user']['id'];
$pages = mpql(mpqw("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE uid=". (int)$arg['uid']));

?>
<div>
	<? if($pages || $arg['uid'] == $conf['user']['uid']): ?>
		<script>
			$(document).ready(function(){
				$(".del_<?=$arg['blocknum']?>").click(function(){
					if(confirm("Удалить статью?")){
						var id = $(this).attr("num");
						$.get("/<?=$arg['modpath']?>:edit/del:"+id, {}, function(data){});
						$("#page_<?=$arg['blocknum']?>_"+id).hide("slow");
					}
					return false;
				});
			});
		</script>
		<ul>
			<? foreach($pages as $k=>$v): ?>
				<li id="page_<?=$arg['blocknum']?>_<?=$v['id']?>" style="overflow:hidden;">
					<? if($arg['uid'] == $conf['user']['uid']): ?>
						<div style="float:right; width:30px;">
							<span><a class="del_<?=$arg['blocknum']?>" num="<?=$v['id']?>" href="/<?=$arg['modpath']?>:edit/del:<?=$v['id']?>" onClick="javascript: return false;"><img src="/img/del.png"></a></span>
							<span><a href="/<?=$arg['modpath']?>:edit/<?=$v['id']?>"><img src="/img/edit.png"></a></span>
						</div>
					<? endif; ?>
					<div style="margin-right:30px;">
						<a href="/<?=$arg['modpath']?>/<?=$v['id']?>"><?=$v['name']?></a>
					</div>
				</li>
			<? endforeach; ?>
		</ul>
		<? if($arg['uid'] == $conf['user']['uid']): ?>
			<div style="margin:10px; text-align:right;">
				<a href="/<?=$arg['modpath']?>:edit">Добавить статью</a>
			</div>
		<? endif; ?>
	<? endif; ?>
</div>
