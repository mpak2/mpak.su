<? die; # Нуль

$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id=". (int)max($arg['blocknum'], $arg['confnum'])), 0, 'param'));

if ((int)$arg['confnum']){
	if($klesh = array(
		($f = "hide")=>($param[ $f ] = $param[ $f ] ?: ""),
		/*	($f = "Высота")=>($param[ $f ] = $param[ $f ] ?: 200),
			"Список"=>array(
			1=>"Одын",
			2=>"Два",
		),
		"Город"=>spisok("SELECT id, name FROM {$conf['db']['prefix']}users_sity ORDER BY name"),*/
		"Таблица" => array(),
	));
	if(array_key_exists("Таблица", $klesh)){ # Если есть таблица то загружаем список таблиц
		foreach(ql("SHOW TABLES") as $v){
			$f = array_shift($v);
			$klesh["Таблица"][ implode("_", array_slice(explode("_", $f), 1)) ] = $f;
		}
	}

	if ($_POST){
		$param = array($_POST['param']=>$_POST['val'])+(array)$param;
		mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id=". (int)$arg['confnum']);
	} if(array_key_exists("null", $_GET)) exit;

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
<? return;}
################################# php код #################################

//$uid = $_GET['id'] && array_key_exists('users', $_GET['m']) ? $_GET['id'] : $conf['user']['id'];
if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){
	if($_POST['opacity'] == "false"){ # Прозрачность не включена. Добавляем полю прозрачности
		$param['hide'] = implode(",", array_merge(explode(",", $param["hide"]), array($_POST['hide'])));
	}else{
		$param['hide'] = implode(",", array_diff(explode(",", $param["hide"]), array($_POST['hide'])));
	} mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id=". (int)$arg['blocknum']); exit;
};

$tpl['etitle'] = array("id"=>"Номер", 'time'=>'Время', 'up'=>'Обновление', 'uid'=>'Пользователь', 'count'=>'Количество', 'level'=>'Уровень', 'ref'=>'Источник', 'cat_id'=>'Категория', 'img'=>'Изображение', 'img2'=>'Изображение2', 'hide'=>'Видим', 'sum'=>'Сумма', 'fm'=>'Фамилия', 'im'=>'Имя', 'ot'=>'Отвество', 'sort'=>'Сорт', 'name'=>'Название', 'duration'=>'Длительность', 'pass'=>'Пароль', 'reg_time'=>'Время регистрации', 'last_time'=>'Последний вход', 'email'=>'Почта', 'skype'=>'Скайп', 'site'=>'Сайт', 'title'=>'Заголовок', 'sity_id'=>'Город', 'country_id'=>'Страна', 'status'=>'Статус', 'addr'=>'Адрес', 'tel'=>'Телефон', 'code'=>'Код', 'price'=>'Цена', 'captcha'=>'Защита', 'href'=>'Ссылка', 'keywords'=>'Ключевики', "users_sity"=>'Город', 'log'=>'Лог', 'max'=>'Макс', 'min'=>'Мин', 'own'=>'Владелец', 'period'=>'Период', "from"=>"С", "to"=>"До", 'description'=>'Описание', 'text'=>'Текст');

/*foreach(ql("SHOW TABLES") as $v){
	$f = array_shift($v);
	$tpl['show'][ $f ] = $f;
}// mpre($tpl['show']);*/

$tpl['table'] = explode("_", $param["Таблица"]);// mpre($tpl['table']);
$tpl['tn'] = implode("_", array_slice($tpl['table'], 1));
$tpl['hide'] = explode(",", $param["hide"]);

$tpl['index'] = ql("SELECT * FROM {$conf['db']['prefix']}{$param["Таблица"]} WHERE id=". (int)$_GET[ $param["Таблица"] ], 0);
$tpl['fields'] = qn("SELECT * FROM {$conf['db']['prefix']}{$param["Таблица"]}_fields");
$tpl['data'] = qn($sql = "SELECT * FROM {$conf['db']['prefix']}{$param["Таблица"]}_data WHERE {$tpl['tn']}_id=". (int)$tpl['index']['id'], "{$tpl['tn']}_fields_id");

foreach($tpl['index'] as $k=>$v){
	if((substr($k, -3) == "_id")){
		# Подгружаем все связанные списки
		$tpl['spisok'][$k] = qn("SELECT id,name FROM {$conf['db']['prefix']}{$tpl['table'][0]}_". substr($k, 0, -3));
		# Если индексная таблица то берем название поля из названия раздела
		if(substr($k, 0, -3) == "index") $conf['settings'][ $s = "{$tpl['table'][0]}_". substr($k, 0, -3) ] = $conf['modules'][ $tpl['table'][0] ]['name'] ;
		# Иначе из названий раздела в админстранице
		if(empty($tpl['etitle'][ $k ]) && !empty($conf['settings'][ $s = "{$tpl['table'][0]}_". substr($k, 0, -3) ])) $tpl['etitle'][ $k ] = $conf['settings'][ $s ];
	} if($k == "time"){
		$tpl['index'][ $k ] = date("d.m.Y", $v);
	}else if($k == "uid"){
		$tpl['spisok']["uid"] = qn("SELECT id,name FROM {$conf['db']['prefix']}users");
	}
}// mpre($tpl['id']);

################################# верстка ################################# ?>
<script src="/include/jquery/my/jquery.klesh.js"></script>
<script>
	$(function(){
		<? foreach($tpl['index'] as $k=>$v): ?>
			<? if(!empty($tpl['spisok'][ $k ])): ?>
				$(".<?=$tpl['tn']?>.<?=$k?>.klesh[uid=<?=$conf['user']['uid']?>]").not(".id").klesh("/<?=$tpl['table'][0]?>:ajax", function(){
				}, <?=json_encode($tpl['spisok'][ $k ])?>);
			<? else: ?>
				$(".<?=$tpl['tn']?>.<?=$k?>.klesh[uid=<?=$conf['user']['uid']?>]").not(".id").klesh("/<?=$tpl['table'][0]?>:ajax");
			<? endif; ?>

/*				$(".<?=$tpl['tn']?>_data.<?=$k?>.klesh[uid=<?=$conf['user']['uid']?>]").klesh("/<?=$arg['modname']?>:ajax", function(){
				}, <?=json_encode($tpl['spisok'][ $k ])?>);*/
//				$(".<?=$tpl['tn']?>_data.<?=$k?>.klesh[uid=<?=$conf['user']['uid']?>]").klesh("/<?=$arg['modname']?>:ajax");
		<? endforeach; ?>
		<? if($arg['access'] > 3): ?>
			$(".fields.<?=$param["Таблица"]?> .hide").click(function(){
				var hide = $(this).attr("hide");// alert(hide);
				var opacity = $(this).is(".opacity");
				$.post("/blocks/<?=$arg['blocknum']?>/null", {hide:hide, opacity:opacity}, $.proxy(function(data){
					if(isNaN(data)){ alert(data) }else{
						$(this).toggleClass("opacity");
					}
				}, this));
			});
			$(".fields.<?=$param["Таблица"]?> .edit").click(function(){
				var fields_id = $(this).attr("fields_id");
				document.location.href = "/?m[<?=$tpl['table'][0]?>]=admin&r=<?=$conf['db']['prefix']?><?=$param["Таблица"]?>_fields&where[id]="+fields_id;
			});
		<? endif; ?>
	});
</script>
<div class="fields <?=$param["Таблица"]?>">
	<style>
		.fields.<?=$param["Таблица"]?> div.list {margin-top:10px;}
		.fields.<?=$param["Таблица"]?> div.list > div > div {display:inline-block; min-height:25px; background-repeat:no-repeat; background-position:50% 50%; cursor:pointer;}
		.fields.<?=$param["Таблица"]?> div.list > div > div.hide {width:30px; background-image:url("data:image/jpeg;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAMAAADXqc3KAAAAA3NCSVQICAjb4U/gAAAACXBIWXMAAA3XAAAN1wFCKJt4AAAAGXRFWHRTb2Z0d2FyZQB3d3cuaW5rc2NhcGUub3Jnm+48GgAAAYZQTFRF////AAAALjQ2AAAALjQ2VVVVLjQ2QEBALjQ2LjQ2LjQ2LjQ2OTk5KytALjQ2LjQ2Nzc3LjQ2Kzk5LjQ2LjQ2Li46LjQ2LjQ2LDMzKzI5P0RGMTc3LzU1MDU1LDY2Njs9LjI2OEBCMDQ4LjI2R0xNS1FSLjU1LjU1LTQ3LzU1Qk1URUpMLzU1LzQ3S1BSLjM1LjM1LjU3LTQ2UVZXLjQ2UVZXLTM1UVZXLTU3LzU3LjQ2UldYT1RVVltcVVpbUVthWFxeWGp5WF1eXWFiWl5fUGFyYmZnY2hpVltcWV5fYmdoYXWGWF1eW19ga29wXmNkZGhpcnZ3e35/foKChYiIg4eHW3CGcIqmfICAkJOTkJOTkJOToqSkdpm9ubu6wMHAysvKx8nHy83L1tbV3NzbLjQ2RlptXoa4X4e5Y3ODaZC6aZfJa5jJcJO+cp/Pc3Z3daDNfKbSjqnJjrHWmKKrqcLcrMTcucnYwMzbycrJ1tnb29za4+Ph4+Ti4+bo6Ojm7e3r7u7s2dV8SwAAAGV0Uk5TAAEBAgMDBAQGBwgJCQwNDg4REhIUFhkeIyQnKiswNDs9PUBCQkJDTU9XWltcXWJpbnR2fn9/gYGCg4SEiIuXmKCgo6mtrq6xs7a3uLq6vMXH09ja3N3e4OHi5ejr9vb2+Pn6/P6px4XUAAAA8UlEQVQoz2NgoDpgllQ3NNLX0VCWQRZVsrAw0ZXj5RGVkFXQtLKUhoiyGlioMSIrY9c3YwczhBgYuOTN7V0dFTmgUgJQWswlNK4qvbS4IDbcW4sNoVUloraxsSi7rDwzLSsvJ0wPJs4f2AgEuXmFJfmpaXl5ufE+3BAJwWCIRF5aKliiMcVfGCJjmtQIMiovKw1oVHZRY2NNkJ8DJ1CCxSO5sbEuIw8MMuqAEtEBNmAHsnkmNjZWZGTn5WVnVDQ2JviKw+xnso6qb6wrys0tqmtsiLFlQvKtlHNIZT3QqupIL1W0UOTTtnN3czIWYRiaAADUwkQ4ln2zwgAAAABJRU5ErkJggg==");}
		.fields.<?=$param["Таблица"]?> div.list > div > div.edit {width:30px; background-image:url("data:image/jpeg;base64,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")}
		.fields.<?=$param["Таблица"]?> div.list > div > div.opacity {position:relative; opacity:.3;}
		.fields.<?=$param["Таблица"]?> div.list > div > span { display:inline-block; min-width:100px; min-height:25px; vertical-align:top; line-height:25px;}
		.fields.<?=$param["Таблица"]?> div.list > div > span:first-child { font-weight:bold; }
	</style>
	<? if($arg['access'] > 3): ?>
		<span style="float:right;">
			<a href="/?m[<?=$tpl['table'][0]?>]=admin&r=<?=$conf['db']['prefix']?><?=$param["Таблица"]?><?=($_GET[ $param["Таблица"] ] ? "&where[id]=". (int)$_GET[ $param["Таблица"] ] : "")?>"><img src="/img/aedit.png"></a>
		</span>
	<? endif; ?>
	<h2 style="opacity:0.5; margin-right:10px;">
		<?=$conf['modules'][ $tpl['table'][0] ]['name']?> &raquo;
		<?=$conf['settings'][ $param["Таблица"] ]?>
	</h2>
	<h1>
		<?=$tpl['index']['name']?>
	</h1>
	<div class="list">
		<? foreach($tpl['index'] as $k=>$v): if(array_search($k, $tpl['hide']) && $arg['access'] <= 3) continue; # Не выводим скрытые поля ?>
			<div>
				<? if($arg['access'] > 3): ?>
					<div class="hide <?=(array_search($k, $tpl['hide']) ? "opacity" : "")?>" hide="<?=$k?>"></div>
				<? endif; ?>
				<span title="<?=$k?>"><?=($tpl['etitle'][ $k ] ?: $k)?></span>
				<span>
					<div uid="<?=$tpl['index']['uid']?>" id="<?=$tpl['index']['id']?>" class="<?=$tpl['tn']?> <?=$k?> klesh"><?=(!empty($tpl['spisok'][ $k ]) ? $tpl['spisok'][ $k ][ $v ]["name"] : $v)?></div>
				</span>
			</div>
		<? endforeach; ?>
		<? foreach($tpl['fields'] as $f): ?>
			<div>
				<? if($arg['access'] > 3): ?>
					<div fields_id="<?=$f['id']?>" class="edit"></div>
				<? endif; ?>
				<span title="Доп поле <?=$f['id']?>"><?=$f['name']?></span>
				<span><div uid="<?=(int)$tpl['data'][ $f['id'] ]['uid']?>" id="<?=(int)$tpl['data'][ $f['id'] ]['id']?>" class="<?=$tpl['tn']?>_data name klesh"><?=$tpl['data'][ $f['id'] ]['name']?></div></span>
			</div>
		<? endforeach; ?>
	</div>
</div>
