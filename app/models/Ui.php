<?php 

class Ui extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('site');
		$this->load->helper('cookie');
		$this->load->helper('ui');
		if(!get_cookie('lang'))
		{
			set_cookie('lang', 'english', 30 * 86400);
		}
		if(!get_cookie('theme'))
		{
			set_cookie('theme', 'light', 30 * 86400);
		}
	}
	
	function text($line)
	{
		$this->lang->load('custom', get_cookie('lang'));
		$res = $this->lang->line($line);
		if($res !== false)
		{
			return $res;
		}
		return '...';
	}

	function get_langs()
	{
		return get_languages();
	}

	function get_templates()
	{
		return get_templates();
	}

	function template_dir()
	{
		return $this->site->get(['theme']);
	}
}

?>