<link rel="stylesheet" href="/include/jquery/treeview/jquery.treeview.css" />
<script src="/include/jquery/treeview/jquery.treeview.js" type="text/javascript"></script>
<script>
	$(function() {
		$("#tree").treeview({
			collapsed: false,
			animated: "medium",
			control:"#sidetreecontrol",
			persist: "location"
		});
	})
</script>
<ul id="tree" class="open">
	<? $func = function($v) use(&$func, $tpl, $conf, $arg){ ?>
		<li>
			<span>
				<?=$v['id']?> <?=$v['fm']?> <?=$v['im']?> (<b><?=$v['uname']?></b>)
			</span>
			<? if($tpl['tree'][ $v['id'] ]): ?>
				<ul>
					<? foreach($tpl['tree'][ $v['id'] ] as $v): ?>
						<? $func($v) ?>
					<? endforeach; ?>
				</ul>
			<? endif; ?>
		</li>
	<? }; $func($tpl['tree'][0][2]); ?>
</ul>