<ul>
	<? foreach(rb("index") as $modules): ?>
		<li>
			<h1><?=$modules['name']?></h1>
			<? if($init = mpopendir("modules/{$modules['folder']}/init.php")) ?>
			<pre>
				<? mpre(htmlspecialchars($tpl['init'][ $modules['folder'] ])) ?>
			</pre>
		</li>
	<? endforeach; ?>
</ul>
