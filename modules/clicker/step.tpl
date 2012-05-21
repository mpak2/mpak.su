<? if($tpl['cmd']): ?>
	$("#clicker").css("left",<?=$tpl['lesson']['left']?>);
	$("#clicker").css("top",<?=$tpl['lesson']['top']?>);
	$("#clicker").show();
	<? $tree = function($cmd, $tree) use($conf, $tpl) { ?>
		$("#clicker").trigger("<?=$cmd['type']?>", {
			"<?=$cmd['type']?>":"<?=$cmd['val']?>",
			"complete":function(){
				<? if($tpl['cmd'][ $cmd['id'] ]) foreach($tpl['cmd'][ $cmd['id'] ] as $v): ?>
					<? $tree($v, $tree); ?>
				<? endforeach; ?>
			}
		});
	<? }; $tree(array_shift($tmp = $tpl['cmd'][0]), $tree); ?>
<? endif; ?>