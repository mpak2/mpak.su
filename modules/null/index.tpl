<? if($index = rb("index", "id", $_GET['id'])): ?>
	<div>
		<h1><?=$index['name']?></h1>
		<p><?=$index['text']?></p>
	</div>
<? else: ?>
	<ul>
		<? foreach(rb("index") as $index): ?>
			<li><a href="/<?=$arg['modname']?>/<?=$index['id']?>"><?=$index['name']?></a></li>
		<? endforeach; ?>
	</ul>
<? endif; ?>