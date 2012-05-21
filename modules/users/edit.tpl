	<script type="text/javascript" src="/include/jquery/jquery.jeditable.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {  
//			$('.jedit').not('#description').editable(
			$('.jedit').editable(
				'/users:edit/<?=$_GET["id"]?>/null',
				{
					width : '80%',
					height: 'none',
					style : 'display: inline',
					submit : 'ОК',
					indicator : "<img src='data:image/jpeg;base64,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'>"}
			);
/*			$('#description').editable(
				'/users:edit/<?=$_GET["id"]?>/null',
				{
					type : 'textarea',
					width : '80%',
					height: 'none',
					style : 'display: inline',
	//				submit : 'Сохранить',
					indicator : "<img src='data:image/jpeg;base64,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'>"}
			);*/
			<? foreach($conf['tpl'] as $k=>$v): if(substr($k, -3) != '_id') continue;?>
				$('#<?=$k?>').editable(
					"/<?=$arg['modpath']?><?=($arg['fn'] ? ":{$arg['fn']}" : '')?>/<?=$_GET['id']?>/null",
					{
						data : '<?=json_encode($v)?>',
						style : 'display: inline',
						type : 'select',
						submit : 'OK',
						indicator : "<img src='data:image/jpeg;base64,R0lGODlhEAAQAIQAAAQCBISChMTCxFxaXOzq7BweHLy6vPT29IyOjAwKDPTy9CwuLIyKjNze3Ozu7CQiJLy+vPz+/JSWlAwODAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh+QQIBwAAACwAAAAAEAAQAAAFXmAkjhFBkChJJNOZRtIjlVMbOYgrPsAjmiIGgEGKzVCE3GtJCgSYTQDguXRGAlLqy3pwHqAkBUQBjigKgAIZ9R0ZpABDYzBoRA6TthldcCykCyJ6ImJkCVIJZVhTKSEAIfkECAcAAAAsAAAAABAAEACEBAIEhIaExMbEPDo87OrsVFJUvLq89Pb0ZGZkFBYUzM7MXFpcBAYElJKUzMrMREJE7O7sVFZUxMLE/P78AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABVngJI5kaYrDcIrOIqCq+JIREJkSIJFO5Jy7lVBkMAxHBgDAuCpOkkuh88k8jiAnRUBBKgAKJQdD+ZtAlADIodGYBNCBkReMUCIc6LKIIHooVVpcJwYJCVUjIQAh+QQIBwAAACwAAAAAEAAQAIQEAgSEgoTEwsREQkSkoqTk4uQUFhT08vSMjoy0srQcHhzs6uz8/vy8urwMDgyMiox0cnQcGhz09vSUlpQkIiTs7uy8vrwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAFYyAjjmRpikFwihWyoKoolQ/wmJIzj4t77qsgY/FaJSKRxMLhKJoiAIChwHSWDFHD0FpKGJJCxkFQIU0ok9JBAVAcRhQARSQQMBpRQGN0ThOiBBVsCmUmEFEQYhZvJwUDAwUmIQAh+QQIBwAAACwAAAAAEAAQAIQEAgSEhoTExsRMTkzs6uzU1tR8enw8OjykpqRUVlT8+vwMDgzMzsy8urwEBgTMysxUUlTs7uzc3txcWlz8/vzEwsQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAFXSAljmRpik0jMgFTCmNKPQ4APGMFVGVgAwESr/T44U4klgtZkkAgEsrhwITYBlIqcnBljpyDqJcSIU5gJSuElAAkRJFy5FcWPRK4wmJRoFixJwg2CCIETAoGBgomIQAh+QQIBwAAACwAAAAAEAAQAIQEAgSEgoTEwsS0srQcHhzs6uz09vQUFhSMjoy8urw0NjT08vQMDgyMiozc3ty0trQkIiTs7uz8/vwcGhyUlpS8vrwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAFXqAkjsN0DGOqTgBwSIuwqIZ4tMdCAMQsGoza4DAZJFqARKqm0vEiqiis4pPSAgER1ioJtLJbqxeQ5f6wTHM0gihEKRBKqgFoRCEASKrQlhgUCjVwclIOLQ5qIgkPUiEAIfkECAcAAAAsAAAAABAAEACEBAIEhIaExMbETE5M5ObkfHp83N7cVFZU/P78FBYUvLq8zM7M7O7sBAYEzMrMVFJU7OrsXFpcxMLEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABVwgIo7G8xhjiizBgjwAMIhM6jSxM8Qz/IyBGCBQGhgYwhrCIXSoYLMRy6VCQKrYkUIh2mYRihjXmw0DuF8tOp0VYB0R90gCkFQPgIPKLioURA4HTlkJCWwjBARYIQAh+QQIBwAAACwAAAAAEAAQAIQEAgSEgoTEwsS0srRUUlQUFhTs6uz09vSMjoy8urwcHhz08vQMDgyMioy0trQcGhzs7uz8/vyUlpS8vrwkIiQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAFYGAkjkcQHGMaLcISBQAQqOKiAMoCy5FESaNEDJAonSIUAGVkw0FUPiBz4qKlHI/CwCoyGCKFWIFrYDAMYcDYWj4PCo8t+cuti1A0CIJ+Z+BTDQANKn8ODl17dQQEdo0iIQAh+QQIBwAAACwAAAAAEAAQAIQEAgSEhoTExsQ8Ojzs6uxMTky8urzc3txUVlTMzsz8/vwEBgSUkpTMyszs7uxUUlTEwsRcWlwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAFW6AijophkKjiiAYAnKnyAE/pnk0kjI4LrCYRAoAgzQqpBqKBIsRQh8fj8CQVXMjnYKC4ArKxrSJaoFZJiUDiLGosXEzUbhTwBVAQAGTU8DEPZgp7aGoiDAxsiSEAOw=='>"
					}
				);
			<? endforeach; ?>
		});
	</script>
<style>
	.wt {
		width:80%;
	}
	.nm {
		width: 30%;
		text-align: right;
		font-weight: bold;
		margin-top:10px;
	}
	.jedit, .jarea{
		clear:both;
#		border:1px solid red;
		width:250px;
		height:18px;
		float:left;
		padding:0 0 10px 20px;
		background-image: url(data:image/gif;base64,R0lGODlhEAAQANUjAACd/zuW26XX9MonAAA3nRxvztb6/3W87gyZ+So1Qv/LUv82AF06AHa87pNfAACb///ss2ZlzAuZ+QA0oZeX////+f/65//zzdH6/zGU4qXX9R1vzszM/9L4/x1v1//rpBxv0f/M/5MAAP///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEAACMALAAAAAAQABAAAAZrwJFwSBwNjsXkaMNZhAbK4UYQaS6gyoKgc4hQrlmB4RCQeEVJraFRfgAmaTEbAXjH1wE6gJAEafB6fEUeGhhkgUkOCoYZEnWCRQ4VChkPbpBEkhYXCpdRDpsQHwpwUQkJEBAMDFFCp6utQ0EAOw==);
		background-repeat: no-repeat;
		margin-left:10px;
	}
	#basic-modal-content {
		display:none;
		background-color:#eee;
		width:320px;
		height:120px;
		-webkit-border-radius: 20px;
		-moz-border-radius: 20px;
		border-radius: 20px;
		padding:20px;
		color: white;
	}
</style>
	<!-- [settings:foto_lightbox] -->
	<script type="text/javascript" src="/include/jquery/simplemodal-demo-basic/js/jquery.simplemodal.js"></script>
	<script type='text/javascript'>
		$(document).ready(function (e) {
			$('.basic').click(function(){
				$('#basic-modal-content').modal();
				return false;
			});
			$('#editpass').mousedown(function(){// alert("mousedown");
				$.post('/<?=$arg["modpath"]?>:pass/<?=$_GET["id"]?>/null', {"old":$("#old").val(), "new":$("#new").val(), "ret":$("#ret").val()}, function(data){
					$('#passresult').html(data);
				});
			});
		});
	</script>
	<div id="basic-modal-content">
		<table>
			<tr>
				<td>Старый пароль: </td>
				<td><input id="old" type="password"<?=($arg['access'] >= 5 ? ' disabled' : '')?>></td>
			</tr>
			<tr>
				<td>Новый пароль: </td>
				<td><input id="new" type="password"></td>
			</tr>
			<tr>
				<td>Повторите пароль: </td>
				<td><input id="ret" type="password"></td>
			</tr>
			<tr>
				<td id="passresult" style="color:red; text-align:right;">&nbsp;</td>
				<td>
					<input id="editpass" type="button" value="Изменить">
					<a class="simplemodal-close" href="/" onClick="javascript: void(0);">Закрыть</a>
				</td>
			</tr>
		</table>
	</div>

	<div align='center'>
		<table cellspacing='0' cellpadding='3' style="width:100%;">
			<? if(isset($conf['tpl']['user']['img'])): ?>
				<tr>
					<td rowspan="2" style="float:right;">
						<div id="gallery">
						<a href="/<?=$arg['modpath']?>:img/<?=$conf['tpl']['uid']?>/tn:index/w:600/h:400/null/img.jpg">
							<img src="/<?=$arg['modpath']?>:img/<?=$conf['tpl']['uid']?>/tn:index/w:150/h:200/null/img.jpg">
						</a>
						</div>
					</td>
					<td valign="bottom">
						<form method="post" enctype="multipart/form-data">
							<input type="file" name="img"><input type="submit" value="Добавить">
						</form>
					</td>
				</tr>
			<? endif; ?>
			<? foreach(array_intersect_key($conf['tpl']['user'], $conf['tpl']['fields']) as $k=>$v): ?>
			<tr>
				<td class="nm">
					<span title="<?=($f = "users_field_$k")?>">
						<?=($conf['settings'][$f] ? $conf['settings'][$f] : $f)?>
					</span>
				</td>
				<td>
					<div id="<?=$k?>" class="<?=($conf['tpl'][$k] ? 'jarea jarea_'. $k : 'jedit')?>"><?=($conf['tpl'][$k] ? $conf['tpl'][$k][$v] : $v)?></div>
				</td>
			</tr>
			<? endforeach; ?>
			<tr>
				<td class="nm">Пароль</td>
				<td><a href="#" class="basic" style="margin-left:20px; color:yellow;">Изменить</a></td>
			</tr>
		</table>
	</div>
