	<script>
		$(function(){
			$(".SecondMenu li a[href='"+"<?=$_SERVER['REDIRECT_URL']?>"+"']").parents("li").addClass("curent");
		});
	</script>
      <div class="SecondMenu">
        <ul>
			<? foreach($menu[0] as $k=>$v): ?>
				<li>
					<a href="<?=$v['link']?>"><span><?=$v['name']?></span></a>
				</li>
			<? endforeach; ?>
        </ul>
        <div class="SeconMenubanner">
          <a href="#"><img src="img/secon_menu_banner.gif"></a>
        </div>  
        <br clear="all" />
      </div>
