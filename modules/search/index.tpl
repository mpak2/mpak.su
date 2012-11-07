<!-- [settings:foto_lightbox] -->
<style>
	ul.search_name {overflow:hidden; list-style-type:none; margin:20px 0 0 0;}
	ul.search_name li {padding:5px 10px; margin:0;}
	ul.search_name li.active {background-color:#ddd;}
</style>
<div style="margin:10px;">
	<? if($_SERVER['HTTP_REFERER']): ?>
		<span style="float:right;"><a href="<?=$_SERVER['HTTP_REFERER']?>">Вернуться</a></span>
	<? endif; ?>
</div>
<div style="text-align:center; margin-top:20px;">Страница <?=($_GET['p']+1)?> из <?=$tpl['pages']?> найденных</div>
<form class="search" style="text-align:center;" method="GET" action="/<?=$arg['modname']?>">
	<input type="hidden" name="search_block_num" value="<?=$tpl['search']['num']?>">
	<input type="hidden" name="search_key" value="<?=$_GET['search_key']?>">
	<input type="text" name="search" style="width:50%;" value="<?=$tpl['search']['name']?>">
	<input type="submit" value="Искать">
</form>
<ul class="search_name">
	<? if($tpl['tab']) foreach($tpl['tab'] as $search_key=>$search_name): ?>
		<? if(($search_key = substr($search_key, strlen($conf['db']['prefix']))) && empty($search_name)) continue; ?>
		<li style="float:left;" class="<?=($_GET['search_key'] == $search_key ? "active" : "")?>">
			<a href="/<?=$arg['modname']?>/search_block_num:<?=(int)$tpl['search']['num']?>/search_key:<?=$search_key?>/<?=$tpl['search']['search']?>"><?=$search_name?></a>
		</li>
	<? endforeach; ?>
</ul>
<div style="text-align:center; margin-bottom:20px;">
	<!-- [settings:<?=$arg['modname']?>_title] -->
</div>
<span><?=$tpl['mpager']?></span>
<div>
	<? if($tpl['param']['tab']): ?>
	<div class="tab" style="width:180px; float:right;">
		<script>
			$(function(){
				$(".tab select").change(function(){
					name = $(this).attr("name");
					value = $(this).find("option:selected").val(); console.log(value);
					if(value > 0){
						if($(".search input.tab[tab="+name+"]").length > 0){
							$(".search input.tab[tab="+name+"]").attr("value", value);
						}else{
							input = $("<input>").addClass("tab").attr("tab", name).attr("type", "hidden").attr("name", "tab["+name+"]").attr("value", value).appendTo(".search"); console.log(input);
						}
					}else{
						$(".search").find("input.tab[tab="+name+"]").remove();
					}
				}).change();
				$(".tab input[type=button]").click(function(){
					$(".search").submit();
				});
			});
		</script>
		<? foreach($tpl['param']['tab'] as $key=>$ar): ?>
			<div style="margin-bottom:10px;">
				<select style="width:80%;" name="<?=$key?>">
					<? foreach($ar as $v): ?>
						<option value="<?=$v['id']?>" <?=($tpl['search']['tab_data'][ $key ] == $v['id'] ? "selected" : "")?>><?=$v['name']?></option>
					<? endforeach; ?>
				</select>
			</div>
		<? endforeach; ?>
		<div><input type="button" value="Уточнить"></div>
	</div>
	<? endif; ?>
	<div style="margin-right:200px;">
		<? if($tpl['result']): ?>
			<? foreach($tpl['result'] as $k=>$v): ?>
				<div style="margin: 15px 3px 3px 3px; overflow:hidden;">
					<? if($v['uid']): ?>
						<a href="/<?=$conf['modules']['users']['modname']?>/<?=$v['uid']['id']?>">
							<div style="float:right; text-align:center;">
								<img src="/<?=$conf['modules']['users']['name']?>:img/<?=$v['uid']['id']?>/tn:index/w:50/h:50/c:1/null/img.jpg">
								<div><?=$v['uid']['name']?></div>
							</div>
						</a>
					<? endif; ?>
					<? if($v['img']): ?>
						<div id="gallery" style="float:left; margin:0 10px; width:110px;">
							<a href="<?=$v['img']?>">
								<img src="<?=$v['logo']?>">
							</a>
						</div>
					<? endif; ?>
					<div style="margin-left:<?=($v['img'] ? 120 : 0)?>;">
						<div style="overflow:hidden; white-space:nowrap;"><a href="<?=$v['link']?>" style="font-size:160%;"><?=mb_substr($v['name'], 0, 60)?></a></div>
						<div style="font-style:italic; margin-top:5px;"><?=$v['text']?></div>
						<div><a href="<?=$v['link']?>" style="color:green;"><?=$v['link']?></a></div>
					</div>
				</div>
			<? endforeach; ?>
		<? else: ?>
			<div style="text-align:center; padding:30px auto;">По заданному запросу ничего не найдено</div>
		<? endif; ?>
	</div>
</div>
<div style="margin:10px;">
	<?=$tpl['mpager']?>
</div>