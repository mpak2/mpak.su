<? # Приглашенные

if(array_key_exists('confnum', $arg)){
/*	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

echo <<<EOF
	<form method="post">
		<input type="text" name="param" value="$param"> <input type="submit" value="Сохранить">
	</form>
EOF;*/

	return;
}
//$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));

$cnt = (int)mpql(mpqw("SELECT COUNT(*) AS cnt FROM {$conf['db']['prefix']}users WHERE refer=". (int)$arg['uid']), 0, 'cnt');

?>

	<div class="infoTable">
		<div class="officeInfoTable" style="background-color: rgb(245, 248, 236);">
		<div class="rcolumnCounters">
		<span id="ctl00_cphMain_L_All">Всего</span>
		</div>
		<div class="lcolumn" style="margin-right: 60px;">
		<span id="ctl00_cphMain_L_Partition2">Раздел</span>
		</div>
	</div>
	<div class="officeInfoTable" style="background-color: rgb(255, 255, 255);">
		<div class="rcolumnCounters ">
		<span id="ctl00_cphMain_ctlTruckStats_lblStatsValue">&nbsp;<?=$cnt?>&nbsp;</span>
		</div>
		<div class="lcolumn suDarkYellowBG" style="margin-right: 60px;">
		<a id="ctl00_cphMain_HL_YourTrucks" href="../EditPages/OwnItems.aspx?EntityType=Truck">Приглашенных рефералов</a>
		</div>
	</div>
	<div id="ctl00_cphMain_hiddenClaimAndRecomendationLinks">
		<div class="officeInfoTable" style="background-color: rgb(255, 255, 255);">
		<div class="rcolumnCounters">
		<span id="ctl00_cphMain_lblFirmRecommedationQuantity">
			
		</span>
		</div>
		<div style="margin-right: 60px;">
			Реферальная ссылка: <a href="http://<?=$_SERVER['HTTP_HOST']?>/<?=$arg['modpath']?>:reg/refer:<?=$arg['uid']?>">
				http://<?=$_SERVER['HTTP_HOST']?>/<?=$arg['modpath']?>:reg/refer:<?=$arg['uid']?>
			</a>
		</div>
		</div>
	</div>   
