<? die; # Заголовка блока
################################# php код #################################

//if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){};

$tpl['index'] = qn("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index");

################################# верстка ################################# ?>
<ul>
	<? foreach($tpl['index'] as $index): ?>
		<li style="float:left; margin-right:3px;">
			<a target="blank" href="<?=$index['href']?>" title="<?=$index['description']?>">
				<img src="/<?=$arg['modname']?>:img/<?=$index['id']?>/tn:index/fn:img/w:32/h:32/c:1/null/img.png" style="border-radius:8px;">
			</a>
		</li>
	<? endforeach; ?>
</ul>