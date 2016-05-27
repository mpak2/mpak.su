<? if($index_cat = rb("index_cat", "id", $conf['user']['sess']['themes_index']['index_cat_id'])): ?>
	<? if($adsense = get($index_cat, 'adsense')): ?>
		<div style="text-align:center;">
			<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
				<!-- <?=$index_cat['name']?> -->
				<ins class="adsbygoogle"
					style="display:inline-block;width:728px;height:90px"
					data-ad-client="ca-pub-8152133120673904"
					data-ad-slot="<?=(get($index_cat, 'adsense') ?: "5205979871")?>"></ins>
				<script>
				(adsbygoogle = window.adsbygoogle || []).push({});
			</script>
		</div>
	<? endif; ?>
<? endif; ?>
