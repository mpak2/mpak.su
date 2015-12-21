<? # Заголовка блока
################################# php код #################################

//if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){};

$tpl['index'] = qn("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index");

################################# верстка ################################# ?>
<script src="/<?=$arg['modname']?>:jquery.nivo.slider/null/script.js"></script>
<link href="/<?=$arg['modname']?>:nivo-slider/null/style.css" type="text/css" rel="stylesheet" />
<style>
/*	.nivo_index .nivoNav {display:none;}*/
</style>
<script>
	$(function(){
		$('.nivo_index').nivoSlider({nav:false});
	});
</script>
<div class="nivo_index" style="width:700px; height:400px;">
	<? foreach($tpl['index'] as $index): ?>
		<img src="/<?=$arg['modname']?>:img/<?=$index['id']?>/tn:index/fn:img/w:700/h:400/c:1/null/img.png">
	<? endforeach; ?>
</div>
