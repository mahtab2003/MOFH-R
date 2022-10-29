<?php 

class I extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('installation');
		$uri = $_SERVER['REQUEST_URI'];
		$protocol = 'http://';
		if(isset($_SERVER['HTTPS']))
		{
			$protocol = 'https://';
		}
		$domain = $_SERVER['HTTP_HOST'];
		$base_url = str_replace('index.php/i/', '', $protocol.$domain.$uri);
		$base_url = str_replace('index.php/i', '', $base_url);
		$base_url = str_replace('index.php/', '', $base_url);
		$base_url = str_replace('welcome/', '', $base_url);
		$base_url = str_replace('step1/', '', $base_url);
		$base_url = str_replace('step2/', '', $base_url);
		$base_url = str_replace('step3/', '', $base_url);
		$base_url = str_replace('done/', '', $base_url);
		$base_url = str_replace('welcome', '', $base_url);
		$base_url = str_replace('step1', '', $base_url);
		$base_url = str_replace('step2', '', $base_url);
		$base_url = str_replace('step3', '', $base_url);
		$this->base_url = str_replace('done', '', $base_url);
	}

	function index()
	{
		if(is_installed())
		{
			redirect('n');
		}
		else
		{
			$this->welcome();
		}
	}

	function welcome()
	{
		if(is_installed())
		{
			redirect('n');
		}
		else
		{
			$this->load->view('default/install/welcome');
		}
	}

	function step1()
	{
		if(is_installed())
		{
			redirect('n');
		}
		else
		{
			if($this->input->post('submit'))
			{
				$config_file = file_get_contents(APPPATH.'install/config.php');
				$constant_file = file_get_contents(APPPATH.'install/constants.php');
				$config_file = str_replace('MODE_BASE_URL', $this->input->post('base_url'), $config_file);
				$config_file = str_replace('MODE_ENCRYPT_KEY', $this->input->post('encrypt_key'), $config_file);
				$config_file = str_replace('MODE_COOKIE_PREFIX', $this->input->post('cookie_prefix'), $config_file);
				$config_file = str_replace('MODE_CSFR_PROTECTION', $this->input->post('csrf_protection'), $config_file);
				file_put_contents(APPPATH.'config/config.php', $config_file);
				file_put_contents(APPPATH.'config/constants.php', $constant_file);
				header('location: '.$this->base_url.'index.php/i/step2');
			}
			else
			{
				$this->load->view('default/install/step1');
			}
		}
	}

	function step2()
	{
		if(is_installed())
		{
			redirect('n');
		}
		else
		{
			if($this->input->post('submit')){
				$test = mysqli_connect(
					$this->input->post('hostname'),
					$this->input->post('username'),
					$this->input->post('password'),
					$this->input->post('database')
				);
				if(!$test)
				{
					$this->session->set_flashdata('msg', json_encode([0, 'Database connection cannot be established.']));
					header('location: '.$this->base_url.'index.php/i/step2');
				}
				else
				{
					$this->load->database([
						'dbdriver' => 'mysqli',
						'hostname' => $this->input->post('hostname'),
						'username' => $this->input->post('username'),
						'password' => $this->input->post('password'),
						'database' => $this->input->post('database'),
						'dbprefix' => $this->input->post('prefix')
					]);

					$db_file = file_get_contents(APPPATH.'install/database.php');
					$db_file = str_replace('MODE_HOSTNAME', $this->input->post('hostname'), $db_file);
					$db_file = str_replace('MODE_USERNAME', $this->input->post('username'), $db_file);
					$db_file = str_replace('MODE_PASSWORD', $this->input->post('password'), $db_file);
					$db_file = str_replace('MODE_DATABASE', $this->input->post('database'), $db_file);
					$db_file = str_replace('MODE_PREFIX', $this->input->post('prefix'), $db_file);
					$sql_file = file_get_contents(APPPATH.'install/table.sql');
					$sql_file = str_replace('nx_', $this->input->post('prefix'), $sql_file);
					$sql_stat = explode('# END', $sql_file);
					for($i = 0; $i < count($sql_stat); $i++)
					{
						$query = $this->db->query($sql_stat[$i]);
					}
					if ($query)
					{
						file_put_contents(APPPATH.'config/database.php', $db_file);
						$this->session->set_flashdata('msg', json_encode([1, 'Database set-up completed successfully.']));
						header('location: '.$this->base_url.'index.php/i/step3');
					}
					else
					{
						$this->session->set_flashdata('msg', json_encode([0, 'An error occured. try again later.']));
						header('location: '.$this->base_url.'index.php/i/step2');
					}
				}
			}
			else
			{
				$this->load->view('default/install/step2');
			}
		}
	}

	function step3()
	{
		if(is_installed())
		{
			redirect('n');
		}
		else
		{
			if($this->input->post('submit'))
			{
				$this->load->database();
				$this->load->model('user');
				$this->load->library(['form_validation' => 'fv']);
				$this->fv->set_rules('name', 'Your Name', ['trim', 'required', 'valid_name']);
				$this->fv->set_rules('email', 'Email Address', ['trim', 'required', 'valid_email']);
				$this->fv->set_rules('password', 'Password', ['trim', 'required']);
				$this->fv->set_rules('confirm_password', 'Confirm Password', ['matches[password]', 'trim', 'required']);
				if($this->fv->run() === TRUE)
				{
					$res = $this->user->register(
						$this->input->post('name'),
						$this->input->post('email'),
						$this->input->post('password'),
						'root'
					);
					if($res !== false)
					{
						file_put_contents(APPPATH.'logs/install.json', json_encode(['time' => time(), 'base_url' => $this->base_url]));
						$this->session->set_flashdata('msg', json_encode([1, 'Admin account created successfully.']));
						header('location: '.$this->base_url.'index.php/i/done');
					}
					else
					{
						$this->session->set_flashdata('msg', json_encode([0, 'An error occured. try again later..']));
						header('location: '.$this->base_url.'index.php/i/step3');
					}
				}
				else
				{
					if(validation_errors() !== '')
					{
						$this->session->set_flashdata('msg', json_encode([0, validation_errors()]));
					}
					else
					{
						$this->session->set_flashdata('msg', json_encode([0, 'Please fill all required fields.']));
					}
					header('location: '.$this->base_url.'index.php/i/step3');
				}
			}
			else
			{
				$this->load->view('default/install/step3');
			}
		}
	}

	function done()
	{
		$this->load->view('default/install/done');
	}
}

?>