<div class="admin_yandex_texts">
	<style>
		.admin_yandex_texts div.active a {display:none;}
		.admin_yandex_texts .table > div > span:nth-child(0) {width:30px;}
		.admin_yandex_texts .table > div > span:nth-child(1) {width:30px;}
	</style>
	<script sync>
		(function($, script){
			$(script).parent().on("click", ".table a", function(e){
				var pages_id = $(e.currentTarget).parents("[pages_id]").attr("pages_id");
				$.post("/<?=$arg['modname']?>:<?=$arg['fn']?>/null", {pages_id:pages_id}, function(pages){
					console.log("pages:", pages);
				})
			})
		})(jQuery, document.scripts[document.scripts.length-1])
	</script>
	<h1>Уникальные тексты</h1>
	<ul style="padding-left:20px;">
		<? if($tpl["pages_index"] = rb("pages_index")): ?>
			<? foreach(rb("index") as $index): ?>
				<li index_id="<?=$index['id']?>">
					<h3><p><?=$index['name']?></p></h3>
					<div class="table">
						<? foreach(rb($tpl["pages_index"], "index_id", "id", $index['id']) as $pages_index): ?>
							<? if($tpl['pages_index:pages'] = rb($tpl["pages_index"], "pages_id", "id", $pages_index['pages_id'])): ?>
								<? if($pages = rb("pages", "id", $pages_index['pages_id'])): ?>
									<div class="<?=((($count = count($tpl['pages_index:pages'])) > 1) ? "active" : "")?>" pages_id="<?=$pages['id']?>">
										<span><?=count($tpl['pages_index:pages'])?></span>
										<span><a href="javascript:void(0)">рег.</a></span>
										<span><?=$pages['name']?></span>
									</div>
								<? endif; ?>
							<? endif; ?>
						<? endforeach; ?>
					</div>
				</li>
			<? endforeach; ?>
		<? endif; ?>
	</ul>
</div>
