<? die;

$data = <<<EOF
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<title><!-- [settings:title] --></title>
	<meta http-equiv="content-type" content="text/html; charset=cp1251">
	<link rel="stylesheet" href="/themes/19/null" type="text/css" />
</head>
<body>
<div id="container">
<div id="header"><h1 id="hed">Мой сайт</h1></div>
<div id="header2"></div>
<div id="wrapper"><div id="content"><!-- [modules] --></div></div>
<div id="navigation"><!-- [blocks:1] --></div>
<div id="extra"><!-- [blocks:2] --></div>
<div id="footer"></div>
<div id="footer2"><!-- [settings:microtime] --> с. <!-- [settings:str_bottom] --></div>
</div>
</body>
</html>
EOF;

mpqw("INSERT INTO mp_themes (`id` ,`modpath`, `type` ,`name` ,`theme`) VALUES ('18' ,'', 'text/html' ,'Серый' ,\"".str_replace('"', '\"', $data)."\")");

$data = <<<EOF
html,body{margin:0; padding:0}
body{font: 76% arial,sans-serif; text-align:center}
p{margin: 0 10px 10px}
a{color: #006; padding:0px}
h1#hed{color: #666; position: relative; top: 30px; left: 70px;}
div#header{height:40px;color: #79B30B; background-color: #f8f8f8; border-top: 1px solid #ccc; border-bottom: 1px solid #000;}
div#header2{height:40px; border-bottom: 1px solid #000;}
div#container{text-align:left}
div#content p{line-height:1.4}
div#container{width:900px; margin:0 auto}
div#wrapper{float:left; width:100%}
div#content{margin: 0 0 0 205px; margin-top: 5px;}
div#navigation{float:left; width:200px; margin-left:-900px;}
div#extra{float:left; width: 200px; margin-left:-200px;}
div.block{padding: 0px; border-bottom: 1px solid #000;}
div.btitle{text-align: center; border-bottom: 1px solid #000; padding: 4px; background-color: #f8f8f8;}
div.bcontent {padding: 10px;}
div .login {width: 100%;}
div#footer{text-align:center; clear:left; width:100%; height: 5px;}
div#footer2{text-align:center; clear:left; width:100%; height: 20px; border-top: 1px solid #000; border-bottom: 1px solid #000; background-color: #f8f8f8;}

EOF;

mpqw("INSERT INTO mp_themes (`id` ,`modpath`, `type` ,`name` ,`theme`) VALUES ('19' ,'', 'text/css' ,'Серый' ,\"".str_replace('"', '\"', $data)."\")");

$data = <<<EOF
<table width=240px cellspacing=0 cellpadding=0 border=0>
   <tr>
      <td width=13><img src=/files/49/null></td>
      <td align=center style='background-image: url(/files/50/null); width:100%;'><b><!-- [block:title] --></b></td>
      <td width=13><img src=/files/51/null></td>
   </tr>
   <tr>
      <td colspan=3><div style='margin: 7px;'><!-- [block:content] --></div></td>
   </tr>
</table>
EOF;

mpqw("INSERT INTO mp_themes_blk (`id` ,`tid`, `sort` ,`name` ,`theme`) VALUES ('1' ,'1', '1' ,'Имя' ,\"".str_replace('"', '\"', $data)."\")");

$data = <<<EOF
		<div class="sidenav">

			<h2><!-- [block:title] --></h2>
			<ul>
				<!-- [block:content] -->
			</ul>

		</div>

EOF;

mpqw("INSERT INTO mp_themes_blk (`id` ,`tid`, `sort` ,`name` ,`theme`) VALUES ('4' ,'9', '2' ,'sidenav' ,\"".str_replace('"', '\"', $data)."\")");

$data = <<<EOF
    <!-- sidebox -->
     <table bgcolor="#000000" border="0" cellpadding="0" cellspacing="0" width="100%">
      <tr>
       <td>

        <table border="0" cellpadding="3" cellspacing="1" width="100%">
         <tr>
          <td bgcolor="#cccccc">
           &nbsp;<a class="title" href="index.html"><!-- [block:title] --></a>
          </td>
         </tr>

         <tr>
          <td bgcolor="#ffffff">
           <!-- [block:content] -->
          </td>
         </tr>
        </table>

       </td>
      </tr>

     </table>

     <!-- end sidebox -->
     <br />

     <!-- sidebox -->
EOF;

mpqw("INSERT INTO mp_themes_blk (`id` ,`tid`, `sort` ,`name` ,`theme`) VALUES ('5' ,'12', '3' ,'sidebox' ,\"".str_replace('"', '\"', $data)."\")");

$data = <<<EOF
      <table border="0" cellpadding="2" cellspacing="1" bgcolor="#000000" width="100%">
       <tr bgcolor="#101842"> 
        <td class="small"> 
         <div align="center"><b>:: <!-- [block:title] --> :.</b></div>
        </td>
       </tr>
       <tr bgcolor="#354463"> 
        <td class="small">
<!-- [block:content] -->
        </td>
       </tr>
      </table>
<p>
EOF;

mpqw("INSERT INTO mp_themes_blk (`id` ,`tid`, `sort` ,`name` ,`theme`) VALUES ('6' ,'14', '4' ,'' ,\"".str_replace('"', '\"', $data)."\")");

$data = <<<EOF
<div class="sidenav">
	<h2><!-- [block:title] --></h2>
	<ul><!-- [block:content] --></ul>
</div>

EOF;

mpqw("INSERT INTO mp_themes_blk (`id` ,`tid`, `sort` ,`name` ,`theme`) VALUES ('7' ,'16', '5' ,'Антикварь' ,\"".str_replace('"', '\"', $data)."\")");

$data = <<<EOF
<div class="block">
	<div class="btitle"><!-- [block:title] --></div>
	<div class="bcontent"><!-- [block:content] --></div>
</div>
EOF;

mpqw("INSERT INTO mp_themes_blk (`id` ,`tid`, `sort` ,`name` ,`theme`) VALUES ('8' ,'18', '6' ,'Серый' ,\"".str_replace('"', '\"', $data)."\")");

?>