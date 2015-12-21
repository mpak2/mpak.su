								<div id="login-form">
									<h1>Регистрация на сайте</h1>

									<fieldset>
										<form action="/users:reg/null" method="post">
											<script src="/include/jquery/jquery.iframe-post-form.js"></script>
											<script sync>
												(function($, script){
													$(script).parent().one("init", function(e){
														setTimeout(function(){
															$(e.delegateTarget).iframePostForm({
																complete:function(data){
																	try{
																		if(json = JSON.parse(data)){
																			console.log("json:", json);
																			document.location.href = "<?=($conf['settings']['users_reg_redirect'] ?: '/')?>";
																		}
																	}catch(e){
																		if(isNaN(data)){ alert(data) }else{
																			console.log("date:", data)
																		}
																	}
																}
															});
														}, 100)
													}).trigger("init")
												})(jQuery, document.scripts[document.scripts.length-1])
											</script>
											<input type="hidden" value="Аутентификация" name="reg">
											<input type="text" name="name" required placeholder="логин">
											<input type="text" name="email" required placeholder="e-mail">
											<input type="password" name="pass" required placeholder="пароль">
											<input type="password" name="pass2" required placeholder="Повторите пароль">
											<input class="btn btn-primary" type="submit" value="зарегистрироватся">
											<footer class="clearfix">
											<span class="post_category" style="font-size: 14px;">
												<a href="/users:resque"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> Забыли пароль?</a></br>
												<a href="/users:login"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Вход</a>
											</span>
											</footer>
										</form>
									</fieldset>
								</div>
										
		
										
										
