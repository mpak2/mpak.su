<? die; # Бегущая строка

if ((int)$arg['confnum']){
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id=". (int)$arg['confnum']), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = ". (int)$arg['confnum']);

echo <<<EOF
	<form method="post">
		<textarea name="param[text]" style="width:100%; height:100px;">{$param['text']}</textarea>
		<div><input type="submit" value="Сохранить"></div>
	</form>
EOF;

	return;
}
$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));

echo <<<EOF

<script type="text/javascript" src="/include/jquery/jscroller-0.4.js"></script>
<style>
	#scroller_container {
		position: relative;
		width: 100%;
		height: 30px;
		overflow: hidden;
	}
	#scroller {
		white-space: nowrap;
	}
</style>
<script type="text/javascript">
$(document).ready(function(){
\$jScroller.add("#scroller_container","#scroller","left",5, true);
          \$jScroller.start();
          });
</script>

<div id="scroller_container">
<div id="scroller">{$param['text']}</div>
</div>
EOF;

?>