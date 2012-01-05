/* The MIT License

Copyright (c) 2008 Stanislav MГјller
http://lifedraft.de

Version: 1.1

*/

var DhoniShow = function(element, options) {
  if(this.elementsSet(element)){
    this.current_index = 0;
    this.queue = new this.queue();
    this.dom = new this.dom(element, this);
    this.options = jQuery.extend(jQuery.extend(this.defaultOptions(), options), new this.options(element));
    
    this.queue.invokeModulesQueue(this, this.options);
  }
};

DhoniShow.prototype = {
  defaultOptions: function(){
    return {
      preloader : true,
      duration : 0.6,
      center : { elements: true },
      effect : 'appear'
    };
  },
  
  animating: function() {
    return this.dom.element.find("*:animated").length;
  },

  elementsSet: function(element) {
    if ( jQuery( element ).children().length > 0 ) return true;

    jQuery( element ).append("<p>Plese read instructions about using DhoniShow on "+
      "<a href='http://dhonishow.de' style='color: #fff;' title='to DhoniShow site'>DhoniShow's website</a></p>").find("p").addClass("error");
    return false;
  }

};

DhoniShow.prototype.queue = function(){
  this.queues = {};
  this.invoked = [];
};

DhoniShow.prototype.queue.prototype = {
  moduleQueue: [],

  register: function(type, scope, func /*, nArgs */){
    this.queues[type] = this.queues[type] || [];
    var args = []; for (var i = 3; i < arguments.length; i++) args.push(arguments[i]);
    this.queues[type].push({scope: scope, func: func, args: args});
    return func;
  },

  invokeAll: function(type /*, nArgs */) {
    if(this.invoked.length != this.moduleQueue.length) { // Cache invokes until all modules have ran
      this.setWaiter(jQuery.makeArray(arguments));
      return;
    }

    for (var i=0; i < this.queues[type].length; i++) {
      if(arguments.length > 1){
        var args = jQuery.makeArray(arguments);
        args.shift();
        jQuery.extend(this.queues[type][i].args, args);
      }
      this.queues[type][i].func.apply(this.queues[type][i].scope, this.queues[type][i].args);
    }
  },

  invokeModulesQueue: function(_this, options){
    var optionsQueue = this.moduleQueue;    
    for (var i=0; i < optionsQueue.length; i++) {
      _this[optionsQueue[i]] = new DhoniShow.fn[optionsQueue[i]](options[optionsQueue[i]], _this);
      this.invoked.push(optionsQueue[i]);
    }
  },

  setWaiter: function(args){
    var _this = this;
    return setTimeout(function(){
      if(_this.invoked.length != _this.moduleQueue.length) {
        setTimeout(arguments.callee, 100);
      } else {
        _this.invokeAll.apply(_this, args);
      }
    }, 100);
  }
};

// ###########################################################################

DhoniShow.prototype.options = function(element) {

  var names = element.className.match(/((\w*)-(\w*)|\w*)_(\w*-\w*-\w*|\w*-\w*|\w*)/g) || [];

  for (var i = 0; i < names.length; i++) {

    var option = /((\w*)-(\w*)|\w*)_(\w*-\w*-\w*|\w*-\w*|\w*)/.exec(names[i]);
    var value = this.recognizeValue(option[4]);

    switch(typeof option[3]){
      case "undefined": // Matches (option_value) for cool browsers
        this[option[1]] = value;
      break;
      case "string": // Matches (option-suboption_value)
        if(option[3].length){
          if(!this[option[2]] || this[option[2]].constructor != Object){
            this[option[2]] = {};
          }
          this[option[2]][option[3]] = value;
        } else if (option[2].length == 0 && jQuery.browser.msie){
          /* All good browsers match: /((\w*)-(\w*)|\w*)_(\w*)/ > ["option_value", undefined, undefined, "value"]
             but IEs matches > ["option_value", "", "", "value"] */
          this[option[1]] = value;
        }
      break;
    };

  }

};

DhoniShow.prototype.options.prototype.recognizeValue = function(value){
  if( /true|false/.test(value) ){
    return value = !!( value.replace(/false/, "") ); // Wild hack
  } 
  if( (/dot/).test(value) ){
    return value = Number( value.replace(/dot/, ".") );
  }
  return value;
};
// ###########################################################################

DhoniShow.helper = {

  image: function ( element ) {
    var img;
    element = (typeof element == "object" && element.nodeType == "undefined" || element.length) ? element[0] : element;
    if(element.nodeName.toLowerCase() != "img") {
      if((img = jQuery(element).find("img")) || (img = jQuery(element).filter("img"))){ 
        if(img.length > 0){
          element = img[0];
        }
      }
    }
    return element;
  },

  delayed_image_load: function ( image, func, scope, args ) {
    var src = image.attr("src");
    image.attr("src", "#");
    image.bind("load", function (){
      if(args) {
        var arg = jQuery.makeArray(args);
        arg.push({width: this.width, height: this.height});
      }
      func.apply(scope, arg || []);
      jQuery(this).unbind("load");
    });
    image.attr("src", src);
  },

  delayed_dimensions_load: function(dimensions, func, scope, args) {
    var image;
    if(!!!dimensions.width && !!!dimensions.height){
      if( ( image = jQuery(this.image(scope)) ).length > 0 ) { 
        this.delayed_image_load(image, func, scope, args);
      }
    }
  },
    
  to_number: function(string){
    return new Number(string.replace(/dot|px/, ""));
  }
};

// ###########################################################################

DhoniShow.prototype.dom = function(element, parent){
  this.parent = parent;
  this.element = jQuery(element);
  this.saveChildren();
  this.placeholders();
  this.invokeModules();

  this.setUpdaters();
};

DhoniShow.prototype.dom.prototype = {
  template : ['<div class="dhonishow-aligner">', // Need this for the align IE6 support, cause double class selectors doesn't work
      '<div class="dhonishow-effect-helper"><ol class="dhonishow-elements">@images</ol></div>',
      '<p class="dhonishow-alt">@alt</p>',
      '<div class="dhonishow-paging-buttons">',
        '<div class="dhonishow-theme-helper">',
          '<a class="dhonishow-next-button" title="Next">Next</a>',
          '<p class="dhonishow-paging">@current/@allpages</p>',
          '<a class="dhonishow-previous-button" title="Previous">Back</a>',
        '</div>',
      "</div>",
    '</div>'].join(""),
  
  setUpdaters: function() {
    this.parent.queue.register("updaters", this, this.alt, this.giveModluePlaceholder("alt"));
    this.parent.queue.register("updaters", this, this.current, this.giveModluePlaceholder("current"));
    this.parent.queue.register("updaters", this, this.allpages, this.giveModluePlaceholder("allpages"));
  },
  
  saveChildren: function(){
    this.children = jQuery(this.element).children();
    this.element.text("");
  },

  placeholders: function() {
    var modulePlaceholders = {};
    jQuery(this.element).append(this.template.replace(/@(\w*)/g, function(searchResultWithExpression, searchResult){
      modulePlaceholders[searchResult] = ".dhonishow_module_"+searchResult;
      return '<span class="dhonishow_module_'+searchResult+'"></span>';
    }));
    return this.modulePlaceholders = modulePlaceholders;
  },

  invokeModules: function(){
    for(var module in this.modulePlaceholders)
      this[module](this.giveModluePlaceholder(module));
  },

  giveModluePlaceholder: function(name){
    return jQuery(this.element).find(this.modulePlaceholders[name]);
  },

  images: function(placeholder){
    var _this = this;
    this.children || [];
    
    placeholder.replaceWith(this.children);
    this.elements = jQuery(this.children).wrap(arguments.callee.wrapper).parent();
  },

  alt: function(placeholder){
    var alt, title, src;
    if(alt = jQuery(this.elements[this.parent.current_index]).find("*[alt]").attr("alt")){
      if(jQuery(alt).filter("*").length){ placeholder.each(function(){ this.innerHTML = alt; }); return; }
      placeholder.text(alt);
      return;
    }
    if(title = jQuery(this.elements[this.parent.current_index]).find("*[title]").attr("title")){
      placeholder.text(title);
      return;
    }
    if(src = jQuery(this.elements[this.parent.current_index]).find("*[src]").attr("src")){
      var src = src.split('/');
      placeholder.text(src[src.length-1]);
      return;
    }
  },

  current: function(placeholder){
    placeholder.text(this.parent.current_index+1);
  },

  allpages: function(placeholder){
    placeholder.text(this.elements.length);
  }
};

DhoniShow.prototype.dom.prototype.images.wrapper = "<li class='element'></li>";

// ###########################################################################

DhoniShow.fn = {};

// ###########################################################################

DhoniShow.prototype.queue.prototype.moduleQueue.push("preloader");

DhoniShow.fn.preloader = function(value, parent){
  this.parent = parent;
  this.value = value;
  this.elements = [];
  
  this.parent.queue.register("loaded_one", this, this.loadedOne);
};

// ###########################################################################

DhoniShow.fn.preloader.prototype = {
  loadedOne: function ( position ) {
    if(this.elements.push(position) == this.parent.dom.elements.length)
      this.parent.queue.invokeAll("loaded_all");
  }
};

// ###########################################################################

DhoniShow.prototype.queue.prototype.moduleQueue.push("duration");

DhoniShow.fn.duration = function (value, parent) {
  if(value == 0) parent.options.duration = 0.01;
};

// ###########################################################################

DhoniShow.prototype.queue.prototype.moduleQueue.push("hide");

DhoniShow.fn.hide = function(value, parent){
  this.parent = parent;

  if(value == undefined || !value.buttons){
    this.parent.queue.register("updaters", this, this.previous_button).call(this);
    this.parent.queue.register("updaters", this, this.next_button).call(this);
  }
};

DhoniShow.fn.hide.prototype = {

  previous_button: function(){
    if(!this.parent.dom.previous_button)
      this.parent.dom.previous_button = this.parent.dom.element.find(".dhonishow-previous-button");
    
    this.parent.dom.previous_button.css("visibility", "hidden");
    if( this.parent.current_index != 0) this.parent.dom.previous_button.css("visibility", "");
  },

  next_button: function() {
    if(!this.parent.dom.next_button)
      this.parent.dom.next_button = this.parent.dom.element.find(".dhonishow-next-button");

    this.parent.dom.next_button.css("visibility", "hidden");
    if( this.parent.current_index != (this.parent.dom.elements.length - 1)) this.parent.dom.next_button.css("visibility", "");
  },

  not_current: function(){
    var element, current = this.parent.current_index;

    jQuery(this.parent.dom.elements).each(function(index){
      if(index != current)
        jQuery(this).hide();
      else
        element = jQuery(this);
    });

    return element;
  }
};

// ###########################################################################

DhoniShow.prototype.queue.prototype.moduleQueue.push("effect");

DhoniShow.fn.effect = function(effectName, parent){
  this.parent = parent;
  var args = effectName.split("-");
  if(args.length > 1) { effectName = args[0]; args.shift(); }
  this.effect = new DhoniShow.fn.effect.fx[effectName](this, args.join("-"));
  this.parent.queue.register("loaded_all", this.effect, this.effect.center);

  this.addObservers();
};

DhoniShow.fn.effect.prototype = {
  addObservers: function(){
    this.parent.dom.element.find(".dhonishow-previous-button").bind("click", this, this.previous);
    this.parent.dom.element.find(".dhonishow-next-button").bind("click", this, this.next);
  },

  next: function(event){
    var _this = event.data;
    if(!_this.parent.animating()){
      _this.update(_this.parent.current_index, ++_this.parent.current_index);
    }
  },

  previous: function(event){
    var _this = event.data;
    if(!_this.parent.animating()){
      _this.update(_this.parent.current_index, --_this.parent.current_index);
    }
  },

  update: function(next, current){
    this.parent.queue.invokeAll("updaters");
    this.effect.update(
        jQuery(this.parent.dom.elements[next]), 
        jQuery(this.parent.dom.elements[current]),
        this.parent.options.duration
    );
  }
};

// ###########################################################################

DhoniShow.fn.effect.fx = {};

// ###########################################################################

DhoniShow.fn.effect.fx.appear = function(parent, options){
  this.parent = parent;
};

DhoniShow.fn.effect.fx.appear.prototype = {
  update: function(next_element, current_element, duration){
    current_element.animate({ opacity: "toggle" }, duration*1000);
    next_element.animate({ opacity: "toggle" }, duration*1000);
  },
  center: function(hide){
    if(!hide) this.parent.parent.hide.not_current();
    
    var center = this.parent.parent.center;

    this.parent.parent.dom.element.css({width: center.dimensions.max.width+"px"});
    this.parent.parent.dom.elements.parent().css({height: center.dimensions.max.height+"px"});

    if(center.value) {
      if(center.value.elements || center.value.elements == undefined) {
        this.parent.parent.dom.elements.each(function(index){
          jQuery(this).css(center.dimensions[index].center);
        });
      }

      if(center.value.width) this.parent.parent.dom.element.css({width: center.value.width+"px"});
      if(center.value.height) this.parent.parent.dom.elements.parent().css({height: center.value.height+"px"});
    }
  }
};

// ###########################################################################

DhoniShow.fn.effect.fx.resize = function(parent, options){
  this.parent = parent;
};

DhoniShow.fn.effect.fx.resize.prototype = {
  update: function(next_element, current_element, duration){
    var dimensions = this.parent.parent.center.dimensions[this.parent.parent.current_index];

    this.parent.parent.dom.elements.parent().animate( { height: dimensions.height }, duration * 1000 );
    this.parent.parent.dom.element.animate( { width: dimensions.width }, duration * 1000 );

    current_element.animate({ opacity: "toggle" }, duration*1000);
    next_element.animate({ opacity: "toggle" }, duration*1000);
  },

  center: function(){
    var elements = jQuery(this.parent.parent.dom.elements.get().reverse());
    for (var i=0; i < elements.length; i++) {
      jQuery(elements[i]).css( "z-index", i + 1 );
      if(i != (elements.length-1 - this.parent.parent.current_index)) jQuery(elements[i]).hide();
    }
    
    var center = this.parent.parent.center;

    this.parent.parent.dom.element.css({width: center.dimensions[this.parent.parent.current_index].width+"px"});
    this.parent.parent.dom.elements.parent().css({height: center.dimensions[this.parent.parent.current_index].height+"px"});
  }
};

// ###########################################################################

DhoniShow.fn.effect.fx.slide = function(parent, options){
  this.parent = parent;
  this.element = this.parent.parent.dom.element.find(".dhonishow-elements");
  this.options = (function(){
    if(options == "top") return { dimension: "height", side: "top" };
    return { dimension: "width", side: "left" };
  })();
};

DhoniShow.fn.effect.fx.slide.prototype = {
  /*
    FIXME Clicking while autoplay doesn't work properly
  */
  
  update: function(next_element, current_element, duration, event) {
    var element_option = {};
    var element = current_element;
    if(this.parent.parent.options.autoplay){ element = next_element; } // Doesn't work on klick
    element_option[this.options.side] = -DhoniShow.helper.to_number(element.css(this.options.side));
    this.element.animate(element_option, duration*1000);
  },

  center: function(){
    
    DhoniShow.fn.effect.fx.appear.prototype.center.apply(this, [true]);

    var dimension = this.options.dimension;
    var side = this.options.side;
    var offset = 0;

    this.dimensions = this.parent.parent.center.dimensions.max;

    var step = this.dimensions[dimension];
    this.parent.parent.dom.elements.each(function(){ 
      this.style[side] = offset+"px";  offset += step;
    });

    var element_option = {};
    element_option[side] = 0;
    element_option[dimension] = this.parent.parent.dom.elements.length*this.dimensions[dimension];

    this.element.css(element_option);

    this.parent.parent.dom.element.find(".dhonishow-effect-helper").css({
      height: this.dimensions.height,
      width: this.dimensions.width
    });
  }
};

// ###########################################################################


DhoniShow.prototype.queue.prototype.moduleQueue.push("autoplay");

DhoniShow.fn.autoplay = function(value, parent){
  this.parent = parent;
  if(value){
    this.create(value);
    this.parent.queue.register("updaters", this, this.reset);
  }
};

DhoniShow.fn.autoplay.prototype = {
  create: function(duration) {
    var _this = this;
    this.executer = setInterval(function(){
      _this.parent.current_index++;

      if(_this.parent.current_index == _this.parent.dom.elements.length) {
        _this.parent.effect.effect.update(
          jQuery(_this.parent.dom.elements[_this.parent.current_index = 0]), 
          jQuery(_this.parent.dom.elements[_this.parent.dom.elements.length-1]),
          _this.parent.options.duration
        );
      } else {
        _this.parent.effect.effect.update(
          jQuery(_this.parent.dom.elements[_this.parent.current_index]),
          jQuery(_this.parent.dom.elements[_this.parent.current_index-1]),
          _this.parent.options.duration
        );
      }
      _this.parent.queue.invokeAll("updaters");
    }, duration*1000);
  },

  reset: function () {
    clearInterval(this.executer);
    this.executer = null;
    this.create(this.parent.options.autoplay);
  }
};

// ###########################################################################

DhoniShow.prototype.queue.prototype.moduleQueue.push("center");

DhoniShow.fn.center = function(value, parent){
  this.parent = parent;
  this.value = value;
  this.dimensions = {
      max: {
        width: 0,
        height: 0
      }
  };

  this.set_dimensions();
};

DhoniShow.fn.center.prototype = {
  set_dimensions: function(){
    var _this = this;

    this.parent.dom.elements.each(function (index) {

      var dimensions = arguments[2] ? arguments[2] : {width: 0, height: 0};

      DhoniShow.helper.delayed_dimensions_load(dimensions, arguments.callee, this, arguments);

      if(!dimensions.width || !dimensions.height) return;


      _this.dimensions.max.height = (dimensions.height > _this.dimensions.max.height) ? dimensions.height : _this.dimensions.max.height;
      _this.dimensions.max.width = (dimensions.width > _this.dimensions.max.width) ? dimensions.width : _this.dimensions.max.width;

      _this.dimensions[index] = {
        width: dimensions.width,
        height: dimensions.height
      };

      if(_this.value == true || _this.value.elements) {
        for(var index in _this.dimensions){
          if(index != "max"){
            _this.dimensions[index].center = {
              paddingLeft: ( (_this.dimensions.max.width - _this.dimensions[index].width) / 2 )+"px",
              paddingTop: ( (_this.dimensions.max.height - _this.dimensions[index].height) / 2 )+"px"
            };
          }
        }
      } else if((_this.value.width || _this.value.height) && (_this.value.elements || _this.value.elements == undefined)) {
        for(var index in _this.dimensions){
          
          var element_dimensions = {
            width : new Number((_this.value.width) ? _this.value.width : _this.dimensions.max.width),
            height : new Number((_this.value.height) ? _this.value.height : _this.dimensions.max.height)
          };
          
          var css = {};
          var offsetWidth = ( _this.dimensions[index].width - element_dimensions.width ) / 2;
          var offsetHeight = ( _this.dimensions[index].height - element_dimensions.height ) / 2;

          if(offsetWidth > 0) {
            css.paddingLeft = 0;
            css.marginLeft = offsetWidth - offsetWidth - offsetWidth + "px";
          } else css.paddingLeft = offsetWidth - offsetWidth - offsetWidth + "px";

          if(offsetHeight > 0){
            css.paddingTop = 0;
            css.marginTop = offsetHeight - offsetHeight - offsetHeight + "px";
          } else css.paddingTop = offsetHeight - offsetHeight - offsetHeight + "px";

          _this.dimensions[index].center = css;
        }
      }
      _this.parent.queue.invokeAll("loaded_one", index);

    });
  }
};

// ###########################################################################

DhoniShow.prototype.queue.prototype.moduleQueue.push("align");

DhoniShow.fn.align = function(options, parent){
  var alignHelper = parent.dom.element.find(".dhonishow-aligner");
  for(var option in options){
    switch(option){
      case "alt":
        parent.dom.element.addClass("align-"+option+"_"+options[option]);
      break;
      default:
        alignHelper.addClass("align-"+option+"_"+options[option]);
      break;
    }
  }
};

// ###########################################################################

DhoniShow.prototype.queue.prototype.moduleQueue.push("thumbnails");
DhoniShow.fn.thumbnails = function(option, parent) {
  this.parent = parent;

  if(option){ 
    this.option = option.constructor == Boolean ? 50 : option.split("-")[1];
    this.parent.queue.register("updaters", this, this.set_active);
    this.parent.queue.register("loaded_all", this, this.loaded_all);
  }
};

DhoniShow.fn.thumbnails.prototype = {
  loaded_all: function(){
    this.create_dom();
    this.center();
    this.addObservers();
    this.set_active();
  },

  create_dom: function() {
    this.lis = this.parent.dom.elements.clone().removeClass("element").css({padding: "", top: "", left: "", display: ""});
    this.lis.children().each(function(index){ jQuery(this).wrap('<a href="'+this.src+"#"+index+'"></a>'); });

    this.element = jQuery("<ol class='dhonishow-thumbnails'></ol>").append(this.lis);
    this.parent.dom.element.find(".dhonishow-aligner").append(this.element);
  },

  center: function(){
    var _this = this;
    var liDimension = {width: this.lis.width(), height: this.lis.height()};

    jQuery.each(this.parent.center.dimensions, function(index){
      if(index != "max"){
        jQuery(_this.lis[index]).children().css({
          left: (liDimension.width/2 - this.width/2)+"px",
          top: (liDimension.height/2 - this.height/2)+"px"
        });
      }
    });
  },

  addObservers: function(){
    var _this = this;
    this.lis.find("a").bind("click", function(event){
      var index = new Number(this.href.split("#")[1]),
      current_index = _this.parent.current_index;
      
      _this.parent.current_index = index;
      _this.parent.effect.update(current_index, index);
      return false;
    });
  },
  
  set_active: function(){
    this.lis.removeClass("active").eq(this.parent.current_index).addClass("active");
  }
};

// ###########################################################################

jQuery.fn.dhonishow = function(options){
  return jQuery.each(this, function(index){
    new DhoniShow(this, options, index);
  });
};

jQuery(function(){jQuery(".dhonishow").dhonishow();});