<ul>
	<? foreach($conf['tpl']['modules'] as $n=>$m): ?>
	<? if(
		($arg['access'] > 0 && ($if = mpopendir("modules/{$m['folder']}/index.php"))) ||
		($arg['access'] > 3 && $af = mpopendir("modules/{$m['folder']}/admin.php"))
	): ?>
	<li style="margin:5px;">
		<? if(!empty($if)): ?>
			<a href="/<?=$m['folder']?>"><?=$m['name']?> (<?=($m['description'])?>)</a>
		<? endif; ?>
		<? if($arg['access'] > 3 && $af): ?>
			<? if(empty($if)): ?>
				<?=$m['description']?>
			<? endif; ?>
			<a href="/?m[<?=$m['folder']?>]=admin">
				<img src="/img/block_conf.png">
			</a>
		<? endif; ?>
	</li>
	<? endif; ?>
	<? endforeach; ?>
</ul>