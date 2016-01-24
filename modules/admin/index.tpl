<div class="cont">
	<ul class="nl MdlsList">
		<? foreach(rb("{$conf['db']['prefix']}modules", "admin", "id", $_GET['id']) as $modules): ?>
			<li>
				<a href="/<?=$modules['folder']?>"><img src="/admin:img/<?=$modules['id']?>/null/modules.png" alt="" /></a>
				<h1><a href="/<?=$modules['folder']?>:admin"><?=$modules['name']?></a></h1>
				<p><?=$modules['description']?></p>
				<div class="button"><a href="/admin/hide:<?=$modules['id']?>/<?=$_GET['id']?>">скрыть</a></div>
				<div class="button"><a href="/settings:admin/r:mp_settings/?&where[modpath]=<?=$modules['folder']?>">настройки</a></div>
			</li>
		<? endforeach; ?>
	</ul>
</div>
