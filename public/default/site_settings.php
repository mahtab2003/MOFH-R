<div class="content-header">
	<div class="container-xl">
		<div class="row">
			<div class="col-sm-6">
				<h1 class="m-0"><?= $this->ui->text($title) ?></h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="<?= base_url('n') ?>"><?= $this->ui->text('home_link_text') ?></a></li>
					<li class="breadcrumb-item"><a href="<?= base_url('n/site_settings') ?>"><?= $this->ui->text('site_link_text') ?></a></li>
					<li class="breadcrumb-item active"><?= $this->ui->text('settings_link_text') ?></li>
				</ol>
			</div>
		</div>
	</div>
</div>
<div class="content">
	<div class="container-xl">
		<?php if (is_updated() === false): ?>
			<div class="callout callout-warning">
				<?= $this->ui->text('update_available_text') ?> <a href="<?= base_url('p/update') ?>"><?= $this->ui->text('click_here_text') ?></a>
			</div>
		<?php endif ?>
		<div class="card card-primary card-outline card-tabs">
			<div class="card-header p-0 pt-1 border-bottom-0">
				<ul class="nav nav-tabs" role="tablist">
					<li class="nav-item">
						<a class="nav-link <?php if(isset($_GET['general']) OR empty($_GET)): ?>active<?php endif ?>" id="general-tab" data-toggle="pill" href="#general-content" role="tab" aria-controls="general-content" aria-selected="true"><?= $this->ui->text('general_settings_text') ?></a>
					</li>
					<li class="nav-item">
						<a class="nav-link <?php if(isset($_GET['smtp'])): ?>active<?php endif ?>" id="smtp-tab" data-toggle="pill" href="#smtp-content" role="tab" aria-controls="smtp-content" aria-selected="true"><?= $this->ui->text('mailer_settings_text') ?></a>
					</li>
					<li class="nav-item">
						<a class="nav-link <?php if(isset($_GET['captcha'])): ?>active<?php endif ?>" id="captcha-tab" data-toggle="pill" href="#captcha-content" role="tab" aria-controls="captcha-content" aria-selected="true"><?= $this->ui->text('captcha_settings_text') ?></a>
					</li>
					<li class="nav-item">
						<a class="nav-link <?php if(isset($_GET['gogetssl'])): ?>active<?php endif ?>" id="gogetssl-tab" data-toggle="pill" href="#gogetssl-content" role="tab" aria-controls="gogetssl-content" aria-selected="true"><?= $this->ui->text('gogetssl_settings_text') ?></a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="<?= base_url('n/mofh_settings') ?>"><?= $this->ui->text('mofh_text') ?></a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="<?= base_url('n/emails') ?>"><?= $this->ui->text('emails_text') ?></a>
					</li>
				</ul>
			</div>
			<div class="card-body">
				<div class="tab-content">
					<div class="tab-pane fade <?php if(isset($_GET['general']) OR empty($_GET)): ?>active show<?php endif ?>" id="general-content" aria-labelledby="general-tab" role="tabpanel">
						<?= form_open('n/site_settings?general=true') ?>
						<?php $general = $this->site->get(['title', 'status', 'theme', 'docs']) ?>
							<div class="row">
								<div class="col-md-6">
									<div class="mb-2">
										<label class="form-label"><?= $this->ui->text('title_text') ?></label>
										<input type="text" name="title" class="form-control" placeholder="..." value="<?= $general['title'] ?>">
									</div>
								</div>
								<div class="col-md-6">
									<div class="mb-2">
										<label class="form-label"><?= $this->ui->text('status_text') ?></label>
										<select class="form-control" name="status">
											<?php foreach (['active', 'inactive'] as $status): ?>
												<option value="<?= $status ?>" <?php if($status == $general['status']): echo('selected'); endif; ?>><?= ucfirst($status) ?></option>
											<?php endforeach ?>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="mb-2">
										<label class="form-label"><?= $this->ui->text('theme_text') ?></label>
										<select class="form-control" name="theme">
											<?php foreach ($this->ui->get_templates() as $theme): ?>
												<option value="<?= $theme['dir'] ?>" <?php if($theme['dir'] == $general['theme']): echo('selected'); endif; ?>><?= ucfirst($theme['name']) ?></option>
											<?php endforeach ?>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="mb-2">
										<label class="form-label"><?= $this->ui->text('docs_text') ?></label>
										<input type="text" class="form-control" value="<?= $general['docs'] ?>" readonly>
									</div>
								</div>
								<div class="col-md-12">
									<div class="mb-2">
										<input type="submit" name="submit" class="btn btn-primary" value="<?= $this->ui->text('update_settings_btn_text') ?>">
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="tab-pane fade <?php if(isset($_GET['smtp'])): ?>active show<?php endif ?>" id="smtp-content" aria-labelledby="smtp-tab" role="tabpanel">
						<?= form_open('n/site_settings?smtp=true') ?>
						<?php $smtp = $this->smtp->get(['hostname', 'username', 'password', 'status', 'port', 'from']) ?>
							<div class="row">
								<div class="col-md-6">
									<div class="mb-2">
										<label class="form-label"><?= $this->ui->text('hostname_text') ?></label>
										<input type="text" name="hostname" class="form-control" placeholder="..." value="<?= $smtp['hostname'] ?>">
									</div>
								</div>
								<div class="col-md-6">
									<div class="mb-2">
										<label class="form-label"><?= $this->ui->text('username_text') ?></label>
										<input type="text" name="username" class="form-control" placeholder="..." value="<?= $smtp['username'] ?>">
									</div>
								</div>
								<div class="col-md-6">
									<div class="mb-2">
										<label class="form-label"><?= $this->ui->text('password_text') ?></label>
										<input type="text" name="password" class="form-control" placeholder="..." value="<?= $smtp['password'] ?>">
									</div>
								</div>
								<div class="col-md-6">
									<div class="mb-2">
										<label class="form-label"><?= $this->ui->text('from_text') ?></label>
										<input type="text" name="from" class="form-control" placeholder="..." value="<?= $smtp['from'] ?>">
									</div>
								</div>
								<div class="col-md-6">
									<div class="mb-2">
										<label class="form-label"><?= $this->ui->text('port_text') ?></label>
										<input type="text" name="port" class="form-control" placeholder="..." value="<?= $smtp['port'] ?>">
									</div>
								</div>
								<div class="col-md-6">
									<div class="mb-2">
										<label class="form-label"><?= $this->ui->text('status_text') ?></label>
										<select class="form-control" name="status">
											<?php foreach (['active', 'inactive'] as $status): ?>
												<option value="<?= $status ?>" <?php if($status == $smtp['status']): echo('selected'); endif; ?>><?= ucfirst($status) ?></option>
											<?php endforeach ?>
										</select>
									</div>
								</div>
								<div class="col-md-12">
									<div class="mb-2">
										<input type="submit" name="submit" class="btn btn-primary" value="<?= $this->ui->text('update_settings_btn_text') ?>">
										<a href="<?= base_url('n/site_settings?smtp=true&test=true') ?>" class="btn btn-danger"><?= $this->ui->text('test_email_btn_text') ?></a>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="tab-pane fade <?php if(isset($_GET['captcha'])): ?>active show<?php endif ?>" id="captcha-content" aria-labelledby="captcha-tab" role="tabpanel">
						<?= form_open('n/site_settings?captcha=true') ?>
						<?php $captcha = $this->captcha->get(['type', 'status', 'site_key', 'secret_key']) ?>
							<div class="row">
								<div class="col-md-6">
									<div class="mb-2">
										<label class="form-label"><?= $this->ui->text('site_key_text') ?></label>
										<input type="text" name="site_key" class="form-control" placeholder="..." value="<?= $captcha['site_key'] ?>">
									</div>
								</div>
								<div class="col-md-6">
									<div class="mb-2">
										<label class="form-label"><?= $this->ui->text('secret_key_text') ?></label>
										<input type="text" name="secret_key" class="form-control" placeholder="..." value="<?= $captcha['secret_key'] ?>">
									</div>
								</div>
								<div class="col-md-6">
									<div class="mb-2">
										<label class="form-label"><?= $this->ui->text('provider_text') ?></label>
										<select class="form-control" name="type">
											<?php foreach (['google', 'human'] as $status): ?>
												<option value="<?= $status ?>" <?php if($status == $captcha['type']): echo('selected'); endif; ?>><?= $this->ui->text($status.'_captcha_text') ?></option>
											<?php endforeach ?>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="mb-2">
										<label class="form-label"><?= $this->ui->text('status_text') ?></label>
										<select class="form-control" name="status">
											<?php foreach (['active', 'inactive'] as $status): ?>
												<option value="<?= $status ?>" <?php if($status == $captcha['status']): echo('selected'); endif; ?>><?= ucfirst($status) ?></option>
											<?php endforeach ?>
										</select>
									</div>
								</div>
								<div class="col-md-12">
									<div class="mb-2">
										<input type="submit" name="submit" class="btn btn-primary" value="<?= $this->ui->text('update_settings_btn_text') ?>">
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="tab-pane fade <?php if(isset($_GET['gogetssl'])): ?>active show<?php endif ?>" id="gogetssl-content" aria-labelledby="gogetssl-tab" role="tabpanel">
						<?= form_open('n/site_settings?gogetssl=true') ?>
						<?php $gogetssl = $this->gogetssl->get(['status', 'username', 'password']) ?>
							<div class="row">
								<div class="col-md-6">
									<div class="mb-2">
										<label class="form-label"><?= $this->ui->text('username_text') ?></label>
										<input type="text" name="username" class="form-control" placeholder="..." value="<?= $gogetssl['username'] ?>">
									</div>
								</div>
								<div class="col-md-6">
									<div class="mb-2">
										<label class="form-label"><?= $this->ui->text('password_text') ?></label>
										<input type="text" name="password" class="form-control" placeholder="..." value="<?= $gogetssl['password'] ?>">
									</div>
								</div>
								<div class="col-md-6">
									<div class="mb-2">
										<label class="form-label"><?= $this->ui->text('status_text') ?></label>
										<select class="form-control" name="status">
											<?php foreach (['active', 'inactive'] as $status): ?>
												<option value="<?= $status ?>" <?php if($status == $gogetssl['status']): echo('selected'); endif; ?>><?= ucfirst($status) ?></option>
											<?php endforeach ?>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="mb-2">
										<label class="form-label"><?= $this->ui->text('callback_text') ?></label>
										<input type="text" class="form-control" placeholder="..." value="<?= base_url('c/gogetssl?callback_version=v1') ?>" readonly>
									</div>
								</div>
								<div class="col-md-12">
									<div class="mb-2">
										<input type="submit" name="submit" class="btn btn-primary" value="<?= $this->ui->text('update_settings_btn_text') ?>">
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>