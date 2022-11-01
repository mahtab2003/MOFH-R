<?php 

if(!function_exists('is_installed'))
{
	function is_installed()
	{
		if(file_exists(APPPATH.'logs/install.json'))
		{
			return true;
		}
		return false;
	}
}

?>