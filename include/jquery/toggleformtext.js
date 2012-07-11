/*
 * ToggleFormText
 *
 * Author:   Grzegorz Frydrychowicz
 * E-mail:   grzegorz.frydrychowicz@gmail.com
 * Date:     16-11-2007
*/

$(document).ready(function(){
	$("input:text, textarea, input:password").each(function(){
		if(this.value == ''){
			this.value = this.title;
			if($(this).is("[type=password]")){
				this.type = "pass";
			}
		}
	})
	$("input:text, textarea, input:password, input[type=pass]").live("focus", function(){
		if(this.value == this.title)
			this.value = '';
			if($(this).is("[type=pass]")){
				this.type = "password";
				this.value = "";
			}
	});
	$("input:text, textarea, input:password").live("blur", function(){
		if(this.value == '')
			this.value = this.title;
			if(this.type == "password"){
				this.type = "pass";
			}
	});
	$("input:image, input:button, input:submit").live("click", function(){
		$(this.form.elements).each(function(){
			if(this.type =='text' || this.type =='textarea' || this.type =='password' ){
				if(this.value == this.title && this.title != ''){
					this.value='';
				}
			}
		});
	});
});