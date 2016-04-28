<style>
	div.table {display:table; width:100%; vertical-align:top;}
	div.table > div {display:table-row;}
	div.table > div > span {display:table-cell; padding:3px; vertical-align:top;}
	div.table > div.th > span {background-color:#444; color:white;}
	
	.pre {position:absolute; z-index:999; background-color:white; border-radius:10px; padding:5px; opacity:0.8; border:3px double red;}
	.pre legend { color:black; top: 13px; position: relative; }
	
	.pager a.active {color:#fe8e23;}
</style>

<? inc("modules/themes/blocks/orders.tpl", array('arg'=>array("modpath"=>"themes"))) ?>

<? if($themes_index = get($conf, 'user', 'sess', 'themes_index')): ?> 
	<? if($pozvonim = get($themes_index, 'pozvonim')): ?> 
		<script crossorigin="anonymous" async type="text/javascript" src="//api.pozvonim.com/widget/callback/v3/<?=$pozvonim?>/connect" id="check-code-pozvonim" charset="UTF-8"></script>
	<? endif; ?> 
	<? if($verification = get($themes_index, 'yandex-verification')): # Проверка вебмастера яндекса ?> 
		<meta name='yandex-verification' content='<?=$verification?>' />
	<? endif; ?> 
	<? if($verification = get($themes_index, 'google-verification')): # Проверка вебмастера гугл ?> 
		<meta name="google-site-verification" content="<?=$verification?>" />
	<? endif; ?> 
	<? if(get($themes_index, 'index_cat_id') && ($themes_cat = rb("{$conf['db']['prefix']}themes_index_cat", "id", $themes_index['index_cat_id']))): ?> 
		<? if($icon = get($themes_cat, "img")): # Фавикон ?> 
			<link rel="icon" type="image/png" href="/themes:img/<?=$themes_cat['id']?>/tn:index_cat/fn:img/w:65/h:65/null/img.png" />
		<? endif; ?> 
	<? endif; ?> 
<? endif; ?> 

<? if(array_search("Администратор", $conf['user']['gid'])): ?> 
	<? if($themes_index = $conf['user']['sess']['themes_index']): ?>
		<script sync>
			(function($, script){
				$(script).parent().one("DOMNodeInserted", function(e){ // Загрузка родительского элемента
					if("object" == typeof(index = $.parseJSON('<?=json_encode(get($conf, "settings", "canonical"))?>'))){// console.log("index", index);
						var themes_index = $.parseJSON('<?=json_encode($themes_index)?>');
						$("<"+"div>").text(index.name).addClass("themes_header_seo_blocks").css({opacity:0.3, cursor:"pointer", border:"1px solid gray", position:"fixed", background:"white", color:"black", padding:"0 5px", left:"10px", top:"10px"}).appendTo("body");
						$(e.delegateTarget).next().on("click", ".themes_header_seo_blocks", function(e){
							window.open("/seo:admin/r:mp_seo_index_themes?&where[location_id]="+index.id+"&where[themes_index]="+themes_index.id);
						});
					}
				})
			})(jQuery, document.scripts[document.scripts.length-1])
		</script>
	<? endif; ?>
<? endif; ?>
