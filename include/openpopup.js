function openPopup (imageURL, caption, w, h) {

  var windowTop = 5;  var windowLeft = 5;  var defaultWidth = w + 50;  var defaultHeight = h + 50;  var onLoseFocusExit = true;  var Poster;
  var Options = "width=" + defaultWidth + ",height=" + defaultHeight + ",top=" + windowTop + ",left=" + windowLeft + ",scrollbars=2,resizable=0,copyhistory=0"

  var myScript = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">\n" +  "<html>\n" +
    "<head>\n" +  "<title>" + caption + "\</title>\n" +  "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=ISO-8859-1\">\n" +  "<meta http-equiv=\"Content-Language\" content=\"en-gb\">\n" +  "<meta http-equiv=\"imagetoolbar\" content=\"no\">\n" +  "<script language=\"JavaScript\" type=\"text/javascript\">\n" +  "function resizewindow () {\n" +
    "  var width = document.myimage.width+20 px;\n" +  "  var height = document.myimage.height+20 px;\n";

if (navigator.appName.indexOf("Netscape") != -1) {
	myScript = myScript +  "  window.innerHeight = height;\n  window.innerWidth = width;\n"
}

else if (navigator.appName.indexOf("Opera") != -1) {
	myScript = myScript +  "  window.resizeTo (width+12, height+31);\n"
}

else if (navigator.appName.indexOf("Microsoft") != -1) {
	myScript = myScript + "  window.resizeTo (width+12, height+31);\n"
}

else {
	myScript = myScript + "  window.resizeTo (width+14, height+34);\n"
}

myScript = myScript + "}\n" + "window.onload = resizewindow;\n" +  "</script>\n</head>\n" + "<body ";

myScript = myScript + "style=\"margin: 10px; margin-left: 10px; margin-right: 10px; margin-top: 10px; margin-bottom: 10px; padding-left: 10px; padding-right: 10px; padding-top: 10px; padding-bottom: 10px;\">\n" +  "<img src=\"" + imageURL + "\" alt=\"" + caption + "\" title=\"" + caption + "\" name=\"myimage\">\n" +  "</body>\n" +  "</html>\n";

  var imageWindow = window.open ("","imageWin",Options);
  imageWindow.document.write (myScript)
  imageWindow.document.close ();
 
}