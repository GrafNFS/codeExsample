<?php

class Helper {

    public static function random_string($length = 32)
    {
    	$chars = array_merge(range(0,9), range('a','z'), range('A','Z'));
    	shuffle($chars);
    	$string = implode(array_slice($chars, 0, $length));
    	return $string;
    }
}
