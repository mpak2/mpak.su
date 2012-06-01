<? die; # Нуль

if ((int)$arg['confnum']){
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST){
		$param = array($_POST['param']=>$_POST['val'])+(array)$param;
		mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	} if(array_key_exists("null", $_GET)) exit;

	$klesh = array(
/*		"Количество символов"=>0,
		"Курс доллара"=>30,
		"Список"=>array(
			1=>"Одын",
			2=>"Два",
		),
		"Город"=>spisok("SELECT id, name FROM {$conf['db']['prefix']}users_sity ORDER BY name"),*/
	);

?>
		<!-- Настройки блока -->
	<script src="/include/jquery/my/jquery.klesh.select.js"></script>
	<script>
		$(function(){
			<? foreach($klesh as $k=>$v): ?>
				<? if(gettype($v) == 'array'): ?>
					$(".klesh_<?=strtr(md5($k), array("="=>''))?>").klesh("/?m[blocks]=admin&r=mp_blocks&null&conf=<?=$arg['confnum']?>", function(){
					}, <?=json_encode($v)?>);
				<? else: ?>
					$(".klesh_<?=strtr(md5($k), array("="=>''))?>").klesh("/?m[blocks]=admin&r=mp_blocks&null&conf=<?=$arg['confnum']?>");
				<? endif; ?>
			<? endforeach; ?>
		});
	</script>
	<div style="margin-top:10px;">
		<? foreach($klesh as $k=>$v): ?>
			<div style="overflow:hidden;">
				<div style="width:200px; float:left; padding:5px; text-align:right; font-weight:bold;"><?=$k?> :</div>
				<? if(gettype($v) == 'array'): ?>
					<div class="klesh_<?=strtr(md5($k), array("="=>''))?>" param="<?=$k?>"><?=$v[ $param[$k] ]?></div>
				<? else: ?>
					<div class="klesh_<?=strtr(md5($k), array("="=>''))?>" param="<?=$k?>"><?=($param[$k] ?: $v)?></div>
				<? endif; ?>
			</div>
		<? endforeach; ?>
	</div>
<? return;

}//$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));
//$uid = $_GET['id'] && array_key_exists('users', $_GET['m']) ? $_GET['id'] : $conf['user']['id'];
//if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){};

$lesson = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}"));

?>
<script>
	$(function(){
		function step(lesson_id){
			$.post("/<?=$arg['modpath']?>:step/next:true/null", {lesson_id:lesson_id}, function(data){
				if(isNaN(data)){
					if(data){ /*alert(data);*/
						$("body").bind("mousedown", function(){
							if(timeout_id != "undefined") clearTimeout(timeout_id);
							$("body").unbind("mousedown");
							$.post("/<?=$arg['modpath']?>:step/next:true/null", {lesson_id:-1}, function(data){
								$("#clicker").animate({"opacity":0}, 1000, function(){
									$("#clicker").hide().css("opacity", 1);
								});
							});
						});
						$.globalEval(data);
					}
				}
			});
		} step(0);
		$("#lesson_<?=$arg['blocknum']?> li").click(function(){
			lesson_id = $(this).attr("lesson_id"); console.log("Выбран урок:"+lesson_id);
			step(lesson_id);
		});
	});
</script>
<? if($arg['access'] > 1): ?>
	<ul id="lesson_<?=$arg['blocknum']?>">
		<? foreach($lesson as $v): ?>
			<li lesson_id="<?=$v['id']?>"><a href="javascript: return false;"><?=$v['name']?></a></li>
		<? endforeach; ?>
	</ul>
<? endif; ?>
<div id="clicker" style="display:none; position:absolute; left:100px; top:100px;">
	<div class="click" style="display:none; position:absolute; left:-30px; padding:5px; color:white; font-weight:bold; border-radius:10px; background-color:red;">клик</div>
	<div class="alert" style="display:none; border-radius:5px; position:absolute; left:35px; top:0; margin:0px; padding:5px; color:red; font-weight:bold; width:200px; background-color:white; min-height:100px; border:1px solid gray; text-align:left;">текст</div>
	<img src="data:image/jpeg;base64, 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">
	<script>
		$(function(){
			function show(complete){
				txt = $("#clicker").find(".alert").attr("text");// console.log(txt);
				link = $("#clicker").find(".alert").attr("link");// console.log(txt);
				text = $("#clicker").find(".alert").text(); console.log(text);
				
				if(txt.length > text.length){
					n = txt.substr(0,text.length+1);// console.log(n);
					$("#clicker").find(".alert").html(link+n); 
					timeout_id = setTimeout(function(){ show(complete) }, 150);
				}else{
					setTimeout(function(){
						$("#clicker").find(".alert").animate({"opacity":0}, 1000, complete);
					} ,1000);
				}
			}
			$("#clicker").bind("click", function(data, param){
				$(main = this).find(".click").show();
				setInterval(function(){
					$(main).find(".click").hide();
					param.complete();
				}, 500);
			});
			$("#clicker").bind("run", function(data, param){
				pos = $(param.run).position();
				$(this).animate({"left":pos.left+20, "top":pos.top}, 1000, param.complete);
			});
			$("#clicker").bind("alert", function(data, param){
				$(this).find(".alert").text("").attr("link", "<a href='/?m[clicker]=admin&r=mp_clicker_cmd&where[id]="+param.id+"'><img src='/img/aedit.png'></a>&nbsp;").attr("text", param.alert).css("opacity", 1).show();
				show(param.complete);
			});
			$("#clicker").bind("location", function(data, param){
				pos = $(this).position();
				$.post("/<?=$arg['modpath']?>:step/null", {left:pos.left, top:pos.top}, function(data){
					document.location.href= param.location;
				});
			});
			$("#clicker").bind("cmd", function(data, param){
				$.globalEval(data.cmd);
				$.globalEval(data.complete);
			});
		});
	</script>
</div>