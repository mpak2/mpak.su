<div class="parts">
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.9.1/styles/default.min.css">
	<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.9.1/highlight.min.js"></script>
	<style>
		.parts pre { -moz-tab-size:4; -o-tab-size:4; tab-size:4; }
	</style>
	<h2>Первоначальная обработка</h2>
	<pre class="javascript hljs">
&lt;script sync&gt;
	(function($, script){
		$(script).parent().one("init", function(e){
			$(FORMS = $(e.currentTarget).is("form") ? e.currentTarget : $(e.currentTarget).find("form")).on("submit", function(e){
				alert("Спасибо. Ваша заявка отправлена.");
			}).attr("target", "response_"+(timeStamp = e.timeStamp));

			$("<"+"iframe>").attr("name", "response_"+timeStamp).appendTo(FORMS).load(function(){
				var response = $(this).contents().find("body").html();
				if(json = $.parseJSON(response)){
					console.log("json:", json);
					$(FORMS).trigger('mysubmit');
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
