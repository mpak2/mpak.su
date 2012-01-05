<table height="29" cellspacing="0" cellpadding="0">
	<tr>
	<? foreach($menu as $k=>$v): ?>
		<td id="mmtd0" style="height:29px;" onmouseover="this.className='mmtd';" onmouseout="this.className='';"  >
			<a class="mml" onmouseover="cssdropdown.dropit(this,event,'mmdiv16')" href="<?=$v['link']?>" ><?=$v['name']?></a>
		</td>
	<? endforeach; ?>
	</tr>
</table>
