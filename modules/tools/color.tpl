<style>
	.color {
		float:left;
		width:85px;
		height:85px;
		margin:1px;
		border:1px solid gray;
	}
	.clr {
		margin:10px;
		padding:3px;
		background-color:#fff;
		text-align:center;
		border:1px solid gray;
	}
</style>
<h1>Таблица цветов</h1>
<? foreach($conf['tpl']['cls'] as $k=>$v): ?>
	<div class="color" style="background-color:<?=$v?>">
		<div class="clr"><?=$v?></div>
	</div>
<? endforeach; ?>