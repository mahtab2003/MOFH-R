<?php 

if(!function_exists('hash_256'))
{
	function hash_256($string)
	{
		$hash = $string;
		for ($i = 0; $i < 25; $i++) {
			$hash = hash(HASH_256_BIT, $hash.':'.HASH_SALT);
		}
		return $hash;
	}
}

if(!function_exists('hash_128'))
{
	function hash_128($string)
	{
		$hash = $string;
		for ($i = 0; $i < 25; $i++) {
			$hash = hash(HASH_128_BIT, $hash.':'.HASH_SALT);
		}
		return $hash;
	}
}

if(!function_exists('hash_64'))
{
	function hash_64($string)
	{
		$hash = $string;
		for ($i = 0; $i < 25; $i++) {
			$hash = hash(HASH_64_BIT, $hash.':'.HASH_SALT);
		}
		return $hash;
	}
}

if(!function_exists('hash_32'))
{
	function hash_32($string)
	{
		$hash = $string;
		for ($i = 0; $i < 25; $i++) {
			$hash = hash(HASH_32_BIT, $hash.':'.HASH_SALT);
		}
		return $hash;
	}
}

?>