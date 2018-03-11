<?
class Tags{
	public static function findTag($name,$type='AND'){
		if(!is_array($name)){
			$name=[$name];
		}
		
		$name[]='';
		foreach(
			$name as &$n
		){
			$n=exArr([
				'name'	=>	$n
			]);
		}
		unset($n);
		
		$res=[];
		
		
		foreach(
			getRows('tags','id',implode(' '.$type.' ',$name))
			as $d
		) $res[]=$d->id;
		
		return $res;
	}
	
	public static function getProjectIdByTag($name,$type='AND'){
		$tagIds=self::findTag($name,$type);
		
		$ids=[-1];
		foreach(
			getRows('tag_list','project_id','tag_id IN('.implode(',',$tagIds).')')
			as $d
		) $ids[]=$d->project_id;
		
		return $ids;
	}
	
	public static function getTagId($name){
		$wh=[
			'name'	=>	$name
		];
		$res=getRow('tags','id',exArr($wh));
		if(!$res){
			return doInsert($wh);
		}else{
			return $res->id;
		}
	}
}