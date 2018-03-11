<?
class Meeting{
	public static $fileP='files/meetLog/';

	public static function getList(){
		$wh=[
			'valid'	=>	1,
			'holder'=>	$_SESSION['id']
		];
		
		return getRows('meeting','',exArr($wh,'AND'),'Order By start_time DESC,id DESC');
	}
	
	public static function edit($d){
		$id=intval($d['id']);
		unset($d['id']);
		
		$comment=$d['commentLog'];
		unset($d['commentLog']);
		
		if($id==-1){
			$id=doInsert('meeting',$d);
		}else{
			doUpdate('meeting',$d);
		}
		
		$realP=ROOT.self::$fileP;
		$filePath=getTime($d['start_time'],'NOS').'/'.$_SESSION['id'].'/';
		
		@mkdir($realP.$filePath,0777,true);
		
		$filePath.=$id.'_'.getTime($d['start_time'],'NOSWT').'.txt';
		file_put_contents($realP.$filePath,$comment);
		doUpdate('meeting',[
			'comment'	=>	$filePath
		],'id="'.$id.'"');
		
		return $id;
	}
	
	public static function getMeeting($id){
		$id=intval($id);

		$info=getRow('meeting','','valid=1 AND id="'.$id.'"');
		$info->commentLog=self::getLog($id);
		
		return $info;
	}
	
	public static function getLog($data){
		$path='';
		if(is_resource($data) && property_exists($data,'comment')){
			$path=$data->comment;
		}else{
			$info=getRow('meeting','comment',exArr([
				'valid'	=>	1,
				'id'	=>	intval($data)
			],'AND'));

			$path=$info->comment;
		}
		
		return file_get_contents(ROOT.self::$fileP.$path);
	}
	
	public static function delMeeting($d){
		$id=intval($d['id']);
		return doUpdate('meeting','valid=0','id="'.$id.'"');
	}
	
}