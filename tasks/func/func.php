<?

set_time_limit(0);
$menu=[];
function getVal($arr,$k,$def=''){
	$res=[];
	$key=explode('|',$k);
	foreach($key as $k){
		if(isset($arr[$k])){
			$res[$k]=$arr[$k];
		}else{
			 $res[$k]=$def;
		}
	}
	
	if(count($key)==1){
		foreach($res as $v){
			return $v;
		}
	}else{
		return $res;
	}
}

function e($s){
	return htmlspecialchars($s,ENT_QUOTES);
}

function Inp($name,$lbName='',$html='',$req=true){
	if(empty($html)) $html='<input type="hidden" value="'.$name.'" name="'.$lbName.'"/>';
	$h=explode(' ',$html);
	$h[0].=" id='$name' name='$name'";
	if($req) $h[0].=" required";
	$html=implode(' ',$h);
	
	
	$out='<div id="row_'.$name.'" class="row'.($req?' required':'').'">';
	if(mb_strpos($lbName,'\\')===0){
		$lbName=mb_substr($lbName,1);
		$out.='
			<span class="left">'.$html.'</span>
			<label class="left" style="margin-left:.5em" for="'.e($name).'">'.$lbName.'</label>
			<div class="clear"></div>
		';
	}elseif(mb_strpos($lbName,'|')===0){
		$lbName=mb_substr($lbName,1);
		$out.="<div class='flex' style='line-height:2'>";
		$out.='<label for="'.e($name).'" style="line-height:1.75rem">'.$lbName.'</label>';
		$out.='<span class="fffa">'.$html.'</span>';
		$out.='</div>';
	}else{
		if(strlen($lbName)>0){
			$out.='<label for="'.e($name).'">'.$lbName.'</label>';
		}
		$out.=$html;
	}
	
	$out.='</div>';
	
	return $out;
}

function getIP(){
	return $_SERVER['REMOTE_ADDR'];
}

function mkLink($act,$func='',$ot=[]){
	$url='./index.php?act='.urlencode($act);
	if(!empty($func)) $url.='&func='.urlencode($func);

	foreach($ot as $k=>$v){
		$url.='&'.urlencode($k).'='.urlencode($v);
	}
	
	if($func===null) return $act;
	return $url;
}

function mkMenu($menu,$cbf=false){
	$html='<ul '.(!$cbf?'class="first"':'').'>';
	foreach($menu as $k=>$v){
		if(!is_array($v)){
			$d=explode('/',$k);
			$uuiid='m'.md5($k);
			$cur=Cookie::get('lastClick','m8eb290512397f663d2d56960d1b84a25');
			$html.='<li href="'.mkLink($d[0],$d[1]?:null).'" id="'.$uuiid.'" '.($cur==$uuiid?'cur':'').'><a onclick="document.cookie=\'lastClick='.$uuiid.'\'" href="'.mkLink($d[0],$d[1]).'">'.e($v).'</a></li>';
		}else{
			$html.='<li id="'.$uuiid.'" class="tit child">'.e($k).mkMenu($v,true).'</li>';
		}
	}
	$html.='</ul>';
	return $html;
}

function getTime($time=null,$type=null){
	if(is_null($time)){
		$time=time();
	}
	if(!is_numeric($time)){
		$time=strtotime($time);
	}
	$style='';
	$type=strtoupper($type);
	switch($type){
		case 'US':
			$style='D, d F Y @ H:i';
		break;
		case 'DAY':
			$style='Y/m/d';
		break;
		case 'NOSWT':
			$style='YmdHis';
		break;
		case 'NOS':
			$style='Ymd';
		break;
		case 'HTML':
			$style='Y-m-d\TH:i';
		break;
		default:
			$style='Y-m-d H:i:s';
		break;
	}
	return date($style,$time);
}

function getMime($filename){
	static $mime=[];
	if(count($mime)==0){
		foreach(explode("\n",file_get_contents(ROOT.'/conf/mime.types')) as $line){
			$line=trim($line);
			if(strpos($line,'#')!==false) continue;
			$info=preg_split('/\s+/',$line);
			$type=$info[0];

			for($i=count($info)-1;$i>0;$i--){
				$mime[$info[$i]]=$type;
			}
		}
		unset($line);
	}
	
	$ex=pathinfo($filename, PATHINFO_EXTENSION);
	return getVal($mime,$ex,'application/octet-stream');

}

function isAdmin(){
	$admin=[2,9];
	$ad=getVal($_SESSION,'admin',0);
	
	return in_array($ad,$admin);
}

function getURLFileName($str){
	$str=rawurlencode($str);
	return $str;
}

function isMobile(){	
	$useragent=$_SERVER['HTTP_USER_AGENT'];
	return preg_match('/(android|ios)/i',$useragent);

}

function mb_basename($s){
	$e=explode('/',$s);
	return end($e);
}

function filenameFilter($str){
	return iconv('UTF-8','BIG5',$str);
}

function loc($path=''){
	header('Location: ./'.$path);
	die;
}


function chkLogin(){
	if(Session::get('id')===null){
		$allow=[
			'User@login',
			'User@logout',
			'User@forget',
		];
		$act=Input::get('act').'@'.Input::get('func');
		if(!in_array($act,$allow,true)){
			loc(mkLink('User','login'));
		}
	}
}

function sentGmail($to,$sub,$msg){
	$mail = new PHPMailer;

	//Tell PHPMailer to use SMTP
	$mail->isSMTP();

	//Enable SMTP debugging
	// 0 = off (for production use)
	// 1 = client messages 
	// 2 = client and server messages
	$mail->SMTPDebug = 0;
	$mail->Debugoutput = 'html';

	//Set the hostname of the mail server
	$mail->Host = 'smtp.gmail.com';

	//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
	$mail->Port = 587;

	//Set the encryption system to use - ssl (deprecated) or tls
	$mail->SMTPSecure = 'tls';

	//Whether to use SMTP authentication
	$mail->SMTPAuth = true;

	//Username to use for SMTP authentication - use full email address for gmail
	$mail->Username = "username@gmail.com";

	//Password to use for SMTP authentication
	$mail->Password = "yourpassword";

	//Set who the message is to be sent from
	$mail->setFrom($mail->Username, 'First Last');


	//Set who the message is to be sent to
	$mail->addAddress($to);

	//Set the subject line
	$mail->Subject = $sub;

	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	$mail->msgHTML($msg);

	//Replace the plain text body with one created manually
	$mail->AltBody = $msg;

	return $mail->send();
	
	//send the message, check for errors
	if (!$mail->send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		echo "Message sent!";
	}

}

function u_dirname($file){
	$file=str_replace('\\','/',$file);
	$file=explode('/',$file);
	array_pop($file);
	
	return implode('/',$file);
}