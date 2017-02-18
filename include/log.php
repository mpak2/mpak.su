<?php

$colors = array(
	"200"=>"[0;32m", "404"=>"[1;31m", "410"=>"[0;31m", "403"=>"[0;91m", "301"=>"[1;33m", "302"=>"[1;36m", "304"=>"[1;32m",
	/*	"c_std"=>"[0;39m", "c_h_std"=>"[1;37m", "c_pink"=>"[0;35m", "c_h_pink"=>"[1;35m", "c_red"=>"[0;31m", "c_h_red"=>"[1;31m" "c_cayan"=>"[0;36m", "c_h_cayan"=>"[1;36m", "c_yellow"=>"[1;33m", "c_green"=>"[0;32m", "c_h_green"=>"[1;32m", "c_blue"=>"[0;34m", "c_h_blue"=>"[1;34m",*/
);
$bots = array("localhost"=>"\e[1;35m", "googlebot"=>"\e[0;44m", "mtphantom"=>"\e[1;45m", "yandex"=>"\e[0;45m", "spider"=>"\e[1;35m", "ahrefs"=>"\e[1;35m", "mail.ru"=>"\e[1;35m", "bot"=>"\e[1;35m");
$url = [/*"wiki:cat", "wiki:index",*/ "wiki:cat_cat", "wiki:index_cat", "hh:ajax/class:order", "магазин", "аренда"];

chdir(__DIR__);
require "idna_convert.class.inc";

$idna = new idna_convert();
$in = fopen('php://stdin', 'r');
$tm = []; $t = microtime(true); $nn = 0; $microtime = microtime(true);
while($str = fgets($in)){
	if(!$ar = explode(" ", $str)){ print_r("Ошибка формирования массива элементов");
	}elseif(!$n = number_format($nt = (100*(count($tm)-1)+($nn%100))/(microtime(true)-$t), 2)){ print_r("Ошибка форматирования времени генерации ресурса");
	}elseif(!($percent = number_format((array_sum(array_column($tm, 'msec'))/1e6)/(microtime(true)-$t)*100, 2)) & false){ print_r("Ошибка вычисления процента загрузки");
	}elseif(!$mtime = $ar[11]/1e6){ print_r("Ошибка вычисления время обработки ресурса"); print_r($ar);
	}elseif(!$mtm = number_format($mtime, 3)){ print_r("Ошибка форматирования микровремени");

	}elseif(!$mtm = (max(min(0, $mtm-0), $mtm-.1) ? $mtm : "\e[1;32m{$mtm}\e[0m")){ print_r("Значение времени больше одной секунды");
	}elseif(!$mtm = (max(min(0, $mtm-.1), $mtm-1) ? $mtm : "\e[1;33m{$mtm}\e[0m")){ print_r("Значение времени больше одной секунды");
	}elseif(!$mtm = (min(0, $mtm-1) ? $mtm : "\e[1;31m{$mtm}\e[0m")){ print_r("Значение времени больше одной секунды");

	}elseif(!$uri = urldecode($ar[7])){ mpre("Ошибка адрес не определен");
	}elseif(!$uri = (array_filter(array_map(function($u) use($uri){ return strpos($uri, $u); }, $url)) ? "\e[1;37m". urldecode($ar[7]). "\e[0m" : urldecode($ar[7]))){ print_r("Локальный адрес не установлен");
	}elseif(!$status = (array_key_exists($ar[9], $colors) ? "\e{$colors[$ar[9]]}{$ar[9]}\e[0m" : $ar[9])){ print_r("Ошибка раскрашивания статуса");
	}elseif((!$is_bot = false) && (!$user = call_user_func(function($user) use($bots, &$is_bot){
			while(list($b, $color) = each($bots)){ if(strpos($user, $b) !== false){ $is_bot = true; return "{$color}{$user}\e[0m"; } }; return "\e[1;32m{$user}\e[0m";
		}, $ar[0]))){ mpre("Ошибка расцветки посетителя");
	}elseif(!$host = $idna->decode($ar[1])){ print_r("Ошибка определения хоста запроса");
	}elseif(!$host = ($is_bot ? "\e[0;34m{$host}\e[0m" : "\e[1;34m{$host}\e[0m")){ print_r("Ошибка установки цвета хоста");
	}elseif(!$size = number_format($ar[10]/1024, 2). "КБ"){ print_r("Ошибка определения размера запроса");
	}elseif(passthru("echo '{$nn} {$n} {$percent}% {$mtm}c {$status} http://{$host}{$uri} ({$user}) {$size}'")){
	}elseif(!($nn++ % 100) && (!$tm = call_user_func(function($tm) use(&$t){
			array_unshift($tm, ['msec'=>0, 'microtime'=>microtime(true)]);
			if(count($tm) > 10){
				$t = array_pop($tm)['microtime'];
			} return $tm;
		}, $tm))){ print_r("Ошибка инкримента номера строки");
	}elseif(!$_sig = (array_key_exists(1, $argv) ? $argv[1] : 10)){ print_r("Ошибка получения уровня сигнала");
	}elseif(!$_n = (empty($_n) ? $n : $_n)){ print_r("Ошибка получения старого значения");
	}elseif($n <= $_sig){// print_r("Нагрузка еще меньше");
	}elseif($_n >= $_sig){// print_r("Нагрузка уже больше");
	}elseif(!$e = explode(".", $n)){ mpre("Ошибка получения массива значений уровня сигнала");
	}elseif(!$_num = "{$e[0]} ". (array_key_exists(1, $e) ? ($e[1] < 10 ? "ноль " : ""). (int)$e[1] : "ровно")){ print_r("Ошибка форматирования уровня сигнала");
	}elseif(!$cmd = "echo '{$_num}' | festival --tts --language russian  > /dev/null &"){ mpre("Ошибка получения строки сигнала");
	}elseif(system($cmd)){ print_r("Ошибка запуска комманды", $cmd);
	}else{// print_r("\n>{$n}< "); print_r("\n>{$_n}< "); print_r("\n>{$_sig}< ");// exit;
	} $_n = $n; $tm[0]['msec'] += $ar[10];
};
