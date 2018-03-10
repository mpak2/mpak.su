<div class="data">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
	<script>
		$(document).ready(function(e){
			$(".data").one("init", function(e){// alert(123);
				$(FORMS = $(e.currentTarget).is("form") ? e.currentTarget : $(e.currentTarget).find("form")).attr("target", "response_"+(timeStamp = e.timeStamp));

				$("<"+"iframe>").attr("name", "response_"+timeStamp).appendTo(FORMS).load(function(){
					var response = $(this).contents().find("body").html();
					if(json = $.parseJSON(response)){
						console.log("json:", json);
//						alert("Информация добавлена в кабинет");
						document.location.reload(true);
					}else{ alert(response); }
				}).hide();
			}).trigger("init");
		})
	</script>
	<? if(!is_array($DATA_INDEX = rb('data-index'))): pre("ОШИБКА выборки списка файлов") ?>
	<? else: ?>
		<style>
			.data_index > span {display:inline-block; position:relative; min-height:80px; min-width:80px; vertical-align:bottom; text-align:right;}
			.data_index > span .del {position:absolute; top:5px; right:5px; width:13px; height:13px; background-image:url(/img/del.png); background-color:white;}
		</style>
		<div class="data_index" name="data_list" style="overflow:hidden;">
			<h2>Список файлов</h2>
			<? foreach($DATA_INDEX as $index): ?>
				<? if($index['img']): ?>
					<div style="width:70px; height:70px; float:left; padding:4px; margin:2px; border:1px solid gray; text-align:center;">
						<a href="/<?=$arg['modname']?>:img/<?=$index['id']?>/tn:index/fn:img/w:800/h:600/null/img.png">
							<img src="/<?=$arg['modname']?>:img/<?=$index['id']?>/tn:index/fn:img/w:60/h:60/null/img.png" style="border:1px solid #aaa; padding:2px">
						</a>
					</div>
				<? endif; ?>
			<? endforeach; ?>
		</div>
		<form id="data_index" action="/data:ajax/class:index/null" enctype="multipart/form-data" method="post" style="margin-top:20px;">
			<span>Файл:</span>
			<input type="hidden" name="test" value="true">
			<span><input type="file" name="img[]" multiple="true"></span>
			<span><input type="submit" value="Добавить"></span>
		</form>
	<? endif; ?>
</div>