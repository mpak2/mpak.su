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
<?// mpre($tpl['index']) ?>
<ul id="tree" class="open">
	<? $func = function($v) use(&$func, $tpl, $conf, $arg){ ?>
		<li>
			<?=(($uid = $tpl['uid'][ $v['id'] ]) ? $uid['name'] : "Все&nbsp;Вместе")?>
			<? if($tree = rb($tpl['index'], "index_id", "id", $v['id'])): ?>
				<ul>
					<? foreach($tree as $t): ?>
						<? $func($t) ?>
					<? endforeach; ?>
				</ul>
			<? endif; ?>
		</li>
	<? }; $func(array("id"=>0)); ?>
</ul>