<style>
	div.table {display:table; width:100%; vertical-align:top;}
	div.table > div {display:table-row;}
	div.table > div > span {display:table-cell; padding:3px;}
	div.table > div.th > span {background-color:#444; color:white;}
	
	.pager a.active {color:#fe8e23;}
</style>

<? inc("modules/themes/blocks/orders.tpl") ?>

<? if($themes_index = get($conf, 'user', 'sess', 'themes_index')): ?> 
	<? if($pozvonim = get($themes_index, 'pozvonim')): ?> 
		<script crossorigin="anonymous" async type="text/javascript" src="//api.pozvonim.com/widget/callback/v3/<?=$pozvonim?>/connect" id="check-code-pozvonim" charset="UTF-8"></script>
	<? endif; ?> 
	<? if($verification = get($themes_index, 'yandex-verification')): # Проверка вебмастера яндекса ?> 
		<meta name='yandex-verification' content='<?=$verification?>' />
	<? endif; ?> 
	<? if($themes_cat = rb("{$conf['db']['prefix']}themes_index_cat", "id", $themes_index['index_cat_id'])): ?> 
		<? if($icon = get($themes_cat, "img")): # Фавикон ?> 
			<link rel="icon" type="image/png" href="/themes:img/<?=$themes_cat['id']?>/tn:index_cat/fn:img/w:65/h:65/null/img.png" />
		<? endif; ?> 
	<? endif; ?> 
<? endif; ?> 
