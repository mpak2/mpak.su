<?

if(array_key_exists("null", $_GET)){
	header("Content-Type: text/xml");
}

?><?="<"?>?xml version="1.0" encoding="UTF-8" ?<?=">"?> 
<urlset
			xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
			xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
			xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
					http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
	<!-- created with Free Online Sitemap Generator www.xml-sitemaps.com -->
	<? if($themes_index = get($conf, 'user', 'sess', 'themes_index')): ?> 
		<? foreach(rb("{$conf['db']['prefix']}seo_index_themes", 1000, "themes_index", "id", $themes_index['id']) as $seo_index_themes): ?>
			<? if($seo_index = rb("{$conf['db']['prefix']}seo_index", "id", $seo_index_themes['index_id'])): ?> 
				<? if(!array_key_exists("hide", $seo_index) || !get($seo_index, 'hide')): ?> 
					<url>
						<loc>http://<?=$themes_index['name']?><?=$seo_index['name']?></loc>
						<priority><?=$seo_index['priority']?></priority>
					</url>
				<? endif; ?> 
			<? endif; ?> 
		<? endforeach; ?> 
	<? else: ?>
		<? foreach(rb("{$conf['db']['prefix']}seo_index") as $seo_index): ?>
			<url>
				<loc>http://<?=$_SERVER['HTTP_HOST']?><?=$seo_index['name']?></loc>
				<priority><?=$seo_index['priority']?></priority>
			</url>
		<? endforeach; ?> 
	<? endif; ?> 
</urlset>
