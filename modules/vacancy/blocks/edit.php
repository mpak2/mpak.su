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
if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum'])){
	if($insert = $_POST['insert']){
		mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_index SET time=". time(). ", uid=". (int)$conf['user']['uid']. ", cat_id=". (int)$insert['cat_id']. ", name=\"". mpquot($insert['name']). "\", description=\"". mpquot($insert['description']). "\"");
		$dat = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE id=". (int)mysql_insert_id()));
	}elseif($del = $_POST['del']){
		mpqw($sql = "DELETE FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE id=". (int)$del['index_id']. " AND uid=". (int)$conf['user']['uid']);// echo $sql;
	}elseif($_GET['cat_id']){
		$cat = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_cat WHERE cat_id=". (int)$_GET['cat_id']));
		echo json_encode($cat); exit;
	}
}else{
// mpre($cat);
	$dat = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE uid=". (int)$arg['uid']));
}
$cat = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_cat"));

?>
<div id="data_<?=$arg['blocknum']?>">
	<? foreach((array)$dat as $k=>$v): ?>
		<div class="id_<?=$arg['blocknum']?>" index_id="<?=$v['id']?>" style="margin-top:10px;">
		<? if($v['uid'] == $conf['user']['uid']): ?>
			<span style="float:right;">
				<a class="del_<?=$arg['blocknum']?>" index_id="<?=$v['id']?>" href="/" onClick="return false;">
					<img src="/img/del.png">
				</a>
			</span>
		<? endif; ?>
		<a href="/<?=$arg['modpath']?>/<?=$v['id']?>" style="font-weight:bold; margin:5px 0;">
			<div><?=$cat[ $cat[ $cat[ $v['cat_id'] ]['cat_id'] ]['cat_id'] ]['name']?></div>
			<div><?=$cat[ $cat[ $v['cat_id'] ]['cat_id'] ]['name']?></div>
			<div><?=$cat[ $v['cat_id'] ]['name']?></div>
		</a>
		<div><?=$v['name']?></div>
		<div><?=$v['description']?></div>
		</div>
	<? endforeach; ?>
</div>
<? if(!array_key_exists('null', $_GET)): ?>
  <script language="javascript">
	$(function(){
	  $("#btn_<?=$arg['blocknum']?>").mousedown(function(){
		var cat_id = $(".cat_<?=$arg['blocknum']?>[level=3]").find("option:selected").val();// alert(cat_id);
		if(cat_id > 0){
			var name = $("#name_<?=$arg['blocknum']?>").val();
			var description = $("#description_<?=$arg['blocknum']?>").val();
			$.post("/blocks/<?=$arg['blocknum']?>/null", {insert:{cat_id:cat_id, name:name, description:description}}, function(data){
			$("#data_<?=$arg['blocknum']?>").append(data);
			$("#description_<?=$arg['blocknum']?>").val('').blur();
			$("#name_<?=$arg['blocknum']?>").val('').blur();
			});
		}else{
			alert("Установите категорию");
		}
	});
		$(".del_<?=$arg['blocknum']?>").live('click', function(){
			var index_id = $(this).attr("index_id");// alert(index_id);
			if(confirm("Удалить вакансию?")){
				$.post("/blocks/<?=$arg['blocknum']?>/null", {del:{index_id:index_id}}, function(data){
					$(".id_<?=$arg['blocknum']?>[index_id="+index_id+"]").hide("slow").destroy();
				});
			}
		});
		$(".cat_<?=$arg['blocknum']?>").change(function(){
			var level = $(this).attr("level");// alert(parseInt(level)+1);
			var cat_id = $(this).find("option:selected").val();// alert(cat_id);
			$.getJSON("/blocks/<?=$arg['blocknum']?>/null", {cat_id:cat_id}, function(data){
//alert($.param(data));
				$(".cat_<?=$arg['blocknum']?>[level="+(parseInt(level)+1)+"]").find("option").remove();
				$.each(data, function(key, val){
					$(".cat_<?=$arg['blocknum']?>[level="+(parseInt(level)+1)+"]").append("<option value='"+val.id+"'>"+val.name+"</option>");
				});
			}); if(level == 1) $(".cat_<?=$arg['blocknum']?>[level=2]").change();
		});
	});
  </script>
	<div style="float:right;"><a href="/<?=$arg['modpath']?>:cat">Категории</a></div>
  <div style="margin-top:10px;">
	<div>
		<select class="cat_<?=$arg['blocknum']?>" level="1">
			<? foreach($cat as $k=>$v): if($v['cat_id']) continue; ?>
				<option value="<?=$v['id']?>"><?=$v['name']?></option>
			<? endforeach; ?>
		</select>
	</div>
	<div><select class="cat_<?=$arg['blocknum']?>" level="2"><option value="">Выберете категорию</option></select></div>
	<div><select class="cat_<?=$arg['blocknum']?>" level="3"><option value="">Выберете категорию</option></select></div>
	<div><input id="name_<?=$arg['blocknum']?>" type="text" title="Заголовок" style="width:100%;"></div>
	<div><textarea id="description_<?=$arg['blocknum']?>" title="Подробное описание" style="width:100%;"></textarea></div>
	<div style="text-align:right;"><input id="btn_<?=$arg['blocknum']?>" type="button" value="Добавить вакансию"></div>
  </div>
<? endif; ?>
