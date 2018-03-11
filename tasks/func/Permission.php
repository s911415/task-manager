<?
/*
	P:edit permission
	R:read
	W:write
	------------------
	|     | P | R | W |
	------------------
	| 000 | X | X | X |
	| 001 | X | X | V |
	| 010 | X | V | X |
	| 011 | X | V | V |
	| 100 | V | X | X |
	| 101 | V | X | V |
	| 110 | V | V | X |
	| 111 | V | V | V |
	-------------------
*/

class Permission{
	public static $default=0x0;
	public static function getMyPermission($d){
		if(getVal($_SESSION,'admin',0)==9) return 0x7;
		$data=self::getInfo($d);
		
		$permission=getVal(
			$data,
			getVal($_SESSION,'id',-1),
			self::$default
		);
		
		return intval($permission);
	}
	
	public static function getInfo($d){
		$ref_id=intval($d['ref_id']);
		$type=$d['type'];
		
		$res=self::getPermissionR($ref_id,$type);
		
		$setUser=[];
		
		foreach($res as $r){
			$uid=intval($r->uid);
			if(isset($setUser[$uid])) continue;
			$permission=intval($r->permission);
			
			$setUser[$uid]=$permission;

			if($uid==-1){
				foreach(User::getAllUserList() as $d){
					$uid=intval($d->id);
					if(isset($setUser[$uid])) continue;
					$setUser[$uid]=$permission;
				}
			}
		}
		return $setUser;
	}
	
	public static function getPermission($d){
		$p=self::getPermissionR($d['ref_id'],$d['type']);
		$ret=[];
		$ids=[];
		foreach($p as $d){
			if(in_array($d->uid,$ids)) continue;
			$ret[]=$d;
			$ids[]=$d->uid;
		}
		
		return $ret;
	}
	
	public static function getPermissionR($ref_id,$type,$pres=[]){
		$ref_id=intval($ref_id);
		if($type!='file') $type='folder';

		$pcacheKey='PermissionCache_'.$type.'_'.$ref_id;
		$pcache=getVal($GLOBALS,$pcacheKey,null);
		if(!is_null($pcache)){
			$pres=$pcache;
			return $pres;
		}
		

		$wh=[
			'type'	=>	$type,
			'ref_id'=>	$ref_id
		];
		
		$sqlWh=exArr($wh,'AND');
		$sqlCacheKey='Permission_'.$sqlWh;
		$sqlCache=getVal(
			$GLOBALS,
			$sqlCacheKey,
			getRows('permission','',$sqlWh,'Order By id ASC')
		);
		$res=$sqlCache;
		$GLOBALS[$sqlCacheKey]=$res;
		
		if(is_array($res)) $pres=array_merge($pres,$res);

		if($ref_id!=0){
			$pid=0;
			switch($type){
				case 'file':
					$info=File::getInfo($ref_id,false);
					if($info) $pid=$info->folder_id;
				break;
				case 'folder':
					$info=Folder::getInfo($ref_id,false);
					if($info) $pid=$info->parent_id;
				break;
				default:
				break;
			}
			$pres=array_merge($pres,self::getPermissionR($pid,'folder',$pres));
		}
		
		$GLOBALS[$pcacheKey]=$pres;
		return $pres;
	}
	
	public static function setPermission($d){
		$wh=[
			'type'	=>	$d['type'],
			'ref_id'=>	$d['ref_id']
		];
		
		doDelete('permission',exArr($wh,'AND'));
		
		foreach($d['data'] as $d){
			doInsert('permission',$d);
		}
		
		return true;
	}
}