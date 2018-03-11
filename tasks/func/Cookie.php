<?php
class Cookie{
	public static function get($k,$v=null){
		$arr=[$_COOKIE];
		foreach($arr as $a){
			if(isset($a[$k])) return $a[$k];
		}
		
		return $v;
	}
	
	public static function set($k,$v){
		setcookie($k,$v);
		$_COOKIE[$k]=$v;
		
		return new static;
	}
	
	public static function forget($k){
		setcookie($k,null,time()-20);
		unset($_COOKIE[$k]);
		
		return new static;
	}
}