/*
	Author: MindFreakTheMon.com
	jQuery 1.3.* Required
	Script was tested on IE7,8; FF3.5; Opera 10.01;
	Chrome ans Safari understands HTML5-placeholder attribute, so there is no need in this script for them.
*/

(function($)
{
	if(!$) return false;

	$.fn.extend({
		storeEvents: function(b)
		{
			return this.each(function()
			{
				var copy = function(j)
				{
					var o = {};
	
					for(i in j)
					{
						o[i] = typeof(j[i]) == 'object' ? arguments.callee(j[i]) : j[i];
					}
	
					return o;
				};
				
				$.data(this, 'storedEvents', copy($(this).data('events')));
				
				if(b)
				{
					$(this).unbind();
				}
			});
		},
		restoreEvents: function(b)
		{
			return this.each(function()
			{
				var events = $.data(this, 'storedEvents');
	
				if(events)
				{
					if(!b)
					{
						$(this).unbind();
					}
	
					for(var type in events)
					{
						for(var handler in events[type])
						{
							$.event.add(this, type, events[type][handler], events[type][handler].data);
						}
					}
				}
			});
		},
		copyAttr: function(e, p, v)
		{
			return this.each(function()
			{
				var a = {}, i, n, m;
				
				if(p === true)
				{
					for(i = 0, v = $.makeArray(v); i < this.attributes.length; i++)
					{
						n = this.attributes[i].nodeName;
						m = this.attributes[i].nodeValue;
	
						if(m && $.inArray(n, v) == -1)
						{
							a[n] =m;
						}
					}
				}
				else
				{				
					for(i = 0, p = $.makeArray(p); i < p.length; i++)
					{
						if(typeof $(this).attr(p[i]) == 'string')
						{
							a[p[i]] = $(this).attr(p[i]);
						}
					}
				}
				
				$(this).attr(a);
			});			
		},
		blurfocus: function(text, options)
		{
			return this.each(function()
			{
				options = $.extend({color: '#bababa', className: 'placeholded', handle_send: true, handle_password: true}, options || {});
				
				if($(this).is(':password') && options.handle_password)
				{
					$(this).data('placeholded_type', 'password');
				}
				
				var blur = function(e)
				{
					if($(this).val().length == 0)
					{
						if($(this).data('placeholded_type') == 'password')
						{
							var input = $('<input type="text" name="' + $(this).attr('name') + '" tabindex="' + $(this).attr('tabindex') + '" />'), events = $(this).storeEvents(true).data('storedEvents');
							$(this).copyAttr(input, true, ['type', 'name']).replaceWith(input);
							input.blur().data('placeholded_type', 'password').data('storedEvents', events).restoreEvents();
						}
						else
						{
							var input = $(this);
						}

						input.attr('readonly', true).data('placeholded_color', $(this).css('color') || '#000').css('color', options.color).val(text).addClass(options.className);
					}
				}, focus = function(e)
				{
					if($(this).val() == text && $(this).hasClass(options.className))
					{
						if($(this).data('placeholded_type') == 'password')
						{
							var input = $('<input type="password" name="' + $(this).attr('name') + '" tabindex="' + $(this).attr('tabindex') + '" />'), events = $(this).storeEvents(true).data('storedEvents');
							$(this).copyAttr(input, true, ['type', 'name']).replaceWith(input);
							input.focus().data('placeholded_type', 'password').data('storedEvents', events).restoreEvents();
						}
						else
						{
							var input = $(this);
						}
						
						input.val('').css('color', $(this).data('placeholded_color') || '#000').removeAttr('readonly').removeClass(options.className);
					}
				}, click = function()
				{
					focus.call(this);
					$(this).storeEvents(true).focus().restoreEvents();
				}, keydown = function(e)
				{
					if(e.keyCode == 9)
					{
						if(!$.browser.msie)
						{
							blur.call(this);
						}
	
						e.stopPropagation();				
					}
				};
				
				if($(this).is(':password, :text, textarea'))
				{
					if($.browser.safari)
					{
						$(this).attr('placeholder', text);
					}
					else
					{
						$(this).val($(this).val() == text ? '' : $(this).val()).blur(blur).focus(focus).click(click).keydown(keydown).blur();
						
						if(options.handle_send)
						{
							$(this).parents('form:eq(0)').each(function()
							{
								if($(this).data('placeholder_form') != 'handled')
								{
									events = $(this).data('placeholder_form', 'handled').storeEvents().data('storedEvents');
									eval('var anonfunc = function(){ ' + ($(this).attr('onsubmit') || '') + ' };');
									events.submit = $.extend(true, {'spec_sub': function(){ $(this).find('.' + options.className).focus(); }},  events.submit, {'anonfunc': anonfunc, 'spec_aft': function(e){ if(e.isDefaultPrevented()) $(this).find(':input').blur(); }});
									$(this).removeAttr('onsubmit').data('storedEvents', events).restoreEvents();
								}
							});
						}
					}
				}
			});
		}
	});
})(jQuery);