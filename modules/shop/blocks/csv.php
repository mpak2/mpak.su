<? # Нуль

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
		<div>
			<span><input type="submit" value="Сохранить"></span>
		</div>
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

//$vendor = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}price_vendor"), 'name');
//$type = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}price_type ORDER BY sort"), 'name');

$mod = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}modules WHERE id=". (int)$param['modules_id']), 0);
if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_FILES){
		if($_POST['price_id'] || !empty($_FILES['csv']['tmp_name'])){
			if($_POST['price_id']){
				$price = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_price WHERE id=". (int)$_POST['price_id']), 0);
				$fn = mpopendir("include/". $price['csv']);
			}elseif($_FILES['csv']['tmp_name']){

				$name = implode('.', array_slice($tmp = explode(".", basename($_FILES['csv']['name'])), 0, count($tmp)-1));
				$_POST['price_id'] = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_price",
					array("name"=>$name),
					array("time"=>time(), 'season'=>$_POST['season'], "name"=>$name, 'premium'=>1),
					array("time"=>time(), 'season'=>$_POST['season'])
				);
				if($_POST['price_id']){
					if($fn = mpfn("{$conf['db']['prefix']}{$arg['modpath']}_price", "csv", $_POST['price_id'], null, array('text/csv'=>'.csv', 'text/comma-separated-values'=>'.csv'))){
						mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']}_price SET csv=\"". mpquot($fn). "\" WHERE id=". (int)$_POST['price_id']);
					}else{
//						mpre($_FILES);
					}
				}else{
					echo "<br />Ошибка загрузки файл не найден";
				} $fn = mpopendir("include/". $fn);
			}else{
				echo "<br />Ошибка загрузки данные не верны"; exit;
			} $f = fopen($fn, "r");
			while (($data = fgetcsv($f, 0, ";", "\"")) !== false){
				if($tmp_line++ == 0){
					$fn = array("Непонятно"=>"0", "Непонятно2"=>"1", "Производитель"=>"vendor", "Наименование"=>"name", "Ценовая группа"=>"group", "Остаток"=>"balance", "Цена"=>"price");
				}
				$min = min(count($fn), count($data)); # Минимальное количество полей в таблице и в файле данных
				$v = array_combine(array_values(array_slice($fn, 0, $min)), array_slice($data, 0, $min));// +array("price_id"=>$_POST['price_id']);


				if($v['name'] = trim($v['name'])){
					echo "<div><span style='color:green;'>Загружено</span>: {$v['name']}</div>";
					mpre($v);

/*					$v['price'] = str_replace(",", ".", $v['price']);
					$v += array("w"=>$reg[1], "ot"=>$reg[2], "d"=>$reg[3]);

					if(preg_match("/(.*)(". strtr(implode("|", array_keys($vendor)), array("/"=>"\/")). ")(.*)/", $v['name'], $reg)){
						$v['vendor_id'] = $vendor[ $reg[2] ]['id'];
					}

					if(preg_match("/(.*)(". strtr(implode("|", array_keys($type)), array("/"=>"\/")). ")(.*)/", $v['name'], $reg)){
						$v['type_id'] = $type[ $reg[2] ]['id'];
					}

					if(preg_match("/(.*)(". strtr(implode("|", array_keys($type)), array("/"=>"\/")). ")(.*)/", $v['name'], $reg)){
						$v['type_id'] = $type[ $reg[2] ]['id'];
					}*/

					$v['vendor_id'] = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_vendor", $w = array("name"=>$v['vendor']), $w);
					$v['groups_id'] = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_groups", $w = array("name"=>$v['group']), $w);

					mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_index", array('name'=>$v['name'], 'index'=>$v['index']), $v, $v);
					echo "<br />". $v['name'];
				}
			} echo "<br />Загрузка завершена";
		}
	exit;
};

$price = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_price ORDER BY time DESC"));

//$list = mpql(mpqw($sql = "SHOW TABLES WHERE Tables_in_{$conf['db']['name']} LIKE \"". mpquot($conf['db']['prefix']. $mod['folder']). "_%\""));

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
			$("select[name='price_id']").change(function(){
				val = $(this).find("option:selected").val();// alert(val);
				if(val > 0){
					$("#file_<?=$arg['blocknum']?>").hide(300);
				}else{
					$("#file_<?=$arg['blocknum']?>").show(300);
				}
			}).change();
		});
	</script>
	<form id="csv" method="post" action="/blocks/<?=$arg['blocknum']?>/null" enctype="multipart/form-data">
		<div style="margin-bottom:5px;">
			<select name="price_id" style="width:100%;">
				<? foreach($price as $k=>$v): ?>
					<option value="<?=$v['id']?>"><?=date('Y.m.d H:i:s', $v['time'])?> <?=$v['name']?></option>
				<? endforeach; ?>
				<option value="0">Загрузить</option>
			</select>
		</div>
		<div id="file_<?=$arg['blocknum']?>" style="margin-bottom:5px; display:none;">
			<input name="csv" type="file">
		</div>
		<div>
			<div style="float:right;">
<!--				<span><a href="/цены/drop"><input type="button" value="Обнулить" onClick="javascript: return confirm('Удалить все данные с базы?');"></a></span>-->
				<span><input type="submit" value="Загрузить"></span>
			</div>
		</div>
	</form>
	<div id="mylog"></div>
