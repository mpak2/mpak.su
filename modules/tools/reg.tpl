<h1>Проверка регулярных выражений</h1>
<div>
	<script>
		$(function(){
			$("#reg_btn").mousedown(function(){
				reg = $("#reg_reg").val();// alert(reg);
				text = $("#reg_text").val();// alert(text);
				$.post("/<?=$arg['modname']?>:<?=$arg['fn']?>/null", {reg:reg, text:text}, function(data){
					$("#reg_result").html(data);
				});
			});
		});
	</script>
	<style>
		#reg {width:80%;}
		#reg > div {text-align:right;}
		#reg > div > input[type=text],textarea {width:100%;}
	</style>
	<div id="reg">
		<div><input id="reg_reg" type="text" value="/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/"></div>
		<div><textarea id="reg_text">admin@mail.com</textarea></div>
		<div><input id="reg_btn" type="button" value="Проверить"></div>
		<pre><div id="reg_result"></div></pre>
	</div>
</div>