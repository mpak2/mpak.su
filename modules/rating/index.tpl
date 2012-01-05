<? if($_GET['id']): ?>
	<style media="screen">
		.tar { text-align:right; }
		.pd { padding:5px; }
		.lvl {margin:0 0 0 10px; float:right;}
		.rvl {margin:0 5px 0 0;}
		.ves_txt {width:150px;}
		.ves_dat {min-height:40px;}
		input:disabled {color:#aaa;}
	</style>
	<script language="javascript">
		$(document).ready(function(){
			$(".ves_btn").mousedown(function(){
				var ves = $(this).attr("ves");
				var name = $(".ves_txt[ves="+ves+"]").val();
				$.post("/<?=$arg['modpath']?>:<?=$arg['fn']?>/<?=(int)$_GET['id']?>/null", {ves:ves, name:name}, function(data){
					if(ves == 'true'){
						var add = '<div class="ves_dat"><div style="width:130px; float:left; clear:both;"><input type="button" class="rvl" value="Согласен" disabled><span class="count">1</span></div><div style="margin-left:100px;">'+data+'</div></div>';
					}else{
						var add = '<div class="ves_dat"><div style="width:130px; float:right; clear:both;"><input type="button" class="lvl" value="Согласен" disabled><span class="count">1</span></div><div style="margin-right:110px;">'+data+'</div></div>';
					}
					$(".data[ves="+ves+"]").append(add);
					$(".ves_txt").val("");
				})
			});
			$(".lvl").add(".rvl").mousedown(function(){
				var desc = $(this).attr("desc");
				var count = $(".count[desc="+desc+"]").text();
				$.post("/<?=$arg['modpath']?>:<?=$arg['fn']?>/<?=(int)$_GET['id']?>/null", {desc:desc}, function(data){
//					alert(data);
					$(".count[desc="+desc+"]").text(parseInt(count)+1);
				});
				$(this).attr("disabled", "disabled");
				return false;
			});
		});
	</script>
	<div><a href="/<?=$arg['modpath']?>">Весь список</a></div>
	<h1 style="text-align:center;"><?=$conf['tpl']['taxi'][0]['sname']?>: <?=$conf['tpl']['taxi'][0]['name']?></h1>
	<div>
		<table style="width:100%;">
			<tr>
				<td class="tar pd" style="width:50%;" valign="top">
					<div class="data" ves="false">
						<div style="color:red;">Не понравилось</div>
						<? foreach($conf['tpl']['ves'] as $k=>$v): if($v['ves'] > 0) continue; ?>
							<div class="ves_dat">
								<div style="float:right; width:130px;">
									<input type="button" class="lvl" value="Согласен" desc="<?=$v['id']?>" <?=($v['sess_id'] ? "disabled" : "")?>>
									<span class="count" desc="<?=$v['id']?>"><?=(int)$v['count']?></span>
								</div>
								<div style="margin-right:110px;"><?=$v['name']?></div>
							</div>
						<? endforeach; ?>
					</div>
				</td>
				<td class="pd" valign="top">
					<div class="data" ves="true">
						<div style="color:green;">Понравилось</div>
						<? foreach($conf['tpl']['ves'] as $k=>$v): if($v['ves'] < 0) continue; ?>
							<div class="ves_dat">
								<div style="width:130px; float:left;">
									<input type="button" class="rvl" value="Согласен" desc="<?=$v['id']?>" <?=($v['sess_id'] ? "disabled" : "")?>>
									<span class="count" desc="<?=$v['id']?>"><?=(int)$v['count']?></span>
								</div>
								<div style="margin-left:125px;"><?=$v['name']?></div>
							</div>
						<? endforeach; ?>
					</div>
				</td>
			</tr>
			<tr>
				<td class="tar pd">
					<input type="button" class="ves_btn" ves="false" value="Добавить">
					<input type="text" class="ves_txt" ves="false">
				</td>
				<td class="pd">
					<input type="text" class="ves_txt" ves="true">
					<input type="button" class="ves_btn" ves="true" value="Добавить">
				</td>
			</tr>
		</table>
	</div>
<? else: ?>
	<script language="javascript">
		$(document).ready(function(){
			$("#index_btn").mousedown(function(){
				var index = $("#index").val();
				var type_id = $("#type_id").find("option:selected").val();
				var type = $("#type_id").find("option:selected").text();
				if(type_id){
					$.post("/<?=$arg['modpath']?>:<?=$arg['fn']?>/<?=(int)$_GET['id']?>/null", {index:index, type_id:type_id}, function(data){
						$("#tb").append('<tr><td></td><td>'+type+'</td><td><a href="/<?=$arg["modpath"]?>:<?$arg["fn"]?>/'+data+'">'+index+'</a></td><td></td><td></td><td></td><td></td></tr>');
						$("#index").val('');
						document.location.href = "/<?=$arg['modpath']?>/"+data;
					});
				}else{
					alert("Укажите тип системы");
				}
			});
			$("#type_id").change(function(){
				var type_id = $(this).find("option:selected").val();
				document.location = "/<?=$arg['modpath']?>"+(type_id ? "/type_id:"+type_id : '');
			});
		});
	</script>
	<style>
		#tb tr:nth-child(odd) {background: #FFF}
	</style>
	<div style="margin:10px 0;">
		<select id="type_id"><option></option>
			<? foreach($conf['tpl']['type'] as $k=>$v): ?>
				<option value="<?=$v['id']?>"<?=($v['id'] == $_GET['type_id'] ? " selected" : '')?>><?=$v['name']?><?=($v['iid'] ? " ({$v['cnt']})" : '')?></option>
			<? endforeach; ?>
		</select>

		<input type="text" id="index" <?=($_GET['type_id'] ? "" : "disabled")?>> <input type="button" id="index_btn" value="Добавить портальную систему">
		<span style="margin:0 20px;">
			<b>Просмотров</b>: <?=$conf['tpl']['rating']['vw']?> <b>Отзывов</b>: <?=$conf['tpl']['rating']['cnt']?> <b>Общий рейтинг</b>: <?=$conf['tpl']['rating']['sm']?>
		</span>
	</div>
	<div>
		<div id="data">
			<table style="min-width:80%;" id="tb">
				<tr>
					<th>Номер</th>
					<th>Тип</th>
					<th>CMS</th>
					<th>Просмотров</th>
					<th>Понравилось</th>
					<th>Непонравилось</th>
					<th>Рейтинг</th>
				</tr>
			<? foreach($conf['tpl']['taxi'] as $k=>$v): ?>
				<tr class="hv">
					<td><?=($k+1)?></td>
					<td><?=$v['sname']?></td>
					<td><a href="/<?=$arg['modpath']?>:<?=$arg['fn']?>/<?=$v['id']?>"><?=$v['name']?></a></td>
					<td><?=($v['view'] ?: '')?></td>
					<td><?=(count($conf['tpl']['ves'][ $v['id'] ][1]) ?: "")?></td>
					<td><?=(count($conf['tpl']['ves'][ $v['id'] ][-1]) ?: "")?></td>
					<td><?=$v['sm']?></td>
				</tr>
			<? endforeach; ?>
			</table>
		</div>
	</div>
<? endif; ?>