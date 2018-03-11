<?
class ProjectList{
	public static function getMyProject(){
		$pid=[-1];
		foreach(
			getRows('project_list','','uid='.$_SESSION['id'])
			as $d
		) $pid[]=$d->project_id;
		
		return $pid;
	}
	
}