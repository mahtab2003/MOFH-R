<div class="hold-transition login-page">
	<div class="login-box text-center">
		<h1 class="display-1">
			<i class="fa fa-sync"></i>
		</h1>
		<p class="mb-2"><?= $this->ui->text('cpanel_login_text') ?></p>
		<form id="form" action="<?= $this->mofh->get(['cpanel_url']) ?>/login.php" name="login" method="POST">
			<input type="hidden" name="uname" value="<?= $info['username'] ?>" alt="username">
			<input type="hidden" name="passwd" value="<?= $info['password'] ?>" alt="password">
			<input type="submit" name="submit" class="btn btn-primary" value="<?= $this->ui->text('redirect_btn_text') ?>" id="btn">
		</form>
	</div>
</div>
<script type="text/javascript">
	document.getElementById('btn').click();
</script>