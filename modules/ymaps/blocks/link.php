<? die; # Баннер

if ((int)$arg['confnum']){
/*	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

echo <<<EOF
	<form method="post">
		<input type="text" name="param" value="$param"> <input type="submit" value="Сохранить">
	</form>
EOF;*/

	return;
}
//$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));

$conf['tpl']['sity'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}users_sity WHERE id=". (int)($conf['user']['sess']['sity_id'] ?: $conf['user']['sity_id'])), 0);
$links = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_point WHERE sity_id=". (int)$conf['tpl']['sity']['id']. " ORDER BY id DESC LIMIT 5"));
$src = array('gallery-img01-thumbs.jpg', 'gallery-img02-thumbs.jpg', 'gallery-img03-thumbs.jpg', 'gallery-img04-thumbs.jpg');

?>
<!-- [settings:foto_lightbox] -->
<div>
	<ul class="thumbs noscript">
		<? for($i=0; $i<=4; $i++): ?>
			<li<?=($i == 4 ? ' class="mr"' : '')?>>
				<? if($v = $links[$i]): ?>
					<? if($v['type']): ?>
						<div>
							<a target=_blank href="<?=$v['link']?>" title="<?=$v['name']?>">
							<img alt="" src="/ymaps:img/<?=$v['id']?>/tn:point/w:227/h:105/c:1/null/img.jpg" />
							</a>
						</div>
					<? else: ?>
						<div style="margin:5px;">
							<div style="font-weight:bold; margin:3px; text-align:center;">
								<? if($v['uid'] > 0): ?><a href="/users/<?=$v['uid']?>"><? endif; ?>
									<?=$v['name']?>
								<? if($v['uid'] > 0): ?></a><? endif; ?>
							</div>
							<? if($v['img']): ?>
								<div style="float:right;">
									<a href="<?=$v['link']?>">
										<img src="/ymaps:img/<?=$v['id']?>/tn:point/w:70/h:70/c:1/null/img.jpg" style="border:0px;">
									</a>
								</div>
							<? endif; ?>
							<div style="font-style:italic;"><?=$v['description']?></div>
							<a href="<?=$v['link']?>"><?=$v['link']?></a>
						</div>
					<? endif; ?>
				<? else: ?>
					<div id="gallery">
						<a class="thumb" name="leaf" href="/themes/w:600/h:500/c:1/null/img/gallery-img0<?=($i+1)?>-thumbs.jpg" title="Title #0">
						<img alt="" src="/themes/w:227/h:105/c:1/null/img/gallery-img0<?=($i+1)?>-thumbs.jpg" />
						</a>
					</div>
				<? endif; ?>
			</li>
		<? endfor; ?>
	</ul>
</div>
