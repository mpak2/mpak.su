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

if(array_key_exists("null", $_GET) && $_POST){
	if($_POST['del_id']){
		mpqw("DELETE FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} WHERE uid=". (int)$conf['user']['uid']. " AND id=". (int)$_POST['del_id']);
		exit((string)$_POST['del_id']);
	}else{
		$tags_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}", null, $_POST, $_POST);
		$tpl[ $arg['fn'] ] = array($tags_id=>array("id"=>$tags_id, "time"=>time(), "uid"=>$conf['user']['uid'], "uname"=>$conf['users']['uname'])+$_POST);
	}
}else{
	$tpl["tags"] = mpqn(mpqw($sql = "SELECT t.*, COUNT(tk.id) AS cnt FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} AS t LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_tasks AS tk ON (t.id=tk.tags_id) WHERE t.uid=". (int)$conf['user']['uid']. " GROUP BY t.id"));
}

$tags = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} WHERE uid=". (int)$conf['user']['uid']));

?>

<style>
	form.<?=$arg['modpath']?>.<?=$arg['fn']?> input[type=text], .<?=$arg['modpath']?>.<?=$arg['fn']?> textarea {width:100%;}
	.<?=$arg['modpath']?>.index > div {margin-top:10px; /*border-top:1px dashed gray;*/}
	.<?=$arg['modpath']?>.index > div > div {overflow:hidden;}
</style>
<script src="/include/jquery/jquery.iframe-post-form.js"></script>
<? if(!array_key_exists("null", $_GET)): ?>
	<script>
		$(function(){
			$("form.<?=$arg['modpath']?>.<?=$arg['fn']?>").iframePostForm({
				complete:function(data){
					if( html = $("<div>").html(data).find(".<?=$arg['modpath']?>.index").html() ){
						$(".<?=$arg['modpath']?>.index").prepend(html);
					}else{ alert(data) }
				}
			});
			$(".<?=$arg['modpath']?>.index a.del").on("click", function(){
				if(confirm("Удалить элемент?")){
					(post = {}).del_id = $(this).parents("[index_id]").attr("index_id");
					$.post("/blocks/<?=$arg['blocknum']?>/null", post, function(data){
						if(isNaN(data)){ alert(data) }else{
							$(".<?=$arg['modpath']?>.index [index_id="+data+"]").remove();
						}
					});
				}
			});
		});
	</script>
	<div class="<?=$arg['modpath']?> <?=$arg['fn']?> toggle" style="display:none;">
		<form class="<?=$arg['modpath']?> <?=$arg['fn']?>" action="/blocks/<?=$arg['blocknum']?>/null" method="post">
			<div><input type="text" name="name" placeholder="Название"></div>
			<div><textarea name="description" placeholder="Описание"></textarea></div>
			<div style="text-align:right;"><input type="submit" value="Добавить"></div>
		</form>
	</div>
	<a class="add form" style="position:relative; top:-20px;" href="javascript:">
		<script>
			$(function(){
				$(".add.form").click(function(){
					$(".<?=$arg['modpath']?>.<?=$arg['fn']?>.toggle").slideToggle();
				});
			});
		</script>
		Добавить
	</a>
<? endif; ?>
<div style="margin-top:-20px;">
	<div class="<?=$arg['modpath']?> index">
		<? if($tpl[ $arg['fn'] ]) foreach($tpl[ $arg['fn'] ] as $v): ?>
			<div index_id="<?=$v['id']?>">
				<span style="float:right;">
					<?=(int)$v['cnt']?>
				</span>
				<span title="<?=$v['description']?>">
					<a class="del" href="javascript:"><img src="/img/del.png"></a>
					<a href="/<?=$arg['modname']?>:tasks/tags_id:<?=$v['id']?>"><?=$v['name']?></a>
				</span>
			</div>
		<? endforeach; ?>
	</div>
</div>
