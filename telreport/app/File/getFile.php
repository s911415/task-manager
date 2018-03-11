<?
$id=getVal($_GET,'id',-1);


$id=intval($id);
$info=File::getInfo($id);
if(!$info) die();

$reqFN=mb_basename(parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH));

if($reqFN=='index.php'){
	header('Location: upload/'.$id.'/'.getURLFileName($info->filename));
	die;
}


/*getFile*/
set_time_limit(0);
while (ob_get_level()) ob_end_clean();

header('Content-Type: '.getMime($info->filename));
//header('Content-Length: '.$info->filesize);
readfile($info->fullpath);
exit;