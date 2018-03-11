<?
class File{
	public static $saveTarget='files/';
	
	public static function getInfo($id,$withPermission=true){
		$id=intval($id);
		$info=getRow('files','','id="'.$id.'"');
		$info->folderPath=Folder::getRealPath($info->folder_id);
		$info->create_time=$info->upload_time;
		$info->upload_time=getTime(strtotime($info->upload_time),'US');
		$info->type=getMime($info->filename);
		$info->fullpath=ROOT.$info->folderPath.filenameFilter($info->path);
		//$info->fullpath=ROOT.'files/'.$info->path;
		
		if($withPermission){
			$info->permission=Permission::getMyPermission([
				'ref_id'	=>	$id,
				'type'		=>	'file'
			]);
		}
		
		return $info;
	}

	public static function getFiles($d){
		session_write_close();
		$wh=[];
		$wh['valid']=1;
		if(isset($d['tid'])) $wh['task_id']=intval($d['tid']);
		if(isset($d['fid'])) $wh['folder_id']=intval($d['fid']);
		if(isset($d['pid'])) $wh['project_id']=intval($d['pid']);
		if(!empty($d['ids'])){
			$ids=[];
			foreach(
				$d['ids']
				as $i
			) $ids[]=intval($i);
			if(count($ids)!=0){
				unset($wh['folder_id']);
				$wh[]='folder_id IN('.implode(',', $ids).')';
			}
			
		}
		
		if(!empty($d['kw'])){
			$kws=[];
			foreach(explode(' ',$d['kw']) as $k){
				if($k) $kws[]='filename LIKE "%'.$k.'%"';
			}
			if(count($kws)){
				$wh[]='('.exArr($kws,'AND').')';
			}
			
		}
		
		$data=[];
		foreach(
			getRows(
				'files',
				'id',
				exArr($wh,'AND'),
				'Order By 
				filename ASC,
				folder_id ASC,
				id DESC'
			) as $dd
		){
			$pAdd=self::getInfo($dd->id);
			unset($pAdd->fullpath);
			unset($pAdd->folderPath);
			unset($pAdd->path);
			$data[]=$pAdd;
		}
		
		
		
		if(getVal($d,'group','false')=='true'){
			$newD=[];
			$lastName='';
			$lastFid=-1;
			foreach($data as $f){
				if(!($f->filename==$lastName && $f->folder_id==$lastFid)){
					$newD[]=$f;
				}
				$lastName=$f->filename;
				$lastFid=$f->folder_id;
			}
			$data=$newD;
		}
		
		return $data;
	}
	
	public static function getSameFid($folder,$filename){
		$id=[];
		foreach(
			getRows('files','id',exArr([
				'valid'	=>	1,
				'folder_id'	=>	$folder,
				'filename'	=>	$filename
			],'AND'))
			as $d
		) $id[]=$d->id;
		
		return $id;
	}
	
	public static function delete($d){
		$fid=intval($d['fid']);
		$delFid=[$fid];
		$info=self::getInfo($fid);
		
		if(getVal($d,'same','false')=='true'){
			$same_id=self::getSameFid($info->folder_id,$info->filename);
			$delFid=array_merge($delFid,$same_id);
		}
		
		Logger::log('刪除了檔案 '.$info->filename.' ('.$fid.')');
		return doUpdate('files','valid=0','id IN('.implode(',',$delFid).')');
	}
	
	public static function rename($d){
		$fid=intval($d['fid']);
		$tarFid=[$fid];
		$info=self::getInfo($fid);
		if(getVal($d,'same','false')=='true'){
			$same_id=self::getSameFid($info->folder_id,$info->filename);
			$tarFid=array_merge($tarFid,$same_id);
		}
		
		return doUpdate('files',[
			'filename'	=>	$d['name'],
			'showname'	=>	$d['name']
		],'id IN('.implode(',',$tarFid).')');
	}
	
	public static function addFile($d){
		$path=getRow('files','UUID() as uuid','1=1','LIMIT 1');
		$finfo=explode('.',$d['filename']);
		$randKey=sha1(uniqid().sha1($path->uuid));
		$ext='';
		if(count($finfo)!=0) $ext=array_pop($finfo);

		$path=implode('.',$finfo).'['.$randKey.']';
		if(!empty($ext)) $path.='.'.$ext;
		$sqlData=[
			'task_id'	=>	getVal($d,'task_id',-1),
			'folder_id'	=>	getVal($d,'folder_id',-1),
			'project_id'	=>	getVal($d,'project_id',-1),
			'owner'		=>	$_SESSION['id'],
			'path'		=>	$path,
			'showname'	=>	getVal($d,'showname',$d['filename']),
			'filename'	=>	$d['filename'],
			'filesize'	=>	$d['filesize'],
			'comment'	=>	getVal($d,'comment',''),
			'valid'		=>	0
		];
			
		$insID=doInsert('files',$sqlData);

		return [
			'id'	=>	$insID,
			'pt'	=>	$path
		];
	}
	
	public static function storageFile($d){
		$start=floatval($d['start']);
		$id=intval($d['fid']);
		$finfo=self::getInfo($id);
		$tfile=$_FILES['data'];
		
		$fp=$finfo->fullpath;
		
		if(!file_exists($fp)){
			@fclose(fopen($fp,'w'));
		}
		@mkdir(u_dirname($fp));
		$f=fopen($fp,'r+');
		fseek($f,$start);
		fwrite($f,file_get_contents($tfile['tmp_name']));
		
		if($start+$tfile['size']>=$finfo->filesize){
			return doUpdate('files','valid=1','id="'.$id.'"');
		}
		return fclose($f);
	}
	
	
	
	public static function getRev($d){
		$fid=intval($d->folder_id);
		$fn=$d->filename;
		
		$ser=[
			'folder_id'	=>	$fid,
			'filename'	=>	$fn,
			'valid'		=>	1
		];
		
		return getRows('files','',exArr($ser,'AND'),'Order By id DESC');
	}
}