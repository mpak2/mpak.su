<div>
	<script>
		$(document).ready(function(){
			$("#block_users > div[blocknum] > div:nth-child(1)").click(function(){
				$(this).parents("[blocknum]").find("div:nth-child(2)").slideToggle();
			});
		});
	</script>
	<h1>Личный кабинет</h1>
	<div id="block_users">
		<div><!-- [blocks:5] --></div>
	</div>
</div>