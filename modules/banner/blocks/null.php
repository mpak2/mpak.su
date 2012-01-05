<? die; # Нуль

if ((int)$arg['confnum']){
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

	echo <<<EOF
	<form method="post">
		<br />Адрес: <input type="text" name="param[addr]" value="{$param['addr']}">
		<br />Количество: <input type="text" name="param[cnt]" value="{$param['cnt']}">
		<br /><input type="submit" value="Сохранить">
	</form>
EOF;

	return;
} $param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));

if($_GET['js']){
	$my = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index ORDER BY RAND() LIMIT 1"), 0);
	mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']}_index SET cnt=cnt+1 WHERE id=". (int)$my['id']);
	echo <<<EOF
	$(document).ready(function(){
		$("#block_banner_title").html("{$my['title']}");
		$("#block_banner_text").html("{$my['text']}");
		$("#block_banner_href").attr("href", "{$my['href']}").attr("target", "_blank").html("{$my['href']}");
	});
EOF;
	exit;
}elseif($param['addr'] && $param['cnt']){
	echo <<<EOF
	<script language="javascript" src="{$param['addr']}/js:{$param['cnt']}/null/banner.js"></script>
	<div id="block_banner_title" style="color:blue; font-weight:bold;"></div>
	<div id="block_banner_text" style="color:black;"></div>
	<div style="float:right;"><a id="block_banner_href"></a></div>
EOF;
}elseif($_POST["block_{$arg['blocknum']}"]){
	if($_POST['del']){
		mpqw($sql = "DELETE FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE uid=". (int)$conf['user']['uid']. " AND id=". (int)$_POST['del']);
		echo (int)$_POST['del'];
	}elseif($mpdbf = mpdbf($tn = "{$conf['db']['prefix']}{$arg['modpath']}_index", $_POST+array('uid'=>$conf['user']['uid'], 'time'=>time()))){
		mpqw($sql = "INSERT INTO $tn SET $mpdbf");
	}
	if(mysql_affected_rows() == 1){
		$my = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE id=". mysql_insert_id()));
	}
}else{
	$my = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index"));
}

?>
<? if(!array_key_exists('null', $_GET) && !$param['cnt']): ?>
	<script language="javascript">
		$(document).ready(function(){
			$("#block_<?=$arg['blocknum']?>_btn").mousedown(function(){
				var title = $("#block_<?=$arg['blocknum']?>_title").val();
				var href = $("#block_<?=$arg['blocknum']?>_href").val();
				var text = $("#block_<?=$arg['blocknum']?>_text").val();
				if(title && href && text){
					$.post("/blocks/<?=$arg['blocknum']?>/null", {title:title, text:text, href:href, "block_<?=$arg['blocknum']?>":true}, function(data){
						$("#block_<?=$arg['blocknum']?>_data").prepend(data);
						$(".block_<?=$arg['blocknum']?>_dat").val('');
					});
				}else{
					alert("Заполните поля перед отправкой");
				}
			});
			$(".block_<?=$arg['blocknum']?>_del").live('click', function(){
				var del = $(this).attr("my");
				if (confirm('Вы уверенны?')){
					$.post("/blocks/<?=$arg['blocknum']?>/null", {del:del, "block_<?=$arg['blocknum']?>":true}, function(data){
						$(".block_"+del+"_block").hide("slow");
					});
				}
			});
		});
	</script>
	<style>
		#block_<?=$arg['blocknum']?>_data > div {
			margin:5px;
		}
	</style>
<div>Ссылка для проектов: <a href="http://<?=$_SERVER['HTTP_HOST']?>/blocks/<?=$arg['blocknum']?>">http://<?=$_SERVER['HTTP_HOST']?>/blocks/<?=$arg['blocknum']?></a></div>
<div id="block_<?=$arg['blocknum']?>_data">
<? endif; ?>
<? foreach((array)$my as $k=>$v): ?>
	<div class="block_<?=$v['id']?>_block" my="<?=$v['id']?>">
		<div style="float:right;"><img my="<?=$v['id']?>" class="block_<?=$arg['blocknum']?>_del" src="/img/del.png"></div>
		<div style="font-weight:bold; color:blue;"><?=$v['title']?></div>
		<div style="color:black;"><?=$v['text']?></div>
		<div style="text-align:right;"><a href="<?=$v['href']?>" style="color:green;"><?=$v['href']?></a></div>
	</div>
<? endforeach; ?>
<? if(!array_key_exists('null', $_GET) && !$param['cnt']): ?>
	<div>
		<div><input class="block_<?=$arg['blocknum']?>_dat" id="block_<?=$arg['blocknum']?>_title" type="text" style="width:100%;" title="Заголовок"></div>
		<div><input class="block_<?=$arg['blocknum']?>_dat" id="block_<?=$arg['blocknum']?>_href" type="text" style="width:100%;" title="Ссылка"></div>
		<div><textarea class="block_<?=$arg['blocknum']?>_dat" title="Текст" id="block_<?=$arg['blocknum']?>_text"></textarea></div>
		<div><input id="block_<?=$arg['blocknum']?>_btn" type="button" value="Добавить"></div>
	</div>
</div>
<? endif; ?>