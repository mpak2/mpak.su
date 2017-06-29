<div class="admin_diff">
	<style>
		.admin_diff .diff {
			width:100%;
		}
		.admin_diff .diff td{
			vertical-align : top;
			white-space    : pre;
			white-space    : pre-wrap;
			font-family    : monospace;
			background-color:#efefef;
		}
		.admin_diff .diff td.diffUnmodified {
			width:50%;
			background-color:white;
		}
		.admin_diff div.table > div > span:first-child{
			width:50%;
		}
		.admin_diff .diffDeleted span {
			border: 1px solid rgb(255,192,192);
			background: rgb(255,224,224);
		}
		.admin_diff .diffInserted span {
			border: 1px solid rgb(192,255,192);
			background: rgb(224,255,224);
		}
		.admin_diff .top div.table > div > span {
			padding:0 10px;
		}
		.admin_diff .top div.table > div > span:first-child {
			text-align:right;
		}
		</style>
		<p>
			<form>
				<input type="text" name="search" value="<?=(get($_GET, 'search') ?: "modules/themes/404.tpl")?>" placeholder="Путь к файлу" style="width:60%;">
				<button type="submit">Найти</button>
			</form>
		</p>
	<? if($search = get($_GET, 'search')): ?>
		<div class="top" style="padding:10px;">
			<? foreach((array)get($tpl, 'search') as $search): ?>
				<div class="table">
					<div>
						<span><?=$search['file2']?></span>
						<span><?=$search['file1']?></span>
					</div>
				</div>
			<? endforeach; ?>
		</div>
		<? foreach((array)get($tpl, 'search') as $search): ?>
			<div class="table">
				<div>
					<span><h2 style="font-size:100%;"><?=$search['file2']?></h2></span>
					<span><h2 style="font-size:100%;"><?=$search['file1']?></h2></span>
				</div>
			</div>
			<div>
				<?=$search['html']?>
			</div>
		<? endforeach; ?>
	<? elseif($folder = "/home/d/d89650lq/chudesa-sveta-hh.ru/public_html/modules"): ?>
		<? /*$d = function($f) use(&$d){ ?>
			<? if($dir = $dir = opendir($f)): ?>
				<? while($file = readdir($dir)){ if(substr($file, 0, 1) == ".") continue; ?>
					<? mpre($file, "$folder/$file", mpreaddir("$folder/$file")) ?>
				<? } ?>
			<? endif; ?>
		<? } $d($filder);*/ ?>
	<? endif; ?>
</div>
