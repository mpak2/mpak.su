<style>
	#data { width:70%; }
	#fm { width:60%; }
	#fm textarea { width:100%; }
</style>
<? if(!array_key_exists("null", $_GET)): ?>
	<script src="/include/jquery/jquery.iframe-post-form.js"></script>
	<script>
		$(function(){
			$("#fm").iframePostForm({
				complete:function(data){
					text = $("<div>").html(data).find("#data").html();
					$("#data").prepend(text);
				}
			});
			$(".del").live("click", function(){
				line_id = $(this).parents("[line_id]").attr("line_id");// alert(line_id);
				$.post("/<?=$arg['modname']?>:<?=$arg['fe']?>/null", {del_id:line_id}, function(data){
					if(isNaN(data)){ alert(data); }else{
						$("#data [line_id="+line_id+"]").hide(300);
					}
				});
			});
		});
	</script>
	<form id="fm" action="/<?=$arg['modname']?>:<?=$arg['fe']?>/null" method="post" enctype="multipart/form-data" style="width:70%; margin-bottom:10px;">
		<div><input type="file" name="file"></div>
		<div><textarea name="description" title="Комментарий"></textarea></div>
		<div style="text-align:right;"><input type="submit" value="Добавить документ"></div>
	</form>
<? endif; ?>
<div id="data">
	<? foreach($conf['tpl']['doc'] as $k=>$v): ?>
		<div line_id="<?=$v['id']?>" style="overflow:hidden;">
			<span style="float:right;"><a class="del" href="javascript: return false;"><img src="/img/del.png"></a></span>
			<span><?=date('Y.m.d H:i:s', $v['time'])?></span>
			<span><a href=""><?=$v['name']?></a></span>
			<span style="white-spice:nowrap;"><?=$v['description']?></span>
		</div>
	<? endforeach; ?>
</div>