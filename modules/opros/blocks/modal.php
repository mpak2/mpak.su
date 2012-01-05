<? die; # Нуль

if ((int)$arg['confnum']){
/*	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

echo <<<EOF
	<form method="post">
		<input type="text" name="param" value="$param"> <input type="submit" value="Сохранить">
	</form>
EOF;*/

	return;
}//$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));
//if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){};

//$dat = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} LIMIT 10")); //$dat

?>

<script type="text/javascript" src="/include/jquery/simplemodal-demo-basic/js/jquery.simplemodal.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('#calling').click(function(){
			$('.opros').modal();
//			$.get("/opros2/1/null", {}, function(data){
//				alert(data);
//				$('.opros').html(data);
//			});
		});
	});
</script>
<div class="opros" style="display:none; border:1px solid red;">
	блаблабла
</div>
<a id="calling" onClick="javascript: return false;">
	<img src="/themes/null/img/banner_manager_calling.jpg">
</a>
