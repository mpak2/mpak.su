<? # ГрафМеню

if(array_key_exists('confnum', $arg)){
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));

	if(!empty($_POST['menu'])) $param = $_POST['menu'];
	$regions = spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_region");
	echo "Текущее меню: <b>{$regions[$param]}</b>";
	echo "<form method=\"post\"><select name=\"menu\">";
	foreach($regions as $k=>$v){
		echo "<option value=\"$k\"".($k == $param ? " selected" : '').">$v</option>";
	}
	echo "</select><input type=\"submit\" value=\"Изменить\"></form>";

	if (!empty($param)) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	return;
}

$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));

echo <<<EOF
<style>
	.jimgMenu {position: relative; width: 670px; height: 200px; overflow: hidden; margin: 25px 0px 0px; }
	.jimgMenu ul {list-style: none; margin: 0px; display: block; height: 200px; width: 10000px;}
	.jimgMenu ul li {float: left; }
	.jimgMenu ul li a {text-indent: -1000px; background:#FFFFFF none repeat scroll 0%; border-right: 2px solid #fff; cursor:pointer; display:block; overflow:hidden; width:78px; height: 200px; }
</style>
<script type="text/javascript" src="/include/image-menu-1/jquery-easing-1.3.pack.js"></script>
<script type="text/javascript" src="/include/image-menu-1/jquery-easing-compatibility.1.2.pack.js"></script>
<!--[if IE]>
	<style type="text/css">.jimgMenu {position:relative;width:630px;height:200px;overflow:hidden;margin-left:20px;}</style>
<![endif]-->
<script type="text/javascript">
	$(document).ready(function () {
		$('div.jimgMenu ul li a').hover(function() {
			if ($(this).is(':animated')) {
				$(this).stop().animate({width: "310px"}, {duration: 450, easing:"easeOutQuad"});
			} else {
				$(this).stop().animate({width: "310px"}, {duration: 400, easing:"easeOutQuad"});
			}
		}, function () {
			if ($(this).is(':animated')) {
				$(this).stop().animate({width: "78px"}, {duration: 400, easing:"easeInOutQuad"})
			} else {
				$(this).stop('animated:').animate({width: "78px"}, {duration: 450, easing:"easeInOutQuad"});
			}
		});
		$('div.jimgMenu ul li a:last').css('min-width', '310px');
	});
	</script>
</head>
<body>
EOF;

echo '<div class="jimgMenu"><ul>';
foreach(mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE region_id=".(int)$param." ORDER BY sort LIMIT 5")) as $k=>$v){
	echo <<<EOF
	<li class="urban">
		<a href="{$v['href']}" style="background: url(/{$arg['modpath']}:img/{$v['id']}/w:320/h:200/c:1/null/img.jpg) repeat scroll 0%;">{$v['name']}</a>
	</li>
EOF;
}
echo '</ul></div>';

?>
