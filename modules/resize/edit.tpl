<form action="/<?=$arg['modpath']?>:<?=$arg['fn']?>" method="post" enctype="multipart/form-data">
	<input type="file" name="img"> <input type="submit" value="Добавить">
	<? if($v = $conf['tpl'][$arg['fn']]): ?>
		<script>
			$(document).ready(function (){
				$('.resize').change(function(){
					var w = $('#w').val();
					var h = $('#h').val();
					var c = $('#c').attr("checked") ? 1 : 0 ;
					$('#div').css('width', w + 'px');
					$('#div').css('height', h + 'px');
//					$('#div').css('line-height', h + 'px');
					src = 'http://<?=$_SERVER['HTTP_HOST']?>/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:index/w:'+w+'px/h:'+h+'px/c:'+c+'/null/img.jpg';
					$('#url').text(src);
					$('#url').attr('href', src);
					$('#img').attr('src', src);
				});
				$('#w').change();
			});
		</script>
		<div style="margin:20px;">
			<a href="/<?=$arg['modpath']?>">Все фото</a>
			Ширина: <input class="resize" id="w" type="text" style="width:50px;" value="300">
			Высота: <input class="resize" id="h" type="text" style="width:50px;" value="500">
			Заполнить: <input class="resize" id="c" type="checkbox">
			<p />
			<a id="url" href=""></a>
			<div id="div" style="display: table-cell; vertical-align: middle; border: 1px solid red; width:250px; height:250px; text-align:center;">
				<img id="img" src="" style="border:1px solid black;margin:-1px;">
			</div>
		</div>
	<? endif; ?>
</form>