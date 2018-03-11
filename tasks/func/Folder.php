<?
class Folder{
	public static $saveTarget='share/';
	
	public static function getInfo($id,$withPermission=true){
		$wh=['valid'=>1];
		$wh['id']=intval($id);
		$info=getRow('folder','',exArr($wh,'AND'));
		
		if($withPermission){
			$info->permission=Permission::getMyPermission([
				'ref_id'	=>	$id,
				'type'		=>	'folder'
			]);
		}
		return $info;
	}

	public static function getFolders($d){
		$wh=[
			'valid'	=>	1
		];
		
		$fid=intval(getVal($d,'fid',0));
		if(isset($d['pid'])) $wh['project_id']=intval($d['pid']);

		$list=[
			'0'	=>	new stdClass()
		];
		
		$list[0]->childs=[];
		$list[0]->path=[''];
		$list[0]->permission=Permission::getMyPermission(['ref_id'=>0,'type'=>'folder']);
		$all=[];
		
		foreach(
			getRows('folder','id',exArr($wh,'AND'),'Order By parent_id ASC, name ASC')
			as $d
		) $all[]=self::getInfo($d->id);
		
		for($i=0,$j=count($all);$i<$j;$i++){
			$all[$i]->childs=[];
			$all[$i]->path=[$all[$i]->name];
			$list[$all[$i]->id]=&$all[$i];
		}
		
		for($i=0,$j=count($all);$i<$j;$i++){
			$pid=$all[$i]->parent_id;

			if(isset($list[$pid])){
				$all[$i]->path=array_merge($list[$pid]->path,$all[$i]->path);
				$list[$pid]->childs[]=$all[$i];
			}
		}
		return $list;
	}
	
	public static function getRealPath($fid){
		
		$fs=self::getFolders([]);
		if(isset($fs[$fid])){
			$p=$fs[$fid]->path;
			array_shift($p);
			foreach($p as &$pp) $pp=filenameFilter($pp);
			unset($pp);

			return self::$saveTarget.implode('/',$p).'/';
		}
		
		
		return File::$saveTarget;
	}
	
	public static function delete($d){
		$fid=intval($d['fid']);
		Logger::log('刪除了資料夾 ('.$fid.')');
		
		return doUpdate('folder','valid=0','id="'.$fid.'"');
	}
	
	public static function rename($d){
		$fid=intval($d['fid']);
		$info=getRow('folder','','id="'.$fid.'"');
		
		if($info->name==$d['name']){
			return '沒有任何更變';
		}
		
		$chkRep=getRow('folder','COUNT(id) as C',exArr([
			'name'	=>	$d['name'],
			'parent_id'=>	$info->parent_id,
			'valid'	=>	1
		],'AND'));
		if($chkRep->C!=0){
			return '資料夾已存在';
		}
		
		$old_folderRealPath=ROOT.self::getRealPath($fid);
		$res=doUpdate('folder',[
			'name'	=>	$d['name']
		],'id="'.$fid.'"');
		
		/*Rename folder in disk*/
		$new_folderRealPath=ROOT.self::getRealPath($fid);
		rename(
			$old_folderRealPath,
			$new_folderRealPath
		);
		/*Rename folder in disk*/
		
		return $res;
	}
	
	public static function addFolder($d){
		$sqlData=[
			'owner'	=>	$_SESSION['id'],
			'name'	=>	$d['name'],
			'parent_id'=>	$d['fid'],
			'project_id'=>	$d['pid']
		];
		
		$chkRep=getRow('folder','COUNT(id) as C',exArr([
			'name'	=>	$d['name'],
			'parent_id'=>	$d['fid'],
			'valid'	=>	1
		],'AND'));
		if($chkRep->C!=0){
			return '資料夾已存在';
		}
		
		$insId=doInsert('folder',$sqlData);
		@mkdir(ROOT.self::getRealPath($insId),0777,true);

		return $insId;
	}
}