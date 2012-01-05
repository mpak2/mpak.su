var cat = <?=json_encode($conf['tpl']['cat'])?>;
var href = <?=json_encode($conf['tpl']['href'])?>;
var top = <?=json_encode($conf['tpl']['top'])?>;

$(document).ready(function(){
	$.each(top, function(key, val){
		$("#href").append("<h3>"+val.name+"</h3>");
		$.each(cat[ val.id ], function(k, v){
			$("#href").append($("<h5>"+v.name+"</h5>").css("margin-left", "20px"));
			if(href[k]){
				$.each(href[k], function(n, h){
					$("#href").append($("<a>").attr("href", h.href).text(h.name).css("margin-left", "50px"));
				});
			}
		});
	});
});