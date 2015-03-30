<div class="cont">
	<? if($conf['tpl']['hide']): ?>
		<select id="sel" class="fl" style="margin:0 12px 0 9px;position:relative;top:4px;width:300px;">
			<? foreach($conf['tpl']['hide'] as $k=>$v): ?>
			<option value="<?=$v['id']?>"><?=$v['name']?></option>
			<? endforeach; ?>
		</select>
		<div class="button"><input type="submit" value="Добавить" onClick="javascript: obj=document.getElementById('sel'); document.location='/admin/display:'+obj.options[obj.selectedIndex].value+'/<?=$_GET['id']?>';"></div>
		<div class="cb"></div>
	<? endif; ?>
	<ul class="nl MdlsList">
		<? foreach($conf['tpl']['modules'] as $k=>$v): ?>
		<li>
			<a href="/<?=$conf['modules'][ $v['folder'] ]['modname']?>"><img src="/admin:img/<?=$v['id']?>/null/img.jpg" alt="" /></a>
			<h1><a href="/<?=$v['folder']?>:admin"><?=$v['name']?></a></h1>
			<p><?=$v['description']?></p>
			<div class="button"><a href="/admin/hide:<?=$v['id']?>/<?=$_GET['id']?>">скрыть</a></div>
			<div class="button"><a href="/?m[settings]=admin&where[modpath]=<?=$v['folder']?>">настройки</a></div>
		</li>
		<? endforeach; ?>
	</ul>
</div>