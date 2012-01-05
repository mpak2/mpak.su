// CREDITS:
// Snowmaker Copyright (c) 2003 Peter Gehrig. All rights reserved.
// Modified (c) 2005, Igor Svetlov, Nickname: 12345c
// Location: http://example.javascript.aho.ru/xmp002/falling-snow.htm
// Distributed by http://www.softtime.ru/forum/; 
// Permission given to use the script provided that this notice remains as is.
var snowmax=25  // Set the number of snowflakes (more than 30 - 40 not recommended)
var snowcolor=["#c4bbcc","#ccddFF","#ccd6DD"]
    // Set the colors for the snow. Add as many colors as you like
var snowtype=["Arial Black","Arial Narrow","Times","Comic Sans MS"]
    // Set the fonts, that create the snowflakes. Add as many fonts as you like
var snowletter=["*","$","€","&#8356;"]; //,"<img src=snow.gif>","<img src=snow21.gif>"
    // Set the letter that creates your snowflake (recommended:*)
var sinkspeed=0.6   // Set the speed of sinking (recommended values range from 0.3 to 2)
var snowmaxsize=45  // Set the maximal-size of your snowflaxes
var snowminsize=18  // Set the minimal-size of your snowflaxes
    var snowsizerange=snowmaxsize-snowminsize
    // Set the snowing-zone
    // Set 1 for all-over-snowing, set 2 for left-side-snowing 
    // Set 3 for center-snowing, set 4 for right-side-snowing
var snowingleft=0.7 //левая граница присутствия снега
var snowingwidth=0.3    //ширина присутствия снега в окне
var opac=0.35       //непрозрачность снега (0.0-1.0), при 1.0 в 2 раза быстрее работает.
var stepTime=120    //шаг покадровой анимации (мсек). При менее 100 сильно нагружает процессор
var snow=new Array()
var marginbottom
var marginright
var timer
var x_mv=new Array();   var crds=new Array();   var lftrght=new Array();
var browserinfos=navigator.userAgent 
d=document
var isOpera=self.opera  
var ie5=d.all&&d.getElementById&&!isOpera
var ns6=d.getElementById&&!d.all
var browserok=ie5||ns6||isOpera

function randommaker(range){return Math.floor(range*Math.random())}

function botRight()
{
    if(ie5||isOpera)
    {
      marginbottom=d.body.clientHeight;  
      marginright=d.body.clientWidth;
    }
    else
      if(ns6)
      {
        marginbottom=innerHeight; marginright=innerWidth;
      }
}

function checkPgDn()
{
 scrltop=ns6?pageYOffset:document.body.scrollTop;
}

function initsnow() 
{
  checkPgDn();if(ns6)setInterval("checkPgDn()",999);
  botRight();
  for (i=0;i<=snowmax;i++)
  {
    crds[i] = 0;                      
    lftrght[i] = Math.random()*20;         
    x_mv[i] = 0.03 + Math.random()/10;
    snow[i]=d.getElementById("s"+i)
    snow[i].style.fontFamily=snowtype[randommaker(snowtype.length)]
    snow[i].style.fontSize=snow[i].size=randommaker(snowsizerange)+snowminsize
    snow[i].style.color=snowcolor[randommaker(snowcolor.length)]
    snow[i].sink=sinkspeed*snow[i].size/5
    newPosSnow(randommaker(marginbottom-3*snow[i].size));
  }
  movesnow();
}

function newPosSnow(y)
{
  var o;
  snow[i].posx=randommaker(marginright*snowingwidth-2*snow[i].size)+marginright*snowingleft
  snow[i].posy=y+(ns6?pageYOffset:d.body.scrollTop);
  snow[i].size=randommaker(snowsizerange)+snowminsize;
  if(snow[i].hasChildNodes()&&(o=snow[i].childNodes[0]).tagName=='IMG') o.width=o.height=randommaker(snowsizerange/1.6)+snowminsize;
}

function movesnow() 
{
  for (i=0;i<=snowmax;i++) 
  {
    snow[i].style.top=snow[i].posy+=snow[i].sink+lftrght[i]*Math.sin(crds[i])/3;
    crds[i] += x_mv[i];
    snow[i].style.left=snow[i].posx+lftrght[i]*Math.sin(crds[i]);
    if(snow[i].posy>=marginbottom-3*snow[i].size+scrltop || parseInt(snow[i].style.left)>(marginright-3*lftrght[i]))newPosSnow(0);
  }
  var timer=setTimeout("movesnow()",stepTime)
}

for (i=0;i<=snowmax;i++)
{
  d.write("<span id='s"+i+"' style='position:absolute;"+(opac<1?"-moz-opacity:"+opac+";filter:alpha(opacity="+(opac*100)+")":"")+";top:-"+snowmaxsize+"'>"
    +snowletter[Math.floor(snowletter.length*Math.random())]+"</span>")
}           //-moz-opacity:0.5;filter:alpha(opacity=50);

$(function(){
	setTimeout("initsnow()",99);
})


onmousewheel = onscroll = function(){checkPgDn()}
onresize = function(){botRight();}
