								<div id="login-form">
									<h1>Регистрация на сайте</h1>

									<fieldset>
										<form action="/users:reg/null" method="post">
											<script sync>
												(function($, script){
													$(script).parent().one("init", function(e){
														$(FORMS = $(e.currentTarget).is("form") ? e.currentTarget : $(e.currentTarget).find("form")).on("submit", function(e){
															$.ajax({
																type: 'POST',
																url: $(e.currentTarget).attr('action'),
																data: $(e.currentTarget).serialize(),
																dataType: 'json',
															}).done(function(json){
																//alert("Спасибо. Информация сохранена.");
																document.location.href = "<?=($conf['settings']['users_reg_redirect'] ?: '/')?>";
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
											</script>
											<p><input type="text" name="name" required placeholder="логин"></p>
											<p><input type="text" name="email" required placeholder="e-mail"></p>
											<p><input type="password" name="pass" required placeholder="пароль"></p>
											<p><input type="password" name="pass2" required placeholder="Повторите пароль"></p>
											<p><input class="btn btn-primary" type="submit" value="зарегистрироватся"></p>
											<footer class="clearfix">
											<span class="post_category" style="font-size: 14px;">
												<a href="/users:resque"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> Забыли пароль?</a></br>
												<a href="/users:login"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Вход</a>
											</span>
											</footer>
										</form>
									</fieldset>
								</div>
										
		
										
										
