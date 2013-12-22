<? if($index = $tpl['index'][ $_GET['id'] ]): ?>
	<h1><?=$index['name']?></h1>

	<? if($index['index_id']): ?>
		<div class="navigation">
			<? $function = function($index) use(&$function, $tpl, $arg){ ?>
				<? if($c = $tpl['index'][ $index['cat_id'] ]): ?>
					<? $function($c) ?>
				<? endif; ?> &raquo; <a href="/<?=$arg['modname']?>:cat/<?=$index['id']?>"><?=$index['name']?></a>
			<? }; $function($tpl['index'][ $index['index_id'] ]); ?>
		</div>
	<? endif; ?>

	<ul>
		<? foreach(rb($tpl['index'], "index_id", "id", $index['id']) as $id): ?>
			<li class="company-menu">
                <a href="<?=$id['href']?>"><?=$id['name']?></a>
			</li>
		<? endforeach; ?>
	</ul>
<? endif; ?>