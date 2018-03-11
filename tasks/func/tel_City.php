<?php
class TelCity{
	static function getList(){
		$fn='cache/cacc_'.date('Y-m-d-H');
		$f=null;//@file_get_contents($fn);
		$list=null;
		if(!$f){
			$list=getRows('telreport_city','*','1=1','Order By city,
	area_no,
	id');
		}else{
			$list=json_decode($f,true);
		}
		
		//file_put_contents($fn,json_encode($list));
		
		return $list;
	}
	
	static function edit($d){
		$id=$d['id'];
		unset($d['id']);

		if($id===-1){
			return doInsert('telreport_city',$d);
		}else{
			return doUpdate('telreport_city',$d,[
				'id'	=>	$id
			]);
		}
	}
	
	
	static function search($name){
		$ids=[-1];
		foreach(
			getRows('telreport_city','id','schoolname LIKE "%'.$name.'%"') as $d
		) $ids[]=$d->id;
		return $ids;
	}
	
}