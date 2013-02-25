<h1>Генерация изображений из поисковых систем</h1>
<style>
	.table {display:table}
	.table > div {display:table-row}
	.table > div > span {display:table-cell; padding:3px;}
</style>
<script src="/include/jquery/jquery.iframe-post-form.js"></script>
<script>
	$(function(){
		$("form.admin_imgs").iframePostForm({
			post:function(){
				var prefix = $(this).find("input[name=prefix]").val();
				if(prefix == ""){
					alert("Задайте префикс для запроса");
					return false;
				}
			},
			complete:function(data){
				try{
					var json = $.parseJSON(data);
				}catch(e){
					$(".pre").text(data);
				}
				$.each(json, function(){
					$("<img>").attr("src", this.thmb_href).appendTo(".thmb");
				});
			}
		});
		$("form.admin_imgs [name=index]").change(function(){
			var index = $(this).find("option:selected").val();// alert(index);
			$("form.admin_imgs [name=key]").find("option[value="+index+"]").next().attr("selected", true);
		});
	});
</script>
<form class="admin_imgs table" action="/<?=$arg['modname']?>:<?=$arg['fn']?>/null" method="post">
	<div>
		<span>Начиная с индекса</span>
		<span>
			<input type="text" name="from" placeholder="1">
		</span>
	</div>
	<div>
		<span>Префикс</span>
		<span>
			<input type="text" name="prefix" placeholder="Префикс поискового запроса">
		</span>
	</div>
	<div>
		<span>Таблица названий</span>
		<span>
			<select name="index">
				<? foreach($tpl['tables'] as $v): ?>
					<option value="<?=$v?>"><?=$v?></option>
				<? endforeach; ?>
			</select>
		</span>
	</div>
	<div>
		<span>Таблица связанных изображений</span>
		<span>
			<select name="key">
				<? foreach($tpl['tables'] as $v): ?>
					<option value="<?=$v?>"><?=$v?></option>
				<? endforeach; ?>
			</select>
		</span>
	</div>
	<div>
		<span>Количество изображений</span>
		<span><input type="text" name="count" value="10"></span>
	</div>
	<div><input type="submit" value="Загрузить"></div>
</form>
<pre class="pre"></pre>