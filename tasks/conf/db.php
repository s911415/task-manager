<?php
$db=new mysqli($DB['hostname'],$DB['username'],$DB['password'],$DB['database']);
function cmd($sql){
	global $db;
	
	$r=$db->query($sql);
    //var_dump(($sql));
	if(!$r){
	    $msg="\n".str_repeat('-',20)."\nSQL:$sql\n---\nError:{$db->error}\n";
        //var_dump(($msg));
		file_put_contents(dirname(__FILE__).'/err.txt',$msg,8);
	}
	return $r;
}

function exArr($arr,$join=','){
	$d=[];
	foreach($arr as $k=>$v){
		if(is_int($k)){
			$d[]=$v;
		}else{
			if(strpos($k, '`')===false){
				$d[]="`$k`='$v'";
			}else{
				$d[]="$k='$v'";
			}
		}
		
	}
	return implode (' '.$join.' ',$d);
}

function getRow($table,$field="*",$wh="1",$ot=""){
	if(empty(trim($field))) $field='*';
	if(is_array($wh)) $wh=exArr($wh,'AND');

	$sql="SELECT $field From $table Where $wh $ot";
	$r=cmd($sql);
	
	if($r){
		return $r->fetch_object();
	}else{
		return [];
	}
}
function getRows($table,$field="*",$wh="1",$ot=""){
	if(empty(trim($field))) $field='*';
	if(is_array($wh)) $wh=exArr($wh,'AND');

	$sql="SELECT $field From $table Where $wh $ot";
	$r=cmd($sql);
	$arr=[];
	
	while($r && $d=$r->fetch_object()) $arr[]=$d;
	
	return $arr;
}

function doInsert($table,$data){
	if(is_array($data)) $data=exArr($data);
	$sql="Insert Into $table Set $data";
	
	$r=cmd($sql);
	if($r){
		global $db;
		return $db->insert_id;
	}else{
		return false;
	}
}

function doUpdate($table,$data,$wh="0"){
	if(is_array($data)) $data=exArr($data);
	if(is_array($wh)) $wh=exArr($wh,'AND');
	
	$sql="Update $table Set $data Where $wh";
    //var_dump($sql);
	return cmd($sql);
}

function doDelete($table,$wh){
	if(is_array($wh)) $wh=exArr($wh,'AND');

	$sql="Delete From $table Where $wh";
	return cmd($sql);
}


function sqlI(&$a){
	if(is_array($a)){
		foreach($a as &$b) sqlI($b);
		unset($b);
	}else{
		global $db;
		$a=$db->escape_string($a);
	}
}

cmd("SET NAMES UTF8");
date_default_timezone_set("Asia/Taipei");
isset($_GET) && sqlI($_GET);
isset($_POST) && sqlI($_POST);