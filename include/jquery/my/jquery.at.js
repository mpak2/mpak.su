jQuery.fn.at = function(keys, addr, func){
	var post = {};
	$.each(keys, $.proxy(function(key, val){
		if(key == "id"){
			if(isNaN(val)){
				if($(this).is("["+val+"]")){
					post[key] = $(this).attr(val);
				}else if($(this).parents("["+val+"]").length){
					post[key] = $(this).parents("["+val+"]").attr(val);
				}else{
					console.log("not_exists_id:", val);
				}
			}
		}else if($(this).is("["+key+"]")){
			if(val == null){
				var val = $(this).attr(key);
				post[key] = val;
			}else{
				$(this).attr(key, val);
			}
		}else if($(this).parents("["+key+"]").length){
			if(val == null){
				val = $(this).parents("["+key+"]").attr(key);
				post[key] = val;
			}else{
				$(this).parents("["+key+"]").attr(key, val);
			}
		}else{
			console.log(".at(not_exists):", key);
		}// console.log(key, val);
	}, this));
	if(addr != null){
		post.id = post.id > 0 ? post.id * -1 : 0;
		$.post(addr, post, $.proxy(function(data){
			if(isNaN(data)){ alert(data) }else{
				if(isNaN(keys.id)){
					(option = {})[keys.id] = data;
					$(this).at(option);
				}else if($(this).is("[id]")){
					$(this).attr("id", data);
				}
			}
		}, this))
	} console.log("post:", post); return post;
}
