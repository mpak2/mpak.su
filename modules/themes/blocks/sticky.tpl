<div class="sticky" style="visibility:hidden; top:0;">
	<script sync>
		(function($, script){
			$(script).parent().on("scroll", function(e, scrollTop){
				var offset = $(e.delegateTarget).data("offset");
				$(e.delegateTarget).css("visibility", (scrollTop > 150 ? "visible" : "hidden"));
			}).on("init", function(e){
				var offset = $(e.currentTarget).offset();
				$(e.delegateTarget).data("offset", offset).css("position", "fixed");
			}).ready(function(e){
				$(script).parent().trigger("init");
				$(document).scroll(function(){
					var scrollTop = $(document).scrollTop();
//					var offset = (sticky = $(".sticky")).offset();
					$(".sticky").css("visibility", (scrollTop > 150 ? "visible" : "hidden"));
				})
			})
		})(jQuery, document.currentScript)
	</script>
	<ul>
		<? foreach(rb("menu", "menu_blocks_id", "id", "[4,NULL]") as $menu): ?>
			<li>
				<?=($_SERVER['REQUEST_URI'] == ($seo = seo($menu['href'])) ? "<span>" : "<a href=\"{$seo}\">") ?>
					<?=$menu['name']?>
				<?=($_SERVER['REQUEST_URI'] == $seo ? "</span>" : "</a>") ?>
			</li>
		<? endforeach; ?>
	</ul>
</div>