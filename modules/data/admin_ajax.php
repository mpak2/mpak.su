<?	
	$return = ['error'=>0,'mess'=>''];
	if($FileType = trim(get($_POST,'FileType'))){		
		if(isset($_REQUEST['cat_id_item']) AND $_REQUEST['cat_id_item']!=='false'){
			$cat_id_item=intval(get($_REQUEST,'cat_id_item'));
		}else{
			preg_match("#[/&?]edit[:=](\d+)#iu", get($_SERVER,'HTTP_REFERER'),$cat_id_item);
			$cat_id_item = intval(get($cat_id_item,1));
		}
		if(isset($_REQUEST['cat']) AND $_REQUEST['cat']!=='false'){
			$cat=preg_replace("#\/\\\"\'\s#iu","",get($_REQUEST,'cat'));
		}else{
			preg_match("@[/&\?]r[:=]([^:/&#\?]+)@iu", get($_SERVER,'HTTP_REFERER'),$cat);
			$cat = preg_replace("#\/\\\"\'\s#iu","",urldecode((string)get($cat,1)));
		}
		
		if(($action=trim(get($_POST,'action'))) == 'upload'){
			$Files = normalize_files_array();
			if(($Files = get($Files,'files')?:(get($Files,'0')?:[]))){
				
				$cat_id = $cat ? fk("cat",$w=['name'=>$cat],$w,$w,'id') : 0;
				$type_id = fk("type",$w=['description'=>$FileType],$w+['name'=>$FileType],$w,'id');				
				
				$fields = [
					'image'=>[
						'type_regex'=>"#^image/\w+$#iu",
						'folder_'=>'images',
						'field'=>'img',
					],
					'default'=>[
						'type_regex'=>"#^.+$#iu",
						'folder_'=>'files',
						'field'=>'file',
					]
				];
				$TypeSett = get($fields,$FileType)?:get($fields,'default');
				
				
				$folder = "include/{$TypeSett['folder_']}";
				$sys_name = "{$conf['db']['prefix']}{$arg['modpath']}_index_{$TypeSett['field']}_%id%_{$cat}_{$cat_id_item}.%ext%";				
				
				foreach($Files as $File){
					if(!$File['error'] AND preg_match($TypeSett['type_regex'],$File['type'])){	
						
						
						$md5_file = md5_file($File['tmp_name']);
						$file_info = pathinfo($File['name']);
						$sys_name_ = preg_replace("#%ext%#iu",$file_info['extension'],$sys_name);
						if($copy_file = rb('index','md5',"[{$md5_file}]")){
							$feldxxx = rb('type','id',get($copy_file,'type_id'),'description');
							$feldxxx = get($fields,$feldxxx,'field')?:get($fields,'default','field');
							fk(
								'index',
								$w=[
									'name'=>$file_info['filename'],
									'md5'=>$md5_file,
									'cat_id'=>$cat_id,
									'type_id'=>$type_id,
									$TypeSett['field']=>get($copy_file,$feldxxx),
									'cat_id_item'=>$cat_id_item
								],
								$w
							);
						}else{
							if($new_file_id = fk('index',null,['name'=>$file_info['filename'],'md5'=>$md5_file,'cat_id'=>$cat_id,'type_id'=>$type_id,'cat_id_item'=>$cat_id_item],null,'id')){
								if(
									$sys_name_ = preg_replace("#%id%#iu",$new_file_id,$sys_name_) AND
									$folder = mpopendir($folder) AND 
									move_uploaded_file($File['tmp_name'], "{$folder}/{$sys_name_}")
								){
									if(!fk('index',['id'=>$new_file_id],null,[$TypeSett['field']=>"{$TypeSett['folder_']}/{$sys_name_}"])){
										$return = ['error'=>1,'mess'=>'Ошибка обновления данных!'];										
										unlink("{$folder}/{$sys_name_}");
										mpqw("DELETE FROM `{$conf['db']['prefix']}{$arg['modpath']}_index` WHERE id='{$new_file_id}' LIMIT 1");
										break;
									}
								}else{
									$return = ['error'=>1,'mess'=>'Ошибка сохранения файла!'];
									mpqw("DELETE FROM `{$conf['db']['prefix']}{$arg['modpath']}_index` WHERE id='{$new_file_id}' LIMIT 1");
									break;									
								}
							}else{
								$return = ['error'=>1,'mess'=>'Ошибка записи данных!'];
							}							
						}						
					}
				}
			}else{
				$return = ['error'=>1,'mess'=>'Отсутствуют загружаемые файлы!'];
			}			
		}elseif($action=='get'){
			$return = ['error'=>0,'mess'=>'','data'=>[],'cats'=>[],'data_id'=>[]];
			
			$cat_id = $cat ? fk("cat",$w=['name'=>$cat],$w,$w,'id') : 0;
			$type_id = rb('type','description',"[{$FileType}]",'id')?:rb('type','description','[image]','id');
			
			if($type_id AND $cat_id AND $cat_id_item){
				$data = rb('index','type_id','cat_id','cat_id_item','id',$type_id,$cat_id,$cat_id_item);
			}elseif($type_id AND $cat_id){
				$data = rb('index','type_id','cat_id','id',$type_id,$cat_id);
			}elseif($type_id){
				$data = rb('index','type_id','id',$type_id);				
			}else{
				$data = rb('index');
			}
			if(get($_REQUEST,'is_cat')){
				$Cats = array_keys(rb('cat','id','name',rb($data,'cat_id')));
				$return['cats'][] = ['value'=>":",'active'=>false, 'name'=>"Все"];
				if($cat AND !rb($Cats,'name',"[{$cat}]")){
					$Cats[] = $cat;
				}
				foreach($Cats as $cat_item){
					$table = preg_replace("#^{$conf['db']['prefix']}#iu",'',$cat_item);
					$module = explode("_",$table)[0];					
					if($module = get($conf['modules'],$module)){
						$return['cats'][] = [
							'active'=> ($cat_item==$cat AND !$cat_id_item) ? true : false,
							'value'=>"{$cat_item}:0",
							'name'=>($tmp_var = ($module['name']?:$module['modname']) ." > ". (get($conf['settings'],$table)?:(preg_match("#_index$#iu",$table)?($module['name']?:"Index"):"Index")))
						];
						if($cat_item==$cat AND $cat_id_item)
							$return['cats'][] = ['active'=>true, 'value'=>"{$cat_item}:{$cat_id_item}",'name'=>$tmp_var." <b>{{$cat_id_item}}</b>"];
					}
				}				
				if(count($return['cats'])==1)
					$return['cats'][0]['active'] = true;
			}
			$get_icon = function($ext){
				$icons = [
					['name'=>'ai.png','ext'=>['ai', 'ait']],
					['name'=>'avi.png','ext'=>['avi']],
					['name'=>'css.png','ext'=>['css','less']],
					['name'=>'csv.png','ext'=>['csv']],
					['name'=>'dbf.png','ext'=>['dbf','odb']],
					['name'=>'doc.png','ext'=>['odt','ott','sxw','stw','doc','docx','docm','docx','dot','dotm']],
					['name'=>'dwg.png','ext'=>['dwg']],
					['name'=>'exe.png','ext'=>['exe','msi']],
					['name'=>'file.png','ext'=>['file']],
					['name'=>'fla.png','ext'=>['fla']],
					['name'=>'html.png','ext'=>['html','mht','mhtml','htm']],
					['name'=>'iso.png','ext'=>['iso','isz','cue','bin','nrg','mdf','dmg','nrg']],
					['name'=>'jpg.png','ext'=>['jpg','jpeg']],
					['name'=>'json.png','ext'=>['json']],
					['name'=>'js.png','ext'=>['js']],
					['name'=>'mp3.png','ext'=>['mp3']],
					['name'=>'mp4.png','ext'=>['mp4']],
					['name'=>'pdf.png','ext'=>['pdf']],
					['name'=>'png.png','ext'=>['png']],
					['name'=>'ppt.png','ext'=>['odp','otp','sxi','sti','ppt','pptx','pptm','pps','ppsx','ppsm','pot','potx','potm','sdx']],
					['name'=>'psd.png','ext'=>['psd','pdd','psb']],
					['name'=>'rtf.png','ext'=>['rtf']],
					['name'=>'search.png','ext'=>['search']],
					['name'=>'svg.png','ext'=>['svg']],
					['name'=>'txt.png','ext'=>['txt']],
					['name'=>'xls.png','ext'=>['ods','ots','sxc','stc','xls','xlsx','xlsm','xlsb','xltx','xltm','xlc','xlm','xlw','xlt']],
					['name'=>'xml.png','ext'=>['xml']],
					['name'=>'zip-1.png','ext'=>['rar','gzip','7z','gzip','bzip2','tar','gz','bz','bz2','arj','cab','chm','cpio','deb','hfs','lzh','lzma','nsis','rpm','udf','wim','xar','z']],
					['name'=>'zip.png','ext'=>['zip']],
				];				
				$ico = '';
				foreach($icons as $icon){
					if(in_array($ext,$icon['ext'])){
						$ico = $icon['name']; break;
					}						
				}
				return $ico?:"file.png";
			};
			
			$return['data_id'] = array_keys($data);
			if($FileType=='image'){
				foreach($data as $data_item){
					$return['data'][] = [
						'id' =>$data_item['id'],
						'name' =>$data_item['name'].".png",
						'url'=>"/data:img/{$data_item['id']}/tn:index/fn:img/null/img.png",
						'bg'=>"/data:img/{$data_item['id']}/tn:index/fn:img/w:200/h:150/null/img.png",
					];
				}
			}else{				
				foreach($data as $data_item){					
					$return['data'][] = [
						'ext'=>($ext=get(pathinfo($data_item['file']),'extension')),
						'id' =>$data_item['id'],
						'name' =>$data_item['name'].".{$ext}",
						'url'=>"/data:file/{$data_item['id']}/tn:index/fn:file/null/".urlencode($data_item['name']).".".$ext,
						'bg'=>"/include/jquery/tinymce4/mods/file_icon/".($get_icon($ext)),
					];
				}
			}
			
			//print_r($return);
			
			
		}elseif($action=='del'){
			$field = $FileType=="image" ? 'img' : 'file';
			foreach(rb('index','id','id',array_flip(get($_REQUEST,'IDs'))) as $file){
				mpqw("DELETE FROM `{$conf['db']['prefix']}{$arg['modpath']}_index` WHERE id='{$file['id']}' LIMIT 1");
				if($file=mpopendir("include/{$file[$field]}")){
					if(!rb('index','md5','id',"[".md5_file($file)."]")){
						unlink($file);
					}
				}
			}
		}
	}else{
		$return = ['error'=>1,'mess'=>'Отсутствуют переменная FileType!'];
	}
	echo json_encode($return);
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
?>