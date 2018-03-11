<?php
include_once('conf/conf.php');
$act=getVal($_GET,'act');

$url='http://127.0.0.1/tasks/soapServer.php?act='.$act;
$s=new SoapServer(null,[
	'uri'		=>	$url
]);
$s->setClass($act);
$s->handle();