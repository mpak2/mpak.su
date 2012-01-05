<? if($_GET['id']): ?>
	<? if ($arg['access'] >= 4 && !empty($conf['tpl']['page'])): ?>
		<script language=JavaScript>
		function winconfirm(obj){ if (confirm('Вы уверенны?')){ return obj.href; }else{ return false; } }
		</script>
		<div style='border: solid 1px red; overflow:hidden;'>
			<a style="float:left;" title="Сделать стартовой" alt="Сделать стартовой" href="/<?=$arg['modpath']?>/<?=$_GET['id']?>/sm:1" onclick="javascript: if (confirm('Сделать страницу стартовой?')){return obj.href;}else{return false;}">
				<img src="/img/start.png" border=0>
			</a>
			<a style="float:left;" title="<?=_('Редактировать')?>" alt="<?=_('Редактировать')?>" href="/?m[<?=$arg['modpath']?>]=admin&r=<?=$conf['db']['prefix']?><?=$arg['modpath']?>_index&where[id]=<?=$_GET['id']?>">
				<img src="/img/edit.png" border=0>
			</a>
			<a style="float:left;" title="Удалить страницу" alt="Удалить страницу" href="/pages/<?=$_GET['id']?>/del:<?=$_GET['id']?>" onclick="javascript: if (confirm('Удалить страницу?')){return obj.href;}else{return false;}">
				<img src="/img/del.png" border=0>
			</a><i><?=$conf['tpl']['page']['name']?></i>
		</div>
	<? endif; ?>
	<? if(!empty($conf['tpl']['page'])): ?>
		<h1><?=$conf['tpl']['page']['name']?></h1>
		<div><?=$conf['tpl']['page']['text']?></div>
		<div style="margin-top:20px;"><?=$conf['settings']['comments']?></div>
	<? else: ?>
		<div style="margin-top:150px; text-align:center;">Данная страница не найдена на сайте.<br />Возможно она была удалена.
			<? if($arg['access'] >= 4): ?>
				<p />Для редактирования страницы кликнике ссылку: <a href=/pages/<?=$_GET['id']?>/new:1>Создать страницу</a>
			<? endif; ?>
		</div>
	<? endif; ?>
<? else: ?>
	<ul>
		<? foreach($conf['tpl']['cat'] as $k=>$v): ?>
			<li><a href="/<?=$arg['modname']?>:list/cid:<?=$v['id']?>"><?=$v['name']?></a> [<?=$v['cnt']?>]</li>
		<? endforeach; ?>
	</ul>
<? endif; ?>