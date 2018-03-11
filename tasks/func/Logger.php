<?
class Logger{
	public static function Log($act,$user=null){
		if($user===null){
			$user=$_SESSION['account'];
		}
		
		doInsert('log',[
			'user'	=>	$user,
			'act'	=>	$act,
			'ip'	=>	getIP()
		]);
	}
	
	public static function getLogs($d){
		$wh=['1=1'];
		if(isset($d['user']) && !empty($d['user'])) $wh['user']=$d['user'];
		
		Logger::log('查看了紀錄檔');
		return getRows(
			'log',
			'',
			exArr($wh,'AND'),
			'Order By id DESC'
		);
	}
}