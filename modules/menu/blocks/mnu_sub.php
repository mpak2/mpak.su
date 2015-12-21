<?

$tpl['menu_index'] = qn("SELECT * FROM {$conf['db']['prefix']}{$arg['modname']}_index");

?>
<? if($menu_index = rb($tpl['menu_index'], "href", array_flip(array($_SERVER['REQUEST_URI'])))): ?>
	<ul class="left_column">
		<? foreach(rb($tpl['menu_index'], "index_id", "id", $menu_index['id']) as $index): ?>
			<li class="AJAX_BUTTON" module="client_create">
				<a href="<?=$index['href']?>">
					<b><?=$index['name']?></b><img src="/<?=$arg['modname']?>:img/<?=$index['id']?>/tn:index/fn:img/w:40/h:40/null/img.jpg">
				</a>
			</li>
		<? endforeach; ?>
	</ul>
<? endif; ?>
