<? # АдминШапка

//include_once mpopendir('include/idna_convert.class.inc');
$http_host = (new idna_convert())->decode($_SERVER['HTTP_HOST']);

?>
<div id="header" style="white-space:nowrap; padding-bottom:5px;">
		<div class="table" style="width:auto;">
			<div>
				<a href="/" class="logo"><img src="i/logo.png"></a>
				<span style="vertical-align:bottom;">
					<? if($version = mpopendir("version.txt")): ?>
						<div><b>Версия</b>: <?=file_get_contents($version)?></div>
					<? endif; ?>
					<div>
						<a href="/" class="SiteName">http://<?=$http_host?>/</a>
						<? if(get($conf, 'settings', 'admin_multisite') && ($themes_index = rb("themes-index", "name", "[{$http_host}]"))): ?>
							<a href="/themes:admin/r:<?=$conf['db']['prefix']?>themes_index?&where[id]=<?=$themes_index['id']?>" title="Переход к хосту"><img src="/img/host.png"></a>
							<a href="<?=$_SERVER['REQUEST_URI']?><?=(strpos($_SERVER['REQUEST_URI'], "?") ? "" : "?")?><?=(get($_GET, 'where', 'themes_index') ? "" : "&where[themes_index]={$themes_index['id']}")?>" title="Фильтровать по хосту"><img src="/img/filter.png"></a>
							<a href="<?=$_SERVER['REQUEST_URI']?><?=(strpos($_SERVER['REQUEST_URI'], "?") ? "" : "?")?><?=(get($_GET, 'limit') ? "" : "&limit=1000")?>" title="Лимит строк 1000"><img src="/img/limit.png"></a>
						<? endif; ?>
					</div>
				</span>
			</div>
		</div>
	<div class="exit">
		Здравствуйте, <?=$conf['user']['name']?> | <a href="/?logoff">выход</a>
	</div>
	<ul class="nl">
		<? foreach(rb("{$conf['db']['prefix']}admin_index") as $admin): ?>
			<li><a href="/admin/<?=$admin['id']?>"><?=$admin['name']?></a><span><?=$admin['name']?></span></li>
		<? endforeach; ?>
	</ul>
</div>
