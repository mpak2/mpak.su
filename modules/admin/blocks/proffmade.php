<? die; # АдминШапка

if ((int)$arg['confnum']){
	# Востановление параметров модуля
//	if (count($res = mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"))))
//		$param = unserialize($res[0]['param']);

	# Сохранение параметров модуля
//	if (count($param)) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	return;
}

$lnk = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}admin"));
include_once mpopendir('include/idna_convert.class.inc');
$idna = new idna_convert();

$http_host = $idna->decode($_SERVER['HTTP_HOST']);

?>

<div id="header">
	<div style="text-align:center; width:220px;">
		<a href="http://<?=$http_host?>/"><div style="padding:7px 0 3px; font-size:22px; color:#FE8E23;">PROFFMADE CMS</div></a>
		<div>система управления</div>
	</div>
<!--	<a href="/" class="SiteName">http://<?=$http_host?>/</a>-->
	<div class="exit">Здравствуйте, <?=$conf['user']['name']?> | <a href="/?logoff">выход</a></div>
	<ul class="nl">
	<? foreach($lnk as $k=>$v): ?>
		<li><a href="/admin/<?=$v['id']?>"><?=$v['name']?></a><span><?=$v['name']?></span></li>
	<? endforeach; ?>
	</ul>
</div>
