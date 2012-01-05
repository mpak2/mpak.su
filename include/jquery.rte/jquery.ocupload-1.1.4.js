/*
 * One Click Upload - jQuery Plugin
 * Copyright (c) 2008 Michael Mitchell - http://www.michaelmitchell.co.nz
 * Patched/fixed by Andrey Gayvoronsky - http://www.gayvoronsky.com
 */
(function($){
	$.fn.upload = function(options) {
		// Merge the users options with our defaults
		options = $.extend({
			name: 'file',
			enctype: 'multipart/form-data',
			action: '',
			autoSubmit: true,
			onSubmit: function() {},
			onComplete: function() {},
			onSelect: function() {},
			params: {}
		}, options);

		return new $.ocupload(this, options);
	},
	
	$.ocupload = function(element, options) {
		// Fix scope problems
		var self = this;
	
		// A unique id so we can find our elements later
		var id = new Date().getTime().toString().substr(8);
		
		// Upload Iframe
		var iframe = $(
			'<iframe '+
				'id="iframe'+id+'" '+
				'name="iframe'+id+'"'+
				'src="#"'+
			'></iframe>'
		).css({
			display: 'none'
		});
		
		// Form
		var form = $(
			'<form '+
				'method="post" '+
				'enctype="'+options.enctype+'" '+
				'action="'+options.action+'" '+
				'target="iframe'+id+'"'+
			'></form>'
		).css({
			margin: 0,
			padding: 0
		});
		
		// File Input
		var input = $(
			'<input '+
				'name="'+options.name+'" '+
				'type="file" '+
			'/>'
		).css({
			'width': 'auto',
			'position': 'absolute',
			'right': 0,
			'top': 0,
			'opacity': 0,
			'zoom': 1,
			'filter': 'alpha(opacity=0)',
			'border': 0,
			'font-size': '10em'
		});
	
		// Put everything together
		element.wrap('<div></div>'); //container

		element.wrap(form);
		element.wrap('<span></span>');

		// Find the container and make it nice and snug
		element.parent().css({
			'float': 'left',
			'white-space': 'nowrap',
			'position': 'relative',
			'z-index': 1,
			'left': 0,
			'top': 0,
			'overflow': 'hidden',
			'display': 'inline',
			'border': 0
		});
			
		element.after(input);
		element.parent().parent().after(iframe);
		form = input.parent().parent(); //FIX for correct submiting

		// Watch for file selection
		input.change(function() {
			// Do something when a file is selected.
			self.onSelect();
			
			// Submit the form automaticly after selecting the file
			if(self.autoSubmit) {
				self.submit();
			}
		});
		
		// Methods
		$.extend(this, {
			autoSubmit: options.autoSubmit,
			onSubmit: options.onSubmit,
			onComplete: options.onComplete,
			onSelect: options.onSelect,
		
			// get filename		
			filename: function() {
				return input.attr('value');
			},
			
			// get/set params
			params: function(params) {
				var params = params ? params : false;
				
				if(params) {
					options.params = $.extend(options.params, params);
				}
				else {
					return options.params;
				}
			},
			
			// get/set name
			name: function(name) {
				var name = name ? name : false;
				
				if(name) {
					input.attr('name', value);
				}
				else {
					return input.attr('name');
				}
			},
			
			// get/set action
			action: function(action) {
				var action = action ? action : false;
				
				if(action) {
					form.attr('action', action);
				}
				else {
					return form.attr('action');
				}
			},
			
			// get/set enctype
			enctype: function(enctype) {
				var enctype = enctype ? enctype : false;
				
				if(enctype) {
					form.attr('enctype', enctype);
				}
				else {
					return form.attr('enctype');
				}
			},
			
			// set options
			set: function(obj, value) {
				var value =	value ? value : false;
								
				function option(action, value) {
					switch(action) {
						default:
							throw new Error('[jQuery.ocupload.set] \''+action+'\' is an invalid option.');
							break;
						case 'name':
							self.name(value);
							break;
						case 'action':
							self.action(value);
							break;
						case 'enctype':
							self.enctype(value);
							break;
						case 'params':
							self.params(value);
							break;
						case 'autoSubmit':
							self.autoSubmit = value;
							break;
						case 'onSubmit':
							self.onSubmit = value;
							break;
						case 'onComplete':
							self.onComplete = value;
							break;
						case 'onSelect':
							self.onSelect = value;
							break;
					}
				}				
				
				if(value) {
					option(obj, value);
				}
				else {				
					$.each(obj, function(key, value) {
						option(key, value);
					});
				}
			},
			
			// Submit the form
			submit: function() {
				// Do something before we upload
				this.onSubmit();
				// add additional paramters before sending
				$.each(options.params, function(key, value) {
					form.append($(
						'<input '+
							'type="hidden" '+
							'name="'+key+'" '+
							'value="'+value+'" '+
						'/>'
					));
				});

				// Submit the actual form.
				// In that way, because we don't want jquery events (it fires submit event for other parent form)
				form.get(0).submit();
				
				// Do something after we are finished uploading
				iframe.unbind().load(function() {
					// Get a response from the server in plain text
					var myFrame = document.getElementById(iframe.attr('name'));
					var response = $(myFrame.contentWindow.document.body).text();
					
					// Do something on complete
					self.onComplete(response); //done :D
				});
			}
		});
	}
})(jQuery);