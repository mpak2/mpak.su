(function($) {
	$.fn.attrs = function() {
		var attributes = {}; 
		if(!this.length)
			return this;
		$.each(this[0].attributes, function(index, attr) {
			attributes[attr.name] = attr.value;
		}); 
		return attributes;
	}
	$.fn.dump = function() {
		result = "";
		for (var i in $(this)){
			result += "\n"+i+":"+attrs[i];
		} return result;
		
	/*		if(!prefix) prefix = "";
		var result = "";
		obj = $(this);
		for (var i in obj){
			result += prefix + "." + i + " = " + (typeof obj[i] == "object" ? "object" : obj[i]) + "\n"
			if(typeof obj[i] == "object" && tree == "undefined") result += obj[i].dump(obj_name + "." + i, prefix+"   ");
		}
		if(prefix == "") alert(result);
		return result*/
	}
})(jQuery);