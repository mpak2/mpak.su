<? die; # Нуль

if ((int)$arg['confnum']){
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

	$mosaic = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index"));

?>
	<form method="post">
		<!-- <input type="text" name="param[id]" value="{$param['id']}"> <input type="submit" value="Сохранить"> -->
		<div><?=$mosaic[ $param['index_id'] ]['name']?></div>
		<div style="margin:3px 0;">
			<select name="param[index_id]">
				<? foreach($mosaic as $k=>$v): ?>
					<option value="<?=$v['id']?>" <?=$v['id'] == $param['index_id'] ? "selected" : ""?>>
						<?=$v['name']?>
					</option>
				<? endforeach; ?>
			</select>
		</div>
		<span><input type="submit" value="Сохранить"></span>
	</form>
<?

	return;
} $param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));
//if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){};

$index = mpql(mpqw("SELECT id.*, s.id AS sid FROM {$conf['db']['prefix']}{$arg['modpath']}_index AS id LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_slide AS s ON id.id=s.index_id WHERE id.id=". (int)($param['index_id'] ?: 1) . ($_GET['slide_id'] ? " AND s.id=". (int)$_GET['slide_id'] : " ORDER BY RAND()")), 0);

$region = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_region WHERE slide_id=". (int)$index['sid']), 'x', 'y');

$width = $index['w']*($index['width']+$index['margin']+4);
$height = $index['h']*($index['height']+$index['margin']+4);

?>
<style>
	.kub_<?=$arg['blocknum']?> {
		width:<?=$index['width']?>px;
		height:<?=$index['height']?>px;
		border:1px solid #ddd;
		float:left;
		margin:0 0 <?=$index['margin']?>px <?=$index['margin']?>px;
		border-radius:5px;
		background-image: url(/<?=$arg['modpath']?>:img/<?=$index['sid']?>/tn:slide/w:<?=$width?>/h:<?=$height?>/c:1/null/img.jpg);
		position:relative;
	}
</style>

<div style="width:<?=$width?>px; height:<?=$height?>">
	<? for($h=0; $h<$index['h']; $h++): ?>
		<? for($w=0; $w<$index['w']; $w++): ?>
			<? if(($r = $region[$h][$w]) && $r['href']): ?>
				<a href="<?=$r['href']?>" title="<?=$r['title']?>">
			<? endif; ?>
			<div
				class="kub_<?=$arg['blocknum']?>"
				style="
					<? if(!$w): ?>clear:both;<? endif; ?>
					<? if($r): ?>
						background-color: <?=$r['bgcolor']?>;
						background-image: url(/<?=$arg['modpath']?>:img/<?=$r['id']?>/tn:region/w:<?=$index['width']?>/h:<?=$index['height']?>/c:1/null/img.jpg);
					<? else: ?>
						background-position: -<?=($w*($index['width']+4))?>px -<?=($h*($index['height']+4))?>px;
					<? endif; ?>"
			>
			<? if($_GET['slide_id']): ?>
				<div style="position:absolute; left:3px; top:3px; margin:5px; background-color:#eee;">
					<?=$h?> <?=$w?>
				</div>
			<? endif; ?>
			<? if($index['name']): ?>
				<div style="color:white; font-size:110%; font-weight:bold; text-align:center; bottom:50%; position:absolute; width:<?=$index['width']?>px;"><?=$r['name']?></div>
			<? endif; ?>
			</div>
			<? if($r && $r['href']): ?>
				</a>
			<? endif; ?>
		<? endfor; ?>
	<? endfor; ?>
</div>
