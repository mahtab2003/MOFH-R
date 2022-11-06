<?php 

class H extends CI_Controller
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
		$this->load->model('hosting');
		$this->load->model('captcha');
		$this->load->model('sitepro');
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
		$this->accounts();
	}

	function all_accounts()
	{
		if($this->user->is_logged())
		{
			if(get_cookie('role') == 'root' OR get_cookie('role') == 'admin')
			{
				$data['title'] = 'all_accounts_title';
				$data['list'] = $this->hosting->get();
				
				$this->load->view($this->ui->template_dir().'/includes/header', $data);
				$this->load->view($this->ui->template_dir().'/includes/navbar');
				$this->load->view($this->ui->template_dir().'/includes/sidebar');
				$this->load->view($this->ui->template_dir().'/all_accounts');
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

	function accounts()
	{
		if($this->user->is_logged())
		{
			$data['title'] = 'accounts_title';
			$data['list'] = $this->hosting->get([], ['for' => $this->user->logged_data(['key'])]);
				
			$this->load->view($this->ui->template_dir().'/includes/header', $data);
			$this->load->view($this->ui->template_dir().'/includes/navbar');
			$this->load->view($this->ui->template_dir().'/includes/sidebar');
			$this->load->view($this->ui->template_dir().'/accounts');
			$this->load->view($this->ui->template_dir().'/includes/footer');
		}
		else
		{
			redirect('f/login');
		}
	}

	function create_account()
	{
		if($this->user->is_logged())
		{
			if(count($this->hosting->get(['id'], ['status' => 'active', 'for' => $this->user->logged_data(['key'])], [['status' => 'processing'], ['status' => 'deactivating'], ['status' => 'reactivating']])) < 3)
			{
				if($this->input->get('check') AND $this->input->post('submit'))
				{
					$res = $this->hosting->check($this->input->post('domain'));
					if(!is_bool($res))
					{
						echo $res;
					}
					elseif(is_bool($res) AND $res !== false)
					{
						echo $this->input->post('domain');
					}
					else
					{
						echo 'This domain is not available.';
					}
				}
				elseif($this->input->post('submit') AND $this->input->get('create'))
				{
					$this->fv->set_rules('domain', $this->ui->text('domain_text'), ['trim', 'required']);
					$this->fv->set_rules('label', $this->ui->text('label_text'), ['trim', 'required']);
					if($this->captcha->verify())
					{
						if($this->fv->run() === true)
						{
							$res = $this->hosting->create(
								$this->input->post('label'),
								$this->input->post('domain')
							);
							if(!is_bool($res))
							{
								$this->session->set_flashdata('msg', json_encode([0, $res]));
								redirect('h/create_account');
							}
							elseif($res !== false)
							{
								$this->session->set_flashdata('msg', json_encode([1, $this->ui->text('account_created_text')]));
								redirect('h/accounts');
							}
							else
							{
								$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('error_occured_text')]));
								redirect('h/create_account');
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
							redirect('h/create_account');
						}
					}
					else
					{
						$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('invalid_captcha_response_text')]));
						redirect('h/create_account');
					}
				}
				else
				{
					$data['title'] = 'create_account_title';
						
					$this->load->view($this->ui->template_dir().'/includes/header', $data);
					$this->load->view($this->ui->template_dir().'/includes/navbar');
					$this->load->view($this->ui->template_dir().'/includes/sidebar');
					$this->load->view($this->ui->template_dir().'/create_account');
					$this->load->view($this->ui->template_dir().'/includes/footer');
				}
			}
			else
			{
				$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('accounts_limit_reached_text')]));
				redirect('h/accounts');
			}
		}
		else
		{
			redirect('f/login');
		}
	}

	function view_account($id)
	{
		if($this->user->is_logged())
		{
			if($this->input->get('reactivate'))
			{
				$h = $this->hosting->get(['for', 'status', 'key'], ['username' => $id]);
				if(get_cookie('role') === 'user' OR get_cookie('role') === 'support' AND $this->user->logged_data(['key']) !== $h[0]['for'])
				{
					redirect('p/error_404');
				}
				if($h[0]['status'] === 'deactivated' OR $h[0]['status'] === 'suspended')
				{
					if(count($this->hosting->get(['id'], ['status' => 'active', 'for' => $this->user->logged_data(['key'])], [['status' => 'processing'], ['status' => 'deactivating'], ['status' => 'reactivating']])) < 3)
					{
						$res = $this->hosting->unsuspend($h[0]['key']);
						if(is_string($res))
						{
							$this->session->set_flashdata('msg', json_encode([0, $res]));
							redirect('h/view_account/'.$id);
						}
						elseif(is_bool($res) AND $res !== false)
						{
							$this->session->set_flashdata('msg', json_encode([1, $this->ui->text('reactivate_account_text')]));
							redirect('h/view_account/'.$id);
						}
						else
						{
							$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('error_occured_text')]));
							redirect('h/view_account/'.$id);
						}
					}
					else
					{
						$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('accounts_limit_reached_text')]));
						redirect('h/view_account/'.$id);
					}
				}
				else
				{
					$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('error_occured_text')]));
					redirect('h/view_account/'.$id);
				}
			}
			elseif($this->input->get('cpanel_redirect'))
			{
				$data['title'] = 'cpanel_login_title';
				$info = $this->hosting->get([], ['username' => $id]);
				if(!is_array($info) AND count($info) > 0)
				{
					redirect('p/error_404');
				}
				$data['info'] = $info[0];

				$this->load->view($this->ui->template_dir().'/includes/header', $data);
				$this->load->view($this->ui->template_dir().'/cpanel_login');
				$this->load->view($this->ui->template_dir().'/includes/footer');
			}
			elseif($this->input->get('builder') AND $this->input->get('builder'))
			{
				$info = $this->hosting->get([], ['username' => $id]);
				if(!is_array($info) AND count($info) > 0)
				{
					redirect('p/error_404');
				}
				else
				{
					$domain = trim($this->input->get('domain'));
					if($domain !== $info[0]['domain'])
					{
						$dir = '/htdocs/'.$domain;
					}
					else
					{
						$dir = '/htdocs/';
					}
					$builder = $this->sitepro->run(
						'https://site.pro',
						$info[0]['username'],
						$info[0]['password'],
						$domain,
						$dir
					);
					if($builder->isSuccessful() === false)
					{
						$this->session->set_flashdata('msg', json_encode([0, $this->getMessage()]));
						redirect('h/view_account/'.$id);
					}
					elseif($builder->isSuccessful() === true)
					{
						 header('location: '.$this->getMessage()); 
					}
				}
			}
			else
			{
				$data['title'] = 'view_account_title';
				$info = $this->hosting->get([], ['username' => $id]);
				
				if($info !== false AND is_array($info) AND count($info) > 0)
				{
					if(get_cookie('role') === 'user' AND $this->user->logged_data(['key']) !== $info[0]['for'] OR get_cookie('role') === 'support' AND $this->user->logged_data(['key']) !== $info[0]['for'])
					{
						redirect('p/error_404');
					}

					$data['info'] = $info[0];

					$this->load->view($this->ui->template_dir().'/includes/header', $data);
					$this->load->view($this->ui->template_dir().'/includes/navbar');
					$this->load->view($this->ui->template_dir().'/includes/sidebar');
					$this->load->view($this->ui->template_dir().'/view_account');
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

	function account_settings($id)
	{
		if($this->user->is_logged())
		{
			if($this->input->get('general') AND $this->input->post('submit'))
			{
				$h = $this->hosting->get(['for', 'status', 'key'], ['username' => $id]);
				if(get_cookie('role') === 'user' OR get_cookie('role') === 'support' AND $this->user->logged_data(['key']) !== $h[0]['for'])
				{
					redirect('p/error_404');
				}

				if($h[0]['status'] !== 'active')
				{
					$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('error_occured_text')]));
					redirect('h/account_settings/'.$id);
				}
				else
				{
					$res = $this->hosting->set(['label' => $this->input->post('label')], ['username' => $id]);
					if(is_string($res))
					{
						$this->session->set_flashdata('msg', json_encode([0, $res]));
						redirect('h/account_settings/'.$id);
					}
					elseif(is_bool($res) AND $res !== false)
					{
						$this->session->set_flashdata('msg', json_encode([1, $this->ui->text('account_label_changed_text')]));
						redirect('h/account_settings/'.$id);
					}
					else
					{
						$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('error_occured_text')]));
						redirect('h/account_settings/'.$id);
					}
				}
			}
			elseif($this->input->get('security') AND $this->input->post('submit'))
			{
				$h = $this->hosting->get(['for', 'status', 'key', 'password'], ['username' => $id]);
				if(get_cookie('role') === 'user' OR get_cookie('role') === 'support' AND $this->user->logged_data(['key']) !== $h[0]['for'])
				{
					redirect('p/error_404');
				}

				if($h[0]['status'] !== 'active')
				{
					$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('error_occured_text')]));
					redirect('h/account_settings/'.$id);
				}
				elseif($h[0]['password'] !== $this->input->post('old_password'))
				{
					$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('password_not_match_text')]));
					redirect('h/account_settings/'.$id);
				}
				else
				{
					$res = $this->hosting->passwd($h[0]['key'], $this->input->post('password'));
					if(is_string($res))
					{
						$this->session->set_flashdata('msg', json_encode([0, $res]));
						redirect('h/account_settings/'.$id);
					}
					elseif(is_bool($res) AND $res !== false)
					{
						$this->session->set_flashdata('msg', json_encode([1, $this->ui->text('account_password_changed_text')]));
						redirect('h/account_settings/'.$id);
					}
					else
					{
						$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('error_occured_text')]));
						redirect('h/account_settings/'.$id);
					}
				}
			}
			elseif($this->input->get('pref') AND $this->input->post('submit'))
			{
				$h = $this->hosting->get(['for', 'status', 'key'], ['username' => $id]);
				if(get_cookie('role') === 'user' OR get_cookie('role') === 'support' AND $this->user->logged_data(['key']) !== $h[0]['for'])
				{
					redirect('p/error_404');
				}

				if($h[0]['status'] !== 'active')
				{
					$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('error_occured_text')]));
					redirect('h/account_settings/'.$id);
				}
				else
				{
					$res = $this->hosting->suspend($h[0]['key'], $this->input->post('reason'));
					if(is_string($res))
					{
						$this->session->set_flashdata('msg', json_encode([0, $res]));
						redirect('h/account_settings/'.$id);
					}
					elseif(is_bool($res) AND $res !== false)
					{
						$this->session->set_flashdata('msg', json_encode([1, $this->ui->text('deactivate_account_text')]));
						redirect('h/accounts');
					}
					else
					{
						$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('error_occured_text')]));
						redirect('h/account_settings/'.$id);
					}
				}
			}
			else
			{
				$data['title'] = 'account_settings_title';
				$info = $this->hosting->get([], ['username' => $id]);
				
				if($info !== false AND is_array($info) AND count($info) > 0)
				{
					if(get_cookie('role') === 'user' AND $this->user->logged_data(['key']) !== $info[0]['for'] OR get_cookie('role') === 'support' AND $this->user->logged_data(['key']) !== $info[0]['for'])
					{
						redirect('p/error_404');
					}

					$data['info'] = $info[0];

					$this->load->view($this->ui->template_dir().'/includes/header', $data);
					$this->load->view($this->ui->template_dir().'/includes/navbar');
					$this->load->view($this->ui->template_dir().'/includes/sidebar');
					$this->load->view($this->ui->template_dir().'/account_settings');
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