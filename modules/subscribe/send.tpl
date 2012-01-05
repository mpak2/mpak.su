<script language="javascript">
	$(function(){
		$(".chk").change(function(){
			if(mail = $(this).is(":checked")){
				subject = $("#subject").val();// alert(subject);
				text = $("#text").val();// alert(text);
				uid = $(this).attr("uid");
				send = $("#send").is(":checked") ? 1 : 0;
				$.post("/<?=$arg['modpath']?>:<?=$arg['fn']?>/null", {uid:uid, send:send, subject:subject, text:text}, function(data){
					if(!data){
						$(".chk[uid="+uid+"]").attr("checked", "");
						alert("Задайте заголовок и текст сообщения !!!");
					}
				});
			}
		});
	});
	function update(sto){
		if($("#update").is(":checked")){
			$(".chk:not(:checked):first").attr("checked", "checked").delay(100).change();
		} if (sto) setTimeout("update(true); ",1000);
	} update(true);
</script>

<div style="margin:10px;">
	<input id="update" type="checkbox"> Автопилот
</div>
<div style="margin:10px;">
	<div><input id="subject" type="text" style="width:100%;"></div>
	<div><textarea id="text"></textarea></div>
</div>
<div style="margin:10px;">
	<? foreach($conf['tpl']['users'] as $k=>$v): ?>
		<div style="float:left; width:150px; white-space:nowrap;">
			<input class="chk" uid="<?=$v['id']?>" type="checkbox">
			<span><?=$v['name']?></span>
		</div>
	<? endforeach; ?>
</div>
