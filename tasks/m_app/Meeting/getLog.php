<?
header('Content-Type: text/plain');
$id=intval(getVal($_GET,'id',0));
echo Meeting::getLog($id);