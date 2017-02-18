<? if($_POST): $_GET['null'] = true; ?>
	<? if(!$get = get($_GET, "get")): mpre("Условие не установлено") ?>
	<? elseif(!$post = $_POST): mpre("Изменения не заданы") ?>
	<? elseif(!$tab = get($_GET, 'tab')): mpre("Таблица не задана") ?>
	<? elseif(!$index = fk("params_{$tab}", $w = $get, $w+=$post, $w)): mpre("Ошибка обновления значения параметра") ?>
	<? else: exit(json_encode($index)) ?>
	<? endif; ?>
<? else: ?>
	<div class="modpath_params">
		<style>
			.modpath_params ul { overflow:hidden; }
			.modpath_params ul li { float:left; margin-left:20px; }
			.modpath_params .table button {padding:0 5px;}
			.modpath_params .table > div > span {padding:2px;}
			
			.modpath_params span.hide {opacity:.5;}
			.modpath_params span.hide.exist {opacity:1;}
		</style>
		<script sync>
			(function($, script){
				$(script).parent().on("click", "button[hide]", function(e){
					var attributes = $(e.currentTarget).parents("[params_id]").get(0).attributes;
					(post = {})['hide'] = $(e.currentTarget).attr("hide");
					var get = "?";
					$.each(attributes, function(n, at){
						get += "&get["+at.name+"]="+at.value;
					});

					$.post(document.location.href+get, post, function(json){ console.log("json:", json);
						$(e.currentTarget).parents("span[hide]").attr("hide", json.hide);
						$(e.currentTarget).parents("span[hide]").find("button[hide]").prop("disabled", false);
						$(e.currentTarget).parents("span[hide]").find("button[hide='"+json.hide+"']").prop("disabled", true);
					}, "json").error(function(error){
						alert(error.responseText);
					})
				}).on("change", "[params_id] input[type=text]", function(e){
					var attributes = $(e.currentTarget).parents("[params_id]").get(0).attributes;
					(post = {})['name'] = $(e.currentTarget).val();
					var get = "?";
					$.each(attributes, function(n, at){
						get += "&get["+at.name+"]="+at.value;
					});

					$.post(document.location.href+get, post, function(json){ console.log("json:", json);
						$(e.currentTarget).css("background-color", "lime");
						setTimeout(function(){ $(e.currentTarget).css("background-color", "inherit"); }, 300);
						var span = $(e.currentTarget).parents("[params_id]").find("span.hide");
						$(span).addClass("exist").attr("hide", json.hide);
						$(span).find("button[hide]").prop("disabled", false);
						$(span).find("button[hide='"+json.hide+"']").prop("disabled", true);
					}, "json").error(function(error){
						alert(error.responseText);
					})
				}).one("init", function(e){
					setTimeout(function(){
						$(e.delegateTarget).find("span.hide").each(function(n, span){
							var hide = $(span).attr("hide");
							var top_exist = $(span).parents("div.hide").is(".exist");
							var exist = $(span).is(".exist");
							console.log("hide", hide, "top_exist:", top_exist, "exist:", exist);
							if(exist){
								$(span).find("button").prop("disabled", false);
								$(span).find("[hide='"+hide+"']").prop("disabled", true);
							}else{
								$(span).find("button").prop("disabled", true);
							}
						});
					}, 1000)
				}).ready(function(e){ $(script).parent().trigger("init"); })
			})(jQuery, document.currentScript)
		</script>
		<? if((!$_name = get($_GET, 'tab')) &0): mpre("Табуляция не задана") ?>
		<? elseif($params = rb("params", "id", get($_GET, 'id'))): ?>
			<h1><?=$params['name']?></h1>
			<ul>
				<? foreach(tables() as $table): ?>
					<? if(!$table = first($table)): mpre("Имя таблицы не найдено") ?>
					<? elseif(0 !== strpos($table, $prefix = "{$conf['db']['prefix']}{$arg['modpath']}_params_")):// mpre("Таблица не от параметров <b>{$table}</b>") ?>
					<? elseif(!$name = substr($table, strlen($prefix))): mpre("Внутреннее имя таблицы не определено") ?>
					<? elseif((!$title = get($conf, 'settings', "{$arg['modpath']}_{$name}")) && !($title = get($conf, 'modules', $arg['modpath'], 'name'))): mpre("Заголовок таблицы не найден") ?>
					<? else:// mpre($table, $name, $title) ?>
						<li style="font-weight:<?=(get($_GET, 'tab') == $name ? "bold" : "inherit")?>;">
							<a href="/<?=$arg['modpath']?>:<?=$arg['fn']?>/<?=$params['id']?>/tab:<?=$name?>"><?=$title?></a>
						</li>
					<? endif; ?>
				<? endforeach; ?>
			</ul>
			<? if(!$_name): mpre("Выберете таблицу значений") ?>
			<? elseif(!$INDEX = rb($_name, 20)): ?>
			<? elseif(!$fields = fields("{$conf['db']['prefix']}{$arg['modpath']}_{$_name}")): mpre("Структура таблицы не определена") ?>
			<? else:// mpre($fields) ?>
				<p><?=$tpl['pager']?></p>
				<div class="table hide <?=(get($fields, "hide") ? "exist" : "")?>">
					<? foreach($INDEX as $index): ?>
						<? if((!$value = rb("params_{$_name}", "params_id", "{$_name}_id", $params['id'], $index['id'])) &0): mpre("Значение элемента не найдено") ?>
						<? else:// mpre($value) ?>
							<div <?="{$_name}_id"?>="<?=$index['id']?>" params_id="<?=$params['id']?>">
								<span><?=get($index, 'name')?></span>
								<span><input type="text" name="name" value="<?=get($value, 'name')?>" style="width:95%;"></span>
								<span class="hide <?=(array_key_exists('hide', $value) ? "exist" : "")?>" style="width:100px;" hide="<?=($value ? get($value, 'hide') : "")?>">
									<button hide="0" disabled>Вкл</button>
									<button hide="1" disabled>Выкл</button>
								</span>
							</div>
						<? endif ?>
					<? endforeach; ?>
				</div>
				<p><?=$tpl['pager']?></p>
			<? endif; ?>
		<? else: mpre("Выберете параметр") ?>
			<ul>
				<? foreach(rb("params") as $params): ?>
					<li>
						<a href="/<?=$arg['modpath']?>:<?=$arg['fn']?>/<?=$params['id']?>"><?=$params['name']?></a>
					</li>
				<? endforeach; ?>
			</ul>
		<? endif; ?>
	</div>
<? endif; ?>