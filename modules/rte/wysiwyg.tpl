<script type="text/javascript" src="/include/jquery/jquery.js"></script>
<script type="text/javascript" src="/include/jquery.rte/jquery.rte.js"></script>
<script type="text/javascript" src="/include/jquery.rte/jquery.rte.tb.js"></script>
<script type="text/javascript" src="/include/jquery.rte/jquery.ocupload-1.1.4.js"></script>
<link type="text/css" rel="stylesheet" href="/include/jquery.rte/jquery.rte.css" />

<textarea name="<?=$conf['settings']['rte_name']?>" id="test" class="rte1" method="post" action="#" style="width:100%; height:300px;">
	<?=$conf['settings']['rte_text']?>
</textarea>
<script type="text/javascript">
	$(document).ready(function() {
		var arr = $('.rte1').rte({
//			css: ['/include/jquery.rte/jquery.rte.css'],
			controls_rte: rte_toolbar,
			controls_html: html_toolbar
		});
	});
</script>