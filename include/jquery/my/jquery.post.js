jQuery.fn.at = function(post){
	$.each(post, $.proxy(function(key, val){
		if(key == "id"){
			if(isNaN(val)){
				if($(this).parents("["+val+"]").length){
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
			console.log("not_exists:", key);
		}// console.log(key, val);
	}, this)); console.log("post:", post); return post;
}
