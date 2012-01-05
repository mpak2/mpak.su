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
//$uid = $_GET['id'] && array_key_exists('users', $_GET['m']) ? $_GET['id'] : $conf['user']['id'];

if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){
	if($_FILES['document']){
		$tn = "{$conf['db']['prefix']}{$arg['modpath']}_index";
		$name = implode('.', array_slice(explode(".", $_FILES['document']['name']), 0, -1));
		mpqw("INSERT INTO $tn SET cat_id=". (int)$_POST['cat_id']. ", uid=". (int)$conf['user']['uid']. ", name=\"". mpquot($name). "\", description=\"". mpquot($_POST['description']). "\"");
		if($fn = mpfn($tn, "document", $id = mysql_insert_id())){
			mpqw("UPDATE $tn SET document=\"". mpquot($fn). "\" WHERE id=". (int)$id);
		}
		$documents = array($_POST+array("id"=>$id, "document"=>$fn, "name"=>$name));
	}elseif($_POST['del']){
		mpqw("DELETE FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE uid=". $conf['user']['uid']. " AND id=". (int)$_POST['del']);
		echo $_GET['del']; exit;
	}
//	echo $id; exit;
}else{
	$documents = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE uid=". (int)$arg['uid']));
}

$cat = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_cat"));

$index = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE uid=". (int)$arg['uid']));

?>
<? if(!array_key_exists("null", $_GET) && ($arg['uid'] == $conf['user']['uid'])): ?>
	<script type="text/javascript" src="/include/jquery/jquery.iframe-post-form.js"></script>
	<script>
		$(function(){
			$("form#file_<?=$arg['blocknum']?>").iframePostForm({
				complete : function (data){
					$("#data_<?=$arg['blocknum']?>").prepend(data);
					$("form#file_<?=$arg['blocknum']?>").reset();
				}
			});
			$(".del_<?=$arg['blocknum']?>").live("click", function(){
				index_id = $(this).parents("[index_id]").attr("index_id");// alert(index_id);
				$.post("/blocks/<?=$arg['blocknum']?>/null", {del:index_id}, function(data){
					if(isNaN(data)){ alert(data) }else{
						$("#data_<?=$arg['blocknum']?>").find("[index_id="+index_id+"]").hide("slow").destroy();
					}
				});
			});
		});
	</script>
	<form id="file_<?=$arg['blocknum']?>" method="post" action="/blocks/<?=$arg['blocknum']?>/null" enctype="multipart/form-data">
		<div><textarea name="description" style="width:60%;" title="Описание"></textarea></div>
		<div>
			<span style="float:right;"><input type="submit" value="Добавить"></span>
			<span>
				<select name="cat_id">
					<? foreach($cat as $k=>$v): ?>
						<option value="<?=$v['id']?>"><?=$v['name']?></option>
					<? endforeach; ?>
				</select>
			</span>
			<span><input name="document" type="file"></span>
		</div>
	</form>
<? endif; ?>
<div id="data_<?=$arg['blocknum']?>">
	<? foreach($documents as $k=>$v): ?>
		<div index_id="<?=$v['id']?>" style="overflow:hidden;">
			<? if($arg['uid'] = $conf['user']['uid']): ?>
				<div style="float:right;">
					<a href="javascript: return false;">
						<img class="del_<?=$arg['blocknum']?>" src="/img/del.png">
					</a>
				</div>
			<? endif; ?>
			<div style="float:left; width:150px;"><?=$cat[ $v['cat_id'] ]['name']?></div>
			<a href="/<?=$arg['modname']?>/<?=$v['id']?>/null/<?=$v['name']?>.<?=array_pop(explode(".", $v['document']))?>">
				<?=$v['name']?>
			</a>
		</div>
		<div style="margin-left:10px; font-style:italic;"><?=$v['description']?></div>
	<? endforeach; ?>
</div>