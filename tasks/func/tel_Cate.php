<?php
class TelCate{
	static function getList(){
		return getRows('telreport_category','*','1=1','Order By id');
	}
	
	static function edit($d){
		$id=$d['id'];
		unset($d['id']);

		if($id===-1){
			return doInsert('telreport_category',$d);
		}else{
			return doUpdate('telreport_category',$d,[
				'id'	=>	$id
			]);
		}
	}
	
	
	
	
}