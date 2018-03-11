<?
include_once('conf/conf.php');
header('Content-Type: text/json');
$act=getVal($_GET,'act');
$func=getVal($_GET,'func');

$res=null;

if(class_exists($act) && method_exists($act,$func)){
	$res=$act::$func($_POST);
}
echo json_encode($res);