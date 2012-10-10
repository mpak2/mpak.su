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

} $param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));
//$uid = $_GET['id'] && array_key_exists('users', $_GET['m']) ? $_GET['id'] : $conf['user']['id'];
//if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){};

$diff = array('id', 'name', 'pass', 'param', 'flush', 'refer', 'type_id', 'img', 'ref', 'reg_time', 'last_time', 'uid', 'sess', 'gid', 'uname');

foreach(range(1, 31) as $v){
	$day[$v] = array("id"=>$v, "name"=>$v);
};

foreach(explode(",", $conf['settings']['themes_month']) as $k=>$v){
	$month_id[$k] = $v;
	$month[$k] = array("id"=>$k, "name"=>$v);
};

foreach(range(1900, date("Y")) as $v){
	$year[$v] = array("id"=>$v, "name"=>$v);
};

if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && ($conf['user']['uid'] == $arg['uid']) && $_POST){
	if($_FILES){
		if($fn = mpfn("{$conf['db']['prefix']}{$arg['modpath']}", "img", $conf['user']['uid'])){
			mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']} SET img=\"". mpquot($fn). "\" WHERE id=". (int)$conf['user']['uid']);
		} exit($conf['user']['uid']);
	}elseif(array_key_exists('day', $_POST) && array_key_exists('month', $_POST) && array_key_exists('year', $_POST)){
		mpqw("UPDATE {$conf['db']['prefix']}users SET birth=". strtotime((int)$_POST['year']. "-". array_search($_POST['month'], $month_id). "-". (int)$_POST['day']). " WHERE id=". (int)$conf['user']['uid']);
		exit;
	}elseif(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && ($conf['user']['uid'] == $arg['uid']) && $_POST['f']){
		if($_POST['f'] == "geo") $_POST['val'] = implode(",", $_POST['val']);
		mpqw($sql = "UPDATE {$conf['db']['prefix']}users SET ". mpquot($_POST['f']). "=\"". mpquot($_POST['val']). "\" WHERE id=". (int)$conf['user']['uid']);
		exit;
	};

}

foreach($conf['user'] as $k=>$v){
	if(substr($k, -3) == '_id'){
		$conf['tpl']['select'][$k] = mpqn(mpqw("SELECT id, name FROM {$conf['db']['prefix']}users_". substr($k, 0, strlen($k)-3). " ORDER BY name"));
	}
} $user = mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}users WHERE id=". (int)$arg['uid']), 0);

?>

<? if($conf['user']['uid'] == $arg['uid']): ?>
	<script src="/include/jquery/my/jquery.klesh.js"></script>
	<script>
		$(function(){
			$(".klesh_<?=$arg['blocknum']?>").klesh("/blocks/<?=$arg['blocknum']?>/null");
			<? foreach((array)$conf['tpl']['select'] as $k=>$v): ?>
				$(".klesh_<?=$k?>_<?=$arg['blocknum']?>[f='<?=$k?>']").klesh("/blocks/<?=$arg['blocknum']?>/null", function(){
				}, <?=json_encode($v)?>);
			<? endforeach; ?>
			$(".klesh_birth_day_<?=$arg['blocknum']?>[f='birth']").klesh("/blocks/<?=$arg['blocknum']?>/null", function(){
				save_birth();
			}, <?=json_encode($day)?>);
			$(".klesh_birth_month_<?=$arg['blocknum']?>[f='birth']").klesh("/blocks/<?=$arg['blocknum']?>/null", function(){
				save_birth();
			}, <?=json_encode($month)?>);
			$(".klesh_birth_year_<?=$arg['blocknum']?>[f='birth']").klesh("/blocks/<?=$arg['blocknum']?>/null", function(){
				save_birth();
			}, <?=json_encode($year)?>);
			function save_birth(){
				day = $(".klesh_birth_day_<?=$arg['blocknum']?>[f='birth']").html();
				month = $(".klesh_birth_month_<?=$arg['blocknum']?>[f='birth']").html();
				year = $(".klesh_birth_year_<?=$arg['blocknum']?>[f='birth']").html();
				$.post("/blocks/<?=$arg['blocknum']?>/null", {day:day, month:month, year:year}, function(data){
					if(isNaN(data)){ alert(data) }
				});
			}
		});
	</script>
<? endif; ?>
<style>
	#f_<?=$arg['blocknum']?> > div {padding-top:5px;}
	#f_<?=$arg['blocknum']?> > div > div {white-space:nowrap;}
	#f_<?=$arg['blocknum']?> > div > div:first-child {float:left; width:150px;}
</style>
<div id="user_info_<?=$arg['blocknum']?>" style="overflow:hidden;">
	<? if($arg['access'] > 3): ?>
		<span style="float:right;">
			<a href="/?m[<?=$arg['modpath']?>]=admin&r=mp_users&where[id]=<?=$user['id']?>"><img src="/img/aedit.png"></a>
		</span>
	<? endif; ?>
	<div style="float:left; width:200px; text-align:center;">
		<img class="user_img" src="/<?=$conf['modules']['users']['modname']?>:img/<?=$user['id']?>/tn:index/w:200/h:200/null/img.jpg">
		<h3 style="text-align:center;"><?=$user['name']?></h3>
		<div><a href="/<?=$conf['modules']['messages']['modname']?>:письмо/uid:<?=$user['id']?>">Написать личное сообщение</a></div>
		<? if($arg['uid'] == $conf['user']['uid']): ?>
			<script src="/include/jquery/jquery.iframe-post-form.js"></script>
			<script>
				$(function(){
					$("#load_img_<?=$arg['blocknum']?>").iframePostForm({
						complete:function(data){
							if(isNaN(data)){ alert(data) }else{
								$("#load_img_<?=$arg['blocknum']?>").find("input[type=file]").val('');
								src = "/users:img/"+data+"/tn:index/w:200/h:200/rand:"+parseInt(Math.random()*1000)+"/null/img.jpg";
								$("#user_info_<?=$arg['blocknum']?> img.user_img").attr("src", src);
							}
						}
					});
				});
			</script>
			<form id="load_img_<?=$arg['blocknum']?>" style="text-align:right;" method="post" action="/blocks/<?=$arg['blocknum']?>/null" enctype="multipart/form-data">
				<input type="hidden" name="uid" value="<?=$conf['user']['uid']?>">
				<input type="file" name="img" style="margin-bottom:5px;">
				<input type="submit" value="Загрузить фото">
			</form>
		<? endif; ?>
	</div>
	<? if($user['name'] != $conf['settings']['default_usr']): ?>
		<div id="f_<?=$arg['blocknum']?>" style="margin-left:210px;">
			<? foreach(array_diff_key($user, array_flip($diff)) as $k=>$v): ?>
				<div style="overflow:hidden;">
					<div><?=($conf['settings'][$f = "users_field_$k"] ?: $f)?>:</div>
					<? if(substr($k, -3) == '_id'): ?>
						<div f="<?=$k?>" class="klesh_<?=$k?>_<?=$arg['blocknum']?>"><?=$conf['tpl']['select'][ $k ][ $v ]['name']?></div>
					<? elseif($k == "geo"): ?>
						<input type="text" name="geo" value="<?=$v?>" <?=($arg['uid'] == $conf['user']['uid'] ? "" : "disabled")?>>
						<form id="search_form" style="display:none;">
							<input type="text" value="Великий Устюг" style="width: 70%;"/>
							<input type="submit" onClick="return false;" value="Найти"/>
						</form>
						<div id="ymaps-map-id_134128145774966671747" style="width: 100%; height: 250px; margin-top:10px;"></div>
						<script type="text/javascript">
							function fid_134128145774966671747(ymaps) {
								var map = new ymaps.Map("ymaps-map-id_134128145774966671747", {center: [<?=($v ?: "30.097690164062513, 59.940978814388316")?>], zoom: <?=($v ? "14" : "8")?>, type: "yandex#map"});
								map.controls.add("zoomControl").add("mapTools").add(new ymaps.control.TypeSelector(["yandex#map", "yandex#satellite", "yandex#hybrid", "yandex#publicMap"]));
								<? if($arg['uid'] == $conf['user']['uid']): ?>
									map.events.add("click",
										function(e) {
											$("input[type='text'][name='geo']").attr("value", geo = e.get("coordPosition"));
											$.post("/blocks/<?=$arg['blocknum']?>/null", {f:"geo", val:geo}, function(data){
												if(isNaN(data)){ alert(data); }
											});
											map.balloon.open(
												e.get("coordPosition"), {
													contentBody: "<b><?=$user['name']?></b><br /><?=$user['addr']?>"
												}   
											)
										}
									);
								<? endif; ?>
								<? if($v): ?>
									map.balloon.open(
										map.getCenter(), {
											contentBody: "<b><?=mpquot($user['name'])?></b><br /><?=mpquot($user['addr'])?>"
										}   
									)
								<? endif; ?>
							};
//							$('#search_form').submit(function () {
//								alert(123);
/*								var search_query = $('input:first').val();

								map.geocode(search_query, {results: 100}).then(function (res) {
									myCollection.removeAll();
									myCollection = res.geoObjects;
									map.geoObjects.add(myCollection);
								});
								return false;*/
//							});
						</script>
						<script type="text/javascript" src="http://api-maps.yandex.ru/2.0/?coordorder=longlat&load=package.full&wizard=constructor&lang=ru-RU&onload=fid_134128145774966671747"></script>
						<!-- Этот блок кода нужно вставить в ту часть страницы, где вы хотите разместить карту (конец) -->
					<? elseif($k == "birth"): ?>
						<div style="display:inline-block;" f="<?=$k?>" class="klesh_<?=$k?>_day_<?=$arg['blocknum']?>"><?=($v ? date("d", (int)$v) : "")?></div>
						<div style="display:inline-block;" f="<?=$k?>" class="klesh_<?=$k?>_month_<?=$arg['blocknum']?>"><?=($v ? $month[date("n", (int)$v)]['name'] : "")?></div>
						<div style="display:inline-block;" f="<?=$k?>" class="klesh_<?=$k?>_year_<?=$arg['blocknum']?>"><?=($v ? date("Y", (int)$v) : "")?></div>
					<? else: ?>
						<div f="<?=$k?>" class="klesh_<?=$arg['blocknum']?>"><?=$user[$k]?></div>
					<? endif; ?>
				</div>
			<? endforeach; ?>
			<div>
				<div>Последний вход:</div>
				<div><?=date("d.m.Y H:i", $user['last_time'])?></div>
			</div>
		</div>
	<? endif; ?>
</div>