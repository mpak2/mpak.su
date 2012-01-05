<? if($v = $conf['tpl']['video'][ $_GET['id'] ]): ?>
	<script type="text/javascript" src="/include/jquery/jquery.media.js"></script>
	<script>
		$(function(){
			$('.media').media({autoplay: 1});
		});
	</script>
	<div><a href="/<?=$arg['modpath']?>">Весь список</a></div>
	<div style="margin:10px;">
		<a class="media {width:320, height:240}" href="/video:video/tn:files/<?=$v['id']?>/null/<?=basename($v['video'])?>"><?=$v['name']?></a>
	</div>
<? else: ?>
	<div>
		<? foreach($conf['tpl']['video'] as $k=>$v): ?>
			<div style="margin-top:10px;">
				<a href="/<?=$arg['modpath']?>/<?=$v['id']?>">
					<div style="text-align:center;"><?=$v['name']?></div>
					<div><img src="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:files/w:120/h:100/null/img.jpg"></div>
				</a>
			</div>
		<? endforeach; ?>
	</div>
<? endif; ?>