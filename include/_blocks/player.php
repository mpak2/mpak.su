<? die; # Плеер

if ((int)$arg['confnum']){
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

echo <<<EOF
	<form method="post">
		Адрес музыкального файла: 
		<p /><input type="text" name="param[url]" value="$param[url]" style="width:80%;"> <input type="submit" value="Сохранить">
	</form>
EOF;

	return;
}
$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));
//$autoplay = ($conf['user']['sess']['count'] == 1 ? "changeAndPlay" : "change");

echo <<<EOF
	<script type=text/javascript src="/include/jquery_player/js/jquery.jplayer.js"></script>
	<script>$(document).ready(function(){
		$("#jquery_jplayer").jPlayer({
			ready: function () {
				$("#jquery_jplayer").changeAndPlay('{$param[url]}');
			},
			cssPrefix: "different_prefix_example"
		});
		$("#jquery_jplayer").jPlayerId("play", "player_play");
		$("#jquery_jplayer").jPlayerId("pause", "player_pause");
		$("#jquery_jplayer").jPlayerId("stop", "player_stop");
	});
	</script>

	<style>
		#player_container {POSITION: relative; BACKGROUND-COLOR: #fff; WIDTH: 90px; HEIGHT: 30px;  border: 2px solid #CCCCCC}
		#player_container UL#player_controls {PADDING-BOTTOM: 0px; LIST-STYLE-TYPE: none; MARGIN: 0px; PADDING-LEFT: 0px; PADDING-RIGHT: 0px; PADDING-TOP: 0px}
		#player_container UL#player_controls LI {TEXT-INDENT: -9999px; OVERFLOW: hidden}
		#player_play {POSITION: absolute; WIDTH: 24px; DISPLAY: block; HEIGHT: 24px; TOP: 3px; CURSOR: pointer; LEFT: 15px}
		#player_pause {POSITION: absolute; WIDTH: 24px; DISPLAY: block; HEIGHT: 24px; TOP: 3px; CURSOR: pointer; LEFT: 15px}
		#player_play {BACKGROUND: url(/include/jquery_player/images/play.png) no-repeat;}
		.different_prefix_example_hover#player_play {BACKGROUND: url(/include/jquery_player/images/play2.png) no-repeat;}
		#player_pause {BACKGROUND: url(/include/jquery_player/images/pause.png) no-repeat;}
		.different_prefix_example_hover#player_pause {BACKGROUND: url(/include/jquery_player/images/pause2.png) no-repeat;}
		#player_stop {POSITION: absolute; WIDTH: 22px; BACKGROUND: url(/include/jquery_player/images/stop.png) no-repeat; HEIGHT: 22px; TOP: 4px; CURSOR: pointer; LEFT: 52px}
		.different_prefix_example_hover#player_stop {BACKGROUND: url(/include/jquery_player/images/stop2.png) no-repeat;}
	</style>
	<div id=jquery_jplayer></div>
	<div id=player_container>
		<ul id=player_controls>
			<li id=player_play><a title=play onclick="$('#jquery_jplayer').play(); return false;" href="#"><span>play</span></a></li>
			<li id=player_pause><a title=pause onclick="$('#jquery_jplayer').pause(); return false;" href="#"><span>pause</span></a></li>
			<li id=player_stop><a title=stop onclick="$('#jquery_jplayer').stop(); return false;" href="#"><span>stop</span></a></li>
		</ul>
	</div>
EOF;

?>