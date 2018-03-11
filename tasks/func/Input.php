<?php
class Input{
	public static function get($k,$v=null){
		$arr=[$_POST,$_GET];
		foreach($arr as $a){
			if(isset($a[$k])) return $a[$k];
		}
		
		return $v;
	}
}