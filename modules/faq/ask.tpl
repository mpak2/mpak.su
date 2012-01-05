<script>
	$(function(){
		$("#faq_ask_btn").click(function(){
			$(this).parents("#faq_ask").find("#faq_fm").toggle();
		});
		$("#faq_fm_btn").click(function(){
			uid = $(this).parents("[uid]").attr("uid");// alert(user_id);
			text = $(this).parents("#faq_fm").find("textarea").val();// alert(text);
			$.post("/<?=$arg['modname']?>:<?=$arg['fe']?>/null", {text:text, uid:uid}, function(data){
				if(isNaN(data)){ alert(data) }else{
					$("#faq_fm").text("Ваш вопрос принят. Об ответе Вам будет сообщено дополнительно.");
				}
			});
		});
	});
</script>
<div id="faq_ask">
	<div style="overflow:hidden; height:90px;">
		<div style="float:right;">
			<? if($conf['tpl']['cnt']): ?>
				<div>
					<a href="/<?=$conf['modules']['faq']['modname']?><?=($_GET['uid'] ? "/uid:".$_GET['uid'] : "")?>">Список ответов</a>
					<?=($_GET['uid'] ? "[{$conf['tpl']['cnt']}]" : "")?>
				</div>
			<? endif; ?>
			<div style="text-align:center;">
				<a id="faq_ask_btn" href="javascript: return false;">
					<div>Задать вопрос</div>
					<div><img src="/<?=$arg['modname']?>:img/w:60/h:60/null/featured_faq.jpg"></div>
				</a>
			</div>
		</div>
		<div id="faq_fm" style="margin-right:150px; display:none; text-align:center;">
			<textarea title="Ваш вопрос" style="width:100%;"></textarea>
			<div style="text-align:right;">
				<input id="faq_fm_btn" type="button" value="Задать вопрос">
			</div>
		</div>
	</div>
</div>
