    <style type="text/css">
		body {font-family: verdana, arial, sans-serif; font-size: 9pt;}
		table { border:1px; background-color: #ffffff; font-size: 9pt; font-family: verdana, arial, sans-serif; }
		input {border:1px solid #ABABAB; }
		textarea {border:1px solid #ABABAB }
		select {border:1px solid #ABABAB }
		#column1 { float:left; width: 130; }
		#column2 {
	text-align:center;
	width: 85%;
	padding-top: 2px;
	padding-right: 0px;
	padding-bottom: 0px;
	padding-left: 6px;
}
	    #cr { clear:both; border:1px; background-color: #ffffff; font-size: 8pt; font-family: verdana, arial, sans-serif; }
    .head {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-style: normal;
	font-weight: bold;
	color: #006699;
	font-size: 16px;
}
    .menu_active {
	font-weight: normal;
	color: #FFFFFF;
	background-color: #003399;
}
    .page_name {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
	color: #FFFFFF;
	background-color: #003399;
	padding: 3px;
}
    .style1 {font-style: normal; font-weight: bolder; color: #006699; font-family: Verdana, Arial, Helvetica, sans-serif;}
    </style>

<div id='column2'><FORM NAME='form1' ID='form1' action="/">
<TABLE border=1 cellpadding="1" cellspacing="0" >

<tr bgcolor="#ECE9FE">
<td align=right>Поиск начинать с каталога </td>
<td><INPUT TYPE=TEXT SIZE=40 MAXLENGTH=254 NAME='f_path' ID='f_path' VALUE="."></td></tr>
<tr bgcolor="#FFFFFF">
<td align=right>Искать файлы по шаблону </td>
<td>
<INPUT TYPE=TEXT SIZE=12 MAXLENGTH=128 NAME='f_name' ID='f_name' VALUE="">
<select SIZE=1 NAME='f_name_connect' ID='f_name_connect'>
  <option value=" -o " selected>или</option>
  <option value=" -a ">и</option>
</select>
<INPUT TYPE=TEXT SIZE=12 MAXLENGTH=128 NAME='f_name1' ID='f_name1' VALUE=""></td></tr>

<tr bgcolor="#ECE9FE">
<td align=right>Не обрабатывать файлы, которые соответсвуют выбранному шаблону </td>
<td>
<INPUT TYPE=CHECKBOX NAME='f_prune' ID='f_prune' VALUE=" -prune "></td></tr>

<tr bgcolor="#ECE9FE">
<td align=right>Выполнить команду </td>
<td>
<INPUT TYPE=TEXT SIZE=24 MAXLENGTH=128 NAME='f_exec' ID='f_exec' VALUE=""> 
с запросом 
<INPUT TYPE=CHECKBOX NAME='f_ok' ID='f_ok' VALUE=" -ok "></td></tr>
<tr bgcolor="#ECE9FE">
<td align=right>Вывести информацию о файле в формате 'ls -dils'</td>
<td>
<INPUT TYPE=CHECKBOX NAME='f_ls' ID='f_ls' VALUE=" -ls "></td></tr>
<tr bgcolor="#ECE9FE">
  <td align=right>Вывести имя файла </td>
  <td>
    <INPUT NAME='f_print' TYPE=CHECKBOX ID='f_print' VALUE=" -print " checked>
  </td>
</tr>
<tr bgcolor="#ECE9FE">
  <td align=right>Искать файлы 'снизу вверх' (используется для cpio) </td>
  <td>
    <INPUT TYPE=CHECKBOX NAME='f_depth' ID='f_depth' VALUE=" -depth ">  </td>
</tr>

<tr bgcolor="#FFFFFF">
<td align=right>Выбрать файлы  созданные  ( дней назад )</td>
<td>
<select SIZE=1 NAME='f_ctime_checker' ID='f_ctime_checker'>
<option value=" " selected>==</option>
<option value=" -">&lt;</option>
<option value=" +">&gt;</option>
</select>
<INPUT TYPE=TEXT SIZE=3 MAXLENGTH=3 NAME='f_ctime' ID='f_ctime' VALUE=""></td></tr>

<tr bgcolor="#FFFFFF">
<td align=right>Выбрать файлы  измененные ( дней назад )</td>
<td>
<select SIZE=1 NAME='f_mtime_checker' ID='f_mtime_checker'>
<option value=" " selected>==</option>
<option value=" -">&lt;</option>
<option value=" +">&gt;</option>
</select>
<input type=TEXT size=3 maxlength=3 name='f_mtime' id='f_mtime' value=""></td>
</tr>

<tr bgcolor="#FFFFFF">
<td align=right>Выбрать файлы, к которым обращались ( дней назад )</td>
<td>
<select SIZE=1 NAME='f_atime_checker' ID='f_atime_checker'>
<option value=" " selected>==</option>
<option value=" -">&lt;</option>
<option value=" +">&gt;</option>
</select>
<INPUT TYPE=TEXT SIZE=3 MAXLENGTH=3 NAME='f_atime' ID='f_atime' VALUE=""></td></tr>


<tr bgcolor="#FFFFFF">
<td align=right> Выбрать файлы, измененные ранее, чем файл</td>
<td>
<INPUT TYPE=TEXT SIZE=40 MAXLENGTH=254 NAME='f_newer' ID='f_newer' VALUE=""></td></tr>

<tr bgcolor="#ECE9FE">
<td align=right>Тип файла </td>
<td>
<select SIZE=1 NAME='f_type' ID='f_type'>
  <option value="" selected>Любой</option>
  <option value=" -type f">Обычный файл</option>
  <option value=" -type d">Каталог</option>
  <option value=" -type l">Символическая ссылка</option>
  <option value=" -type p">Fifo файл</option>
  <option value=" -type b">Блочное устройство</option>
  <option value=" -type c">Сырое устройство</option>
  <option value=" -type s">Сокет</option>
  <option value=" -type D">Door (Solaris)</option>
  <option value=" -type n ">Network special file (hp-ux)</option>
  <option value=" -type M ">Mount point (hp-ux)</option>
</select></td></tr>

<tr bgcolor="#ECE9FE">
<td align=right>Размер файла  (в байтах)</td>
<td>
<select SIZE=1 NAME='f_size_checker' ID='f_size_checker'>
<option value=" " selected>==</option>
<option value=" -">&lt;</option>
<option value=" +">&gt;</option>
</select>
<INPUT TYPE=TEXT SIZE=12 MAXLENGTH=12 NAME='f_size' ID='f_size' VALUE=""></td></tr>

<tr bgcolor="#ECE9FE">
<td align=right>Inode файла </td>
<td>
<select SIZE=1 NAME='f_inode_checker' ID='f_inode_checker'>
<option value=" " selected>==</option>
<option value=" -">&lt;</option>
<option value=" +">&gt;</option>
</select>
<INPUT TYPE=TEXT SIZE=12 MAXLENGTH=12 NAME='f_inode' ID='f_inode' VALUE=""></td></tr>

<tr bgcolor="#ECE9FE">
<td align=right>У файла имеется ссылок</td>
<td>
<select SIZE=1 NAME='f_links_checker' ID='f_links_checker'>
<option value=" " selected>==</option>
<option value=" -">&lt;</option>
<option value=" +">&gt;</option>
</select>
<INPUT TYPE=TEXT SIZE=2 MAXLENGTH=2 NAME='f_links' ID='f_links' VALUE=""></td></tr>
<tr bgcolor="#FFFFFF">
  <td align=right>Права доступа у владельца файла </td>
  <td>
<select name='f_perm_user_checker' size=1 id='f_perm_user_checker'>
  <option value="0" selected></option>
  <option value="u=">==</option>
  <option value="u+">включая</option>
  <option value="u-">исключая</option>
</select>    
r      <input name="f_perm_user_r" type="checkbox" id="f_perm_user_r" value="r">
    w    <input name="f_perm_user_w" type="checkbox" id="f_perm_user_w" value="w"> 
    x    <input name="f_perm_user_x" type="checkbox" id="f_perm_user_x" value="x"> 
    set-ID
    <input name="f_perm_user_s" type="checkbox" id="f_perm_user_s" value="s"></td>
</tr>
<tr bgcolor="#FFFFFF">
  <td align=right>Права доступа у группы</td>
  <td>
    <select NAME='f_perm_group_checker' SIZE=1 ID='f_perm_group_checker'>
      <option value="0" selected></option>
      <option value="g=">==</option>
      <option value="g+">включая</option>
      <option value="g-">исключая</option>
    </select>
    r
    <input name="f_perm_group_r" type="checkbox" id="f_perm_group_r" value="r">
    w
    <input name="f_perm_group_w" type="checkbox" id="f_perm_group_w" value="w">
    x
    <input name="f_perm_group_x" type="checkbox" id="f_perm_group_x" value="x">
    set-ID
    <input name="f_perm_group_s" type="checkbox" id="f_perm_group_s" value="s"></td>
</tr>
<tr bgcolor="#FFFFFF">
  <td align=right>Права доступа для остальных</td>
  <td>
    <select NAME='f_perm_other_checker' SIZE=1 ID='f_perm_other_checker'>
      <option value="0" selected></option>
      <option value="o=">==</option>
      <option value="o+">включая</option>
      <option value="o-">исключая</option>
    </select>
    r
    <input name="f_perm_other_r" type="checkbox" id="f_perm_other_r" value="r">
    w
    <input name="f_perm_other_w" type="checkbox" id="f_perm_other_w" value="w">
    x
    <input name="f_perm_other_x" type="checkbox" id="f_perm_other_x" value="x"></td>
</tr>
<tr bgcolor="#FFFFFF">
  <td align=right> Специальные права доступа </td>
  <td><select NAME='f_perm_special_checker' SIZE=1 ID='f_perm_special_checker'>
    <option value="0" selected></option>
    <option value="+">включая</option>
    <option value="-">исключая</option>
    </select>
    sticky bit 
    <input name="f_perm_special_t" type="checkbox" id="f_perm_special_t" value="t">
    mandatory locking 
    <input name="f_perm_special_l" type="checkbox" id="f_perm_special_l" value="l"></td>
</tr>

<tr bgcolor="#ECE9FE">
<td align=right> Искать только на указанной файловой системе</td>
<td>
<INPUT TYPE=CHECKBOX NAME='f_mount' ID='f_mount' VALUE=" -mount "></td></tr>

<tr bgcolor="#ECE9FE">
<td align=right>Искать на файловой системе следующего типа</td>
<td>
<select SIZE=1 NAME='f_fstype' ID='f_fstype'>
<option value="" selected>&nbsp;</option>
<option value=" -fstype ufs">ufs</option>
<option value=" -fstype 4.3">4.3</option>
<option value=" -fstype 4.2">4.2</option>
<option value=" -fstype nfs">nfs</option>
<option value=" -fstype tmp">tmp</option>
<option value=" -fstype mfs">mfs</option>
<option value=" -fstype S51K">S51K</option>
<option value=" -fstype S52K">S52K</option>
<option value=" -fstype vxfs">vxfs</option>
<option value=" -fstype zfs">zfs</option>
</select>
<INPUT TYPE=TEXT SIZE=8 MAXLENGTH=8 NAME='f_fstype_txt' ID='f_fstype_txt' VALUE=""></td></tr>
<tr bgcolor="#ECE9FE">
  <td align=right>Раскрывать символические ссылки </td>
  <td>
    <INPUT TYPE=CHECKBOX NAME='f_follow' ID='f_follow' VALUE=" -follow ">  </td>
</tr>

<tr bgcolor="#FFFFFF">
<td align=right> Файл принадлежит несуществующему пользователю</td>
<td>
<INPUT TYPE=CHECKBOX NAME='f_nouser' ID='f_nouser' VALUE=" -nouser "></td></tr>

<tr bgcolor="#FFFFFF">
<td align=right> Файл принадлежит несуществующей группе</td>
<td>
<INPUT TYPE=CHECKBOX NAME='f_nogroup' ID='f_nogroup' VALUE=" -nogroup "></td></tr>

<tr bgcolor="#FFFFFF">
<td align=right>Файл принадлежит пользователю </td>
<td>
<INPUT TYPE=TEXT SIZE=8 MAXLENGTH=32 NAME='f_user' ID='f_user' VALUE=""></td></tr>
<tr bgcolor="#FFFFFF">
<td align=right>Файл принадлежит группе </td>
<td>
<INPUT TYPE=TEXT SIZE=8 MAXLENGTH=32 NAME='f_group' ID='f_group' VALUE=""></td></tr>
                    
<tr align=center><td colspan=2>
<TEXTAREA ID='result' NAME='result' rows=3 cols=70>
</TEXTAREA>
</td></tr>
<tr align=center><td colspan=2>
<INPUT TYPE=button value='Показать команду ' ONCLICK="show_value();">
<INPUT TYPE=reset value='Сбросить ' >
</td></tr>
</TABLE></FORM>
</div>
<div id='cr' align=center>

<SCRIPT type="text/javascript" 
language="javascript">
function getObj(objID)
{
    if (document.getElementById) {return document.getElementById(objID);}
    else if (document.all) {return document.all[objID];}
    else if (document.layers) {return document.layers[objID];}
}

	function show_value() {
		var f;
		var perm = [];
		var perm_add=false;
		f='find '+getObj('f_path').value	+ getObj('f_type').value;
		if( getObj('f_atime').value != '' ) {
			f=f+' -atime '+getObj('f_atime_checker').value+getObj('f_atime').value;
		}
		if( getObj('f_ctime').value != '' ) {
			f=f+' -ctime '+getObj('f_ctime_checker').value+getObj('f_ctime').value;
		}		
		if( getObj('f_mtime').value != '' ) {
			f=f+' -mtime '+getObj('f_mtime_checker').value+getObj('f_mtime').value;
		}		
		if( getObj('f_newer').value != '' ) {
			f=f+' -newer '+getObj('f_newer').value;
		}	
		if( getObj('f_name').value != '' ) {
				if( getObj('f_name1').value != '' ) {
					f=f+' \\( -name "'+getObj('f_name').value+'"'
							+ getObj('f_name_connect').value
							+' -name "'+getObj('f_name1').value+'" \\) ';
				} else 
					f=f+' -name "'+getObj('f_name').value+'"';		
		}

		if( getObj('f_size').value != '' ) {
			f=f+' -size '+getObj('f_size_checker').value+getObj('f_size').value+'c';
		}		
		if( getObj('f_depth').checked ) 
				f=f+getObj('f_depth').value;
		else {
			if( getObj('f_prune').checked ) 
					f=f+getObj('f_prune').value;
		}
		if( getObj('f_follow').checked ) 
				f=f+getObj('f_follow').value;				
		if( getObj('f_inode').value != '' ) {
			f=f+' -inode '+getObj('f_inode_checker').value+getObj('f_inode').value;
		}
		if( getObj('f_links').value != '' ) {
			f=f+' -links '+getObj('f_links_checker').value+getObj('f_links').value;
		}
		if( getObj('f_mount').checked ) 
				f=f+getObj('f_mount').value;
				
		if( getObj('f_fstype_txt').value != '' ) {
			f=f+' -fstype '+getObj('f_fstype_txt').value;
		} else
			f=f+getObj('f_fstype').value;
		if( getObj('f_nogroup').checked ) 
				f=f+getObj('f_nogroup').value;			
		if( getObj('f_nouser').checked ) 
				f=f+getObj('f_nouser').value;
		if( getObj('f_user').value != '' ) {
			f=f+' -user '+getObj('f_user').value;
		}
		if( getObj('f_group').value != '' ) {
			f=f+' -group '+getObj('f_group').value;
		}
		var i=0;
		if( getObj('f_perm_user_checker').selectedIndex != 0 ) {
			perm[i]=getObj('f_perm_user_checker').value;
				if( getObj('f_perm_user_r').checked ) perm[i]=perm[i]+'r';			
				if( getObj('f_perm_user_w').checked ) perm[i]=perm[i]+'w';			
				if( getObj('f_perm_user_x').checked ) perm[i]=perm[i]+'x';			
				if( getObj('f_perm_user_s').checked ) perm[i]=perm[i]+'s';			
				perm_add=true;
				i++;
		}
		if( getObj('f_perm_group_checker').selectedIndex != 0 ) {
			perm[i]=getObj('f_perm_group_checker').value;
				if( getObj('f_perm_group_r').checked ) perm[i]=perm[i]+'r';			
				if( getObj('f_perm_group_w').checked ) perm[i]=perm[i]+'w';			
				if( getObj('f_perm_group_x').checked ) perm[i]=perm[i]+'x';			
				if( getObj('f_perm_group_s').checked ) perm[i]=perm[i]+'s';			
				perm_add=true;
				i++;
		}
		if( getObj('f_perm_other_checker').selectedIndex != 0 ) {
			perm[i]=getObj('f_perm_other_checker').value;
				if( getObj('f_perm_other_r').checked ) perm[i]=perm[i]+'r';			
				if( getObj('f_perm_other_w').checked ) perm[i]=perm[i]+'w';			
				if( getObj('f_perm_other_x').checked ) perm[i]=perm[i]+'x';			
				perm_add=true;
				i++;
		}				
		if( getObj('f_perm_special_checker').selectedIndex != 0 ) {
			perm[i]=getObj('f_perm_special_checker').value;
				if( getObj('f_perm_special_t').checked ) perm[i]=perm[i]+'t';			
				if( getObj('f_perm_special_l').checked ) perm[i]=perm[i]+'l';			
				perm_add=true;
		}
		if( perm_add ) 	f=f+' -perm '+ perm.join(',');
		if( getObj('f_exec').value != '' ) {
			if( getObj('f_ok').checked ) 
				f=f+' -ok '+getObj('f_exec').value+' {} \\; ';
			else 
				f=f+' -exec '+getObj('f_exec').value+' {} \\; ';
		} 
		if( getObj('f_ls').checked ) {
				f=f+getObj('f_ls').value;
		} else {
			if( getObj('f_print').checked )
				f=f+getObj('f_print').value;
		}

		getObj('result').innerHTML=f;
	}
</SCRIPT>
