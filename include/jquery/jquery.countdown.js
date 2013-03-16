/*
 * Metadata - jQuery countdown plugin
 * http://alexmuz.ru/jquery-countdown/
 *
 * Copyright (c) 2009 Alexander Muzychenko
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */

(function($) {
	$.fn.countdown = function (date, options) {
		options = $.extend({
/*			lang: {
				years:   ['год', 'года', 'лет'],
				months:  ['месяц', 'месяца', 'месяцев'],
				days:    ['день', 'дня', 'дней'],
				hours:   ['час', 'часа', 'часов'],
				minutes: ['минута', 'минуты', 'минут'],
				seconds: ['секунда', 'секунды', 'секунд'],
				plurar:  function(n) {
					return (n % 10 == 1 && n % 100 != 11 ? 0 : n % 10 >= 2 && n % 10 <= 4 && (n % 100 < 10 || n % 100 >= 20) ? 1 : 2);
				}
			},*/ 
			lang: {
				years:   ['г', 'г', 'л'],
				months:  ['м', 'м', 'м'],
				days:    ['д', 'д', 'д'],
				hours:   ['ч', 'ч', 'ч'],
				minutes: ['м', 'м', 'м'],
				seconds: ['с', 'с', 'с'],
				plurar:  function(n) {
					return (n % 10 == 1 && n % 100 != 11 ? 0 : n % 10 >= 2 && n % 10 <= 4 && (n % 100 < 10 || n % 100 >= 20) ? 1 : 2);
				}
			}, 
			prefix: "Осталось: ", 
			finish: "Всё"			
		}, options);
		
		var timeDifference = function(begin, end) {
		    if (end < begin) {
			    return false;
		    }
		    var diff = {
		    	seconds: [end.getSeconds() - begin.getSeconds(), 60],
		    	minutes: [end.getMinutes() - begin.getMinutes(), 60],
		    	hours: [end.getHours() - begin.getHours(), 24],
		    	days: [end.getDate()  - begin.getDate(), new Date(begin.getYear(), begin.getMonth() + 1, 0).getDate()],
		    	months: [end.getMonth() - begin.getMonth(), 12],
		    	years: [end.getYear()  - begin.getYear(), 0]
		    };
		    var result = new Array();
		    var flag = false;
		    for (i in diff) {
		    	if (flag) {
		    		diff[i][0]--;
		    		flag = false;
		    	}    	
		    	if (diff[i][0] < 0) {
		    		flag = true;
		    		diff[i][0] += diff[i][1];
		    	}
		    	if (!diff[i][0]) continue;
			    result.push(diff[i][0] + '' + options.lang[i][options.lang.plurar(diff[i][0])]);
		    }
		    return result.reverse().join(' ');
		};
		var elem = $(this);
		var timeUpdate = function () {
		    var s = timeDifference(new Date(), date);
		    if (s.length) {
		    	elem.html(options.prefix + s);
		    } else {
		        clearInterval(timer);
		        elem.html(options.finish);
		    }		
		};
		timeUpdate();
		var timer = setInterval(timeUpdate, 1000);		
	};
})(jQuery);
