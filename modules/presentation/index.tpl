<? if($id = $conf['tpl'][ $arg['fn'] ][ $_GET['id'] ]): ?>
		<div style="width:650px;">
			<ul style="overflow:hidden;">
				<? foreach((array)$conf['tpl']['img'][ $_GET['id'] ] as $k=>$v): ?>
					<li style="float:left; width:300px; white-space:nowrap; overflow:hidden;">
						<?=++$num?>. <a href="/<?=$arg['modname']?>/<?=$_GET['id']?>/img_id:<?=$v['id']?>" style="<?=($v['id'] == $_GET['img_id'] ? "color:black;" : "")?>">
							<?=$v['description']?>
						</a>
					</li>
				<? endforeach; ?>
			</ul>
			<h1><?=$v['name']?></h1>
			<h1 style="margin-top:15px;"><?=$id['description']?></h1>
			<div style="margin:5px;">
				<span style="float:right;"><a href="/<?=$arg['modname']?>/<?=$_GET['id']?>/img_id:<?=$conf['tpl']['next_id']?>">
					<img src="/presentation:img/w:80/h:80/null/img/forward.png">
				</a></span>
				<span>
					<a href="/<?=$arg['modname']?>/<?=$_GET['id']?>/img_id:<?=$conf['tpl']['prev_id']?>">
					<img src="/presentation:img/w:80/h:80/null/img/forward2.png">
					</a>
				</span>
			</div>
			<img src="/presentation:img/<?=$conf['tpl']['cur']['id']?>/tn:index_img/w:650/h:600/null/img.jpg">
			<div><?=$v['text']?></div>
			<?=$conf['settings']['comments']?>
		</div>
<? else: ?>
	<? foreach($conf['tpl'][ $arg['fn'] ] as $k=>$v): ?>
		<div><a href="/<?=$arg['modname']?>:<?=$arg['fn']?>/<?=$v['id']?>"><?=$v['name']?></a></div>
	<? endforeach; ?>
<? endif; ?>