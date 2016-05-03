<div class="cont">
	<ul class="nl MdlsList">
		<? foreach(rb("{$conf['db']['prefix']}modules_index", "admin", "id", $_GET['id']) as $modules): ?>
			<? if($conf['modules'][ $modules['id'] ]['access'] >= 4): ?>
				<li>
					<a href="/<?=$modules['folder']?>"><img src="/admin:img/<?=$modules['id']?>/null/modules.png" alt="" /></a>
					<h1><a href="/<?=$modules['folder']?>:admin"><?=$modules['name']?></a></h1>
					<p><?=$modules['description']?></p>
	<!--				<div class="button"><a href="/admin/hide:<?=$modules['id']?>/<?=$_GET['id']?>">скрыть</a></div>-->
					<? if($conf['modules']['settings']['access'] >= 4): ?>
						<div class="button"><a href="/settings:admin/r:mp_settings/?&where[modpath]=<?=$modules['folder']?>">настройки</a></div>
					<? endif; ?>
				</li>
			<? endif; ?>
		<? endforeach; ?>
	</ul>
</div>
