<div class="<?=$arg['modpath']?>_<?=$arg['fn']?>">
	<style>
		.pages_docs ul {margin-left:20px; padding:10px 0;}
		.pages_docs li {padding:5px;}
	</style>
	<? $f = function($path) use(&$f, $arg, $conf){  ?>
		<ul>
			<? while($file = readdir($dir ?: $dir = opendir($d = mpopendir("modules/{$arg['modpath']}/docs/{$path}")))): if(substr($file, 0, 1) == ".") continue; ?>
				<? if(is_dir("{$d}/{$file}")): ?>
					<li><?=$file?></li>
					<? $f($file) ?>
				<? else: ?>
					<li><a href="/<?=$arg['modname']?>:<?=$arg['fn']?>/null/<?=$path?>/<?=$file?>"><?=$file?></a></li>
				<? endif; ?>
			<? endwhile; ?>
		</ul>
	<? }; $f(""); ?>
</div>
