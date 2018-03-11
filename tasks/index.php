<?
include_once('conf/conf.php');

	define('APP_PATH',ROOT.'app/');

$act=getVal($_GET,'act');
$func=getVal($_GET,'func');

if(!is_string($act) || empty($act)){
	$act='welcome';
}

if(!is_string($func)) $func='';

if((strtoupper($_SERVER['REQUEST_METHOD']) === 'POST' && !isset($_GET['telPage'])) || isset($_GET['sp'])) {
	if(class_exists($act)){
		$incPath='';
		if(empty($func)){
			$incPath=APP_PATH.$act.'.php';
		}else{
			$incPath=APP_PATH.$act.'/'.$func.'.php';
		}
		include_once($incPath);
	}
}else{
	require_once(APP_PATH.'index.php');
}