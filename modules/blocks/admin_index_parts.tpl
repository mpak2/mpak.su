<div class="parts">
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.9.1/styles/default.min.css">
	<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.9.1/highlight.min.js"></script>
	<style>
		.parts pre { -moz-tab-size:4; -o-tab-size:4; tab-size:4; }
	</style>
	<h2>Аякс запрос</h2>
	<pre class="javascript hljs">
&lt;script async&gt;
	(function($, script){
		$(script).parent().on("click", "a.create", function(e){
			if(!(name = prompt("Задайте название скопления"))){ alert("ОШИБКА название скопления обязательно для заполнения");
			}else if(!(ajax = $.ajax({url:null, method:"POST", data:{act:"test", name:name}, async:false, dataType:"json"}))){ console.error("ОШИБКА создания нового скопления");
			}else if(!(json = ajax.responseJSON)){ alert(ajax.responseText);
			}else{ console.log("json:", json);
				document.location.reload(true);
			}
		}).one("init", function(e){
		}).ready(function(e){ $(script).parent().trigger("init"); })
	})(jQuery, document.currentScript)
&lt;/script&gt;
	</pre>
	<h2>Первоначальная обработка</h2>
	<pre class="javascript hljs">
&lt;script sync&gt;
	(function($, script){
		$(script).parent().one("init", function(e){
			$(FORMS = $(e.currentTarget).is("form") ? e.currentTarget : $(e.currentTarget).find("form")).on("submit", function(e){
				$.ajax({
					type: 'POST',
					url: $(e.currentTarget).attr('action'),
					data: $(e.currentTarget).serialize(),
					dataType: 'json',
				}).done(function(json){
					alert("Спасибо. Информация сохранена.");
				}).fail(function(error){
					alert(error.responseText);
				}); return false;
			}).attr("target", "response_"+(timeStamp = e.timeStamp));

			$("<"+"iframe>").attr("name", "response_"+timeStamp).appendTo(FORMS).load(function(){
				var response = $(this).contents().find("body").html();
				if(json = $.parseJSON(response)){
					console.log("json:", json);
					alert("Информация добавлена в кабинет");
				}else{ alert(response); }
			}).hide();
		}).ready(function(e){ $(script).parent().trigger("init"); })
	})(jQuery, document.currentScript)
&lt;/script&gt;
	</pre>
	<h2>ajax запрос к странице обрабатывающей внешние данные</h2>
	<pre class="javascript hljs">
&lt;script sync&gt;
	(function($, script){
		$(script).parent().on("ajax", function(e, table, get, post, complete, rollback){
			var href = "/&lt;?=$arg['modname']?&gt;:ajax/class:"+table;
			$.each(get, function(key, val){ href += "/"+ (key == "id" ? parseInt(val) : key+ ":"+ val); });
			$.post(href, post, function(data){ if(typeof(complete) == "function"){
				complete.call(e.currentTarget, data);
			}}, "json").fail(function(error) {if(typeof(rollback) == "function"){
					rollback.call(e.currentTarget, error);
			} alert(error.responseText) });
		}).on("reload", function(e, selector){
			if(!selector){ console.log("<?=__LINE__?>.ОШИБКА не задан элемент для перезагрузки");
			}else if(!(href = document.location.href)){ console.log("<?=__LINE__?>.ОШИБКА получения адреса страницы");
			}else{// console.log("<?=__LINE__?>.href:", href);
				var html = $("&lt;div&gt;").load(href, function(response){
					if(!(node = $(response).find("string" == typeof(selector) ? selector : selector.selector)).length){ console.log("<?=__LINE__?>.ОШИБКА в загруженном документе элемент не найден");
					}else if(!(html = $(node).get(0).innerHTML)){ console.log("<?=__LINE__?>.ОШИБКА определения HTML кода элемента");
					}else if(!(node = ("string" == typeof(selector) ? $(response).find(selector) : selector)).length){ console.log("<?=__LINE__?>.ОШИБКА элемент цель не найден");
					}else if(!$(node).html(html)){ console.log("<?=__LINE__?>.ОШИБКА добавления хтмл кода в элемент");
					}else{ console.log("<?=__LINE__?>.reload:", href);
					}
				})
			}
		})
	})(jQuery, document.currentScript)
&lt;/script&gt;
	</pre>
	<h2>Форма сохранение данных без перезагрузки</h2>
	<pre class="javascript hljs">
&lt;script async&gt;
	(function($, script){
		$(script).parent().one("init", function(e){
			var forms = $(e.delegateTarget).attr("target", "response_"+(timeStamp = e.timeStamp));
			$("<"+"iframe>").attr("name", "response_"+timeStamp).appendTo(forms).load(function(){
				var response = $(this).contents().find("body").html();
				if(json = $.parseJSON(response)){
					console.log("json:", json);
					alert("Спасибо, мы вам перезвоним");
					$(e.delegateTarget).parents(".myform").find(">.close").trigger("click");
				}else{ alert(response); }
			}).hide();
		}).ready(function(e){ $(script).parent().trigger("init"); })
	})(jQuery, document.currentScript)
&lt;/script&gt;
	</pre>
</div>
