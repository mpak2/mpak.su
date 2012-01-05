<link rel="stylesheet" href="/include/dhonishow/dhonishow.css" type="text/css" media="screen" />
<script src="/include/dhonishow/jquery.dhonishow.js" type="text/javascript"></script>

<div class="dhonishow thumbnails_true hide-buttons_true hide-alt_true autoplay_4 duration_1">
	<? foreach($conf['tpl']['index'] as $k=>$v): ?>
		<img src="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:index/w:600/h:400/c:1/null/img.jpg">
	<? endforeach; ?>
</div>  