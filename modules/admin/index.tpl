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
		<? foreach(rb("{$conf['db']['prefix']}modules", "admin", "id", $_GET['id']) as $modules): ?>
			<li>
				<a href="/<?=$conf['modules'][ $modules['folder'] ]['modname']?>"><img src="/admin:img/<?=$modules['id']?>/null/img.jpg" alt="" /></a>
				<h1><a href="/<?=$modules['folder']?>:admin"><?=$modules['name']?></a></h1>
				<p><?=$modules['description']?></p>
				<div class="button"><a href="/admin/hide:<?=$modules['id']?>/<?=$_GET['id']?>">скрыть</a></div>
				<div class="button"><a href="/settings:admin/r:mp_settings/?where[modpath]=<?=$modules['folder']?>">настройки</a></div>
			</li>
		<? endforeach; ?>
	</ul>
</div>
