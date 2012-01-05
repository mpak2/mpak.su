<style>
	.br {
		line-height:30px;
		vertical-align:middle;
#		height:30px;
		text-align:center;
#		float:right;
		color:white;
		background-color:green;
		width:120px;
		-webkit-border-radius: 10px;
		-moz-border-radius: 10px;
		border-radius: 10px;
		margin-bottom:5px;
	}
</style>
<div>
	Текущая тема: <b><?=$conf['settings']['theme']?></b>
</div>
<div>
	<? foreach($conf['tpl']['themes'] as $k=>$v): ?>
	<div style="overflow:hidden; padding:10px; border-top: 1px dashed gray;">
		<? if(file_exists(mpopendir("themes/$v/screen.png"))): ?>
			<a href="/<?=$arg['modpath']?>:<?=$arg['fn']?>/theme:<?=$v?>">
				<img src="/themes/theme:<?=$v?>/null/screen.png" style="float:left; margin-right:10px; border: 0px;">
			</a>
		<? endif; ?>
		<?=$v?>
		<div style="float:right;">
			<div class="br">
				<a href="/<?=$arg['modpath']?>:<?=$arg['fn']?>/theme:<?=$v?>" style="color:white;">Смотреть</a>
			</div>
			<? if($arg['access'] > 4): ?>
				<div class="br">
					<a href="/<?=$arg['modpath']?>:edit/tm:<?=$v?>" style="color:white;">Установить</a>
				</div>
			<? endif; ?>
		</div>
	</div>
	<?  endforeach; ?>
</div>