<div class="hold-transition login-page">
	<h1 class="display-1">
		401
	</h1>
	<p class="text-muted px-5 text-center">
		<?= $this->ui->text('error_401_text') ?> <a href="<?= base_url('f/logout') ?>"><?= $this->ui->text('logout_btn_text') ?></a> or <a href="<?= base_url('p/error_401?resend=true') ?>"><?= $this->ui->text('resend_email_btn_text') ?></a>
	</p>
</div>