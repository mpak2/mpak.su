<?

if(!$SESS = qn($sql = "SELECT * FROM {$conf['db']['prefix']}sess WHERE last_time > ". (time()-abs($conf['settings']['sess_time'])). " AND LENGTH(sess)=32 ORDER BY last_time DESC")){
}elseif(!$USERS = rb("{$conf['db']['prefix']}users", "id", "id", rb($SESS, "uid"))){
}elseif(!$BOTS = $logo = array("Mediapartners-Google.png"=>"Mediapartners-Google", "naver.com.png"=>"naver", "C-T bot"=>"c-t_bot.png", "statdom.png"=>"statdom", "ezooms.png"=>"ezooms", "ahrefs.png"=>"ahrefs", "yahoo.png"=>"Yahoo", "rambler.png"=>"StackRambler", "msnbot.png"=>"msnbot", "bing.png"=>"bing", "cctld.ru.png"=>"cctld.ru/bot", "adsbot.png"=>"adsbot", "archive.org.png"=>"archive.org_bot", "begun.png"=>"Begun", "majestic12.png"=>"majestic12", "mail.ru.png"=>"Mail.RU", "picsearch.png"=>"picsearch", "semrush.png"=>"semrush", "opensiteexplorer.png"=>"opensiteexplorer", "exabot.png"=>"exabot", "openlinkprofiler.png"=>"OpenLinkProfiler", "surly.png"=>"SurdotlyBot", "queryseekerspider.png"=>"queryseeker", "cliqzbot.png"=>"cliqzbot", "twitterbot.png"=>"Twitterbot", "miralinks.png"=>"Miralinks", "wotbox.png"=>"wotbox", "digincore.png"=>"digincore", "ct.png"=>"C-T bot", "linkdex.png"=>"linkdexbot", "yesup.png"=>"yesup.net", "meanpath.png"=>"meanpathbot", "robot.png"=>"robot", "semanticbot.png"=>"semanticbot", "tobbot.png"=>"tobbot.com", "studydoc.png"=>"studydoc.ru", "applebot.png"=>"applebot", "mfisoft.png"=>"mfisoft.ru", "plukkie.png"=>"Plukkie", "yacybot.png"=>"yacybot", "safedns.png"=>"SafeDNS", "irlbot.png"=>"IRLbot", "focusbot.png"=>"focusbot", "gbot.png"=>"gbot", "google-image.png"=>"Googlebot-Image", "google.png"=>"Googlebot", "yandeximages.png"=>"YandexImages", "yandexmetrika.png"=>"YandexMetrika", "yadirectfetcher.png"=>"YaDirectFetcher", "yandexmobile.png"=>"YandexMobileBot", "yandexfavicon.png"=>"YandexFavicons", "yandex.png"=>"Yandex", "bot.png"=>"bot")){ mpre("Ошибка задания списка ботов");
}elseif(!$OS = array("mobi.png"=>" Mobile ", "win.png"=>"Windows", "macintosh.png"=>'Macintosh', "linux.png"=>"Linux", "android.png"=>"Android")){ mpre("Ошибка задания списка операционных систем");
}elseif(!$guest = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}users WHERE name=\"". mpquot($conf['settings']['default_usr']). "\""), 0)){ mpre("Гость не найден");
}elseif(!$SESS = array_map(function($sess) use($BOTS, $OS, $arg){
		if((!$sess += (($ar = array_filter(array_map(function($bot, $img) use($sess, $arg){
				return (strpos($sess['agent'], $bot) ? ["bot"=>$bot, "bot_img"=>"/{$arg['modpath']}:img/w:40/h:40/null/{$img}"] : []);
			}, $BOTS, array_keys($BOTS)))) ? first($ar) : ['bot'=>'', "bot_img"=>""])) &0){ mpre("Ошибка определения в боте агента");
		}elseif((!$sess += (($ar = array_filter(array_map(function($os, $img) use($sess, $arg){
				return (strpos($sess['agent'], $os) ? ["os"=>$os, "os_img"=>$img] : []);
			}, $OS, array_keys($OS)))) ? first($ar) : ['os'=>'', 'os_img'=>''])) &0){ mpre("Ошибка определения операционной системы по агенту");
		}else{ return $sess; }
	}, $SESS)){ mpre("Ошибка определения свойств посетителей");
}elseif(!$OSS = rb($SESS, "os", "id")){ mpre("ОШибка распредления пользователей по операционным системам");
}elseif(!$BOTSS = rb($SESS, "bot", "id")){ mpre("ОШибка распредления пользователей по ботам");
}elseif(!$USERS = rb($SESS, "uid", "id")){ mpre("ОШибка распредления пользователей по ботам");
}else{ ?>
	<h3>Пользователи</h3>
	<? foreach($USERS as $uid=>$users): ?>
		<? if(!$uid):// mpre("Гость") ?>
		<? elseif(!$user = rb("{$conf['db']['prefix']}users", "id", $uid)): mpre("Ошибка определения пользователя") ?>
		<? else:// mpre("users", $users) ?>
			<div style="display:inline-block; margin:1px; border:1px solid #ddd; position:relative;" title="<?=$user['name']?>">
				<a href="/users/412707">
					<div style="position:absolute; top:1px; left:1px; opacity:0.8; background-color:white; width:15px; height:15px;"><?=count($users)?></div>
					<div style="position:absolute; top:1px; right:1px; opacity:0.8;"><img src="/users:img/w:15/h:15/null/<?=last(array_column($users, 'os_img', 'id'))?>"></div>
					<img src="/users:img/<?=$uid?>/tn:index/w:40/h:40/c:1/null/img.jpg" style="z-index:-1; padding:1px;">
				</a>
			</div>
		<? endif; ?>
	<? endforeach; ?>

	<h3>Операционные системы</h3>
	<? foreach($OSS as $os=>$oss): ?>
		<? if(!$os):// mpre("Ось не определена") ?>
		<? elseif(!$img = array_search($os, $OS)): mpre("Изображение ОС не найдено {$os}"); ?>
		<? else: ?>
			<div style="display:inline-block; margin:1px; border:1px solid #ddd; position:relative;" title="<?=$os?>">
				<a href="/users/412707">
					<div style="position:absolute; top:1px; left:1px; opacity:0.8; background-color:white; width:15px; height:15px;"><?=count($oss)?></div>
					<div style="position:absolute; top:1px; right:1px; opacity:0.8;"><img src="/users:img/w:15/h:15/null/<?=$img?>"></div>
					<img src="/users:img/412707/tn:index/w:40/h:40/c:1/null/img.jpg" style="z-index:-1; padding:1px;">
				</a>
			</div>
		<? endif; ?>
	<? endforeach; ?>

	<h3>Боты</h3>
	<? foreach($BOTSS as $bot=>$bots): ?>
		<? if(!$bot):// mpre("Не бот") ?>
		<? elseif(!$img = array_search($bot, $BOTS)): mpre("Изображение ОС не найдено {$os}"); ?>
		<? else: ?>
			<div style="display:inline-block; margin:1px; border:1px solid #ddd; position:relative;" title="<?=$bot?>">
				<a href="/users/412707">
					<div style="position:absolute; top:1px; left:1px; opacity:0.8; background-color:white; width:15px; height:15px;"><?=count($bots)?></div>
					<div style="position:absolute; top:1px; right:1px; opacity:0.8;"><img src="/users:img/w:15/h:15/null/<?=$img?>"></div>
					<img src="/users:img/412707/tn:index/w:40/h:40/c:1/null/img.jpg" style="z-index:-1; padding:1px;">
				</a>
			</div>
		<? endif; ?>
	<? endforeach; ?>
<? /*mpre($SESS);*/ }

