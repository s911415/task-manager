<?php
class Tel{
	static function getList($wh){
		$where=[
			'valid'	=>	1
		];
		
		$chkPass=function($key) use($wh){
			return (isset($wh[$key]) && ((is_numeric($wh[$key]) && $wh[$key]>=0) || (!is_numeric($wh[$key]) && !empty($wh[$key]))));
		};
		
		if(!empty($wh['start_time']) && !empty($wh['end_time'])){
			$where[]="(DATE(incall_time) BETWEEN '{$wh['start_time']}' AND '{$wh['end_time']} 23:59:59')";
		}
		
		if($chkPass('cate_id')){
			$where[]="cate_id={$wh['cate_id']}";
		}
		
		if($chkPass('school_id')){
			$where[]="school_id={$wh['school_id']}";
		}
		
		
		if($chkPass('task_uid')){
			$where[]="task_uid={$wh['task_uid']}";
		}
		if($chkPass('finish')){
			$where[]="task_uid={$wh['task_uid']}";
		}
		if($chkPass('tea_name')){
			$where[]="tea_name LIKE '%{$wh['tea_name']}%'";
		}
		if($chkPass('tea_phone')){
			$where[]="tea_phone LIKE '%{$wh['tea_phone']}%'";
		}
		if($chkPass('sch_name')){
			$where[]="school_id IN(".implode(',',TelCity::search($wh['sch_name'])).")";
		}
		
		
		
		
		if(isset($wh['oth'])){
			$where[]='('.$wh['oth'].')';
		}
		return getRows('telreport_city_report','',$where,'Order By id DESC');
	}
	
	static function getInfo($id){
		return getRow('telreport_city_report','','id='.$id);
	}
	
	static function getNo(){
		$now=date('Y-m-d');
		$last=getRow('telreport_city_report','MAX(no) as no',[
			'DATE(incall_time) = "'.$now.'"',
			'valid'	=>	1
		]);
		$no=explode('-',$last->no)[1]?:0;
		$no++;
		if($last) return date('Ymd').'-'.str_pad($no,4,'0',STR_PAD_LEFT);
	}
	
	
	static function edit($d){
		$id=$d['id'];
		unset($d['id']);

		if($id==-1){
			$d['no']= self::getNo();
			return doInsert('telreport_city_report',$d);
		}else{
			return doUpdate('telreport_city_report',$d,[
				'id'	=>	$id
			]);
		}
	}
	
	static function del($d){
		$id=$d['id'];
		return self::edit([
			"id"	=>	$id,
			'valid'	=>	0
		]);
	}
/*
	static function export(){
        $where=[
            'valid'	=>	1
        ];

        return getRows('telreport_city_report as rep
        INNER JOIN telreport_category as cat ON rep.cate_id = cat.id
        INNER JOIN users ON users.id = rep.task_uid

        LEFT JOIN telreport_city_record as record ON rep.school_id = record.id  
        ','',$where,'Order By id DESC');


    }
*/
}