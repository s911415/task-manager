<?
class Track{
	static function getTasks($d){
		$wh=$d;
		$wh['valid']=1;
		
		$arr=[];
		foreach(
			getRows('track_list','',$wh,'Order By id DESC')
			as $data
		){
			$arr[]=Task::getTask($data->tid);
		}
		
		return $arr;
	}
	
	static function getTrackList($d){
		$arr=[];
		
		$tid=$d['tid'];
		
		$wh=['valid'=>1];
		
		if(isset($d['tid'])) $wh['tid']=$d['tid'];
		if(isset($d['uid'])) $wh['uid']=$d['uid'];
		
		foreach(
			getRows('track_list','',$wh,'Order By id')
			as $b
		){
			$arr[]=$b->uid;
		}
		
		return $arr;
	}
	
	static function setTrackList($d){
		$tid=$d['tid'];
		$uids=explode(',',$d['uids']);
		
		doDelete('track_list',['tid'=>$tid]);
		
		foreach($uids as $uid){
			doInsert('track_list',[
				'tid'	=>	$tid,
				'uid'	=>	$uid
			]);
		}
		
		return true;
	}
	
}