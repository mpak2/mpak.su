<? if($conf['user']['uid'] > 0): ?>
	<style>
		.anket_<?=$arg['blocknum']?> h3 { cursor:pointer; border:1px solid blue; margin:3px 0; padding:0 10px; border-radius:20px; background-color:#00eeff; }
		.anket_<?=$arg['blocknum']?> div.toggle { display:block; }
		.anket_<?=$arg['blocknum']?> div.toggle > div { text-align:right; /*display:none; */}
		.anket_<?=$arg['blocknum']?> div.toggle > div > span { display:inline-block; vertical-align:top; text-align:left;}
		.anket_<?=$arg['blocknum']?> div.toggle > div > span:nth-child(1) {text-align:right; /*min-width:50%;*/ padding:4px 3px;}
		.anket_<?=$arg['blocknum']?> div.toggle > div > span:nth-child(2) {min-width:40%}
		.lvl > div {padding:2px;}
	</style>
	<script src="/include/jquery/my/jquery.klesh.js"></script>
	<script type="text/javascript" src="http://jqueryui.com/latest/ui/ui.core.js"></script>
	<script>
		$(function(){
			var select = <?=json_encode($select)?>;
			$(".anket_<?=$arg['blocknum']?> .klesh[select=false]").klesh("/<?=$arg['modname']?>:<?=$arg['fn']?>/null", function(){
	//			$(this).removeClass("hasDatepicker");
			});
			$(".klesh.level").klesh("/<?=$arg['modname']?>:<?=$arg['fn']?>/null");
			$.each(select, function(key, val){
				$(".anket_<?=$arg['blocknum']?> .klesh[select=true][alias="+key+"]").klesh("/<?=$arg['modname']?>:<?=$arg['fn']?>/null", function(){
				}, val);
			});
			$(".anket_<?=$arg['blocknum']?> h3").click(function(){
				$(this).next("div").slideToggle();
			});
		});
	</script>

	<div class="anket_<?=$arg['blocknum']?>" style="min-height:20px; overflow:hidden; padding-bottom:20px;">
		<div style="width:25%; float:left; text-align:center;">
			<? if($refer): ?>
				<div>
					<div><img src="/<?=$arg['modpath']?>:img/<?=$refer['id']?>/tn:index/w:100/h:100/null/img.jpg"></div>
					<div>Ваш наставник: <b><?=$refer['name']?></b></div>
					<div>Фамилия наставника: <b><?=$refer['fm']?></b></div>
					<div>Имя наставника: <b><?=$refer['im']?></b></div>
					<div>Электронная почта наставника: <b><?=$refer['email']?></b></div>
				</div>
			<? endif; ?>
		</div>
		<div style="float:right; min-width:70%;">
			<? foreach($tpl['anket'] as $anket_type_id=>$anket): $num = 0; ?>
				<div>
					<? if($anket_type_id): ?>
						<h3 style="padding:5px 12px; width:70%; text-align:right;">
							<?=$tpl['anket_type'][ $anket_type_id ]['sort']?><?=($tpl['anket_type'][ $anket_type_id ] ? "." : "")?>
							<?=$tpl['anket_type'][ $anket_type_id ]['name']?>
						</h3>
					<? endif; ?>
					<div class="toggle">
						<? foreach($anket as $v): ++$num; ?>
							<div style="overflow:hidden; white-space:nowrap;">
								<span>
									<? if($arg['access'] > 3): ?>
										<span>
											<a href="/?m[users]=admin&r=mp_users_anket_data&where[uid]=<?=$arg['uid']?>&where[anket_id]=<?=$v['id']?>">
												<img src="/img/aedit.png">
											</a>
										</span>
									<? endif; ?>
									<?=$tpl['anket_type'][ $anket_type_id ]['sort']?>.<?=$num?>. <?=$v['name']?>:
								</span>
								<? if($v['alias'] == "geoname"): ?>

									<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css" />
									<script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>

									<script>
										$(function() {
											function log( message ) {
												$( "<div>" ).text( message ).prependTo( "#log" );
												$( "#log" ).scrollTop( 0 );
											}
									
											$( "#city" ).autocomplete({
												source: function( request, response ) {
													$.ajax({
														url: "http://ws.geonames.org/searchJSON",
														dataType: "jsonp",
														data: {
															lang:"RU",
															featureClass: "P",
															style: "full",
															maxRows: 12,
															name_startsWith: request.term
														},
														success: function( data ) {
															response( $.map( data.geonames, function( item ) {
																return {
																	label: item.name + (item.adminName1 ? ", " + item.adminName1 : "") + ", " + item.countryName,
																	value: item.name,
																	item:item
																}
															}));
														}
													});
												},
												minLength: 2,
												select: function( event, ui ) {
													ui.item.item.alternateNames = null;
													$.post("/blocks/<?=$arg['blocknum']?>/null", {"geoname":ui.item.item}, function(data){
														if(isNaN(data)){ alert(data) }else{
															$("#city").css("background-color","#00ff00");
															setTimeout(function(){
																$("#city").css("background-color","");
															}, 300);
														}
													});
													console.log(ui.item);
													log( ui.item ?
														"Selected: " + ui.item.label :
														"Nothing selected, input was " + this.value);
												},
												open: function() {
													$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
												},
												close: function() {
													$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
												}
											});
										});
									</script>
									<div class="ui-widget" style="display:inline-block;">
										<input id="city" placeholder="Ваш город" value="<?=$conf['tpl']['select'][ $k ][ $v ]['name']?>" />
									</div>
									<div class="ui-widget" style="margin-top: 2em; font-family: Arial; display:none;">
										Result: <div id="log" style="height: 200px; width: 300px; overflow: auto;" class="ui-widget-content"></div>
									</div>


								<? else: ?>
									<span>
										<div class="klesh" alias="<?=$v['alias']?>" select="<?=$select[ $v['alias'] ] ? "true" : "false"?>" anket_id="<?=$v['id']?>" style="width:100%;"><?=($select[ $v['alias'] ] ? $select[ $v['alias'] ][ $tpl['anket_data'][ $v['id'] ]['name'] ]['name'] : $tpl['anket_data'][ $v['id'] ]['name'])?></div>
									</span>
								<? endif; ?>
							</div>
						<? endforeach; ?>
					</div>
				</div>
			<? endforeach; ?>
		</div>
	</div>	
<? else: ?>
	<style>
		.anket {font-size:15px;}
		.anket span {display:inline-block; width:45%;}
		.anket span:nth-child(1) {text-align:right; width:52%;}
		.anket span input {width:80%;}
	</style>
	<script src="/include/jquery/jquery.iframe-post-form.js"></script>
	<script>
		$(function(){
			$("input[alias='name']").change(function(){
				if(name = $(main = this).val()){
					$.post("/<?=$arg['modname']?>:check/null", {name:name}, function(data){
						if(data == "true"){
							$(main).attr("check", data).css("background-color", "#ffaaaa");
						}else if(data == "false"){
							$(main).attr("check", data).css("background-color", "#aaffaa");
						}else{ alert(data) }
					});
				}
			}).change();
			$("input[alias='refer']").change(function(){
				if(refer = $(main = this).val()){
					$.post("/<?=$arg['modname']?>:check/null", {name:refer}, function(data){
						if(data == "true"){
							$(main).attr("check", data).css("background-color", "#aaffaa");
						}else if(data == "false"){
							$(main).attr("check", data).css("background-color", "#ffaaaa");
						}else{ alert(data) }
					});
				}
			}).change();
			$("form.anket").iframePostForm({
				post:function(){
					name_check = $("input[alias='name']").attr("check");
					refer_check = $("input[alias='refer']").attr("check");
					if(name_check == "true"){
						alert("Пользователь с этими данными уже зарегистрирован");
						return false;
					}else if(refer_check == "false"){
						alert("Укажите правильно данные спонсора");
						return false;
					}
					if(pass = $("input[alias='pass']")){
						if($(pass).length == 2){
							if($(pass).eq(0).val() != $(pass).eq(1).val()){
								alert("Введенные пароли не совпадают");
								return false;
							}
						}
					}
				},
				complete:function(data){
					if(isNaN(data)){ alert(data) }else{
						alert("Вы успешно зарегистрированны");
						document.location.reload(true);
					}
				}
			});
			var refer = <?=json_encode($tpl['refer'])?>; console.log(refer);
			if(refer.id > 0){
				$("input[alias='refer']").attr("readonly", true).val(refer.name);
			}
		});
	</script>
	<script src="/include/jquery/calendar/calendar.js"></script>
	<link rel="stylesheet" href="/include/jquery/calendar/calendar.css" type="text/css" media="screen, projection" />
	<script>
		$(function(){
			$("form.anket input[alias='birth']").simpleDatepicker()
		});
	</script>
	<h1 style="text-align:center; padding-bottom:10px; margin-bottom:3px;"><!-- [settings:users_reg_title] --></h1>
	<div style="text-align:center;"><!-- [settings:users_reg_latin] --></div>
	<form class="anket" action="/<?=$arg['modname']?>:<?=$arg['fn']?>/null" method="post">
		<? foreach($tpl['anket'] as $anket_type_id=>$anket): ?>
			<div style="border-top:1px solid #333; color:gray; padding:10px 0;">
				<h2 style="margin-bottom:20px;"><?=$tpl['anket_type'][ $anket_type_id ]['name']?></h2>
				<div style="margin-left:10px;">
					<? foreach($anket as $v): ?>
						<div>
							<span><label><?=$v['name']?> <?=($v['required'] ? "&nbsp;<b style='color:red;'>*</b>" : "")?></label></span>
							<span style="text-align:left;">
								<? if($v['alias'] == "sex_id"): ?>
									<div style="float:left; white-space:nowrap;"><input type="radio" name="anket[<?=$v['id']?>]" value="0">&nbsp;Женский</div>
									<div style="float:left; white-space:nowrap; margin-left:50px;"><input type="radio" name="anket[<?=$v['id']?>]" value="1">&nbsp;Мужской</div>
								<? else: ?>
									<input <?=($v['required'] ? "required" : "")?> alias="<?=$v['alias']?>" type="text" name="anket[<?=$v['id']?>]">
								<? endif; ?>
							</span>
						</div>
					<? endforeach; ?>
				</div>
			</div>
		<? endforeach; ?>
		<div style="text-align:center;"><input type="submit" value="Зарегистрироваться" style="font-weight:bold; padding:10px;"></div>
	</form>
<? endif; ?>