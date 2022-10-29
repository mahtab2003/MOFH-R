<?php 

class S extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('installation');
		if(is_installed() === false)
		{
			redirect('i');
		}
		$this->load->library(['form_validation' => 'fv']);
		$this->load->library('session');
		$this->load->model('site');
		$this->load->model('user');
		$this->load->model('ui');
		$this->load->model('ssl');
		$this->load->model('captcha');
		if($this->user->is_logged())
		{
			if($this->user->logged_data(['status']) !== 'active')
			{
				redirect('p/error_501');
			}
			if($this->user->logged_data(['2fa_status']) === 'active' AND get_cookie('2fa') !== 'OK')
			{
				redirect('f/fa');
			}
		}
	}

	function index()
	{
		$this->ssl();
	}

	function all_ssl()
	{
		if($this->user->is_logged())
		{
			if(get_cookie('role') == 'root' OR get_cookie('role') == 'admin')
			{
				$data['title'] = 'all_ssl_title';
				$data['list'] = $this->ssl->get();
				
				$this->load->view($this->ui->template_dir().'/includes/header', $data);
				$this->load->view($this->ui->template_dir().'/includes/navbar');
				$this->load->view($this->ui->template_dir().'/includes/sidebar');
				$this->load->view($this->ui->template_dir().'/all_ssl');
				$this->load->view($this->ui->template_dir().'/includes/footer');
			}
			else
			{
				redirect('p/error_503');
			}
		}
		else
		{
			$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('login_to_continue_text')]));
			redirect('f/login');
		}
	}

	function ssl()
	{
		if($this->user->is_logged())
		{
			$data['title'] = 'ssl_title';
			$data['list'] = $this->ssl->get([], ['for' => $this->user->logged_data(['key'])]);
				
			$this->load->view($this->ui->template_dir().'/includes/header', $data);
			$this->load->view($this->ui->template_dir().'/includes/navbar');
			$this->load->view($this->ui->template_dir().'/includes/sidebar');
			$this->load->view($this->ui->template_dir().'/ssl');
			$this->load->view($this->ui->template_dir().'/includes/footer');
		}
		else
		{
			redirect('f/login');
		}
	}

	function create_ssl()
	{
		if($this->user->is_logged())
		{
			if($this->input->post('submit'))
			{
				$this->fv->set_rules('domain', $this->ui->text('domain_text'), ['trim', 'required']);
				$this->fv->set_rules('provider', $this->ui->text('provider_text'), ['trim', 'required']);
				if($this->captcha->verify())
				{
					if($this->fv->run() === true)
					{
						$res = $this->ssl->create($this->input->post('provider'), $this->input->post('domain'));
						$emails = $this->user->get(['name', 'email'], ['key' => $this->user->logged_data(['email'])], [['role' => 'support'], ['role' => 'admin'], ['role' => 'root']]);
						foreach ($emails as $value)
						{
							$this->mailer->send('new_ssl', $value['email'], [
								'ssl_id' => $key,
								'ssl_url' => base_url('s/ssl'),
								'user_name' => $value['name']
							]);
						}
						if(!is_bool($res))
						{
							$this->session->set_flashdata('msg', json_encode([0, $res]));
							redirect('s/ssl');
						}
						elseif(is_bool($res) AND $res !== false)
						{
							$this->session->set_flashdata('msg', json_encode([1, $this->ui->text('ssl_requested_text')]));
							redirect('s/ssl');
						}
						else
						{
							$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('error_occured_text')]));
							redirect('s/create_ssl');
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
							$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('required_fields_text')]));
						}
						redirect('s/create_ssl');
					}
				}
				else
				{
					$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('invalid_captcha_response_text')]));
					redirect('s/create_ssl');
				}
			}
			else
			{
				$data['title'] = 'create_ssl_title';
					
				$this->load->view($this->ui->template_dir().'/includes/header', $data);
				$this->load->view($this->ui->template_dir().'/includes/navbar');
				$this->load->view($this->ui->template_dir().'/includes/sidebar');
				$this->load->view($this->ui->template_dir().'/create_ssl');
				$this->load->view($this->ui->template_dir().'/includes/footer');
			}
		}
		else
		{
			redirect('f/login');
		}
	}
	
	function view_ssl($id)
	{
		if($this->user->is_logged())
		{
			if($this->input->post('submit'))
			{
				$s = $this->ssl->get(['for'], ['key' => $id]);
				if(get_cookie('role') === 'user' AND $this->user->logged_data(['key']) !== $s[0]['for'] OR get_cookie('role') === 'support' AND $this->user->logged_data(['key']) !== $s[0]['for'])
				{
					redirect('p/error_404');
				}
			}
			else
			{
				$data['title'] = 'view_ssl_title';
				$where = ['key' => $id];
				$info = $this->ssl->get([], $where);
				
				if($info !== false)
				{
					$data['info'] = $this->ssl->fetch($info[0]['domain'], $id);
					if ($data['info'] === false) {
						redirect('p/error_404');
					}
					$data['info']['provider'] = $info[0]['provider'];
					
					if(get_cookie('role') === 'user' OR get_cookie('role') === 'support' AND $this->user->logged_data(['key']) !== $info[0]['for'])
					{
						redirect('p/error_404');
					}

					$this->load->view($this->ui->template_dir().'/includes/header', $data);
					$this->load->view($this->ui->template_dir().'/includes/navbar');
					$this->load->view($this->ui->template_dir().'/includes/sidebar');
					$this->load->view($this->ui->template_dir().'/view_ssl');
					$this->load->view($this->ui->template_dir().'/includes/footer');
				}
				else
				{
					redirect('p/error_404');
				}
			}
		}
		else
		{
			$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('login_to_continue_text')]));
			redirect('f/login');
		}
	}
}

?>