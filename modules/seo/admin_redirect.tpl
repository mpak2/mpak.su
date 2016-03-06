<? foreach(rb("redirect") as $seo_redirect): ?>
	<? meta(array($seo_redirect['to'], $seo_redirect['from']), $seo_redirect+=array("cat_id"=>1)) ?>
<? endforeach; ?>
