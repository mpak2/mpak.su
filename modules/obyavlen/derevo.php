<? die;

// ----------------------------------------------------------------------
// mpak Content Management System
// Copyright (C) 2007 by the mpak.
// (Link: http://mp.s86.ru)
// ----------------------------------------------------------------------
// LICENSE and CREDITS
// This program is free software and it's released under the terms of the
// GNU General Public License(GPL) - (Link: http://www.gnu.org/licenses/gpl.html)http://www.gnu.org/licenses/gpl.html
// Please READ carefully the Docs/License.txt file for further details
// Please READ the Docs/credits.txt file for complete credits list
// ----------------------------------------------------------------------
// Original Author of file: Krivoshlykov Evgeniy (mpak) +7 3462 634132
// Purpose of file:
// ----------------------------------------------------------------------

if (file_exists($file_name = "themes/{$GLOBALS['conf']['settings']['theme']}/modules/{$arg['modpath']}/".(int)$_GET['pid'].".html")){
	$content = file_get_contents($file_name);
}elseif (file_exists($file_name = "themes/{$GLOBALS['conf']['settings']['theme']}/modules/{$arg['modpath']}/*.html")){
	$content = file_get_contents($file_name);
}else{
	$content = "<div><!-- pages:text --></div>";
}

{
	$folder = file_exists($file_name = 'themes/sf.ugratel.ru/dmenu_folder.html') ? file_get_contents($file_name) : "<a href='{link}' onClick=\"javascript: obj=document.getElementById('div_{id}'); if(obj.style.display==''){obj.style.display='none'}else{obj.style.display=''}; return false;\">{line}<img src=/img/tree_folder.png border=0> {ссылка}{name}</a> {количество}";
	$file = file_exists($file_name = 'themes/sf.ugratel.ru/dmenu_link.html') ? file_get_contents($file_name) : "{line}<a href='/?m[{$arg['modpath']}]&id={id}'><img src=/img/view.png border=0> {name}</a>";

	$shablon = array(
        	'id'=>'id',
        	'pid'=>'pid',
        	'поля'=>array('*'=>'0'),
        	'line'=>array(
                	'++'=>'<img src=/img/tree_plus.png border=0>', # Закрытая не последняя директория
                        '+-'=>'<img src=/img/tree_pplus.png border=0>', # Закрытая последняя директория
                        '-+'=>'[3]', # Открытая не последняя директория
                        '--'=>'[4]', # Открытая последняя директория
                        '+'=>'<img src=/img/tree_split.png border=0>', # Не последний файл
                        '-'=>'<img src=/img/tree_psplit.png border=0>', # Последний файл
                        'null'=>'[7]' # Верктикальная линия
        	),
        	'file'=>"<table cellspacing='0' cellpadding='0' border='{поля}' width=100%>
                	                <tr valign=center>
                        	                <td width=1>{tmp:prefix}</td>
                                	        <td>$file</td>
                                	</tr>
                        	</table>",
        	'folder'=>"<table cellspacing='0' cellpadding='0' border='{поля}' width=100%>
	                                <tr valign=center>
        	                                <td width=1>{tmp:prefix}</td>
                	                        <td>$folder</td>
                        	        </tr>
	                        </table>
        	                <div cellspacing='0' cellpadding='0' id='div_{id}' style='display:{отображение};'>{folder}</div>",
	        'prefix'=>array(
			'pre'=>'<table border=0 cellspacing=0 cellpadding=0><tr>',
			'+'=>'<td valign=top><img src=/img/tree_vl.png></td>', # Вертикальная полоса
			'-'=>'<td valign=top><img src=/img/tree_pvl.png></td>', # Пробел
			'post'=>'</tr></table>',
        	),
        	'отображение'=>array('*'=>'none'),
	);
}

$shablon['ссылка'] = spisok("SELECT CONCAT('0', id), CONCAT('</a><a href=/?m[{$arg['modpath']}]&kid=', id, '>') FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_kat WHERE aid>1");

$shablon['количество'] = spisok("SELECT CONCAT('0', t.id), CONCAT('[', COUNT(*), ']') FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_kat as t, {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']} as o WHERE t.id=o.kid GROUP BY o.kid");

$data = mptree(mpql(mpqw("SELECT CONCAT('0', id) as id, CONCAT('0', pid) as pid, name FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_kat WHERE aid>0 ORDER BY name")), $_GET['kid'] ? $_GET['kid'] : '00', $shablon);

echo "<div cellspacing='0' cellpadding='0'>$data</div>";

?>