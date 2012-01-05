<? die; # Нуль

if ((int)$arg['confnum']){
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

?>
	<form method="post">
		<div>
			<select name="param">
				<option value="0" >Четные</option>
				<option value="1" <?=($param%2 ? "selected" : "")?>>Нечетные</option>
			</select>
		</div>
		<div><input type="submit" value="Сохранить"></div>
	</form>
<?

	return;
} $param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));
//$uid = $_GET['id'] && array_key_exists('users', $_GET['m']) ? $_GET['id'] : $conf['user']['id'];
//if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){};

if(!array_key_exists($arg['modname'], $_GET['m'])){
	$regions = mpql(mpqw($sql = "SELECT *, (@max:=100) AS max, SUBSTR(description, 1, @max) AS description, CHAR_LENGTH(description) AS strlen FROM {$conf['db']['prefix']}{$arg['modpath']}_index ORDER BY sort"));//  echo $sql;
}

?>
<? if($regions): ?>
	<div>
		<? foreach($regions as $k=>$v): if($k%2 == $param) continue; ?>
			<div>
				<div style="margin:5px; font-weight:bold; text-align:center;">
					<a href="/<?=$arg['modname']?>/<?=$v['id']?>"><?=$v['name']?></a>
				</div>
				<div>
					<div style="float:right;">
						<a href="/<?=$arg['modname']?>/<?=$v['id']?>">
							<img src="/<?=$arg['modname']?>:img/<?=$v['id']?>/tn:index/w:50/h:50/c:1/null/img.jpg">
						</a>
					</div>
					<?=strip_tags($v['description'])?>
					<? if($v['strlen'] > $v['max']): ?>...<? endif; ?>
				</div>
			</div>
		<? endforeach; ?>
	</div>
<? endif; ?>