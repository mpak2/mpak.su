<? die; # Кабинет

if ((int)$arg['confnum']){
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

$refer = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE uid=". (int)$arg['uid']. " ORDER BY id DESC"));

?>
	<style>
		.infoTable { padding:0 3px; }
		.officeInfoTable { margin-top:5px;}
		.officeInfoTable .rcolumnCounters {float:right; width:40%; text-align:center;}
		.officeInfoTable .lcolumn {margin-right:50%;}
	</style>
<div style="overflow:hidden;">
<!--	<div><a href="/<?=$arg['modpath']?>">Просмотреть дерево</a></div>-->
	<div class="infoTable">
		<div class="officeInfoTable" style="background-color: rgb(245, 248, 236);">
			<div class="rcolumnCounters">
				<span>Всего</span>
			</div>
			<div class="lcolumn" style="margin-right: 60px;">
				<span>Раздел</span>
			</div>
		</div>
		<div class="officeInfoTable">
			<div class="rcolumnCounters ">
				<span><?=count($refer)?></span>
			</div>
			<div class="lcolumn suDarkYellowBG" style="margin-right: 60px;">
				Приглашенные
			</div>
		</div>
		<div>
			<div class="officeInfoTable">
				<div class="rcolumnCounters">
				<span><?=(count($refer)*500*0.2)?> р.</span>
			</div>
			<div class="lcolumn suYellowBG" style="margin-right: 60px;">
				Вознаграждение
			</div>
		</div>
		<div>
			<div class="lcolumn suYellowBG" style="margin-right: 60px;">
				Ссылка для приглашения
			</div>
			<div class="officeInfoTable" style="text-align:right;">
				<span><a href="/utree/<?=$conf['user']['uid']?>">http://<?=mpidn($_SERVER['HTTP_HOST'])?>/utree/<?=$conf['user']['uid']?></a></span>
			</div>
		</div>
	</div>   

	<div style="margin:10px 0;">
		<? foreach($refer as $k=>$v): ?>
			<div style="float:left; margin:3px;">
				<a href="/<?=$conf['modules']['users']['modname']?>/<?=$v['usr']?>">
					<img src="/<?=$conf['modules']['users']['modname']?>:img/<?=$v['usr']?>/tn:index/w:50/h:50/c:1/null/img.jpg">
				</a>
			</div>
		<? endforeach; ?>
	</div>
</div>