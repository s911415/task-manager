<?
class Task{
	public static function getTask($id){
		$d=getRow('tasks','','id='.intval($id));
		if(!$d) return $d;
		$d->project=Project::getProject($d->project_id);
		$d->project_name=Project::getName($d->project_id);
		$d->report_to=Project::getResp($d->project_id);
		$d->track=self::getTrack($d->track);
		$d->allowEdit=false;
		$uid=Session::get('id');
		$myInfo=User::getUser($uid);
		$allEdit=[1,2,9];
		if(true || $d->own_id==$uid || $d->project->pm==$uid || in_array($myInfo->admin,$allEdit)){
			$d->allowEdit=true;
		}
		
		$d->fields=Project::getFields($d->project_id);
		
		foreach($d->fields as $k=>$v){
			$d->{'__cust__'.$k}=CustomField::getVal($id,$k);
		}
		
		$d->fie_original=[];
		foreach($d->fields as $k=>$v){
			$name=CustomField::getInfo($k)->name;
			$val=CustomField::getVal($id,$k);
			$d->fie_original[$name]=$val;
		}

		return $d;
	}
	
	public static function getTrack($s){
		if($s=='') return [];
		return explode(',',$s);
	}
	
	public static function getList($d){
		$wh=[];
		$wh['valid']=1;
		
		if(isset($d['pid']) && !empty($d['pid'])){
			$wh[]='project_id IN('.$d['pid'].')';
		}
		
		if(isset($d['track_all']) && $d['track_all']==1){
			$pids=[-1];
			$tmpP=getRows('project','id', ['show_in_track'=>1, 'done'=>0]);
			foreach($tmpP as $v) $pids[]=$v->id;
			
			$wh[]='project_id IN('.implode(',', $pids).')';
		}
		
		if(isset($d['resp_id'])){
			$wh['resp_id']=intval($d['resp_id']);
		}
		if(isset($d['own_id'])){
			$wh['own_id']=intval($d['own_id']);
			unset($wh['own_id']);
		}
		
		if(isset($d['track']) && $d['track']!='0' && !empty($d['track'])){
			$uid=$d['track'];
			$wh[]='(track LIKE "%,'.$uid.',%" OR track LIKE "'.$uid.',%" OR track LIKE "%,'.$uid.'" OR track LIKE "'.$uid.'")';
		}
		
		if(isset($d['priority'])) $wh[]='priority IN ('.$d['priority'].')';
		if(isset($d['deadline_time'])) $wh[]=$d['deadline_time'];
		if(isset($d['end_time'])) $wh[]=$d['end_time'];
		if(isset($d['update_time'])) $wh[]='update_time>="'.$d['update_time'].'"';
		if(isset($d['done'])) $wh['done']=$d['done']==1?'1':'0';
		if(!empty($d['search'])) $wh[]='(`title` LIKE "%'.$d['search'].'%")';
		if(!empty($d['progress'])) $wh[]=$d['progress'];
		
		$tabf=Input::get('tabf');
		if($tabf!==null){
			$wh[]=$tabf;
		}
		
		$wh=exArr($wh,'AND');
		$arr=getRows(
			'tasks',
			'',
			$wh,
			'
			Order By 
			done ASC,
			priority ASC,
			deadline_time ASC,
			progress ASC,
			evaluate ASC,
			id ASC
			'
		);
		
		foreach($arr as &$a){
			//$a=self::getTask($a->id);
			$a->project=Project::getProject($a->project_id);
			$a->project_name=Project::getName($a->project_id);
			$a->deadline_time=getTime($a->deadline_time,'day');
			$a->start_time=getTime($a->start_time,'day');
			
			$a->allowEdit=false;
			$uid=Session::get('id');
			$myInfo=User::getUser($uid);
			$allEdit=[1,2,9];
			if($d->own_id==$uid || $d->project->pm==$uid || in_array($myInfo->admin,$allEdit)){
				$d->allowEdit=true;
			}
		}
		unset($a);
		
		return $arr;
	}
	
	public static function setDone($d){
		$tid=intval($d['tid']);
		return doUpdate('tasks','done="'.intval($d['done']).'"','id="'.$tid.'"');
	}
	
	public static function delTask($d){
		$tid=intval($d['tid']);
		
		return doUpdate('tasks','valid=0','id="'.$tid.'"');
	}
	
	public static function edit($d){
		$id=intval($d['id']);
		unset($d['id']);
		
		$tel_id=Input::get('tel_id',-1);
		unset($d['tel_id']);
		
		Cookie::set('last_pid',$d['project_id']);
		Cookie::set('last_track',@$d['track']);
		
		$custField=[];
		foreach($d as $k=>$v){
			$mhs=[];
			$ma=preg_match('/^__cust__(\d+)$/is',$k,$mhs);
			if(count($mhs)==2){
				$fid=$mhs[1];
				$custField[$fid]=$v;
				
				unset($d[$k]);
			}
		}
		if($id<0){
			$id=doInsert('tasks',$d);
			
			Tel::edit([
				'id'	=>	$tel_id,
				'task_id'=>	$id
			]);
			
			if($id!==false){
				Logger::log('新增了一個工作 ID:'.$id);
			}

		}else{
			$originalData=getRow('tasks','','id="'.$id.'"');
			$uid=Session::get('id');
			$un=User::getName($uid);
			$logData=[
				"$un($uid)在\"".date('Y-m-d H:i:s')."\"更改工作編號(ID:{$id})內容:\n======\n"
			];
			$difKey=[];
			
			$keyMap=[
				//'id'			=>		'',
				//'show_id'		=>		'',
				'project_id'	=>		'專案編號',
				//'project_no'	=>		'',
				'own_id'		=>		'提出人',
				'resp_id'		=>		'負責人',
				'title'			=>		'標題',
				'description'	=>		'描述',
				//'status'		=>		'值行情況說明',
				'progress'		=>		'進度',
				'start_time'	=>		'提出日期',
				'end_time'		=>		'預計完成日期',
				'finish_time'	=>		'實際完成日期',
				'deadline_time'	=>		'期限',
				'evaluate'		=>		'執行狀態',
				'score'			=>		'等第',
				'priority'		=>		'優先順序',
				'done'			=>		'結案',
				'poster_id'		=>		'建立者',
				//'update_time'	=>		'',
				//'valid'		=>		'',
				'spent'			=>		'實際花費時間',
				'estimated'		=>		'預計花費時間',
				'track'			=>		'稽核人員'
			];
			
			foreach($keyMap as $k=>$desc){
				$od=$originalData->{$k};
				if($od!=@$d[$k]){
					$logData[]="$desc:\n從\n===\n$od\n===\n修改為{$d[$k]}\n======\n";
					$difKey[]=$k;
				}
			}
			
			if(in_array('deadline_time',$difKey)){
				$d['last_change_deadline']=Session::get('id');
			}

			$d['status']=implode("\n",$logData)."\n".$originalData->status;
			
			
			if(doUpdate('tasks',$d,'id="'.$id.'"')){
				Logger::log('修改工作('.$id.')內容:'."\n".implode("\n",$logData));
			}
		}
		
		foreach($custField as $k=>$v){
			CustomField::saveField($id,$k,$v);
		}
		
		return $id;
	}
	
	public static function setEval($d){
		$tid=intval($d['tid']);
		unset($d['tid']);
		$data=[
			'evaluate'	=>	$d['evaluate']
		];
		return doUpdate('tasks',$data,'id="'.$tid.'"');
	}
	
	public static function setScore($d){
		$tid=intval($d['tid']);
		unset($d['tid']);
		$data=[
			'score'	=>	$d['score']
		];
		return doUpdate('tasks',$data,'id="'.$tid.'"');
	}
	
	public static function setProgress($d){
		$tid=intval($d['tid']);
		unset($d['tid']);
		$data=[
			'progress'	=>	$d['progress']
		];
		return doUpdate('tasks',$data,'id="'.$tid.'"');
	}
	
	
}