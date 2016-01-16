<?

if(array_key_exists('confnum', $arg)){
	return;
}

$online = mpql(mpqw($sql = "SELECT SQL_CALC_FOUND_ROWS u.*, s.count, s.cnull, s.id AS sid, s.agent, s.ref FROM {$conf['db']['prefix']}sess AS s LEFT JOIN {$conf['db']['prefix']}users AS u ON s.uid=u.id WHERE s.last_time > ". (time()-$conf['settings']['sess_time']). " AND CHAR_LENGTH(sess)=32 ORDER BY s.last_time DESC"));
$count = mpql(mpqw("SELECT FOUND_ROWS() AS count"), 0, 'count');
$logo = array(
	"Mediapartners-Google.png"=>"Mediapartners-Google",
	"naver.com.png"=>"naver",
	"C-T bot"=>"c-t_bot.png",
	"statdom.png"=>"statdom",
	"ezooms.png"=>"ezooms",
	"ahrefs.png"=>"ahrefs",
	"yahoo.png"=>"Yahoo",
 	"google.png"=>"Googlebot",
	"rambler.png"=>"StackRambler",
	"msnbot.png"=>"msnbot",
	"bing.png"=>"bing",
	"cctld.ru.png"=>"cctld.ru/bot",
	"adsbot.png"=>"adsbot",
	"archive.org.png"=>"archive.org_bot",
	"begun.png"=>"Begun",
	"majestic12.png"=>"majestic12",
	"mail.ru.png"=>"Mail.RU",
	"picsearch.png"=>"picsearch",
	"semrush.png"=>"semrush",
	"opensiteexplorer.png"=>"opensiteexplorer",
	"exabot.png"=>"exabot",
	"openlinkprofiler.png"=>"OpenLinkProfiler",
	"surly.png"=>"SurdotlyBot",
	"queryseekerspider.png"=>"queryseeker",
	"cliqzbot.png"=>"cliqzbot",
	"twitterbot.png"=>"Twitterbot",
	"miralinks.png"=>"Miralinks",
	"wotbox.png"=>"wotbox",
	"digincore.png"=>"digincore",
	"ct.png"=>"C-T bot",
	"linkdex.png"=>"linkdexbot",
	"yesup.png"=>"yesup.net",
	"meanpath.png"=>"meanpathbot",
	"robot.png"=>"robot",
	"semanticbot.png"=>"semanticbot",
	"tobbot.png"=>"tobbot.com",

	"yandexmetrika.png"=>"YandexMetrika",
	"yandex.png"=>"Yandex",
	"yadirectfetcher.png"=>"YaDirectFetcher",

	"bot.png"=>"bot",
);
$os = array(
	"mobi.png"=>"Mobi",
	"win.png"=>"Windows",
	"linux.png"=>"Linux",
);
if(!function_exists("strpos_array")){
	function strpos_array($haystack, $needles) {
		if(is_array($needles)){
			foreach ($needles as $img=>$str) {
				if(is_array($str)){
					$pos = strpos_array($haystack, $str);
				}else{
					$pos = strpos($haystack, $str);
				}
				if($pos !== FALSE){
					return $img;
				}
			}
		}else{
			return strpos($haystack, $needles);
		}
	}
}
foreach($online as $k=>$v){
	if($img = strpos_array($v['agent'], $logo)){
		$online[$k]['image'] = "/{$arg['modpath']}:img/w:40/h:40/null/{$img}";
		$online[$k]['bot'] = 1;
		$on[ $img ][ 1 ][] = $online[$k];
	}else{
		$online[$k]['os'] = strpos_array($v['agent'], $os);
		$uid = $v['name'] == $conf['settings']['default_usr'] ? "-1" : $v['id'];
		$online[$k]['image'] = "/{$arg['modpath']}:img/". (int)$uid. "/tn:index/w:40/h:40/c:1/null/img.jpg";
		$on[ $uid ][ $online[$k]['os'] ][] = $online[$k];
	}
}// mpre($on);
$guest = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}users WHERE name=\"". mpquot($conf['settings']['default_usr']). "\""), 0);
?>
<div class="online" style="overflow:hidden;">
	<style>
		.online a {padding:0 !important;}
	</style>
	<div style="clear:both;">на сайте <b><?=$count?></b> <?=mpfm($count, 'посетитель', 'посетителя', 'посетителей')?></div>
	<? foreach($on as $os): ?>
		<? foreach($os as $s=>$o): ?>
			<? $keys = array_keys($ar = array_slice($o, 0, 1)) ?>
			<? $v = $ar[min($keys)]; ?>
			<div style="float:left; margin:1px; border:1px solid #ddd; position:relative;" title="<?=(array_key_exists('bot', $v) ? $v['agent'] : $v['name']. ($v['name'] != $conf['settings']['default_usr'] ? "" : "-{$v['sid']}"). (!empty($v['ref']) && ($arg['access'] > 3) ? " {$v['count']}/{$v['cnull']} ({$v['ref']})" : ""))?>">
				<? if($v['id'] != $guest['id']): ?>
					<a href="/<?=$arg['modpath']?>/<?=$v['id']?>">
				<? elseif($arg['access'] > 3): ?>
					<a href="/?m[sess]=admin&where[id]=<?=$v['sid']?>">
				<? else: ?>
					<a href="/<?=$arg['modpath']?>/<?=$guest['id']?>">
				<? endif; ?>
					<? if(array_key_exists('bot', $v)): ?>
						<div style="position:absolute; top:1px; left:1px; opacity:0.8;"><?=count($o)?></div>
						<div style="position:absolute; top:1px; right:1px; opacity:0.8;"><img src="/<?=$arg['modpath']?>:img/w:15/h:15/null/bot.png"></div>
					<? elseif($v['os']): ?>
						<?// if(($cnt = count($o)) > 1): ?>
							<div style="position:absolute; top:1px; left:1px; opacity:0.8; background-color:white; width:15px; height:15px;"><?=count($o)?></div>
						<?// endif; ?>
						<div style="position:absolute; top:1px; right:1px; opacity:0.8;"><img src="/<?=$arg['modpath']?>:img/w:15/h:15/null/<?=$v['os']?>"></div>
					<? endif; ?>
					<img src="<?=$v['image']?>" style="z-index:-1; padding:1px;">
				</a>
			</div>
		<? endforeach; ?>
	<? endforeach; ?>
	<? if($count > count($online)): ?>
	<? endif; ?>
</div>
