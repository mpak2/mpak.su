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

if(array_key_exists($arg['modname'], $_GET['m'])){
	$href = mpql(mpqw($sql = "SELECT *, (@max:=100) AS max, SUBSTR(description, 1, @max) AS description, CHAR_LENGTH(description) AS strlen FROM {$conf['db']['prefix']}{$arg['modpath']}_href WHERE id%2=". (int)$param. ($_GET['id'] ? " AND index_id=". (int)$_GET['id'] : ""). " ORDER BY id DESC LIMIT 5"));//  echo $sql;
}

?>
<? if($href): ?>
	<ul>
		<? foreach($href as $k=>$v): ?>
			<li style="text-align:center;">
				<a target=blank href="<?=$v['href']?>">
					<div>
						<img src="/<?=$arg['modname']?>:img/<?=$v['id']?>/tn:href/w:120/h:100/null/img.jpg" style="padding:5px; border:1px solid #ddd;">
					</div>
					<?=$v['name']?>
				</a>
			</li>
		<? endforeach; ?>
	</ul>
<? endif; ?>