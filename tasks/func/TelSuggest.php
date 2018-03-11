<?
class TelSuggest{
	const table = 'telreport_suggest';

	public static function getList($d){
	    $wh=['1=1'];
        $ord='id ASC';
        if(!isset($d['from']) || $d['from']!='manage') $ord='feq DESC, id ASC';

        return getRows(self::table, '*', $wh, 'Order by '.$ord);
    }

    public static function edit($d){
        $id=$d['id'];unset($d['id']);
        $id*=1;
        if($id<0){
            return doInsert(self::table, $d);
        }else{
            return doUpdate(self::table, $d, 'id='.$id);
        }
    }

    public static function del($id){
        doDelete(self::table, 'id='.$id);
    }

    public static function getNew(){
        $o=new stdClass();
        $o->id=-1;
        $o->keyword='';
        $o->problem='';
        $o->handle='';
        $o->cate=0;
        $o->feq=0;

        return $o;
    }

    public static function addClick($d){
        $id=(@$d['id'])*1;
        doUpdate(self::table, 'feq=feq+1', 'id='.$id);
    }

}