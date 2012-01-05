<? if($v = $conf['tpl']['index'][ $_GET['id'] ]): ?>
	<h1><?=$v['name']?></h1>
	<div><?=$v['description']?></div>
	<div><?=$v['text']?></div>
	<div><?=$conf['settings']['comments']?></div>
<? elseif($_GET['cat_id']): ?>
	<ul>
		<? foreach($conf['tpl']['index'] as $k=>$v): ?>
			<li>
				<div><a href="/<?=$arg['modname']?>/<?=$v['id']?>/<?=str_replace('%', '%25', $v['name'])?>"><?=$v['name']?></a></div>
				<div style="margin:5px 0 10px 20px; font-size:80%; font-style:italic;"><?=$v['description']?></div>
			</li>
		<? endforeach; ?>
	</ul>
<? else: ?>
	<script src="/include/jquery/treeview/jquery.treeview.js" type="text/javascript"></script>
	<script src="/include/jquery/treeview/jquery.cookie.js" type="text/javascript"></script>
	<link rel="stylesheet" href="/include/jquery/treeview/jquery.treeview.css" />

	<script type="text/javascript">
		$(document).ready(function(){
			$("#black, #gray").treeview({
				persist: "cookie",
				cookieId: "treeview-black"
			});
		});
	</script>

	<div style="text-align:center; margin:10px; font-style:italic;"><!-- [settings:<?=$arg['modpath']?>_title] --></div>
	<ul class="treeview-gray treeview" id="gray">
		<? $tree = function($tr, $tree) use($conf, $arg) { if(empty($tr[''])) return; ?>
			<li>
				<span><a href="/<?=$arg['modname']?>/cat_id:<?=$tr['']['id']?>"><?=$tr['']['name']?></a> [<?=$tr['']['cnt']?>]</span>
				<? if(count($tr) > 1): ?>
					<div class="hitarea closed-hitarea collapsable-hitarea"></div>
					<ul>
						<? foreach($tr as $k=>$v): ?>
							<?=$tree($v, $tree)?>
						<? endforeach; ?>
					</ul>
				<? endif; ?>
			</li>
		<? }; $tree($conf['tpl']['tree'], $tree); ?>
	</ul>
	
<? endif; ?>
