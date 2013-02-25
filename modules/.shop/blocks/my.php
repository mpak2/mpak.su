<? die; # МоиМашины

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

if(array_key_exists('blocks', $_GET['m'])){
	if($_POST['del']){
		mpqw($sql = "DELETE FROM {$conf['db']['prefix']}{$arg['modpath']}_desc WHERE id=". (int)$_POST['del']. " AND uid=". (int)$conf['user']['uid']);
	}elseif($_POST){
		if($mpdbf = mpdbf($tn = "{$conf['db']['prefix']}{$arg['modpath']}_desc", $_POST+array('time'=>time(), "uid"=>$conf['user']['uid']))){
			mpqw($sql = "INSERT INTO $tn SET $mpdbf");
			if(is_file(mpopendir('include/'. $mpfn = mpfn($tn, 'img', $id = mysql_insert_id())))){
				mpqw("UPDATE $tn SET img=\"". mpquot($mpfn). "\" WHERE id=". (int)$id);
			}
		}else{
			echo "Ошибка добавления";
		}
	}elseif($_FILES && ($tn = "{$conf['db']['prefix']}{$arg['modpath']}_img")){
		$id = mpql(mpqw("SELECT max(id) AS id FROM $tn"), 0, 'id')+1;
		if(is_file(mpopendir('include/'. $mpfn = mpfn($tn, 'img', $id)))){
			mpqw($sql = "INSERT INTO $tn SET id=". (int)$id. ", uid=". (int)$conf['user']['uid']. ", desc_id=\"". (int)$_GET['desc_id']. "\", img=\"". mpquot($mpfn). "\"");
			echo $id;
		}else{
			echo 'file not exists';
		} exit;
	}
}

$my = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_desc WHERE uid=". (int)$arg['uid']. " ORDER BY id DESC LIMIT 5"), 'id');
$img = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_img WHERE uid=". (int)$arg['uid']), 'desc_id', 'id');

$conf['tpl']['sity'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_sity ORDER BY name"), 'id');
$conf['tpl']['obj'] = mpqn(mpqw("SELECT id, obj_id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_obj"), 'obj_id' , 'id');
$conf['tpl']['obj_id'] = mpqn(mpqw("SELECT id, obj_id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_obj"), 'id');

?>

<? if(!array_key_exists('null', $_GET) && ($arg['uid'] == $conf['user']['uid'])): ?>
	<script type="text/javascript" src="/include/jquery/jquery.form.js"></script>
	<script language="javascript">
		var obj = <?=json_encode($conf['tpl']['obj']);?>;
		$(document).ready(function(){
//			$("#obj").append("<option></option>");
			$.each(obj[0], function(key, val){ $("#obj").append("<option value='"+key+"'>"+val.name+"</option>"); });
			$("#obj").change(function(){
				var id = $(this).find("option:selected").val();
				$("#obj_id").find("option").remove();
				$("#obj_id").append("<option></option>")
				$.each(obj[id], function(key, val){ $("#obj_id").append("<option value='"+key+"'>"+val.name+"</option>"); });
			});
			var options = {
				resetForm: true,
				success:       function(data, statusText ){
					$("#data_<?=$arg['blocknum']?>").html(data);
				},
			}
			$("#my_<?=$arg['blocknum']?>").ajaxForm(options);
			$(".del").live('click', function(){
				if (confirm('Вы уверенны?')){
					var id = $(this).attr("id");
					$.post('/blocks/<?=$arg['blocknum']?>/null', {del:id}, function(data){
						$(".my#"+id).animate({height:0, opacity:0}, function(){ $(this).remove(); });
					});
				}
			});
			$(".edit").live('click', function(){
//				var id = $(this).attr("id");
//				var sity_id = $("#data_<?=$arg['blocknum']?>").find(".my[id="+id+"]").find("input[type=hidden]").length;
//				$("my_<?=$arg['blocknum']?>").find("select[name=obj_id]").find("option[value=""]")
			});
		});
		function maifload(obj){
			var my = $(obj).attr("my");
			if(id = $(obj).contents().find('body').html()){
				if(!isNaN(id)){
					$(".imgplace[my="+my+"]").append("<div class=\"divimg\"><img class='mgimg' src=\"/<?=$arg['modpath']?>:img/"+id+"/tn:2/w:50/h:50/null/img.jpg\"></div>");
				}else{ alert("error: "+id); }
			}
		};
	</script>
	<style>
		.bold {font-weight:bold;}
		.ma {width:60%;float:right;}
		.cb {clear:both;}
	</style>
	<form id="my_<?=$arg['blocknum']?>" action="/blocks/<?=$arg['blocknum']?>/null" enctype="multipart/form-data">
		<div style="margin:10px 10px;">
			<div style="margin:5px;">
				<div class="cb">Город: 
					<select name="sity_id" class="ma">
						<? foreach($conf['tpl']['sity'] as $k=>$v): ?>
							<option value="<?=$v['id']?>"><?=$v['name']?></option>
						<? endforeach; ?>
					</select>
				</div>
				<div class="cb">Категория: <select id="obj" class="ma"><option></option></select></div>
				<div class="cb">Подкатегория: <select id="obj_id" name="obj_id" class="ma"><option></option></select></div>
				<div class="cb">Цена: <input type="text" name="price" class="ma"></div>
				<div class="cb">Название: <input type="text" name="name" class="ma"></div>
				<div class="cb">Краткое описание: <input type="text" name="description" class="ma"></div>
				<div class="cb">Фото: <input type="file" name="img" class="ma"></div>
				<div><textarea name="text" style="width:100%;" title="Расширенное описание"></textarea></div>
			</div>
			<div style="text-align:right;">
				<input class="mamybtn" type="submit" value="Добавить">
			</div>
		</div>
	</form>
	<style>
		.my {
			clear:both;
		}
		.divimg {
			text-align:center;
			width:60px;
			height:60px;
			float:left;
			margin: 1px;
			overflow: hidden;
		}
		
		.imgcarhover:hover {
			border: 1px solid white;
		}
		
		.imgcarhover {
			border: 1px solid #ccc;
			padding: 2px;
			overflow: hidden;
			display: block;
			float: left;
		}
		
		.nm {
			font-weight: bold;
		}
	</style>
<? endif; ?>

<div id="data_<?=$arg['blocknum']?>">
<? if($my): ?>
	<? foreach($my as $k=>$v): ?>
	<div class="my" id="<?=$v['id']?>">
		<table width=98% style="; margin: 10px 5px 0 5px;">
			<tr>
				<td>
					<img src="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:1/w:100/h:100/null/img.jpg">
				</td>
				<td valign="top" style="text-align:right; border-top:1px solid #ccc;">
					<div style="width:100%;">
						<? if($arg['uid'] == $conf['user']['uid']): ?>
						<span style="float:right; width:80px;">
							<a href="/" onclick="return false;"><img src="/img/edit.png" class="edit" id="<?=$v['id']?>"></a>
							<a href="/" onclick="return false;"><img src="/img/del.png" class="del" id="<?=$v['id']?>"></a>
						</span>
						<? endif; ?>
					</div>
				</td>
			</tr>
			<tr>
				<td>Город:</td>
				<td>
					<input type="hidden" id="sity_id" value="<?=$v['sity_id']?>">
					<a href=""><?=$conf['tpl']['sity'][ $v['sity_id'] ]['name']?></a>
				</td>
			</tr>
			<tr>
				<td>Категория:</td>
				<td>
					<input type="hidden" id="obj_id" value="<?=$v['obj_id']?>">
					<a href="/<?=$arg['modpath']?>/oid:<?=$v['obj_id']?>"><?=$conf['tpl']['obj_id'][ $v['obj_id'] ]['name']?></a>
				</td>
			</tr>
			<tr>
				<td>Цена:</td>
				<td>
					<input type="hidden" id="price" value="<?=$v['obj_id']?>">
					<?=$v['price']?>
				</td>
			</tr>
			<tr>
				<td>Название:</td>
				<td>
					<input type="hidden" id="name" value="<?=$v['name']?>">
					<?=$v['name']?>
				</td>
			</tr>
			<tr>
				<td>Описание:</td>
				<td>
					<input type="hidden" id="description" value="<?=$v['description']?>">
					<?=$v['description']?>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<input type="hidden" id="text" value="<?=$v['text']?>">
					<?=$v['text']?>
				</td>
			</tr>
			<? if($arg['uid'] == $conf['user']['uid']): ?>
			<tr>
				<td>Добавить фото:</td>
				<td colspan="2">
					<form action="/blocks/<?=$arg['blocknum']?>/desc_id:<?=$v['id']?>/null" method="POST" target="maif_<?=$v['id']?>" enctype="multipart/form-data">
						<input type="file" name="img" onchange="this.form.submit();">
					</form>
					<iframe onload="javascript: maifload(this);" class="maif" my="<?=$v['id']?>" name="maif_<?=$v['id']?>" style="width:200px; height:200px; display:none;"></iframe>
				</td>
			</tr>
			<? endif; ?>
			<tr>
				<td colspan=3>
					<div style="clear:both;" class="imgplace" my="<?=$v['id']?>" id="gallery">
						<? foreach((array)$img[ $v['id'] ] as $k=>$v): ?>
							<div class="divimg">
								<a class="imgcarhover" href="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:2/w:600/h:500/null/img.jpg">
									<img class="mgimg" src="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:2/w:50/h:50/null/img.jpg">
								</a>
							</div>
						<? endforeach; ?>
					</div>
				</td>
			</tr>
		</table>
	</div>
	<? endforeach; ?>
<? endif; ?>
<div>
