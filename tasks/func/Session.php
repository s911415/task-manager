<?php
class Session{
	public static function get($k,$v=null){
		$arr=[$_SESSION];
		foreach($arr as $a){
			if(isset($a[$k])) return $a[$k];
		}
		
		return $v;
	}
	
	public static function set($k,$v){
		$_SESSION[$k]=$v;
		
		return new static;
	}
	
	public static function forget($k=null){
		if($k===null){
			session_unset();
		}else{
			unset($_SESSION[$k]);
		}
		
		return new static;
	}
}