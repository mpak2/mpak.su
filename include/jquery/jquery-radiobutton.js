/**
 * jQuery custom radiobuttons
 * 
 * Copyright (c) 2010-2012 Tomasz Wójcik (bthlabs.pl)
 * Licensed under the MIT License:
 * http://www.opensource.org/licenses/mit-license.php
 *
 * @version 2.0
 * @category visual
 * @package jquery
 * @subpakage ui.checkbox
 * @author Tomasz Wójcik <labs@tomekwojcik.pl>
 */
(function() {
    jQuery.fn.radiobutton = function(options) {
        options = options || {};
        
    	var defaults = {
    		className: 'jquery-radiobutton',
    		checkedClass: 'jquery-radiobutton-on'
    	};
    	
    	var settings = jQuery.extend(defaults, options || {});
    	
    	return this.each(function() {
    	   var self = $(this);
    	   
    	   var replacement = jQuery(
    			'<div class="' + settings.className + '-wrapper">' +
    				'<a class="' + settings.className + '" href="#" name="' + self.attr('id') + '" rel="' + self.attr('name') + '"></a>' + 
    			'</div>'
    		);
    		var element = jQuery('a', replacement);
    		
            if (self.attr('checked') === 'checked') {
                element.addClass(settings.checkedClass);
            }
            
            element.on('click', function(event) {
                event.preventDefault();
                event.stopPropagation();
                
                var input = jQuery('input#' + jQuery(this).attr('name'), replacement.parent());
                if (input.attr('checked') === 'checked') {
                	input.removeAttr('checked');
                } else {
                	input.attr('checked', 'checked');
                }
                input.trigger('change');
                
                return false;
            });
            
            self.on('change', function(event) {
                var input = jQuery(this);
    			jQuery('a[rel="' + input.attr('name') + '"].' + settings.checkedClass).removeClass(settings.checkedClass);
    			
    			if (input.attr('checked') === 'checked') {
    				jQuery('a[name=' + input.attr('id') + ']', replacement.parent()).addClass(settings.checkedClass);
    			} else {
    				jQuery('a[name=' + input.attr('id') + ']', replacement.parent()).removeClass(settings.checkedClass);
    			} // eof if()
            });
            
            self.css({ 'position': 'absolute', 'top': '-200px', 'left': '-200px'}).before(replacement);
            replacement.parent().css('overflow', 'hidden');
        });
    };
})();