<? die; # Нуль

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

if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){
	if($_POST['del']){
		mpqw("DELETE FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE uid=". (int)$conf['user']['uid']. " AND id=". (int)$_POST['del']); exit($_POST['del']);
	}elseif($_POST['type_id'] && $_POST['name']){
		mpqw($sql = "INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_index SET time=". time(). ", uid=". (int)$conf['user']['uid']. ", type_id=". (int)$_POST['type_id']. ", name=\"". mpquot($_POST['name']). "\"");
		$my = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE uid=". (int)$arg['uid']. " AND id=". (int)mysql_insert_id()));
	}
}elseif($_GET['index_id']){
	$index = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE id=". (int)$_GET['index_id']), 0);
	mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']}_index SET count=count+1 WHERE id=". (int)$_GET['index_id']);
	$url = strpos($index['name'], "://") ? $index['name'] : "http://{$index['name']}";
	header("Location: ". mpidn($url, 1));
}else{
	$my = mpql(mpqw("SELECT id.* FROM {$conf['db']['prefix']}{$arg['modpath']}_index AS id LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_type AS t ON id.type_id=t.id WHERE uid=". (int)$arg['uid']. " ORDER BY t.sort"));
}

$res = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE uid=". (int)$arg['uid']));
$type = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_type ORDER BY sort"));

?>
<? if($my || ($arg['uid'] == $conf['user']['uid'])): ?>
	<div id="data_<?=$arg['blocknum']?>">
		<? foreach($my as $k=>$v): ?>
			<div style="overflow:hidden;" class="my_<?=$arg['blocknum']?>" my="<?=$v['id']?>">
				<? if($arg['uid'] == $conf['user']['uid']): ?>
					<span style="float:right;"><a href="/" class="del_<?=$arg['blocknum']?>" my="<?=$v['id']?>" onClick="javascript: return false;"><img src="/img/del.png"></a></span>
				<? endif; ?>
				<span style="float:left; margin:0 10px;"><img src="/<?=$arg['modpath']?>:img/<?=$v['type_id']?>/tn:type/w:30/h:30/c:1/null/img.jpg"></span>
				<div style="overflow:hidden;">
					<span style="float:right;">переходов: <?=$v['count']?></span>
					<span><?=$type[ $v['type_id'] ]['name']?></span>
				</div>
				<div style="margin-left:60px;">
					<a target="blank" href="/blocks/<?=$arg['blocknum']?>/index_id:<?=$v['id']?>/null"><?=$v['name']?></a>
				</div>
			</div>
		<? endforeach; ?>
	</div>
<? endif; ?>
<? if(!$_POST && ($arg['uid'] == $conf['user']['uid'])): ?>
	<script language="javascript">
		$(function(){
			$("#btn_<?=$arg['blocknum']?>").mousedown(function(){
				var name = $("#name_<?=$arg['blocknum']?>").val();// alert(name);
				var type_id = $("#type_<?=$arg['blocknum']?>").find("option:selected").val();// alert(type_id);
				if(name == "http://адрес.страницы.рф/"){
					alert("Укажите адрес страницы!");
				}else{
					$.post("/blocks/<?=$arg['blocknum']?>/null", {name:name, type_id:type_id}, function(data){
						$("#data_<?=$arg['blocknum']?>").append(data);
					}); $("#name_<?=$arg['blocknum']?>").val('').blur();
				}
			});
			$(".del_<?=$arg['blocknum']?>").live('click', function(){
				if(confirm("Удалить ресурс?")){
					var id = $(this).attr("my");
					$.post("/blocks/<?=$arg['blocknum']?>/null", {del:id}, function(data){
						if(isNaN(data)){
							alert(data);
						}else{
							$(".my_<?=$arg['blocknum']?>[my="+data+"]").hide("slow").destroy();
						}
					});
				}
			});
		});
	</script>
	<div>
		<div style="margin:5px;"><input type="text" id="name_<?=$arg['blocknum']?>" title="http://адрес.страницы.рф/" style="width:100%;"></div>
		<div style="margin:0 5px;">
			<div style="float:right;"><input id="btn_<?=$arg['blocknum']?>" type="button" value="Добавить ресурс"></div>
			<div>
				<select id="type_<?=$arg['blocknum']?>">
					<? foreach($type as $k=>$v): ?>
						<option value="<?=$v['id']?>"><?=$v['name']?></option>
					<? endforeach; ?>
				</select>
			</div>
		</div>
	</div>
<? endif; ?>