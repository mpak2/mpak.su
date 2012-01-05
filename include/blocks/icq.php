<? die; # icq

if ((int)$arg['confnum']){
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

echo <<<EOF
	<form method="post">
		<br>Текст блока:<input type="text" name="param[text]" value="{$param['text']}">
		<br>Внутренний текст: <input type="text" name="param[val]" value="{$param['val']}">
		<br>Номер icq: <input type="text" name="param[icq]" value="{$param['icq']}">
		<br><input type="submit" value="Сохранить">
	</form>
EOF;

	return;
}
$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));

echo <<<EOF
  <script>
    $(document).ready(function(){

	$("div#block_icq").hover(
		function() {
			$(this).find("div").show(300);
		},
		function() {
			$(this).find("div").hide(300);
		}
	)
    });
  </script>
  <div id="block_icq" style="position:relative;">
    {$param['text']}
      <div style="position:absolute; display:none; border:1px solid black; padding:50px 50px; text-align:center; background-color:#fff;">
      {$param['val']}
      <p />icq: <a href="http://wwp.icq.com/scripts/contact.dll?msgto={$param['icq']}">{$param['icq']}</a>
      </div>
   </div>
EOF;

?>