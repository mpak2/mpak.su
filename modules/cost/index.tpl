<? if(!array_key_exists("null", $_GET)) include "menu.tpl"; ?>
<div class="base">
	<? if(!array_key_exists("null", $_GET)): ?>
		<script>
			function log(str, level){ if(level == "setInterval") console.log(str); };
			$(function(){
				setInterval(function(){
					$(".workers > div").each(function(key, val){
						if(duration = $(this).attr("duration") === "0"){
							time = parseInt($(this).attr("time"))+1;
							$(this).attr("time", time); log("time:"+time, "setInterval");
							log("Math.floor(time/86400):"+Math.floor(time/86400), "setInterval");
							var days = Math.floor(time/86400); if(days <= 0) days = ""; log("days:"+days, "setInterval");
							var hours = Math.floor(time/3600)%24; if(hours <= 9) hours = "0"+hours;
							var minutes = Math.floor(time/60)%60; if(minutes <= 9) minutes = "0"+minutes;
							var seconds = time%60; if(seconds <= 9) seconds = "0"+seconds;
							$(this).find(".time").text(days + ' ' + hours + ':' + minutes + ':' + seconds);

							course = $(this).parents(".workers").attr("course"); log("course:"+course);
							$(this).find(".cost").text(cost = (time*course).toFixed(2)); log("cost:"+cost);
						}
					});
				}, 1000);
			});
		</script>
	<? endif; ?>
	<? if($uid = $tpl['workers'][ $_GET['uid'] ]): ?>
		<style>
			div[week] {display:none; margin-left:20px; overflow:hidden;}
			.year {overflow:hidden;}
		</style>
		<script>
			$(function(){
				$("a[week]").click(function(){
					week = $(this).attr("week"); console.log(week);
					$("div[week="+week+"]").slideToggle();
				});
			});
		</script>
		<span style="float:right;"><?=$uid['fm']?> <?=$uid['im']?> <?=$uid['ot']?></span>
		<h1><?=$uid['uname']?></h1>
		<div><?=$tpl['mpager']?></div>
		<div class="workers modules" course="<?=$uid['course']?>">
			<? foreach($tpl["projects_works"] as $v): ?>
				<div duration="<?=$v['duration']?>" time="<?=($v['time'] ? time()-$v['time'] : 0)?>" style="overflow:hidden;">
					<div>
						<span style="float:right;"><?=date("d.m.Y", $v['time'])?></span>
						<span><a href="/<?=$arg['modname']?>:projects/<?=$v['projects_id']?>"><?=$v['projects']?></a></span>
						<span><?=$v['works']?></span>
					</div>
					<div style="font-style:italic; margin-left:10px; color:#bbb;">
						<span style="float:right" title="<?=$v['duration']?>">
							<span class="cost" style="font-weight:bold;"><?=number_format($v['duration']*$uid['course'], 2)?></span>
							<span class="time"><?=($v['duration'] ? gmdate('H:i:s', $v['duration']) : gmdate('H:i:s', time()-$v['time']))?></span>
						</span>
						<? if($v['tasks_id']): ?>
							<a href="/<?=$arg['modname']?>:tasks/<?=$v["tasks_id"]?>"><?=$v["tasks_name"]?></a>
						<? else: ?>
							<?=$v['description']?>
						<? endif; ?>
					</div>
				</div>
			<? endforeach; ?>
		</div>
		<div><?=$tpl['mpager']?></div>
		<div style="margin-top:20px;">
			<? foreach($tpl['days'] as $year=>$year_data): ?>
				<div>
					Год: <b><?=$year?></b> <!-- <?=number_format($tpl['duration'][ $year ]['sum']*$uid['course'], 2)?> р. -->
				</div>
				<div class="year">
					<? foreach($year_data as $month=>$month_data): ?>
						<div style="margin:10px 0;">
							Период: <b><?=$tpl['month'][ $month ]?></b> <?=number_format($tpl['duration'][ $year ][ $month ]['sum']*$uid['course'], 2)?> р.
						</div>
						<div>
							<? foreach($month_data as $week=>$week_data): ?>
								<div>
									<span style="float:right;"><a week="<?=$week?>" href="javascript:">Открыть</a></span>
									Неделя: <b><?=$week?></b> <?=number_format($tpl['duration'][ $year ][ $month ][ $week ]['sum']*$uid['course'], 2)?> р.
								</div>
								<div week="<?=$week?>">
									<? foreach($week_data as $d=>$v): ?>
										<div>
											<div style="float:right;">
												<span style="font-weight:bold;"><?=number_format($v[0]["sum"]*$uid['course'], 2)?></span>
												<span><?=gmdate('H:i:s', $v[0]["sum"])?></span>
											</div>
											<?=$d?>
										</div>
									<? endforeach; ?>
								</div>
							<? endforeach; ?>
						</div>
					<? endforeach; ?>
				</div>
			<? endforeach; ?>
		</div>
		<div style="projects_stat">
			<? foreach($tpl['projects_stat'] as $ud=>$projects_uid): ?>
				<h3 style="overflow:hidden;">
					<span style="float:right;">
						<span style="font-weight:normal;" title="Выплачено"><?=number_format($tpl['wages'][ $ud ]['sum'], 2, '.', ' ')?> (<?=(int)$tpl['wages'][ $ud ]['cnt']?>)</span> /
						<span title="<?=number_format($tpl['projects_sum']["sum"][ $ud ]*$uid['course']-$tpl['wages'][ $ud ]['sum'], 2, '.', ' ')?>">
							<?=number_format($tpl['projects_sum']["sum"][ $ud ]*$uid['course'], 2, '.', ' ')?>
						</span>
						<? if($arg['access'] > 3): ?>
							<span>
								<a href="/?m[<?=$arg['modpath']?>]=admin&r=<?=$conf['db']['prefix']?><?=$arg['modpath']?>_wages&where[uid]=<?=$ud?>&where[workers_id]=<?=$tpl['workers'][ $uid['uid'] ]['id']?>">
									<img src="/img/aedit.png">
								</a>
							</span>
						<? endif; ?>
					</span>
					<?=$tpl['wks'][ $ud ]['name']?>
				</h3>
				<div style="overflow:hidden;">
					<? foreach($projects_uid as $projects_id=>$sum): ?>
						<div style="overflow:hidden;">
							<span style="float:right;">
								<? if($tpl['wages_projects'][ $ud ][ $projects_id ]['sum']): ?>
									<span><?=number_format($tpl['wages_projects'][ $ud ][ $projects_id ]['sum'], 2, '.', ' ')?> (<?=(int)$tpl['wages_projects'][ $ud ][ $projects_id ]['cnt']?>)</span>
								<? endif; ?>
								<span style="font-weight:bold;"><?=number_format($sum*$uid['course'], 2, '.', ' ')?></span>
							</span>
							<span><a href="/<?=$arg['modname']?>:projects/<?=$tpl['projects'][ $projects_id ]['id']?>"><?=$tpl['projects'][ $projects_id ]['name']?></a></span>
						</div>
					<? endforeach; ?>
				</div>
			<? endforeach; ?>
		</div>
	<? else: ?>
		<div class="workers modules">
			<? foreach($tpl['workers'] as $v): ?>
				<div duration="<?=$v['duration']?>" time="<?=($v['time'] ? time()-$v['time'] : 0)?>">
					<span class="time" style="float:right;"><?=($v['time'] ? gmdate('H:i:s', time()-$v['time']) : "")?></span>
					<span>
						<a href="/<?=$arg['modname']?>/uid:<?=$v['uid']?>"><?=$v['uname']?></a> <?=$v['fm']?> <?=$v['im']?> <?=$v['ot']?> <?=$v['name']?>
					</span>
					<div style="padding-left:10px;">
							<b><?=$v['projects']?></b>
						<? if($v['tasks_id']): ?>
							<span><a href="/<?=$arg['modname']?>:tasks/<?=$v['tasks_id']?>"><?=$v['tasks_name']?></a></span>
						<? else: ?>
							<span style="color:#bbb;"><?=$v['description']?></span>
						<? endif; ?>
					</div>
				</div>
			<? endforeach; ?>
		</div>
	<? endif; ?>
</div>