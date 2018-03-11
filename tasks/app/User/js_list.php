<?
header('Content-Type: text/javascript');
echo 'var USERS='.json_encode(User::getAllUser()).';';