<?

header("Content-Type: text/xml");

?><?="<"?>?xml version="1.0" encoding="UTF-8" ?<?=">"?> 
<urlset
			xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
			xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
			xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
					http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
	<!-- created with Free Online Sitemap Generator www.xml-sitemaps.com -->
	<? if($themes_index = $conf['user']['sess']['themes_index']): ?> 
		<? foreach(rb("{$conf['db']['prefix']}seo_index_themes", "themes_index", "id", $themes_index['id']) as $seo_index_themes): ?>
			<? if($seo_index = rb("{$conf['db']['prefix']}seo_index", "id", $seo_index_themes['index_id'])): ?> 
				<url>
					<loc>http://<?=$themes_index['name']?><?=$seo_index['name']?></loc>
					<priority><?=$seo_index['priority']?></priority>
				</url>
			<? endif; ?>
		<? endforeach; ?> 
	<? endif; ?> 
</urlset>
