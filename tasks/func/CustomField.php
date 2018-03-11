<?
class CustomField{
	static $types=['text','number','select','checkbox','radio','date','email'];
	static $needSplit=['select','checkbox','radio'];
	static function getList(){
		$list=getRows('custom_field','','valid=1','Order By sort,id');
		
		return [];
		
		return $list;
	}
	
	static function getInfo($fid){
		$data=getRow('custom_field','','id='.$fid);
		if(in_array($data->type,self::$needSplit)){
			$data->p_val=explode('|',$data->p_val);
		}
		
		return $data;
	}
	
	static function getHTML($fid){
		$info=self::getInfo($fid);
		$args=[
			'__cust__'.$fid,
			$info->name,
			'',	//html
			$info->req=='1'
		];
		$type=$info->type;
		switch($type){
			case 'text':case 'number':case 'email':case 'date':
				$args[2]='<input type="'.$type.'" value="'.$info->def.'"/>';
			break;
			case 'select':
				$args[2]='<select class="txt" >';
				foreach(
					$info->p_val as $op
				){
					$args[2].='<option>'.$op.'</option>';
				}
				$args[2].='</select>';

			break;
			case 'checkbox':case 'radio':
				$html='';
				//$args[0]=substr($args[0],1);
				foreach(
					$info->p_val as $op
				){
					$html.="<span class='agsdh'><label><input type='$type' value='{$op}' name='{$args[0]}[]'/><span class='hwr'>{$op}</span></label></span>";
				}
				
				$args[2]='<span >'.$html.'</span>';
			break;
		}
		return call_user_func_array('Inp',$args);
	}
	
	static function saveField($tid,$fid,$val){
		$data=[
			'tid'	=>	$tid,
			'fid'	=>	$fid,
			'value'	=>	$val
		];
		
		$wh=$data;
		unset($wh['value']);

		$curData=getRow('custom_field_value','',$wh);
		
		if($curData){
			return doUpdate('custom_field_value',$data,$wh);
		}else{
			return doInsert('custom_field_value',$data);
		}
	}
	
	static function getVal($tid,$fid){
		$finfo=self::getInfo($fid);
		
		$wh=[
			'tid'	=>	$tid,
			'fid'	=>	$fid
		];
		
		$data=getRow('custom_field_value','',$wh);
		if($data){
			return $data->value;
		}
		return $finfo->def?:'';
	}
	
	
	public static function edit($d){
		$id=intval($d['id']);
		unset($d['id']);
		
		$res=false;
		
		if($id<0){
			$id=doInsert('custom_field',$d);
			if($id!==false) $res=true;
		}else{
			$res=doUpdate('custom_field',$d,'id="'.$id.'"');
		}
		
		return $res;
	}
	
}