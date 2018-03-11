<?php
class Memo{
	public static function getMyMemo($d){
	    $wh=[
	        'valid' =>  1,
            'uid'   =>  Session::get('id')
        ];
        if(isset($d['search'])){
            $wh[]='memo LIKE "%'.$d['search'].'%"';
        }
        if(isset($d['archive']) && $d['archive']==0){
            $wh['archive']='0';
        }

        return getRows('memo', '*', $wh, 'Order By id DESC');
    }

    public static function addMemo($d){
        return doInsert('memo', [
            'uid'   =>  Session::get('id'),
            'menu'  =>  $d['memo']
        ]);
    }

    public static function archiveMemo($d){
        return doUpdate('memo', 'archive=1', [
            'uid'   =>  Session::get('id'),
            'id'    =>  $d['id']
        ]);
    }

    public static function editMemo($d){
        return doUpdate('memo', [
            'memo'  =>  $d['memo']
        ], [
            'id'    =>  $d['id'],
            'uid'   =>  Session::get('id')
        ]);
    }

    public static function delMemo($d){
        return doUpdate('memo', [
            'valid' =>  0
        ], [
            'uid'   =>  Session::get('id'),
            'id'    =>  $d['id']
        ]);
    }
}