<?php 

class P extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('installation');
		if(is_installed() === false)
		{
			redirect('i');
		}
		$this->load->library('session');
		$this->load->model('site');
		$this->load->model('ui');
	}

	function index()
	{
		$this->error_404();
	}

	function error_404()
	{
		$data['title'] = 'error_404_title';

		$this->load->view($this->ui->template_dir().'/includes/header', $data);
		$this->load->view($this->ui->template_dir().'/error_404');
		$this->load->view($this->ui->template_dir().'/includes/footer');
	}

	function error_503()
	{
		$data['title'] = 'error_503_title';

		$this->load->view($this->ui->template_dir().'/includes/header', $data);
		$this->load->view($this->ui->template_dir().'/error_503');
		$this->load->view($this->ui->template_dir().'/includes/footer');
	}

	function error_501()
	{
		$data['title'] = 'error_501_title';

		$this->load->view($this->ui->template_dir().'/includes/header', $data);
		$this->load->view($this->ui->template_dir().'/error_501');
		$this->load->view($this->ui->template_dir().'/includes/footer');
	}

	function about()
	{
		$data['title'] = 'about_title';

		$this->load->view($this->ui->template_dir().'/includes/header', $data);
		$this->load->view($this->ui->template_dir().'/about');
		$this->load->view($this->ui->template_dir().'/includes/footer');
	}

	function update()
	{
		$this->load->model('user');
		if($this->user->is_logged() AND get_cookie('role') === 'root')
		{
			if(is_updated() === false)
			{
				$json = @file_get_contents(NX_REPO.'v.'.is_updated(true).'.json');
				$latest = json_decode($json, true);
				if($this->input->get('update'))
				{
					if($latest['files'] === "true")
					{
						$json = @file_get_contents(NX_REPO.'v.'.is_updated(true).'/files.json');
						$list = json_decode($json, true);
						foreach ($list as $key => $value)
						{ 
							file_put_contents(APPPATH.'../'.$key, base64_decode($value));
						}
					}
					if($latest['db'] === "true")
					{
						$this->load->database();
						$json = @file_get_contents(NX_REPO.'v.'.is_updated(true).'/db.json');
						$list = json_decode($json, true);
						foreach ($list as $value)
						{
							$this->db->query(str_replace('nx_', $this->db->dbprefix, $value));
						}
					}
					$data = file_get_contents(APPPATH.'config/constants.php');
					$data = str_replace(get_info('version'), is_updated(true), $data);
					file_put_contents(APPPATH.'config/constants.php', $data);
					redirect('p/about');
				}
				else
				{
					$json = @file_get_contents(NX_REPO.'v.'.is_updated(true).'/list.json');
					$list = json_decode($json, true);
					$data['title'] = 'update_title';
					$data['list'] = $list;

					$this->load->view($this->ui->template_dir().'/includes/header', $data);
					$this->load->view($this->ui->template_dir().'/update');
					$this->load->view($this->ui->template_dir().'/includes/footer');
				}
			}
			else
			{
				redirect('p/about');
			}
		}
		else
		{
			redirect('p/error_503');
		}
	}

	function error_401()
	{
		$this->load->model('user');
		$this->load->model('mailer');
		if($this->user->is_logged() AND $this->user->logged_data(['status']) === 'inactive')
		{
			if($this->input->get('resend'))
			{
				$res = $this->user->logged_data(['name', 'email', 'rec']);
				$this->mailer->send('new_user', $res['email'], [
					'user_name' => $res['name'] ,
					'user_email' => $res['email'] ,
					'activation_url' => base_url('f/activate_user/'.$res['rec'])
				]);
				$this->session->set_flashdata('msg', json_encode([1, $this->ui->text('resend_email_updated_text')]));
				redirect('p/error_401');
			}
			else
			{
				$data['title'] = 'error_401_title';

				$this->load->view($this->ui->template_dir().'/includes/header', $data);
				$this->load->view($this->ui->template_dir().'/error_401');
				$this->load->view($this->ui->template_dir().'/includes/footer');
				http_response_code(401);
			}
		}
		else
		{
			redirect('f/login');
		}
	}
}

?>