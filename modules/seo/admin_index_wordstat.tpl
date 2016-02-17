<div>
	<? if(get($_POST, 'wordstat')): ?>
		<div class="table">
			<? foreach(explode("\t", $_POST['wordstat']) as $line): ?>
				<? if(count($ln = explode("\n", $line)) == 2): ?>
					<div>
						<span style="font-weight:bold;"><?=strtr($ln[1], array(" "=>"-", "+"=>""))?>.рф</span>
						<span><?=$ln[0]?></span>
					</div>
				<? endif; ?>
			<? endforeach; ?>
		</div>
	<? else: ?>
		<form method="post">
			<p><textarea name="wordstat" style="width:100%;height:200px;"></textarea></p>
			<p><button>Создать</button></p>
		</form>
	<? endif; ?>
</div>
