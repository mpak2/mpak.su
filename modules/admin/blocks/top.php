<? # АдминШапка

include_once mpopendir('include/idna_convert.class.inc');
$idna = new idna_convert();
$http_host = $idna->decode($_SERVER['HTTP_HOST']);

?>
<div id="header">
	<a href="/" class="logo"></a>
	<a href="/" class="SiteName">http://<?=$http_host?>/</a>
	<div class="exit">
		Здравствуйте, <?=$conf['user']['name']?> | <a href="/?logoff">выход</a>
	</div>
	<ul class="nl">
		<? foreach(rb("{$conf['db']['prefix']}admin") as $admin): ?>
			<li><a href="/admin/<?=$admin['id']?>"><?=$admin['name']?></a><span><?=$admin['name']?></span></li>
		<? endforeach; ?>
	</ul>
</div>
