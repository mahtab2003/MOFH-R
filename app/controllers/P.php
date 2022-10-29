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