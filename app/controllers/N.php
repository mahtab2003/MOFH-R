<?php 

class N extends CI_Controller
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
		$this->load->model('captcha');
		$this->load->model('smtp');
		$this->load->model('gogetssl');
		$this->load->model('ssl');
		$this->load->model('ticket');
		$this->load->model('hosting');
		$this->load->model('mofh');
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
		$this->dashboard();
	}

	function settings()
	{
		if($this->user->is_logged())
		{
			if($this->input->post('submit') AND $this->input->get('general'))
			{
				$this->fv->set_rules('name', $this->ui->text('name_text'), ['trim', 'required', 'valid_name']);
				if($this->fv->run() === true)
				{
					$res = $this->user->set(
						['name' => $this->input->post('name')],
						['email' => $this->user->logged_data(['email'])]
					);
					if($res !== false)
					{
						$this->session->set_flashdata('msg', json_encode([1, $this->ui->text('user_settings_updated_text')]));
						redirect('n/settings');
					}
					else
					{
						$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('error_occured_text')]));
						redirect('n/settings');
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
					redirect('n/settings');
				}
			}
			elseif($this->input->post('submit') AND $this->input->get('pref'))
			{
				set_cookie('lang', $this->input->post('language'), 30 * 86400);
				set_cookie('theme', $this->input->post('theme'), 30 * 86400);
				$this->session->set_flashdata('msg', json_encode([1, $this->ui->text('user_preference_updated_text')]));
				redirect('n/settings');
			}
			elseif($this->input->post('submit') AND $this->input->get('security'))
			{
				$this->fv->set_rules('password', $this->ui->text('password_text'), ['trim', 'required']);
				$this->fv->set_rules('confirm_password', $this->ui->text('confirm_password_text'), ['trim', 'required', 'matches[password]']);
				if($this->fv->run() === true)
				{
					$res = $this->user->set(
						['password' => hash_256($this->input->post('password'))],
						['email' => $this->user->logged_data(['email'])]
					);
					if($res !== false)
					{
						redirect('f/logout?msg='.$this->ui->text('user_password_updated_text'));
					}
					else
					{
						$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('error_occured_text')]));
						redirect('n/settings');
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
					redirect('n/settings');
				}
			}
			elseif($this->input->post('submit') AND $this->input->get('2fa'))
			{
				$this->fv->set_rules('status', $this->ui->text('status_text'), ['trim', 'required']);
				if($this->fv->run() === true)
				{
					$res = $this->user->set(
						['2fa_status' => $this->input->post('status')],
						['email' => $this->user->logged_data(['email'])]
					);
					if($res !== false)
					{
						$this->session->set_flashdata('msg', json_encode([1, $this->ui->text('user_2fa_settings_text')]));
						redirect('n/settings');
					}
					else
					{
						$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('error_occured_text')]));
						redirect('n/settings');
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
					redirect('n/settings');
				}
			}
			else
			{
				$data['title'] = 'settings_title';

				$this->load->view($this->ui->template_dir().'/includes/header', $data);
				$this->load->view($this->ui->template_dir().'/includes/navbar');
				$this->load->view($this->ui->template_dir().'/includes/sidebar');
				$this->load->view($this->ui->template_dir().'/settings');
				$this->load->view($this->ui->template_dir().'/includes/footer');
			}
		}
		else
		{
			redirect('f/login');
		}
	}

	function clients()
	{
		if($this->user->is_logged())
		{
			if(get_cookie('role') == 'root' OR get_cookie('role') == 'admin')
			{
				$data['title'] = 'clients_title';
				$where = ['role' => 'user'];
				$or_where = [
					['role' => 'support']
				];
				if(get_cookie('role') === 'root')
				{
					$or_where[] = ['role' => 'admin'];
				}
				$data['list'] = $this->user->get([], $where, $or_where);
				
				$this->load->view($this->ui->template_dir().'/includes/header', $data);
				$this->load->view($this->ui->template_dir().'/includes/navbar');
				$this->load->view($this->ui->template_dir().'/includes/sidebar');
				$this->load->view($this->ui->template_dir().'/clients');
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

	function dashboard()
	{
		if($this->user->is_logged())
		{
			$data['title'] = 'dashboard_title';
				
			$this->load->view($this->ui->template_dir().'/includes/header', $data);
			$this->load->view($this->ui->template_dir().'/includes/navbar');
			$this->load->view($this->ui->template_dir().'/includes/sidebar');
			$this->load->view($this->ui->template_dir().'/dashboard');
			$this->load->view($this->ui->template_dir().'/includes/footer');
		}
		else
		{
			$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('login_to_continue_text')]));
			redirect('f/login');
		}
	}

	function xdashboard()
	{
		if($this->user->is_logged())
		{
			if(get_cookie('role') == 'root' OR get_cookie('role') == 'admin')
			{
				$data['title'] = 'xdashboard_title';
				
				$this->load->view($this->ui->template_dir().'/includes/header', $data);
				$this->load->view($this->ui->template_dir().'/includes/navbar');
				$this->load->view($this->ui->template_dir().'/includes/sidebar');
				$this->load->view($this->ui->template_dir().'/xdashboard');
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

	function view_client($key)
	{
		if($this->user->is_logged())
		{
			if(get_cookie('role') == 'root' OR get_cookie('role') == 'admin')
			{
				$res = $this->user->get([], ['key' => $key]);
				
				if($res === false)
				{
					redirect('p/error_404');
				}

				if($this->input->post('submit') AND $this->input->get('general'))
				{
					$this->fv->set_rules('name', $this->ui->text('name_text'), ['trim', 'required', 'valid_name']);
					if($this->fv->run() === true)
					{
						$res = $this->user->set(
							['name' => $this->input->post('name')],
							['key' => $key]
						);
						if($res !== false)
						{
							$this->session->set_flashdata('msg', json_encode([1, $this->ui->text('user_settings_updated_text')]));
							redirect('n/view_client/'.$key);
						}
						else
						{
							$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('error_occured_text')]));
							redirect('n/view_client/'.$key);
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
						redirect('n/view_client/'.$key);
					}
				}
				elseif($this->input->post('submit') AND $this->input->get('pref'))
				{
					$this->fv->set_rules('status', $this->ui->text('status_text'), ['trim', 'required']);
					$this->fv->set_rules('role', $this->ui->text('role_text'), ['trim', 'required']);
					if($this->fv->run() === true)
					{
						$res = $this->user->set(
							[
								'status' => $this->input->post('status'),
								'role' => $this->input->post('role')
							],
							['key' => $key]
						);
						if($res !== false)
						{
							$this->session->set_flashdata('msg', json_encode([1, $this->ui->text('user_preference_updated_text')]));
							redirect('n/view_client/'.$key);
						}
						else
						{
							$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('error_occured_text')]));
							redirect('n/view_client/'.$key);
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
						redirect('n/view_client/'.$key);
					}
				}
				elseif($this->input->post('submit') AND $this->input->get('security'))
				{
					$this->fv->set_rules('password', $this->ui->text('password_text'), ['trim', 'required']);
					$this->fv->set_rules('confirm_password', $this->ui->text('confirm_password_text'), ['trim', 'required', 'matches[password]']);
					if($this->fv->run() === true)
					{
						$res = $this->user->set(
							['name' => hash_256($this->input->post('password'))],
							['key' => $key]
						);
						if($res !== false)
						{
							$this->session->set_flashdata('msg', json_encode([1, $this->ui->text('user_password_updated_text')]));
							redirect('n/view_client/'.$key);
						}
						else
						{
							$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('error_occured_text')]));
							redirect('n/view_client/'.$key);
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
						redirect('n/view_client/'.$key);
					}
				}
				elseif($this->input->post('submit') AND $this->input->get('2fa'))
				{
					$this->fv->set_rules('status', $this->ui->text('status_text'), ['trim', 'required']);
					if($this->fv->run() === true)
					{
						$res = $this->user->set(
							['2fa_status' => $this->input->post('status')],
							['key' => $key]
						);
						if($res !== false)
						{
							$this->session->set_flashdata('msg', json_encode([1, $this->ui->text('user_2fa_settings_text')]));
							redirect('n/view_client/'.$key);
						}
						else
						{
							$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('error_occured_text')]));
							redirect('n/view_client/'.$key);
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
						redirect('n/view_client/'.$key);
					}
				}
				else
				{
					$data['title'] = 'view_client_title';
					$data['data'] = $res[0];

					$this->load->view($this->ui->template_dir().'/includes/header', $data);
					$this->load->view($this->ui->template_dir().'/includes/navbar');
					$this->load->view($this->ui->template_dir().'/includes/sidebar');
					$this->load->view($this->ui->template_dir().'/view_client');
					$this->load->view($this->ui->template_dir().'/includes/footer');
				}
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
	
	function site_settings()
	{
		if($this->user->is_logged())
		{
			if(get_cookie('role') == 'root')
			{
				if($this->input->post('submit') AND $this->input->get('general'))
				{
					$this->fv->set_rules('title', $this->ui->text('title_text'), ['trim', 'required']);
					$this->fv->set_rules('status', $this->ui->text('status_text'), ['trim', 'required']);
					$this->fv->set_rules('theme', $this->ui->text('theme_text'), ['trim', 'required']);
					if($this->fv->run() === true)
					{
						$res = $this->site->set([
							'title' => $this->input->post('title'),
							'status' => $this->input->post('status'),
							'theme' => $this->input->post('theme')
						]);
						if($res !== false)
						{
							$this->session->set_flashdata('msg', json_encode([1, $this->ui->text('general_settings_updated_text')]));
							redirect('n/site_settings?general=true');
						}
						else
						{
							$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('error_occured_text')]));
							redirect('n/site_settings?general=true');
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
						redirect('n/site_settings?general=true');
					}
				}
				elseif($this->input->post('submit') AND $this->input->get('smtp'))
				{
					$this->fv->set_rules('hostname', $this->ui->text('hostname_text'), ['trim', 'required']);
					$this->fv->set_rules('username', $this->ui->text('username_text'), ['trim', 'required']);
					$this->fv->set_rules('password', $this->ui->text('password_text'), ['trim', 'required']);
					$this->fv->set_rules('status', $this->ui->text('status_text'), ['trim', 'required']);
					$this->fv->set_rules('port', $this->ui->text('port_text'), ['trim', 'required']);
					$this->fv->set_rules('from', $this->ui->text('from_text'), ['trim', 'required']);
					if($this->fv->run() === true)
					{
						$res = $this->smtp->set([
							'hostname' => $this->input->post('hostname'),
							'username' => $this->input->post('username'),
							'password' => $this->input->post('password'),
							'port' => $this->input->post('port'),
							'status' => $this->input->post('status'),
							'from' => $this->input->post('from')
						]);
						if($res !== false)
						{
							$this->session->set_flashdata('msg', json_encode([1, $this->ui->text('smtp_settings_updated_text')]));
							redirect('n/site_settings?smtp=true');
						}
						else
						{
							$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('error_occured_text')]));
							redirect('n/site_settings?smtp=true');
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
						redirect('n/site_settings?smtp=true');
					}
				}
				elseif($this->input->get('smtp') AND $this->input->get('test'))
				{
					$res = $this->mailer->test_mail();
					if($res !== false)
					{
						$this->session->set_flashdata('msg', json_encode([1, $this->ui->text('smtp_test_text')]));
						redirect('n/site_settings?smtp=true');
					}
					else
					{
						$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('error_occured_text')]));
						redirect('n/site_settings?smtp=true');
					}
				}
				elseif($this->input->post('submit') AND $this->input->get('captcha'))
				{
					$this->fv->set_rules('site_key', $this->ui->text('site_key_text'), ['trim', 'required']);
					$this->fv->set_rules('secret_key', $this->ui->text('secret_key_text'), ['trim', 'required']);
					$this->fv->set_rules('status', $this->ui->text('status_text'), ['trim', 'required']);
					$this->fv->set_rules('type', $this->ui->text('provider_text'), ['trim', 'required']);
					if($this->fv->run() === true)
					{
						$res = $this->captcha->set([
							'site_key' => $this->input->post('site_key'),
							'secret_key' => $this->input->post('secret_key'),
							'status' => $this->input->post('status'),
							'type' => $this->input->post('type')
						]);
						if($res !== false)
						{
							$this->session->set_flashdata('msg', json_encode([1, $this->ui->text('captcha_settings_updated_text')]));
							redirect('n/site_settings?captcha=true');
						}
						else
						{
							$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('error_occured_text')]));
							redirect('n/site_settings?captcha=true');
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
						redirect('n/site_settings?captcha=true');
					}
				}
				elseif($this->input->post('submit') AND $this->input->get('gogetssl'))
				{
					$this->fv->set_rules('username', $this->ui->text('username_text'), ['trim', 'required']);
					$this->fv->set_rules('password', $this->ui->text('password_text'), ['trim', 'required']);
					$this->fv->set_rules('status', $this->ui->text('status_text'), ['trim', 'required']);
					if($this->fv->run() === true)
					{
						$res = $this->gogetssl->set([
							'username' => $this->input->post('username'),
							'password' => $this->input->post('password'),
							'status' => $this->input->post('status')
						]);
						if($res !== false)
						{
							$this->session->set_flashdata('msg', json_encode([1, $this->ui->text('gogetssl_settings_updated_text')]));
							redirect('n/site_settings?gogetssl=true');
						}
						else
						{
							$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('error_occured_text')]));
							redirect('n/site_settings?gogetssl=true');
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
						redirect('n/site_settings?gogetssl=true');
					}
				}
				else
				{
					$data['title'] = 'site_settings_title';

					$this->load->view($this->ui->template_dir().'/includes/header', $data);
					$this->load->view($this->ui->template_dir().'/includes/navbar');
					$this->load->view($this->ui->template_dir().'/includes/sidebar');
					$this->load->view($this->ui->template_dir().'/site_settings');
					$this->load->view($this->ui->template_dir().'/includes/footer');
				}
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
	
	function mofh_settings()
	{
		if($this->user->is_logged())
		{
			if(get_cookie('role') == 'root')
			{
				if($this->input->post('submit') AND $this->input->get('mofh'))
				{
					$this->fv->set_rules('username', $this->ui->text('username_text'), ['trim', 'required']);
					$this->fv->set_rules('password', $this->ui->text('password_text'), ['trim', 'required']);
					$this->fv->set_rules('cpanel_url', $this->ui->text('cpanel_url_text'), ['trim', 'required']);
					$this->fv->set_rules('ns_1', $this->ui->text('ns_1_text'), ['trim', 'required']);
					$this->fv->set_rules('ns_2', $this->ui->text('ns_2_text'), ['trim', 'required']);
					$this->fv->set_rules('plan', $this->ui->text('plan_text'), ['trim', 'required']);
					if($this->fv->run() === true)
					{
						$res = $this->mofh->set([
							'username' => $this->input->post('username'),
							'password' => $this->input->post('password'),
							'cpanel_url' => $this->input->post('cpanel_url'),
							'ns_1' => $this->input->post('ns_1'),
							'ns_2' => $this->input->post('ns_2'),
							'plan' => $this->input->post('plan')
						]);
						if($res !== false)
						{
							$this->session->set_flashdata('msg', json_encode([1, $this->ui->text('mofh_settings_updated_text')]));
							redirect('n/mofh_settings?mofh=true');
						}
						else
						{
							$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('error_occured_text')]));
							redirect('n/mofh_settings?mofh=true');
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
						redirect('n/mofh_settings?mofh=true');
					}
				}
				elseif($this->input->post('submit') AND $this->input->get('domain'))
				{
					$this->fv->set_rules('domain', $this->ui->text('domain_text'), ['trim', 'required']);
					if($this->fv->run() === true)
					{
						$domain = $this->input->post('domain');
						if(substr($domain, 0, 1) !== '.')
						{
							$domain = '.'.$domain;
						}
						$res = $this->base->new('mofh_ext', [
							'domain' => $domain
						]);
						if($res !== false)
						{
							$this->session->set_flashdata('msg', json_encode([1, $this->ui->text('mofh_settings_updated_text')]));
							redirect('n/mofh_settings?domain=true');
						}
						else
						{
							$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('error_occured_text')]));
							redirect('n/mofh_settings?domain=true');
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
						redirect('n/mofh_settings?domain=true');
					}
				}
				elseif($this->input->get('domain') AND $this->input->get('delete'))
				{
					$res = $this->base->delete('mofh_ext', [
						'domain' => $this->input->get('domain')
					]);
					if($res !== false)
					{
						$this->session->set_flashdata('msg', json_encode([1, $this->ui->text('mofh_settings_updated_text')]));
						redirect('n/mofh_settings?domain=true');
					}
					else
					{
						$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('error_occured_text')]));
						redirect('n/mofh_settings?domain=true');
					}
				}
				else
				{
					$data['title'] = 'mofh_settings_title';

					$this->load->view($this->ui->template_dir().'/includes/header', $data);
					$this->load->view($this->ui->template_dir().'/includes/navbar');
					$this->load->view($this->ui->template_dir().'/includes/sidebar');
					$this->load->view($this->ui->template_dir().'/mofh_settings');
					$this->load->view($this->ui->template_dir().'/includes/footer');
				}
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

	function emails()
	{
		if($this->user->is_logged())
		{
			if(get_cookie('role') == 'root')
			{
				$data['title'] = 'emails_title';
				$data['list'] = $this->base->get('emails');
				
				$this->load->view($this->ui->template_dir().'/includes/header', $data);
				$this->load->view($this->ui->template_dir().'/includes/navbar');
				$this->load->view($this->ui->template_dir().'/includes/sidebar');
				$this->load->view($this->ui->template_dir().'/emails');
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
	
	function edit_email($id)
	{
		if($this->user->is_logged())
		{
			if(get_cookie('role') == 'root')
			{
				if($this->input->post('submit'))
				{
					$this->fv->set_rules('subject', $this->ui->text('subject_text'), ['trim', 'required']);
					$this->fv->set_rules('content', $this->ui->text('content_text'), ['required']);
					if($this->fv->run() === true)
					{
						$res = $this->emails->set([
							'subject' => $this->input->post('subject'),
							'content' => $this->input->post('content')
						], $id);
						if($res !== false)
						{
							$this->session->set_flashdata('msg', json_encode([1, $this->ui->text('email_updated_text')]));
							redirect('n/edit_email/'.$id);
						}
						else
						{
							$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('error_occured_text')]));
							redirect('n/edit_email/'.$id);
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
						redirect('n/edit_email/'.$id);
					}
				}
				else
				{
					$data['title'] = 'edit_email_title';
					$data['info'] = $this->emails->get(['id', 'subject', 'content', 'docs'], $id);
					$this->load->view($this->ui->template_dir().'/includes/header', $data);
					$this->load->view($this->ui->template_dir().'/includes/navbar');
					$this->load->view($this->ui->template_dir().'/includes/sidebar');
					$this->load->view($this->ui->template_dir().'/edit_email');
					$this->load->view($this->ui->template_dir().'/includes/footer');
				}
			}
			else
			{
				redirect('p/error_503');
			}
		}
	}
}

?>