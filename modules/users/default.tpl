<div>
	<script>
		$(document).ready(function(){
			$("#block_users > div[blocknum] > div:nth-child(1)").click(function(){
				$(this).parents("[blocknum]").find("div:nth-child(2)").slideToggle();
			});
		});
	</script>
	<div id="block_users">
		<div><!-- [blocks:5] --></div>
<!--			<div style="width:50%; margin-left:50%;" ><!-- [blocks:6] --></div>-->
	</div>
</div>