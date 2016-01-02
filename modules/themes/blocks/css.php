<? # Нуль

if(array_key_exists('confnum', $arg)){
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if($_POST){
		$param = array($_POST['param']=>$_POST['val'])+(array)$param;
		mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	} if(array_key_exists("null", $_GET)) exit;

	$klesh = array(
		($f = "id")=>($param[ $f ] = $param[ $f ] ?: ""),
		($f = "class")=>($param[ $f ] = $param[ $f ] ?: ""),
		($f = "attr")=>($param[ $f ] = $param[ $f ] ?: ""),
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
	if(array_key_exists('m', $_POST)){
		$param['m'] = $_POST['m'];
	}else if(array_key_exists('width', $_POST)){
		$param['width'] = $_POST['width'];
	}else{
		$param[ $_POST['type'] ][ $_POST['name'] ] = $_POST['mode'];
	} mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".mpquot(serialize($param))."' WHERE id = ". (int)$arg['blocknum']);
	die($arg['blocknum']);
};// mpre($param);
//$dat = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} LIMIT 10"));

?>
<script src="/include/jquery/my/jquery.klesh.js"></script>
<script>
	$(function(){
		var param = <?=json_encode($param)?>; console.log(param);
		jQuery.fn.reverse = function(){
			return this.pushStack(this.get().reverse(), arguments);
		};
		$(".css a.del").on("click", function(){
			var width = $(".css").css("width"); var w = $(".css").attr("w");
			if(w == width){
				$(".css").css("width", width = "");
			}else{
				$(".css").css("width", width = w);
			}// alert(width);
			$.post("/blocks/<?=$arg['blocknum']?>/null", {width:width}, function(data){
				if(isNaN(data)){ alert(data) }
			});
		});
		if(<?=(int)$param['width']?> > 0){
			$(".css").css("width", "<?=(int)$param['width']?>px");
		} $(".css").appendTo("html");
		$("div,li,img,a").not(".css").not(".css div").not(".css li").not(".css img").not(".css a").click(function(event){
			if($(".css").css("width") != $(".css").attr("w")){
				$(".css .info").html("");
				$(event.target).parents().reverse().add(event.target).each(function(key, val){
					var click = function(){
						var post = {};
						post.type = $(this).attr("type");// alert(type);
						post.name = $(this).attr("name");// alert(name);
						var mode = $(this).attr("mode");
						if(mode < 0 || mode > 0){
							post.mode = 0;
						}else{
							var mode = $(this).parents("[mode]").attr("mode");// alert(mode);
							if(mode < 0){
								post.mode = -1;
							}else{
								post.mode = 1;
							}
						}
						$(".css .info > span[type='"+post.type+"'][name='"+post.name+"']").attr("mode", post.mode);
						$.post("/blocks/<?=$arg['blocknum']?>/null", post, function(data){
							if(isNaN(data)){ alert(data) }else{
								param[ post.type ][ post.name ] = post.mode;
								console.log("post:", post);
							}
						});
					}; var attr = {};
					var tn = $(val).get(0).nodeName.toLowerCase();// alert(tagName);
					$("<span>").html("&nbsp;").addClass("hid").appendTo(".css .info");
					var mode = (typeof(param["tag"]) == "undefined" || typeof(param["tag"][ tn ]) == "undefined" ? "0" : param["tag"][ tn ]);
					$("<span>").on("click", click).attr("type", "tag").attr("name", tn).attr("mode", mode).html(tn).appendTo(".css .info");
					for(var i=0;i<(attrs = val.attributes).length;i++) {
						attr[attrs[i].nodeName] = attrs[i].nodeValue;
					}

					$.each(attr, function(k, v){
						var span = $("<span>").attr("mode", 0).on("click", click);
						if(k == "id"){
							var exists = $(".css .use > span[name="+k+"]");
							var mode = (typeof(param["id"]) == "undefined" ? "0" : param["id"][ v ]);
							$(span).attr("type", "id").attr("name", v).attr("mode", mode).text("#"+v).appendTo("<span>").appendTo(".css .info");
						}else if(k == "class"){
							if(cl = v.split(" ")){
								$.each(cl, function(){
									var mode = (typeof(param["class"]) == "undefined" ? "0" : param["class"][ this ]);
									$(span).attr("type", "class").attr("name", this).attr("mode", mode).text("."+this).appendTo("<span>").appendTo(".css .info");
								});
							}
						}else if(k == "style"){
						}else{
							var mode = (typeof(param["attr"]) == "undefined" ? "0" : param["attr"][ k ]);
							$(span).attr("type", "attr").attr("name", k).attr("mode", mode).text("["+k+"="+v+"]").appendTo("<span>").appendTo(".css .info");
						}
					});
				}); event.stopPropagation();

				if($(".css .info > span:visible").not(".hid").length == 0){
					$(".css .mode input[value=-1]").attr("checked", true).change();
				} if($(".css").css("width") != $(".css").attr("w")) return false;
			}
		});
		$(".css input[name=mode]").change(function(){
			var mode = $(this).val();// alert(val);
			$(".css .info").attr("mode", mode);
			$.post("/blocks/<?=$arg['blocknum']?>/null", {m:mode}, function(data){
				if(isNaN(data)){ alert(data) }
			});

			var path = "";
			$(".css .info > span:visible").each(function(){
				var type = $(this).attr("type");
				var name = $(this).attr("name");
				var hid = $(this).is(".hid");
				var text = $(this).text();
				if(hid){
					path = path + " ";
				}else if(type == "tag"){
					path = path + name;
				}else if(type == "id"){
					path = path + "#"+name;
				}else if(type == "class"){
					path = path + "."+name;
				}else if(type == "attr"){
					path = path + text;
				}
			}); $(".counter").text($(path).length > 0 ? $(path).length : "");
		});
		$(".css input[name=mode][value="+param.mode+"]").attr("checked", true).change();
	});
</script>
<div class="css" w="120px" style="width:<?=$param['width']?>;">
	<style>
		.css {position:fixed; left:10px; top:20px; min-height:50px; z-index:999999999999; background-color:white; overflow:hidden;}
		.css .info {margin:10px 10px 0 0; white-space:nowrap;}
		.css .info[mode='-1'] > span[mode='-1'] {color:red}
		.css .info[mode='-1'] > span[mode='1'], .css .info[mode='0'] > span[mode='1'], .css .info[mode='1'] > span[mode='1'] {color:green}

		.css .info[mode='0'] > span[mode='-1'] {display:none;}

		.css .info[mode='1'] > span[mode='-1'] {display:none;}
		.css .info[mode='1'] > span[mode='0'] {display:none;}
		.css .info span:hover {font-weight:bold;}
		.css .info span {cursor:pointer; white-space:nowrap;}

/*		.css .info[mode='1'] > span[type='tag'][mode='0']:last-child {display:inline;}
		.css .info[mode='1'] > span[type='attr'][mode='0']:last-child {display:inline;}
		.css .info[mode='1'] > span[type='class'][mode='0']:last-child {display:inline;}*/
	</style>
	<div><a class="del" href="javascript:" style="float:right;"><img src="/img/del.png"></a></div>
	<div style="overflow:hidden; margin-right:20px;">
		<div class="use" style="display:none;">
			<div><b>Идент:</b><span class="id"></span></div>
			<div><b>Классы:</b><span class="class"></span></div>
			<div><b>Атрибуты:</b><span class="attr"></span></div>
		</div>
		<div class="mode" style="white-space:nowrap;">
			<label><input type="radio" name="mode" value="-1"> <span style="color:red;">Все</span></label>
			<label><input type="radio" name="mode" value="0"> Исключения</label>
			<label><input type="radio" name="mode" value="1"> <span style="color:green;">Особые</span></label>
		</div>
		<span class="info" mode="<?=$param['m']?>"></span><span class="counter" style="color:blue;font-weight:bold;"></span>
	</div>
</div>
