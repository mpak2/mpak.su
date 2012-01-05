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
/*
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
*/

$mod = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}modules WHERE id=". (int)$param['modules_id']), 0);
if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_FILES['csv']){
	
    function fuck_csv($file_name, $separator=';', $quote='"') {
    // Загружаем файл в память целиком
    $f=fopen($file_name,'r');
    $str=fread($f,filesize($file_name));
    fclose($f);
    
  //  print_r($str);
 
    // Убираем символ возврата каретки
    $str=trim(str_replace("\r",'',$str))."\n";
 
    $parsed=Array();    // Массив всех строк
    $i=0;               // Текущая позиция в файле
    $quote_flag=false;  // Флаг кавычки
    $line=Array();      // Массив данных одной строки
    $varr='';           // Текущее значение
 
    while($i<=strlen($str)) {
        // Окончание значения поля
        //echo $str[$i];
        if ($str[$i]==$separator && !$quote_flag) {
            $varr=str_replace("\n","\r\n",$varr);        
            $line[]=$varr;
            $varr='';
        }
        // Окончание строки
        elseif ($str[$i]=="\n" && !$quote_flag) {
            $varr=str_replace("\n","\r\n",$varr);         
            $line[]=$varr;
            $line[0] = iconv("CP1251", "UTF-8",  $line[0]);
            $varr='';
            $parsed[]=$line;
            $line=Array();
        }
        // Начало строки с кавычкой
        elseif ($str[$i]==$quote && !$quote_flag) {
            $quote_flag=true;
        }
        // Кавычка в строке с кавычкой
        elseif ($str[$i]==$quote && $str[($i+1)]==$quote && $quote_flag) {
            $varr.=$str[$i];
            $i++;
        }
        // Конец строки с кавычкой
        elseif ($str[$i]==$quote && $str[($i+1)]!=$quote && $quote_flag) {
            $quote_flag=false;
        }
        else {
            $varr.=$str[$i];          
        }
        $i++;
    }
    return $parsed;
} 
    
    $data_file = fuck_csv($_FILES['csv']['tmp_name']);
  
    
  //  $f = fopen($_FILES['csv']['tmp_name'], "r");    

	//while (($data = fgetcsv($f, 0,';','' )) !== false){

      //  if($data[0]=='') continue;
      //  $data[0] = iconv("CP1251", "UTF-8",  $data[0]);
        
    //   print_r($data);
    
    foreach($data_file as $data){
        
        if($data[0] == '') continue;
       
		if($tmp_line++ == 0){
			$fn = array("Имя"=>"name", "Единица измерения"=>"ediz", "Цена"=>"price");
			continue;
		} $v = array_combine($fn, $data);
             
     //  print_r($v);
        
		if(empty($v['ediz']) && !$v['price'] && $v['name']){
			$type_id = mpfdk("{$conf['db']['prefix']}price_type",
				array("name"=>trim($v['name'])),
				array("name"=>trim($v['name']))
			);
			 continue;
		} $v+=array("type_id"=>$type_id);
//mpre($v);

		mpfdk("{$conf['db']['prefix']}price_index", array("name"=>$v['name']), $v, $v);
        
        
		echo "<div>Добавлено: {$v['name']}</div>";
	} exit;
};


if($_GET['action'] == 'delete_all'){
$delete_product =  mpqw("DELETE FROM {$conf['db']['prefix']}price_index " );
$delete_category =  mpqw("DELETE FROM {$conf['db']['prefix']}price_type " );

if($delete_category == true && $delete_product == true){
  echo "<div>Список товаров и категорий очищен</div>";
} else {   
  echo "<div>Произошла ошибка при удалении</div>";  
}
  
    exit;
}

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
            
            $("#delete_all").click(function(){
               $.get("/blocks/<?=$arg['blocknum']?>/", { action : "delete_all"}, function(data){
     $("#mylog").html(data).css("margin", "10px");
     if(data == '<div>Список товаров и категорий очищен</div>'){
         $("ul.priceList").remove();  
         $("#list_cat").html('');  
             
     }
    });
            });
            
		});
        
	</script>
	<form id="csv" method="post" action="/blocks/<?=$arg['blocknum']?>/null" enctype="multipart/form-data">
		<div>
			<input name="csv" type="file">
		</div>
		<div>
<!--			<select name="tn" id="tn_<?=$arg['blocknum']?>">
				<option></option>
				<? foreach($list as $k=>$v): ?>
					<option value="<?=$v["Tables_in_{$conf['db']['name']}"]?>"><?=$v["Tables_in_{$conf['db']['name']}"]?></option>
				<? endforeach; ?>
			</select>-->
			<input type="submit" id="csv_<?=$arg['blocknum']?>" value="CSV">
			<input type="reset" value="Очистить">
            <input type="button" id="delete_all" value="Удалить" >
		</div>
	</form>
	<div id="mylog"></div>
