<div class="hold-transition login-page">
	<div class="login-box">
		<div class="login-logo">
			<a href="<?= base_url('n') ?>"><?= $this->site->get(['title']) ?></a>
		</div>
		<div class="card">
			<div class="card-body login-card-body">
				<p class="login-box-msg"><?= $this->ui->text('login_header_text') ?></p>
				<?= form_open('f/login') ?>
					<div class="mb-3">
						<label class="form-label text-muted"><?= $this->ui->text('email_text') ?></label>
						<div class="input-group">
							<input type="email" name="email" class="form-control" placeholder="<?= $this->ui->text('email_placeholder_text') ?>">
							<div class="input-group-append">
								<div class="input-group-text">
									<span class="fa fa-envelope"></span>
								</div>
							</div>
						</div>
					</div>
					<div class="mb-3">
						<label class="form-label text-muted"><?= $this->ui->text('password_text') ?></label>
						<div class="input-group">
							<input type="password" name="password" class="form-control" placeholder="<?= $this->ui->text('password_placeholder_text') ?>">
							<div class="input-group-append">
								<div class="input-group-text">
									<span class="fa fa-lock"></span>
								</div>
							</div>
						</div>
					</div>
					<?= $this->captcha->captcha() ?>
					<div class="mb-3">
						<div class="row">
							<div class="col-8">
								<div class="icheck-primary">
									<input type="checkbox" id="remember" name="remember">
									<label for="remember">
										<?= $this->ui->text('remember_me_text') ?>
									</label>
								</div>
							</div>
							<div class="col-4">
								<input type="submit" name="submit" class="btn btn-primary btn-block" value="<?= $this->ui->text('login_btn_text') ?>">
							</div>
						</div>
					</div>
				</form>
				<p class="mb-1">
					<a href="<?= base_url('f/forget') ?>"><?= $this->ui->text('forget_password_page_text') ?></a>
				</p>
				<p class="mb-1">
					<a href="<?= base_url('f/register') ?>"><?= $this->ui->text('create_account_page_text') ?></a>
				</p>
			</div>
		</div>
	</div>
</div>