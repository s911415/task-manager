<?php
class Server{
	public static function get($k,$v=null){
		$arr=[$_SERVER];
		foreach($arr as $a){
			if(isset($a[$k])) return $a[$k];
		}
		
		return $v;
	}
}