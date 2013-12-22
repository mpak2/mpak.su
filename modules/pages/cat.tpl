<div>
	<? if($cat = $tpl['cat'][ $_GET['id'] ]): ?>
		<h2><?=$cat['name']?></h2>
		<? if($cat['cat_id']): ?>
			<div class="bradcrumbs">
				<? $function = function($cat) use(&$function, $tpl, $arg){ ?>
					<? if($c = $tpl['cat'][ $cat['cat_id'] ]): ?>
						<? $function($c) ?>
					<? endif; ?> &raquo; <a href="/<?=$arg['modname']?>:cat/<?=$cat['id']?>"><?=$cat['name']?></a>
				<? }; $function($tpl['cat'][ $cat['cat_id'] ]); ?>
			</div>
		<? endif; ?>
		<? if(rb($tpl['cat'], "cat_id", "id", $cat['id'])): ?>
			<ul>
				<? foreach(rb($tpl['cat'], "cat_id", "id", $cat['id']) as $scat): ?>
					<li><a href="/pages:cat/<?=$scat['id']?>"><?=$scat['name']?></a></li>
				<? endforeach; ?>
			</ul><br /><br />
		<? endif; ?>
	<? endif; ?>
	<? foreach(rb($tpl['cat'], "id", "id", ($_GET['id'] ?: $tpl['cat'])) as $cat): ?>
		<div>
			<ul>
				<? foreach(rb($tpl['index'], "cat_id", "id", $cat['id']) as $index): ?>
					<li><a href="/<?=$arg['modname']?>/<?=$index['id']?>"><?=$index['name']?></a></li>
				<? endforeach; ?>
			</ul>
		</div>
	<? endforeach; ?>
</div>
