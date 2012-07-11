jQuery.fn.klesh = function(action, callbackFnk){
	var f = function(){};
	jQuery(this).bind("click", f = function(){
		klesh = this;
		$(this).unbind("click", f);
		text = $(this).html()
		input = $("<input type='text'>").css("width", "100%").attr("old", text).val(text);
		$(this)
			.html($("<input type='button'>").addClass("fnok").val("ok").css("float", "right").css("width", "50px"))
			.append($("<div>").css("margin-right", "60px").html(input));
		$(this).find("input").select();
		$(this).find("input[type=text]").change(ch = function(){
			val = $(this).val();
			old = $(this).attr("old");
			
			var attr = {val:val, old:old};
			for(var i=0;i<(attrs = $(this).parent().parent()[0].attributes).length;i++) {
				attr[attrs[i].nodeName] = attrs[i].nodeValue;
			}
			if(val != old){
				$.post(action, attr, function(data){
					if(isNaN(data)/* && (typeof callbackFnk != 'function')*/){
						alert(data);
					};
				});
			};
			$(this).parent().parent().html(val).bind("click", f);
			if(typeof callbackFnk == 'function'){
				callbackFnk.call(klesh);
			}
		});
	}).focusout(function(){
		if($(this).find("input[type=text]").is("[old]")){
			val = $(this).find("input[type=text]").attr("old");
		}else{
			val = $(this).find("input[type=text]").val();
		} $(this).html(val).bind("click", f);
	}).css("padding-left", "15px")
		.css("background-repeat", "no-repeat")
		.css("background-position", "0 7px")
		.css("background-image", "url(/img/edit.png)")
		.css("min-height", "25px")
		.css("min-width", "25px")
		.css("line-height", "25px")
		.css("overflow", "hidden");
	return this;
};