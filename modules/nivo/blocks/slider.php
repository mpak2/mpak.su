<? die; # Заголовка блока
################################# php код #################################

//if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){};

$tpl['index'] = qn("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index");

################################# верстка ################################# ?>
<script src="/<?=$arg['modname']?>:jquery.nivo.slider/null/script.js"></script>
<script>
	$(function(){
		$('.nivo_index').nivoSlider();
	});
</script>
<div class="nivo_index" style="width:600px; height:400px;">
	<? foreach($tpl['index'] as $index): ?>
		<img src="/<?=$arg['modname']?>:img/<?=$index['id']?>/tn:index/fn:img/w:600/h:400/c:1/null/img.png">
	<? endforeach; ?>
</div>
