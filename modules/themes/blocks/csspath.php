<? # Нуль

if ((int)$arg['confnum']){
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST){
		$param = array($_POST['param']=>$_POST['val'])+(array)$param;
		mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	} if(array_key_exists("null", $_GET)) exit;

	$klesh = array(
		($f = "Классы")=>($param[ $f ] = $param[ $f ] ?: 200),
		($f = "Атрибуты")=>($param[ $f ] = $param[ $f ] ?: 200),
/*		"Список"=>array(
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

} $param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));
//$uid = $_GET['id'] && array_key_exists('users', $_GET['m']) ? $_GET['id'] : $conf['user']['id'];
if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){
	if(array_key_exists("width", $_POST)){
		$param['Ширина'] = mpquot($_POST['width']);
	}else if($_POST['a']){
		$param['Атрибуты'] .= strtr($_POST['a'], array("&nbsp;"=>""));
	}else if($_POST['c']){
		$param['Классы'] .= strtr($_POST['c'], array("&nbsp;"=>""));
	}else{
		$param[ $_POST['name'] ] = $_POST['val'];
	} mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".mpquot(serialize($param))."' WHERE id = ". (int)$arg['blocknum']);
	die($arg['blocknum']);
};

//$dat = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} LIMIT 10"));

?>
<script src="/include/jquery/my/jquery.klesh.js"></script>
<script>
	$(function(){
		jQuery.fn.reverse = function() {
			return this.pushStack(this.get().reverse(), arguments);
		};

		$(".csspath").appendTo("html");
		$("div,li,img,a").not(".csspath").not(".csspath div").not(".csspath li").not(".csspath img").not(".csspath a").click(function(event){
			var width = $(".csspath").css("width"); var w = $(".csspath").attr("w");
			if(width != w){
				var c  = $(".csspath .class").not(".new").text().split(".");// console.log(c);
				var a = $(".csspath .attr").not(".new").text().split(".");
				$(".csspath .new").text(""); $(".csspath .info").text("");

				$(event.target).parents().reverse().each(function(key, val){
//					console.log(key, val);
					var attr = {};
					var exceptions = ["id", "class", "style"];
					for(var i=0;i<(attrs = val.attributes).length;i++) {
						if($.inArray(attrs[i].nodeName, exceptions) >= 0) continue;
						attr[attrs[i].nodeName] = attrs[i].nodeValue;
					}// console.log(attr);
					$.each(attr, function(key, val){
						if($.inArray(key, a) < 0){
							$("<span>").html("."+key).css("color", "blue").click(function(){
								var c = $(this).html();// alert(c);
								$.post("/blocks/<?=$arg['blocknum']?>/null", {a:c}, $.proxy(function(data){
									if(isNaN(data)){ alert(data) }else{
										$(this).remove();
									}
								}, this));
							}).appendTo(".csspath .new.attr");
						}
					});

					var span = "";
					$.each(c, function(k, v){
						if($(val).is("."+v)){
							span += "."+v;
						}
					});
					if(span != ""){
						$("<span>").html(span).appendTo(".csspath .info");
					}else if(typeof($(val).attr("class")) != "undefined"){
						$.each($(val).attr("class").split(/\s+/), function(){
							$("<span>").html("."+this).css("color", "blue").click(function(){
								var c = $(this).html();// alert(c);
								$.post("/blocks/<?=$arg['blocknum']?>/null", {c:c}, $.proxy(function(data){
									if(isNaN(data)){ alert(data) }else{
										$(this).remove();
									}
								}, this));
							}).appendTo(".csspath .new.class");
						});
					}
					$.each(attr, function(key, val){
						if($.inArray(key, a) >= 0){
							$("<span>").html("["+key+"="+val+"]").appendTo(".csspath .info");
						}
					}); $(".csspath .info").append("&nbsp;");
					
				});
				var tagName = event.target.tagName.toLowerCase();
				$("<span>").html(tagName).appendTo(".csspath .info");
				if(typeof($(event.target).attr("class")) != "undefined"){
					$.each($(event.target).attr("class").split(/\s+/), function(){
						$("<span>").html("."+this).appendTo(".csspath .info");
					});
				}
				event.stopPropagation();
				return false;
			}
		});
		$(".kle").klesh("/blocks/<?=$arg['blocknum']?>/null");
		$(".csspath a.del").on("click", function(){
			var width = $(".csspath").css("width"); var w = $(".csspath").attr("w");
			if(w == width){
				$(".csspath").css("width", width = "");
			}else{
				$(".csspath").css("width", width = w);
			}// alert(width);
			$.post("/blocks/<?=$arg['blocknum']?>/null", {width:width}, function(data){
				if(isNaN(data)){ alert(data) }
			});
		});
		if(<?=(int)$param['Ширина']?> > 0){
			$(".csspath").css("width", "<?=(int)$param['Ширина']?>px");
		}
	});
</script>
<div class="csspath" w="120px">
	<style>
		.csspath {position:fixed; left:10px; top:20px; min-height:50px; z-index:999999999999; background-color:white; overflow:hidden;}
	</style>
	<div>
		<a class="del" href="javascript:" style="float:right;"><img src="/img/del.png"></a>
	</div>
	<div>
		<b>Классы:</b>:
		<div class="kle class" name="Классы"><?=$param['Классы']?></div>
	</div>
	<div>
		<b>Атрибуты</b>:
		<div class="kle attr" name="Атрибуты"><?=$param['Атрибуты']?></div>
	</div>
	<div class="new class">&nbsp;</div>
	<div class="new attr">&nbsp;</div>
	<div class="info">&nbsp;</div>
</div>
