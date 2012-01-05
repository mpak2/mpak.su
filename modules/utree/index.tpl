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
	<? $tree = function($tr, $tree) use($conf) { if(empty($tr[''])) return; ?>
		<li style="list-style-type:none;">
			<span style="white-space:nowrap;">
				<? if($tr['']['uid'] > 0): ?>
					<a href="/users/<?=$tr['']['uid']?>"><?=$tr['']['name']?></a>
					<b><?=$tr['']['fm']?></b> <b><?=$tr['']['im']?></b> <b><?=$tr['']['ot']?></b>
					<? if($tr['']['ref']): ?>
						<span>
							Источник: <noindex><a target=blank href="<?=$tr['']['ref']?>"><?=mpidn(urldecode($tr['']['ref']))?></a></noindex>
						</span>
					<? endif; ?>
				<? else: ?>
					<a href="/users/<?=$tr['']['uid']?>"><?=$conf['settings']['default_usr']. $tr['']['uid']?></a>
					Вход: <b><?=date('Y.m.d', $tr['']['last_time'])?></b>
					Время: <b><?=$tr['']['count_time']?></b>
					Cтраниц: <b><?=$tr['']['count']?></b>
					<? if($tr['']['ref']): ?>
						Источник: <noindex><a target=blank href="<?=$tr['']['ref']?>"><?=mpidn($tr['']['ref'])?></a></noindex>
					<? endif; ?>
				<? endif; ?>
			</span>
			<? if(count($tr) > 1): ?>
				<div class="hitarea closed-hitarea collapsable-hitarea"></div>
				<ul>
				<? foreach($tr as $k=>$v): ?>
					<?=$tree($v, $tree)?>
				<? endforeach; ?>
				</ul>
			<?// else: ?>
<!--				<?=$tr['']['fm']?> (<?=$tr['']['name']?>) <a target="blank" href="<?=$tr['']['url']?>"><?=$tr['']['site']?></a> -->
			<? endif; ?>
		</li>
	<? }; $tree($conf['tpl']['tree'], $tree); ?>
</ul>
