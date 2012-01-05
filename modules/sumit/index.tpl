<? if($v = $conf['tpl']['sumit'][ $_GET['id'] ]): ?>
	<div style="float:right;"><a href="/<?=$arg['modpath']?>">Все приложения</a></div>
	<div>
		<h3><a href="/<?=$arg['modpath']?>/<?=$v['id']?>"><?=$v['name']?></a></h3>
		<div style="margin-left:20px;"><?=$v['description']?></div>
	</div>
	<div><!-- [settings:comments] --></div>
<? else: ?>
	<script type="text/javascript" src="/include/jquery/toggleformtext.js"></script>
	<style>
		textarea { width:100%; }
	</style>
	<script language="javascript">
		$(function(){
			$("#newproj a").click(function(){
				$(this).parent().next().show();
			});
		});
	</script>

	<? foreach($conf['tpl']['sumit'] as $k=>$v): ?>
		<div>
			<h3><a href="/<?=$arg['modpath']?>/<?=$v['id']?>"><?=$v['name']?></a></h3>
			<div style="margin-left:20px;"><?=$v['description']?></div>
		</div>
	<? endforeach; ?>

	<div id="newproj" style="text-align:right;">
		<a href="/" onClick="javascript: return false;">Добавить новый проект</a>
	</div>
	<form enctype="multipart/form-data" method="post" style="display:none;">
		<div><input type="text" name="sumit[name]" title="Название"></div>
		<div><input type="file" name="sumit[img]"></div>
		<textarea name="sumit[description]" title="Краткое описание проекта"></textarea>
		<div style="text-align:right;"><input type="submit" value="Добавить"></div>
	</form>
<? endif; ?>