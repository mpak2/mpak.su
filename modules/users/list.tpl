<style>
	.users_list > div {width:100px; height:100px; float:left; margin:5px;}
</style>
<div class="users_list">
	<? foreach($tpl['list'] as $v): ?>
		<div>
			<a href="/users/<?=$v['id']?>"><img src="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:index/w:100/h:100/c:1/null/img.jpg"></a>
		</div>
	<? endforeach; ?>
</div>