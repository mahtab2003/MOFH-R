<?php 

if(!function_exists('get_languages'))
{
	function get_languages()
	{
		$dirs = scandir(APPPATH.'language');
		$langs = [];
		foreach ($dirs as $lang) {
			if($lang !== '.' AND $lang !== '..' AND $lang !== 'index.html')
			{
				$langs[] = ['name' => str_replace('-', ' ', ucfirst($lang)), 'code' => $lang];
			}
		}
		return $langs;
	}
}

if(!function_exists('get_templates'))
{
	function get_templates()
	{
		$dirs = scandir(APPPATH.'../public/');
		$templates = [];
		foreach ($dirs as $dir) {
			if($dir !== '.' AND $dir !== '..' AND $dir !== 'index.html')
			{
				$templates[] = ['name' => str_replace('-', ' ', ucfirst($dir)), 'dir' => $dir];
			}
		}
		return $templates;
	}
}

if(!function_exists('get_info'))
{
	function get_info($data = '')
	{
		if ($data == 'title') :
			return NX_TITLE;
		elseif ($data == 'version') :
			return NX_VERSION;
		elseif ($data == 'build') :
			return NX_BUILD;
		else:
			return '...';
		endif;
	}
}

if(!function_exists('is_updated'))
{
	function is_updated($version = false)
	{
		$latest = file_get_contents(APPPATH.'logs/update.json');
		$data = json_decode($latest, true);
		if(floatval($data['version']) > floatval(get_info('version')))
		{
			if ($version !== false)
			{
				return $data['version'];
			}
			return false;
		}
		if ($version !== false)
		{
			return get_info('version');
		}
		return true;
	}
}

?>