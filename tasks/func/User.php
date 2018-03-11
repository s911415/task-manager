<?
class User{
	public static function login($d){
		$d=getVal($d,'account|password');
		$d['valid']=1;

		$r=getRow('users','',exArr($d,'AND'));
		
		
		if($r){
			$_SESSION['id']=$r->id;
			$_SESSION['account']=$r->account;
			$_SESSION['name']=$r->name;
			$_SESSION['admin']=$r->admin;
			$_SESSION['last_login']=$r->last_login;
			$_SESSION['password']=$r->password;
			$_SESSION['email']=$r->email;
			$_SESSION['showTrackAll']=$r->showTrackAll;
			$_SESSION['telManager']=$r->telManager;
			self::setLastLoginDate();
			Logger::log('登入');

			return true;
		}else{
			Logger::log('登入失敗',$d['account']);
			return false;
		}
	}
	
	public static function logout(){
		Logger::log('登出');
		session_destroy();
		return true;
	}
	
	public static function setLastLoginDate(){
		$date=getTime();
		return doUpdate('users','last_login="'.$date.'"','id='.$_SESSION['id']);
	}
	
	public static function getMenu(){
		$admin=getVal($_SESSION,'admin',null);
		if(is_null($admin)) return [];
		
		$res=[];
		$res['User/back']='工作管理系統';
		switch(intval($admin)){
			//Super Admin
			case 9:
				$res['Task/List']='我的工作';
				$res['Project/manage']='專案管理';
				$res['User/Manage']='帳戶管理';
				//$res['User/Log']='操作紀錄';
				//$res['Field/manage']='自訂欄位';
			break;
			//Admin
			case 2:
				$res['User/Manage']='帳戶管理';
				$res['User/Log']='操作紀錄';
			break;
			//Holder
			case 1:
				$res['Task/List']='我的工作';
				$res['Task/List2']='進度追蹤';
				$res['Task/List3']='稽核工作';
				$res['Project/manage']='專案管理';
				$res['Meeting/List']='會議紀錄';
			break;
			//PM
			case 4:
				$res['Task/List']='我的工作';
				$res['Task/List2']='進度追蹤';
				$res['Task/List3']='稽核工作';
				$res['Project/manage']='專案管理';
				//$res['Task/Track']='追蹤工作清單';
			break;
			default:
				$res['Task/List']='我的工作';
				$res['Task/List3']='稽核工作';
				$res['Project/List']='專案清單';
				//$res['Task/Track']='追蹤工作清單';
			break;
		}

		$res['Memo/index']='個人備忘錄';
		$res['Tel/index']='客服回報';
		$res['File/folder']='檔案分享';
		$res['File/folder_mob']='檔案分享';
		if(Session::get('admin')==9 || Session::get('showTrackAll')==1){
			$res['Task/trackAll']='追蹤所有人工作';
		}
		$res['User/logout']='登出系統';
		$res['javascript:updateInfo()']='修改帳戶';
		return $res;
	}
	
	public static function getAllUser(){
		$id=[];
		foreach(self::getAllUserList() as $d) $id[]=$d->id;
		
		return self::getName($id);
	}
	
	public static function getName($id){
		$data=[];
		if(is_array($id)){
			$id[]=-1;
		}else{
			$id=[$id];
		}
		foreach(
			getRows('users','id,name','id IN('.implode(',',$id).')')
			as $d
		){
			$data[$d->id]=$d->name;
		}
		if(count($id)==1){
			foreach($data as $d) return $d;
		}
		return $data;
	}
	
	public static function getAllUserList($wh=[]){
		static $cache=null;
		$wh[]='valid=1';
		if(is_null($cache)) $cache=getRows('users','',$wh,'Order By trackSort ASC,admin DESC,id ASC');
		
		return $cache;
	}
	
	public static function delete($d){
		$uid=intval($d['uid']);
		Logger::log('刪除了使用者 UID:'.$uid);
		return doUpdate('users','valid=0','id="'.$uid.'"');
	}
	
	public static function checkPM($uid){
		$uid=intval($uid);
		$uinfo=getRow('users','','id="'.$uid.'"');
		$ckPM=[0,4];
		
		if(!in_array($uinfo->admin,$ckPM)) return;
		$wh=[
			'valid'	=>	1,
			'pm'	=>	$uid,
			'done'	=>	0
		];
		$pmCount=getRow('project','COUNT(id) as C',exArr($wh,'AND'));
		
		$newAdmin=0;
		if($pmCount->C>0) $newAdmin=4;
		
		if($newAdmin==$uinfo->admin) return;
		
		doUpdate('users',[
			'admin'	=>	$newAdmin
		],'id="'.$uid.'"');
	}
	
	public static function getUser($uid){
		$uid=intval($uid);
		return getRow('users','','id="'.$uid.'"');
	}
	
	public static function edit($d){
		$id=intval($d['id']);
		unset($d['id']);
		
		$res=false;
		
		if($id<0){
			$id=doInsert('users',$d);
			Logger::log('新增了使用者 UID:'.$id);
			if($id!==false) $res=true;
		}else{
			Logger::log('更改了使用者資訊 UID:'.$id);
			$res=doUpdate('users',$d,'id="'.$id.'"');
		}
		
		return $res;
	}
	
	public static function getNotice(){
		if(isset($_SESSION['noticed'])) return;
		$last_login=$_SESSION['last_login'];
		$gid=Project::getGroupPid($_SESSION['id']);
		$gid[]=-1;
		$baseSend=[
			'pid'	=>	implode(',',$gid),
			'resp_id'=>	$_SESSION['id'],
			'update_time'=>	$_SESSION['last_login']
		];
		
		$taskData=Task::getList($baseSend);
		$data=[
			'modTaskCount'	=>	count($taskData)
		];
		
		
		$_SESSION['noticed']=true;
		
		return $data;
	}
	
	public static function forget($d){
		$email=$d['email'];
		$d=getRow('users','','email="'.$email.'"');
		if($d){
			return sentGmail($d->email,"工作管理系統密碼","
				<h1>工作管理系統</h1>
				<h2>帳號及密碼</h2>
				<p>親愛的{$d->name}您好，您剛剛申請忘記密碼的服務，您的帳號及密碼如下:</p>
				<p>帳號:{$d->account}</p>
				<p>密碼:{$d->password}</p>
			");
		}else{
			return false;
		}
		sentGmail($email);
		if(true){//sent
		
			return true;
		}else{
			return false;
		}
		
	}
	
	public static function updateInfo($d){
		$wh=[];
		$wh['valid']=1;
		$wh['id']=Session::get('id');
		$data=[];
		if(!isset($d['oldpwd']) || $d['oldpwd']!=Session::get('password')) return "舊密碼錯誤";
		$bindData=function($key) use ($d, &$data){
			if(!empty($d[$key])) $data[$key]=$d[$key];
		};
		
		
		
		$bindData('password');
		$bindData('email');
		
		if(count($data)>0){
			return doUpdate('users', $data, $wh);
		}else{
			return "NNN";
		}
	}
}