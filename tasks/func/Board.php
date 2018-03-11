<?
class Board{
	public static function getBoard($d){
		$tid=intval($d['tid']);
		
		$wh=[];
		$wh['valid']=1;
		$wh['task_id']=$tid;
		
		$arr=getRows(
			'board',
			'',
			exArr($wh,'AND'),
			'Order by id DESC'
		);
		
		foreach($arr as &$d){
			$d->post_time=getTime(strtotime($d->post_time),'US');
		}
		unset($d);
		
		return $arr;
	}
	
	public static function postBoard($d){
		$tid=intval($d['tid']);
		$d['msg']=trim($d['msg']);
		$d=[
			'task_id'	=>	$tid,
			'msg'		=>	$d['msg'],
			'uid'		=>	$_SESSION['id']
		];
		
		doUpdate('tasks','update_time=CURRENT_TIME','id="'.$tid.'"');
		
		return doInsert('board',$d);
	}
}