<?php 

class T extends CI_Controller
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
		$this->load->model('ticket');
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
		$this->tickets();
	}

	function all_tickets()
	{
		if($this->user->is_logged())
		{
			if(get_cookie('role') == 'root' OR get_cookie('role') == 'admin' OR get_cookie('role') == 'support')
			{
				$data['title'] = 'all_ticket_title';
				$where = ['status' => 'open'];
				$or_where = [
					['status' => 'client']
				];
				$data['list'] = $this->ticket->get('ticket', [], $where, $or_where);
				
				$this->load->view($this->ui->template_dir().'/includes/header', $data);
				$this->load->view($this->ui->template_dir().'/includes/navbar');
				$this->load->view($this->ui->template_dir().'/includes/sidebar');
				$this->load->view($this->ui->template_dir().'/all_ticket');
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

	function tickets()
	{
		if($this->user->is_logged())
		{
			$data['title'] = 'ticket_title';
			$data['list'] = $this->ticket->get('ticket', [], ['for' => $this->user->logged_data(['key'])]);
				
			$this->load->view($this->ui->template_dir().'/includes/header', $data);
			$this->load->view($this->ui->template_dir().'/includes/navbar');
			$this->load->view($this->ui->template_dir().'/includes/sidebar');
			$this->load->view($this->ui->template_dir().'/ticket');
			$this->load->view($this->ui->template_dir().'/includes/footer');
		}
		else
		{
			redirect('f/login');
		}
	}

	function create_ticket()
	{
		if($this->user->is_logged())
		{
			if($this->input->post('submit'))
			{
				$this->fv->set_rules('subject', $this->ui->text('subject_text'), ['trim', 'required']);
				$this->fv->set_rules('content', $this->ui->text('content_text'), ['trim', 'required']);
				if($this->captcha->verify())
				{
					if($this->fv->run() === true)
					{
						$key = hash_64($this->input->post('subject').':'.$this->input->post('content').':'.time());
						$res = $this->ticket->create('ticket', [
							'subject' => $this->input->post('subject'),
							'content' => $this->input->post('content'),
							'status' => 'open',
							'key' => $key,
						]);
						$emails = $this->user->get(['name', 'email'], ['key' => $this->user->logged_data(['email'])], [['role' => 'support'], ['role' => 'admin'], ['role' => 'root']]);
						foreach ($emails as $value)
						{
							$this->mailer->send('new_ticket', $value['email'], [
								'ticket_id' => $key,
								'ticket_url' => base_url('t/view_ticket/'.$key),
								'user_name' => $value['name']
							]);
						}
						if($res !== false)
						{
							$this->session->set_flashdata('msg', json_encode([1, $this->ui->text('ticket_created_text')]));
							redirect('t/tickets');
						}
						else
						{
							$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('error_occured_text')]));
							redirect('t/create_ticket');
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
						redirect('t/create_ticket');
					}
				}
				else
				{
					$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('invalid_captcha_response_text')]));
					redirect('t/create_ticket');
				}
			}
			else
			{
				$data['title'] = 'create_ticket_title';
					
				$this->load->view($this->ui->template_dir().'/includes/header', $data);
				$this->load->view($this->ui->template_dir().'/includes/navbar');
				$this->load->view($this->ui->template_dir().'/includes/sidebar');
				$this->load->view($this->ui->template_dir().'/create_ticket');
				$this->load->view($this->ui->template_dir().'/includes/footer');
			}
		}
		else
		{
			redirect('f/login');
		}
	}

	function view_ticket($id)
	{
		if($this->user->is_logged())
		{
			if($this->input->post('submit'))
			{
				$this->fv->set_rules('content', $this->ui->text('content_text'), ['trim', 'required']);
				if($this->captcha->verify())
				{
					if($this->fv->run() === true)
					{
						$data = $this->ticket->get('ticket', ['for', 'status'], ['key' => $id]);
						if(count($data) > 0)
						{
							if($data[0]['for'] !== $this->user->logged_data(['key']))
							{
								$status = 'support';
							}
							else
							{
								$status = 'client';
							}
							$res = $this->ticket->set('ticket',
								['status' => $status],
								['key' => $id]
							);
							$res = $this->ticket->create('reply', [
								'content' => $this->input->post('content'), 
								'key' => $id,
							]);
							$keys = $this->ticket->get('reply', ['for'], ['key' => $id]);
							if(count($keys) > 0)
							{
								foreach ($keys as $key)
								{
									$or_where[] = ['key' => $key['for']];
								}
							}
							$where['key'] = $this->user->logged_data(['key']);
							$emails = $this->user->get(['name', 'email'], $where, $or_where);
							foreach ($emails as $value)
							{
								$this->mailer->send('reply_ticket', $value['email'], [
									'ticket_id' => $id,
									'ticket_url' => base_url('t/view_ticket/'.$id),
									'user_name' => $value['name']
								]);
							}
							if($res !== false)
							{
								$this->session->set_flashdata('msg', json_encode([1, $this->ui->text('reply_created_text')]));
								redirect('t/view_ticket/'.$id);
							}
							else
							{
								$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('error_occured_text')]));
								redirect('t/view_ticket/'.$id);
							}
						}
						else
						{
							$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('error_occured_text')]));
							redirect('t/view_ticket/'.$id);
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
						redirect('t/view_ticket/'.$id);
					}
				}
				else
				{
					$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('invalid_captcha_response_text')]));
					redirect('t/view_ticket/'.$id);
				}
			}
			elseif($this->input->get('reopen'))
			{
				$data = $this->ticket->get('ticket', ['status'], ['key' => $id]);
				if(count($data) > 0)
				{
					if($data[0]['status'] === 'closed')
					{
						$res = $this->ticket->set('ticket',
							['status' => 'open'],
							['key' => $id]
						);
						if($res !== false)
						{
							$this->session->set_flashdata('msg', json_encode([1, $this->ui->text('ticket_reopened_text')]));
							redirect('t/view_ticket/'.$id);
						}
						else
						{
							$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('error_occured_text')]));
							redirect('t/view_ticket/'.$id);
						}
					}
					else
					{
						$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('error_occured_text')]));
						redirect('t/view_ticket/'.$id);
					}
				}
				else
				{
					$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('error_occured_text')]));
					redirect('t/view_ticket/'.$id);
				}
				
			}
			elseif($this->input->get('close'))
			{
				$data = $this->ticket->get('ticket', ['status'], ['key' => $id]);
				if(count($data) > 0)
				{
					if($data[0]['status'] !== 'closed')
					{
						$res = $this->ticket->set('ticket',
							['status' => 'closed'],
							['key' => $id]
						);
						if($res !== false)
						{
							$this->session->set_flashdata('msg', json_encode([1, $this->ui->text('ticket_closed_text')]));
							redirect('t/view_ticket/'.$id);
						}
						else
						{
							$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('error_occured_text')]));
							redirect('t/view_ticket/'.$id);
						}
					}
					else
					{
						$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('error_occured_text')]));
						redirect('t/view_ticket/'.$id);
					}
				}
				else
				{
					$this->session->set_flashdata('msg', json_encode([0, $this->ui->text('error_occured_text')]));
					redirect('t/view_ticket/'.$id);
				}
				
			}
			else
			{
				$data['title'] = 'view_ticket_title';
				$where = ['key' => $id];
				$data['info'] = $this->ticket->get('ticket', [], $where);
				
				if($data['info'] !== false)
				{
					if(get_cookie('role') === 'user' AND $this->user->logged_data(['key']) !== $data['info'][0]['for'])
					{
						redirect('p/error_404');
					}

					$data['list'] = $this->ticket->get('reply', [], $where);

					$this->load->view($this->ui->template_dir().'/includes/header', $data);
					$this->load->view($this->ui->template_dir().'/includes/navbar');
					$this->load->view($this->ui->template_dir().'/includes/sidebar');
					$this->load->view($this->ui->template_dir().'/view_ticket');
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
