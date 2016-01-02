<? die; # Нуль

if ((int)$arg['confnum']){
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

	$modules = mpqn(mpqw("SELECT id, name FROM {$conf['db']['prefix']}modules ORDER BY name"));
?>
	<style> #params div { margin:5px 10px; } </style>
	<form id="params" method="post">
		<div><?=$modules[ $param['modules_id'] ]['name']?></div>
		<div>
			<select name="param[modules_id]">
				<? foreach($modules as $k=>$v): ?>
					<option value="<?=$v['id']?>" <?=($v['id'] == $param['modules_id'] ? "selected" : "")?>>
						<?=$v['name']?>
					</option>
				<? endforeach; ?>
			</select>
		</div>
<!--		<div><input type="text" name="param[modules_id]" value="<?=$param['modules_id']?>"></div>-->
		<div><input type="submit" value="Сохранить"></div>
	</form>
<?

	return;
} $param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));

function csv_to_array($input, $delimiter='|'){
	$header = null;
	$data = array();
	$csvData = str_getcsv($input, "\n");

	foreach($csvData as $csvLine){
		if(is_null($header)){
			$header = explode($delimiter, $csvLine);
		}else{
			$items = explode($delimiter, $csvLine);
			for($n = 0, $m = count($header); $n < $m; $n++){
				$prepareData[$header[$n]] = $items[$n];
			} $data[] = $prepareData;
		}
	} return $data;
} 

$mod = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}modules WHERE id=". (int)$param['modules_id']), 0);
if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){
	if($_POST['tn'] && mpql(mpqw($sql = "SHOW TABLES WHERE Tables_in_{$conf['db']['name']} LIKE \"". mpquot($conf['db']['prefix']. $mod['folder']). "%\" AND Tables_in_{$conf['db']['name']}=\"". mpquot($_POST['tn']). "\""), 0)){
		if(!empty($_FILES['csv']['tmp_name'])){
			$f = fopen($_FILES['csv']['tmp_name'], "r");
			while (($data = fgetcsv($f, 0, ",", "\"")) !== false){
				if($tmp_line++ == 0){
					$fn = $data;
					if(array_diff_key($fs = mpqn(mpqw("SHOW COLUMNS FROM ". mpquot($_POST['tn'])), 'Field'), array_flip($fn))){
						echo "Списки полей не совпадают";
						mpre(array_flip($fn));
						mpre(array_flip(array_keys($fs)));
						exit;
					}else{
						continue;
					}
				} $v = array_combine($fn, $data);
				mpfdk($_POST['tn'], array("id"=>$v['id']), $v);
			} echo "Данные обновлены";
		}else{
			header("Content-Type: text/html; charset=UTF-8");
			header("Content-type: text/csv");
			header("Content-Disposition: attachment; filename={$_POST['tn']}.csv");
			$f = fopen($csv = tempnam("/tmp", ""), 'w');
			$data = mpql(mpqw("SELECT * FROM ". mpquot($_POST['tn']). ""));
			foreach($data as $k=>$v){
				if(!$k){
					fputcsv($f, array_keys($v), ",", "\"");
				}
				foreach($v as $fd=>$z){
					if(substr($fd, -3) == '_id' && is_numeric($z)){
						$v[$fd] = mpql(mpqw($sql = "SELECT name FROM {$conf['db']['prefix']}". mpquot($mod['folder']). "_". substr($fd, 0, strlen($fd)-3). " WHERE id=". (int)$z), 0, 'name');
					}
				} fputcsv($f, $v, ",", "\"");
			} fclose($f);
			readfile($csv);
		}
	}else{
		echo "<span style=color:red>Ошибка</span>";
	} exit;
};

$list = mpql(mpqw($sql = "SHOW TABLES WHERE Tables_in_{$conf['db']['name']} LIKE \"". mpquot($conf['db']['prefix']. $mod['folder']). "_%\""));

?>
	<script type="text/javascript" src="/include/jquery/jquery.iframe-post-form.js"></script>
	<script>
		$(function(){
			$("#csv").iframePostForm({
				complete : function(data){
					$("#mylog").html(data).css("margin", "10px");
				}
			}).find("input:file").change(function(){
				filename = $(this).val().split('.').shift();// alert(filename);
				$("#tn_<?=$arg['blocknum']?>").find("option[value='"+filename+"']").attr("selected", "selected");
			});
		});
	</script>
	<form id="csv" method="post" action="/blocks/<?=$arg['blocknum']?>/null" enctype="multipart/form-data">
		<div>
			<input name="csv" type="file">
		</div>
		<div>
			<select name="tn" id="tn_<?=$arg['blocknum']?>">
				<option></option>
				<? foreach($list as $k=>$v): ?>
					<option value="<?=$v["Tables_in_{$conf['db']['name']}"]?>"><?=$v["Tables_in_{$conf['db']['name']}"]?></option>
				<? endforeach; ?>
			</select>
			<input type="submit" id="csv_<?=$arg['blocknum']?>" value="CSV">
			<input type="reset" value="Обновить">
		</div>
	</form>
	<div id="mylog"></div>
