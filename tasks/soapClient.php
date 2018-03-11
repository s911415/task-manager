<?php
include_once('conf/conf.php');
header('Content-Type: text/json');

$host=getVal($_GET,'host');
$act=getVal($_GET,'act');
$func=getVal($_GET,'func');

$url='http://'.$host.'/tasks/soapServer.php?act='.$act;
$c=new SoapClient(null,[
	'location'	=>	$url,
	'uri'		=>	$url
]);

$res=$c->$func($_POST);

echo json_encode($res);
