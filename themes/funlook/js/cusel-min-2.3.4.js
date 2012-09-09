/* -------------------------------------

	cusel version 2.3.4
	смена обычного селект на стильный
	autor: Evgen Ryzhkov
	www.xiper.net
----------------------------------------	
*/
function cuSel(params){jQuery(document).ready(function(){jQuery(params.changedEl).each(function(num)
{var chEl=jQuery(this),chElWid=chEl.outerWidth(),chElClass=chEl.prop("class"),chElId=chEl.prop("id"),chElName=chEl.prop("name"),defaultVal=chEl.val(),activeOpt=chEl.find("option[value='"+defaultVal+"']").eq(0),defaultText=activeOpt.text(),disabledSel=chEl.prop("disabled"),scrollArrows=params.scrollArrows,chElOnChange=chEl.prop("onchange"),chElTab=chEl.prop("tabindex"),chElMultiple=chEl.prop("multiple");if(!chElId||chElMultiple)return false;if(!disabledSel)
{classDisCuselText="",classDisCusel="";}
else
{classDisCuselText="classDisCuselLabel";classDisCusel="classDisCusel";}
if(scrollArrows)
{classDisCusel+=" cuselScrollArrows";}
activeOpt.addClass("cuselActive");var optionStr=chEl.html(),spanStr=optionStr.replace(/option/ig,"span");spanStr=spanStr.replace(/value=/ig,"val=");if(params.checkZIndex)
{num=jQuery(".cusel").length;}
var cuselFrame='<div class="cusel '+chElClass+' '+classDisCusel+'"'+' id=cuselFrame-'+chElId+' style="width:'+chElWid+'px"'+' tabindex="'+chElTab+'"'+'>'+'<div class="cuselFrameRight"></div>'+'<div class="cuselText">'+defaultText+'</div>'+'<div class="cusel-scroll-wrap"><div class="cusel-scroll-pane" id="cusel-scroll-'+chElId+'">'+
spanStr+'</div></div>'+'<input type="hidden" id="'+chElId+'" name="'+chElName+'" value="'+defaultVal+'" />'+'</div>';chEl.replaceWith(cuselFrame);if(chElOnChange)jQuery("#"+chElId).bind('change',chElOnChange);var newSel=jQuery("#cuselFrame-"+chElId),arrSpan=newSel.find("span"),defaultHeight;if(!arrSpan.eq(0).text())
{defaultHeight=arrSpan.eq(1).outerHeight();arrSpan.eq(0).css("height",arrSpan.eq(1).height());}
else
{defaultHeight=arrSpan.eq(0).outerHeight();}
if(arrSpan.length>params.visRows)
{newSel.find(".cusel-scroll-wrap").eq(0).css({height:defaultHeight*params.visRows+"px",display:"none",visibility:"visible"}).children(".cusel-scroll-pane").css("height",defaultHeight*params.visRows+"px");}
else
{newSel.find(".cusel-scroll-wrap").eq(0).css({display:"none",visibility:"visible"});}
var arrAddTags=jQuery("#cusel-scroll-"+chElId).find("span[addTags]"),lenAddTags=arrAddTags.length;for(i=0;i<lenAddTags;i++)arrAddTags.eq(i).append(arrAddTags.eq(i).attr("addTags")).removeAttr("addTags");});jQuery("html").unbind("click");jQuery("html").click(function(e)
{var clicked=jQuery(e.target),clickedId=clicked.attr("id"),clickedClass=clicked.prop("class");if((clickedClass.indexOf("cuselText")!=-1||clickedClass.indexOf("cuselFrameRight")!=-1)&&clicked.parent().prop("class").indexOf("classDisCusel")==-1)
{var cuselWrap=clicked.parent().find(".cusel-scroll-wrap").eq(0);if(cuselWrap.css("display")=="none")
{jQuery(".cusel-scroll-wrap").css("display","none");cuselWrap.css("display","block");var cuselArrows=false;if(clicked.parents(".cusel").prop("class").indexOf("cuselScrollArrows")!=-1)cuselArrows=true;if(!cuselWrap.find(".jScrollPaneContainer").eq(0).is("div"))
{cuselWrap.find("div").eq(0).jScrollPaneCusel({showArrows:cuselArrows});}}
else
{cuselWrap.css("display","none");}}
else if(clickedClass.indexOf("cusel")!=-1&&clickedClass.indexOf("classDisCusel")==-1&&clickedClass.indexOf("cuselActive")==-1)
{var cuselWrap=clicked.find(".cusel-scroll-wrap").eq(0);if(cuselWrap.css("display")=="none")
{jQuery(".cusel-scroll-wrap").css("display","none");cuselWrap.css("display","block");var cuselArrows=false;if(clicked.prop("class").indexOf("cuselScrollArrows")!=-1)cuselArrows=true;if(!cuselWrap.find(".jScrollPaneContainer").eq(0).is("div"))
{cuselWrap.find("div").eq(0).jScrollPaneCusel({showArrows:cuselArrows});}}
else
{cuselWrap.css("display","none");}}
else if(clicked.is("span")&&clicked.parents(".cusel-scroll-wrap").is("div"))
{var clickedVal;(clicked.attr("val")==undefined)?clickedVal=clicked.text():clickedVal=clicked.attr("val");clicked.parents(".cusel-scroll-wrap").find(".cuselActive").eq(0).removeClass("cuselActive").end().parents(".cusel-scroll-wrap").next().val(clickedVal).end().prev().text(clicked.text()).end().css("display","none");clicked.addClass("cuselActive");if(clickedClass.indexOf("cuselActive")==-1)clicked.parents(".cusel").find(".cusel-scroll-wrap").eq(0).next("input").change();}
else if(clicked.parents(".cusel-scroll-wrap").is("div"))
{return;}
else
{jQuery(".cusel-scroll-wrap").css("display","none");}});jQuery(".cusel").focus(function()
{jQuery(this).addClass("cuselFocus");});jQuery(".cusel").blur(function()
{jQuery(this).removeClass("cuselFocus");});jQuery(".cusel").hover(function()
{jQuery(this).addClass("cuselFocus");},function()
{jQuery(this).removeClass("cuselFocus");});jQuery(".cusel").unbind("keydown");jQuery(".cusel").keydown(function(event)
{var key,keyChar;if(window.event)key=window.event.keyCode;else if(event)key=event.which;if(key==null||key==0||key==9)return true;if(jQuery(this).prop("class").indexOf("classDisCusel")!=-1)return false;if(key==40)
{var cuselOptHover=jQuery(this).find(".cuselOptHover").eq(0);if(!cuselOptHover.is("span"))var cuselActive=jQuery(this).find(".cuselActive").eq(0);else var cuselActive=cuselOptHover;var cuselActiveNext=cuselActive.next();if(cuselActiveNext.is("span"))
{jQuery(this).find(".cuselText").eq(0).text(cuselActiveNext.text());cuselActive.removeClass("cuselOptHover");cuselActiveNext.addClass("cuselOptHover");var scrollWrap=jQuery(this).find(".cusel-scroll-pane").eq(0);if(scrollWrap.parent().find(".jScrollPaneTrack").eq(0).is("div"))
{var idScrollWrap=scrollWrap.prop("id"),hOption=scrollWrap.find("span").eq(0).outerHeight();jQuery("#"+idScrollWrap)[0].scrollBy(hOption);}
return false;}
else return false;}
if(key==38)
{var cuselOptHover=jQuery(this).find(".cuselOptHover").eq(0);if(!cuselOptHover.is("span"))var cuselActive=jQuery(this).find(".cuselActive").eq(0);else var cuselActive=cuselOptHover;cuselActivePrev=cuselActive.prev();if(cuselActivePrev.is("span"))
{jQuery(this).find(".cuselText").eq(0).text(cuselActivePrev.text());cuselActive.removeClass("cuselOptHover");cuselActivePrev.addClass("cuselOptHover");var scrollWrap=jQuery(this).find(".cusel-scroll-pane").eq(0);if(scrollWrap.parent().find(".jScrollPaneTrack").eq(0).is("div"))
{var idScrollWrap=scrollWrap.prop("id"),hOption=-parseInt(scrollWrap.find("span").eq(0).outerHeight());jQuery("#"+idScrollWrap)[0].scrollBy(hOption);}
return false;}
else return false;}
if(key==27)
{var cuselActiveText=jQuery(this).find(".cuselActive").eq(0).text();jQuery(this).find(".cusel-scroll-wrap").eq(0).css("display","none").end().find(".cuselOptHover").eq(0).removeClass("cuselOptHover");jQuery(this).find(".cuselText").eq(0).text(cuselActiveText);}
if(key==13)
{var cuselHover=jQuery(this).find(".cuselOptHover").eq(0);if(cuselHover.is("span"))
{jQuery(this).find(".cuselActive").removeClass("cuselActive");var cuselHoverVal=cuselHover.prop("value");cuselHover.addClass("cuselActive");}
else var cuselHoverVal=jQuery(this).find(".cuselActive").prop("value");jQuery(this).find(".cusel-scroll-wrap").eq(0).css("display","none").end().find(".cuselOptHover").eq(0).removeClass("cuselOptHover");jQuery(this).find("input").eq(0).val(cuselHoverVal).change();}
if(jQuery.browser.opera)return false;});var arr=[];jQuery(".cusel").keypress(function(event)
{var key,keyChar;if(window.event)key=window.event.keyCode;else if(event)key=event.which;if(key==null||key==0||key==9)return true;if(jQuery(this).prop("class").indexOf("classDisCusel")!=-1)return false;var o=this;arr.push(event);clearTimeout(jQuery.data(this,'timer'));var wait=setTimeout(function(){handlingEvent()},500);jQuery(this).data('timer',wait);function handlingEvent()
{var charKey=[];for(var iK in arr)
{if(window.event)charKey[iK]=arr[iK].keyCode;else if(arr[iK])charKey[iK]=arr[iK].which;charKey[iK]=String.fromCharCode(charKey[iK]).toUpperCase();}
var arrOption=jQuery(o).find("span"),colArrOption=arrOption.length,i,letter;for(i=0;i<colArrOption;i++)
{var match=true;for(var iter in arr)
{letter=arrOption.eq(i).text().charAt(iter).toUpperCase();if(letter!=charKey[iter])
{match=false;}}
if(match)
{jQuery(o).find(".cuselOptHover").removeClass("cuselOptHover").end().find("span").eq(i).addClass("cuselOptHover").end().end().find(".cuselText").eq(0).text(arrOption.eq(i).text());var scrollWrap=jQuery(o).find(".cusel-scroll-pane").eq(0);if(scrollWrap.parent().find(".jScrollPaneTrack").eq(0).is("div"))
{var idScrollWrap=scrollWrap.prop("id"),hOption=scrollWrap.find("span").eq(0).outerHeight()-0.2;jQuery("#"+idScrollWrap)[0].scrollTo(hOption*i);}
arr=arr.splice;arr=[];break;return true;}}
arr=arr.splice;arr=[];}
if(jQuery.browser.opera&&window.event.keyCode!=9)return false;});jQuery(".cusel span").mouseover(function()
{jQuery(this).parent().find(".cuselOptHover").eq(0).removeClass("cuselOptHover");});var arrCusel=jQuery(".cusel"),colCusel=arrCusel.length-1,i;for(i=0;i<=colCusel;i++)
{arrCusel.eq(i).css("z-index",colCusel-i);}});}
function cuSelRefresh(params)
{var arrRefreshEl=params.refreshEl.split(","),lenArr=arrRefreshEl.length,i;for(i=0;i<lenArr;i++)
{var refreshScroll=jQuery(arrRefreshEl[i]).parents(".cusel").find(".cusel-scroll-wrap").eq(0);refreshScroll.find(".cusel-scroll-pane").jScrollPaneRemoveCusel();refreshScroll.css({visibility:"hidden",display:"block"});var arrSpan=refreshScroll.find("span"),defaultHeight=arrSpan.eq(0).outerHeight();if(arrSpan.length>params.visRows)
{refreshScroll.css({height:defaultHeight*params.visRows+"px",display:"none",visibility:"visible"}).children(".cusel-scroll-pane").css("height",defaultHeight*params.visRows+"px");}
else
{refreshScroll.css({display:"none",visibility:"visible"});}}}
(function($){$.jScrollPaneCusel={active:[]};$.fn.jScrollPaneCusel=function(settings)
{settings=$.extend({},$.fn.jScrollPaneCusel.defaults,settings);var rf=function(){return false;};return this.each(function()
{var $this=$(this);var cuselWid=this.parentNode.offsetWidth;$this.css('overflow','hidden');var paneEle=this;if($(this).parent().is('.jScrollPaneContainer')){var currentScrollPosition=settings.maintainPosition?$this.position().top:0;var $c=$(this).parent();var paneWidth=cuselWid;var paneHeight=$c.outerHeight();var trackHeight=paneHeight;$('>.jScrollPaneTrack, >.jScrollArrowUp, >.jScrollArrowDown',$c).remove();$this.css({'top':0});}else{var currentScrollPosition=0;this.originalPadding=$this.css('paddingTop')+' '+$this.css('paddingRight')+' '+$this.css('paddingBottom')+' '+$this.css('paddingLeft');this.originalSidePaddingTotal=(parseInt($this.css('paddingLeft'))||0)+(parseInt($this.css('paddingRight'))||0);var paneWidth=cuselWid;var paneHeight=$this.innerHeight();var trackHeight=paneHeight;$this.wrap("<div class='jScrollPaneContainer'></div>").parent().css({'height':paneHeight+'px','width':paneWidth+'px'});if(!window.navigator.userProfile)
{var borderWid=parseInt($(this).parent().css("border-left-width"))+parseInt($(this).parent().css("border-right-width"));paneWidth-=borderWid;$(this).css("width",paneWidth+"px").parent().css("width",paneWidth+"px");}
$(document).bind('emchange',function(e,cur,prev)
{$this.jScrollPaneCusel(settings);});}
if(settings.reinitialiseOnImageLoad){var $imagesToLoad=$.data(paneEle,'jScrollPaneImagesToLoad')||$('img',$this);var loadedImages=[];if($imagesToLoad.length){$imagesToLoad.each(function(i,val){$(this).bind('load',function(){if($.inArray(i,loadedImages)==-1){loadedImages.push(val);$imagesToLoad=$.grep($imagesToLoad,function(n,i){return n!=val;});$.data(paneEle,'jScrollPaneImagesToLoad',$imagesToLoad);settings.reinitialiseOnImageLoad=false;$this.jScrollPaneCusel(settings);}}).each(function(i,val){if(this.complete||this.complete===undefined){this.src=this.src;}});});};}
var p=this.originalSidePaddingTotal;var cssToApply={'height':'auto','width':paneWidth-settings.scrollbarWidth-settings.scrollbarMargin-p+'px'}
if(settings.scrollbarOnLeft){cssToApply.paddingLeft=settings.scrollbarMargin+settings.scrollbarWidth+'px';}else{cssToApply.paddingRight=settings.scrollbarMargin+'px';}
$this.css(cssToApply);var contentHeight=$this.outerHeight();var percentInView=paneHeight/contentHeight;if(percentInView<.99){var $container=$this.parent();$container.append($('<div class="jScrollPaneTrack"></div>').css({'width':settings.scrollbarWidth+'px'}).append($('<div class="jScrollPaneDrag"></div>').css({'width':settings.scrollbarWidth+'px'}).append($('<div class="jScrollPaneDragTop"></div>').css({'width':settings.scrollbarWidth+'px'}),$('<div class="jScrollPaneDragBottom"></div>').css({'width':settings.scrollbarWidth+'px'}))));var $track=$('>.jScrollPaneTrack',$container);var $drag=$('>.jScrollPaneTrack .jScrollPaneDrag',$container);if(settings.showArrows){var currentArrowButton;var currentArrowDirection;var currentArrowInterval;var currentArrowInc;var whileArrowButtonDown=function()
{if(currentArrowInc>4||currentArrowInc%4==0){positionDrag(dragPosition+currentArrowDirection*mouseWheelMultiplier);}
currentArrowInc++;};var onArrowMouseUp=function(event)
{$('html').unbind('mouseup',onArrowMouseUp);currentArrowButton.removeClass('jScrollActiveArrowButton');clearInterval(currentArrowInterval);};var onArrowMouseDown=function(){$('html').bind('mouseup',onArrowMouseUp);currentArrowButton.addClass('jScrollActiveArrowButton');currentArrowInc=0;whileArrowButtonDown();currentArrowInterval=setInterval(whileArrowButtonDown,100);};$container.append($('<div></div>').attr({'class':'jScrollArrowUp'}).css({'width':settings.scrollbarWidth+'px'}).bind('mousedown',function()
{currentArrowButton=$(this);currentArrowDirection=-1;onArrowMouseDown();this.blur();return false;}).bind('click',rf),$('<div></div>').attr({'class':'jScrollArrowDown'}).css({'width':settings.scrollbarWidth+'px'}).bind('mousedown',function()
{currentArrowButton=$(this);currentArrowDirection=1;onArrowMouseDown();this.blur();return false;}).bind('click',rf));var $upArrow=$('>.jScrollArrowUp',$container);var $downArrow=$('>.jScrollArrowDown',$container);if(settings.arrowSize){trackHeight=paneHeight-settings.arrowSize-settings.arrowSize;$track.css({'height':trackHeight+'px',top:settings.arrowSize+'px'})}else{var topArrowHeight=$upArrow.height();settings.arrowSize=topArrowHeight;trackHeight=paneHeight-topArrowHeight-$downArrow.height();$track.css({'height':trackHeight+'px',top:topArrowHeight+'px'})}}
var $pane=$(this).css({'position':'absolute','overflow':'visible'});var currentOffset;var maxY;var mouseWheelMultiplier;var dragPosition=0;var dragMiddle=percentInView*paneHeight/2;var getPos=function(event,c){var p=c=='X'?'Left':'Top';return event['page'+c]||(event['client'+c]+(document.documentElement['scroll'+p]||document.body['scroll'+p]))||0;};var ignoreNativeDrag=function(){return false;};var initDrag=function()
{ceaseAnimation();currentOffset=$drag.offset(false);currentOffset.top-=dragPosition;maxY=trackHeight-$drag[0].offsetHeight;mouseWheelMultiplier=2*settings.wheelSpeed*maxY/contentHeight;};var onStartDrag=function(event)
{initDrag();dragMiddle=getPos(event,'Y')-dragPosition-currentOffset.top;$('html').bind('mouseup',onStopDrag).bind('mousemove',updateScroll);if($.browser.msie){$('html').bind('dragstart',ignoreNativeDrag).bind('selectstart',ignoreNativeDrag);}
return false;};var onStopDrag=function()
{$('html').unbind('mouseup',onStopDrag).unbind('mousemove',updateScroll);dragMiddle=percentInView*paneHeight/2;if($.browser.msie){$('html').unbind('dragstart',ignoreNativeDrag).unbind('selectstart',ignoreNativeDrag);}};var positionDrag=function(destY)
{destY=destY<0?0:(destY>maxY?maxY:destY);dragPosition=destY;$drag.css({'top':destY+'px'});var p=destY/maxY;$pane.css({'top':((paneHeight-contentHeight)*p)+'px'});$this.trigger('scroll');if(settings.showArrows){$upArrow[destY==0?'addClass':'removeClass']('disabled');$downArrow[destY==maxY?'addClass':'removeClass']('disabled');}};var updateScroll=function(e)
{positionDrag(getPos(e,'Y')-currentOffset.top-dragMiddle);};var dragH=Math.max(Math.min(percentInView*(paneHeight-settings.arrowSize*2),settings.dragMaxHeight),settings.dragMinHeight);$drag.css({'height':dragH+'px'}).bind('mousedown',onStartDrag);var trackScrollInterval;var trackScrollInc;var trackScrollMousePos;var doTrackScroll=function()
{if(trackScrollInc>8||trackScrollInc%4==0){positionDrag((dragPosition-((dragPosition-trackScrollMousePos)/2)));}
trackScrollInc++;};var onStopTrackClick=function()
{clearInterval(trackScrollInterval);$('html').unbind('mouseup',onStopTrackClick).unbind('mousemove',onTrackMouseMove);};var onTrackMouseMove=function(event)
{trackScrollMousePos=getPos(event,'Y')-currentOffset.top-dragMiddle;};var onTrackClick=function(event)
{initDrag();onTrackMouseMove(event);trackScrollInc=0;$('html').bind('mouseup',onStopTrackClick).bind('mousemove',onTrackMouseMove);trackScrollInterval=setInterval(doTrackScroll,100);doTrackScroll();};$track.bind('mousedown',onTrackClick);$container.bind('mousewheel',function(event,delta){initDrag();ceaseAnimation();var d=dragPosition;positionDrag(dragPosition-delta*mouseWheelMultiplier);var dragOccured=d!=dragPosition;return false;});var _animateToPosition;var _animateToInterval;function animateToPosition()
{var diff=(_animateToPosition-dragPosition)/settings.animateStep;if(diff>1||diff<-1){positionDrag(dragPosition+diff);}else{positionDrag(_animateToPosition);ceaseAnimation();}}
var ceaseAnimation=function()
{if(_animateToInterval){clearInterval(_animateToInterval);delete _animateToPosition;}};var scrollTo=function(pos,preventAni)
{if(typeof pos=="string"){$e=$(pos,$this);if(!$e.length)return;pos=$e.offset().top-$this.offset().top;}
$container.scrollTop(0);ceaseAnimation();var destDragPosition=-pos/(paneHeight-contentHeight)*maxY;if(preventAni||!settings.animateTo){positionDrag(destDragPosition);}else{_animateToPosition=destDragPosition;_animateToInterval=setInterval(animateToPosition,settings.animateInterval);}};$this[0].scrollTo=scrollTo;$this[0].scrollBy=function(delta)
{var currentPos=-parseInt($pane.css('top'))||0;scrollTo(currentPos+delta);};initDrag();scrollTo(-currentScrollPosition,true);$('*',this).bind('focus',function(event)
{var $e=$(this);var eleTop=0;while($e[0]!=$this[0]){eleTop+=$e.position().top;$e=$e.offsetParent();}
var viewportTop=-parseInt($pane.css('top'))||0;var maxVisibleEleTop=viewportTop+paneHeight;var eleInView=eleTop>viewportTop&&eleTop<maxVisibleEleTop;if(!eleInView){var destPos=eleTop-settings.scrollbarMargin;if(eleTop>viewportTop){destPos+=$(this).height()+15+settings.scrollbarMargin-paneHeight;}
scrollTo(destPos);}})
if(location.hash){scrollTo(location.hash);}
$(document).bind('click',function(e)
{$target=$(e.target);if($target.is('a')){var h=$target.attr('href');if(h.substr(0,1)=='#'){scrollTo(h);}}});$.jScrollPaneCusel.active.push($this[0]);}else{$this.css({'height':paneHeight+'px','width':paneWidth-this.originalSidePaddingTotal+'px','padding':this.originalPadding});$this.parent().unbind('mousewheel');}})};$.fn.jScrollPaneRemoveCusel=function()
{$(this).each(function()
{$this=$(this);var $c=$this.parent();if($c.is('.jScrollPaneContainer')){$this.css({'top':'','height':'','width':'','padding':'','overflow':'','position':''});$this.attr('style',$this.data('originalStyleTag'));$c.after($this).remove();}});}
$.fn.jScrollPaneCusel.defaults={scrollbarWidth:10,scrollbarMargin:5,wheelSpeed:18,showArrows:false,arrowSize:0,animateTo:false,dragMinHeight:1,dragMaxHeight:99999,animateInterval:100,animateStep:3,maintainPosition:true,scrollbarOnLeft:false,reinitialiseOnImageLoad:false};$(window).bind('unload',function(){var els=$.jScrollPaneCusel.active;for(var i=0;i<els.length;i++){els[i].scrollTo=els[i].scrollBy=null;}});})(jQuery);(function($){$.event.special.mousewheel={setup:function(){var handler=$.event.special.mousewheel.handler;if($.browser.mozilla)
$(this).bind('mousemove.mousewheel',function(event){$.data(this,'mwcursorposdata',{pageX:event.pageX,pageY:event.pageY,clientX:event.clientX,clientY:event.clientY});});if(this.addEventListener)
this.addEventListener(($.browser.mozilla?'DOMMouseScroll':'mousewheel'),handler,false);else
this.onmousewheel=handler;},teardown:function(){var handler=$.event.special.mousewheel.handler;$(this).unbind('mousemove.mousewheel');if(this.removeEventListener)
this.removeEventListener(($.browser.mozilla?'DOMMouseScroll':'mousewheel'),handler,false);else
this.onmousewheel=function(){};$.removeData(this,'mwcursorposdata');},handler:function(event){var args=Array.prototype.slice.call(arguments,1);event=$.event.fix(event||window.event);$.extend(event,$.data(this,'mwcursorposdata')||{});var delta=0,returnValue=true;if(event.wheelDelta)delta=event.wheelDelta/120;if(event.detail)delta=-event.detail/3;event.data=event.data||{};event.type="mousewheel";args.unshift(delta);args.unshift(event);return $.event.handle.apply(this,args);}};$.fn.extend({mousewheel:function(fn){return fn?this.bind("mousewheel",fn):this.trigger("mousewheel");},unmousewheel:function(fn){return this.unbind("mousewheel",fn);}});})(jQuery);