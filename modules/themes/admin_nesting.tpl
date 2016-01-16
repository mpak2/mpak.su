<form method="post">
	<p><textarea name="html" style="width:100%; height:300px;"><?=htmlspecialchars(get($_POST, "html"))?></textarea></p>
	<p><button>Проверить текст</button></p>
</form>
<? if($html = get($_POST, 'html')): ?>
	<? if($nesting = nesting($html)): ?>
		<? mpre($nesting) ?>
	<? else: ?>
		<? mpre("Ошибок в коде не выявлено") ?>
	<? endif; ?>
<? endif; ?>
