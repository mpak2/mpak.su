<script>
	$(function(){
		var index = <?=json_encode($tpl['index'])?>;
		var imgs = <?=json_encode(rb($tpl['imgs'], "index_id", "id"))?>;
		console.log("imgs:", imgs);
		var text = <?=json_encode(rb($tpl['text'], "index_id", "id"))?>;
		var id = <?=json_encode($tpl['index:first'])?>;
		setInterval(function(){
			id = index[ id.next ];
			console.log("id:", id);
			$(".paralax").trigger("init", id.id);
		}, 3000);
		$(".paralax").on("init", function(event, index_id){
			console.log("index_id:", index_id);
			$(".paralax").find(".active").filter(function(){
				var random = Math.random();
					setTimeout($.proxy(function(){
					$(this).animate({left:"90%", opacity:0}, function(){
						$(this).removeClass("active");
					});
				}, this), random * 500);
			})
			$.each(imgs[ index_id ], function(){
				var random = Math.random();
				setTimeout($.proxy(function(){
					$(".paralax").find("[imgs_id="+this.id+"]").css({left:"10%", top:this.top+"px", opacity:0});
					$(".paralax").find("[imgs_id="+this.id+"]").animate({left:this.left+"%", top:this.top+"px", opacity:1}, function(){
						$(this).addClass("active");
					});
				}, this), random * 500);
			});
			$.each(text[ index_id ], function(){
				var random = Math.random();
				setTimeout($.proxy(function(){
					$(".paralax").find("[text_id="+this.id+"]").css({left:"10%", top:this.top+"px", opacity:0});
					$(".paralax").find("[text_id="+this.id+"]").animate({left:this.left+"%", top:this.top+"px", opacity:1}, function(){
						$(this).addClass("active");
					});
				}, this), random * 500);
			});
		}).trigger("init", id.id);
	});
</script>
<style>
	.paralax [imgs_id], .paralax [text_id] {position:absolute; opacity:0;}
</style>
<h1>&nbsp;</h1>
<div class="paralax" style="position:relative; overflow:hide;">
	<? foreach($tpl['index'] as $index): ?>
		<? foreach(rb($tpl['imgs'], "index_id", "id", $index['id']) as $imgs): ?>
			<div imgs_id="<?=$imgs['id']?>" class="index_imgs">
				<img src="/paralax:img/<?=$imgs['id']?>/tn:imgs/fn:img/w:<?=$imgs['width']?>/h:<?=$imgs['height']?>/null/img.png" title="<?=$imgs['name']?>">
			</div>
		<? endforeach; ?>
		<? foreach($tpl['text'] as $text): ?>
			<div text_id="<?=$text['id']?>" class="index_text" style="<?=($text['font-size'] ? "font-size:{$text['font-size']}px;" : "")?><?=($text['color'] ? "color:{$text['color']};" : "")?>"><?=$text['name']?></div>
		<? endforeach; ?>
	<? endforeach; ?>
</div>