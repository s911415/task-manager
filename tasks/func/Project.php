<?
class Project{
	static $split=",";

	
	public static function getManageList($d){
		$wh=[
			'valid'	=>	1,
			'('.exArr([
				//'owner'	=>	$_SESSION['id'],
				'pm'	=>	$_SESSION['id'],
				'9'	.'='.	$_SESSION['admin'],
				'1'	.'='.	$_SESSION['admin'],
				'id IN('.implode(',',
					self::getAllowAddTaskProject()
				).')'
			],'OR').')'
		];
		
		if(isset($d['cat']) &&  ! empty($d['cat'])){
			/*
			$pwh=[];
			
			foreach(
				explode('|',$d['cat']) as $cc
			){
				if($cc=='[未分類]'){
					$pwh[]='category=""';
				}else{
					$pwh[]='('.exArr([
						'category LIKE "%'.self::$split.$cc.'%"',
						'category LIKE "%'.$cc.self::$split.'%"',
						'category="'.$cc.'"'
					],'OR').')';		
				}
				
			}
			$wh[]='('.exArr($pwh,'AND').')';
			*/
			
			$cc=str_replace('|',self::$split,$d['cat']);
			if($d['tab']=='2'){
				$wh[]='(
					category="'.$cc.'"
				)';
			}else{
				$wh[]='(
					category LIKE "'.$cc.',%" OR
					category="'.$cc.'"
				)';
			}
			
			
		}
		
		if(isset($d['name'])) $wh[]='name LIKE "%'.$d['name'].'%"';
		
		return getRows(
			'project',
			'',
			exArr($wh,'AND'),
			'Order By done ASC,p_order ASC,id ASC'
		);
	}
	
	public static function getRelatedUid($d){
		$pid=intval($d['pid']);
		$pinfo=self::getProject($pid);

		$uid=[$pinfo->pm];
		$uid=array_merge($uid,explode(',',$pinfo->partner));
		
		return $uid;
	}
	
	public static function getAllowAddTaskProject(){
		$gid=self::getGroupPid($_SESSION['id']);
		$allow_id=[-1];
		
		foreach(
			getRows('project','id',exArr([
				'allow_add=1',
				'id IN('.implode(',',$gid).')'
			],'AND'))
			as $d
		){
			$allow_id[]=$d->id;
		}
		
		return $allow_id;
	}
	
	public static function setOrder($data){
		$flag=true;
		foreach($data as $k=>$v){
			$flag=$flag && doUpdate('Project','p_order="'.$v.'"','id="'.$k.'"');
		}
		return $flag;
	}
	
	public static function delProject($d){
		$pid=$d['pid'];
		Logger::log('刪除了專案('.$pid.')');
		return doUpdate('Project','valid=0','id="'.$pid.'"');
	}
	
	public static function getProject($id){
		if(isset($GLOBALS['projects_'.$id])){
			return $GLOBALS['projects_'.$id];
		}
		$data=getRow('project','*','id="'.$id.'"');
		if(!$data) return false;

		$data->partner=implode(',',self::getPartner($id));
		$GLOBALS['projects_'.$id]=$data;
		return $data;
	}
	
	public static function getPartner($pid){
		$arr=[];
		foreach(
			getRows('project_list','uid','project_id="'.$pid.'"','Order By uid')
			as $d
		){
			$arr[]=$d->uid;
		}
		return $arr;
	}
	
	public static function edit($d){
		$id=intval($d['id']);
		$partner=$d['partner'];
		unset($d['partner']);
		unset($d['id']);
		
		if($id<0){
			$id=doInsert('project',$d);
		}else{
			$oldPM=getRow('project','pm','id="'.$id.'"');
			$oldPM=$oldPM->pm;
			doUpdate('project',$d,'id="'.$id.'"');
			User::checkPM($oldPM);
		}
		User::checkPM($d['pm']);
		
		doDelete('project_list','project_id='.$id);
		foreach(
			explode(',',$partner)
			as $uid
		){
			doInsert('project_list',[
				'project_id'=>	$id,
				'uid'		=>	intval($uid)
			]);
		}
		
		return true;
	}
	
	public static function getName($pid){
		$r=getRow('project','name','id="'.intval($pid).'"');
		if(!$r) return null;
		return $r->name;
	}
	
	public static function getGroupPid($uid){
		$uid=intval($uid);

		$pid=[];
		foreach(
			getRows('project_list','project_id','uid="'.$uid.'"')
			as $d
		){
			$pid[]=$d->project_id;
		}
		
		return $pid;
	}
	
	public static function getNames($pids){
		$arr=[];
		$pids[]=-1;
		foreach(
			getRows('project','id,name','valid=1 AND id IN('.implode(',',$pids).')')
			as $d
		){
			$arr[$d->id]=$d->name;
		}
		
		return $arr;
	}
	
	public static function getLastNo($project_id){
		$project_id=intval($project_id);

		$maxNo=getRow('tasks','MAX(project_no) as m','project_id="'.$project_id.'"');
		$maxNo=$maxNo->m;

		return $maxNo+1;
	}
	/*
	public static function getAllCategory(){
		$mkCat=function($val){
			return [
				'value'	=>	$val,
				'child'	=>	[]
			];
		};
		$arr=[];
		foreach(
			getRows('Project','category','valid=1')
			as $d
		) $arr=array_merge($arr,explode(self::$split,$d->category));
		
		$arr=array_unique($arr);
		
		$spI=array_search("",$arr);
		if($spI!==false){
			unset($arr[$spI]);
		}
		
		$arr=array_values($arr);
		$arr[]="";
		
		return $arr;
	}
	*/
	
	public static function getAllCategory(){
		$mkCat=function($val,$deep){
			return [
				'value'	=>	$val,
				'child'	=>	[],
				'deep'	=>	$deep
			];
		};
		$arr=$mkCat('root',0);
		$maxDeep=0;
		foreach(
			getRows('Project','category','valid=1')
			as $d
		){
			$cs=explode(self::$split,$d->category);
			$deep=0;
			$parent=&$arr;
			foreach($cs as $c){
				if(empty($c)) continue;
				$parent['child'][$c]=$parent['child'][$c]?:$mkCat($c,++$deep);
				$parent=&$parent['child'][$c];
				$maxDeep=$deep>$maxDeep?$deep:$maxDeep;
			}
			unset($parent);
		}
		$arr['maxDeep']=$maxDeep;

		return $arr;
	}
	
	static function getResp($pid){
		$p=self::getProject($pid);
		if(!$p) return new stdClass;
		if($p->pm!=0) return $p->pm;
		return $p->owner;
	}
	/*
	public static function test(){
		$data=getRows('tasks','','1=1','Order By project_id,id');
		
		$no=0;
		$upd=[];
		$preP=-1;
		
		foreach($data as $d){
			if($d->project_id!=$preP) $no=0;
			$upd[$d->id]=++$no;
			$preP=$d->project_id;
		}
		
		
		foreach($upd as $k=>$u){
			doUpdate('tasks','project_no='.$u,'id='.$k);
		}
	}
	*/
	
	static function getFields($pid){
		$info=self::getProject($pid);
		
		$cf=$info->cust_field;
		
		$info->cust_field=[];
		foreach(explode(',',$cf) as $f){
			if(empty($f)) continue;
			$info->cust_field[$f]=CustomField::getHTML($f);
		}
		
		return $info->cust_field;
	}
}