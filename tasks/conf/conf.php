<?
define('ROOT',str_replace('\\','/',realpath(dirname(__FILE__).'/..')).'/');
ob_start();
error_reporting(0);
//error_reporting(E_ALL & ~E_DEPRECATED);
session_start();

$DB=[
	'hostname'	=>	'127.0.0.1',
	'username'	=>	'root',
	'password'	=>	'hereislab',
	'database'	=>	'tasks'
];

include_once(ROOT.'conf/db.php');
foreach(glob(ROOT.'func/*.php') as $p){
	include_once($p);
}

$scores=explode('|','未平等|優|良|甲|乙|丙');
