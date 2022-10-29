<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Database - Installation</title>
	<link rel="stylesheet" type="text/css" href="<?= $this->base_url.'public/default/assets/css/all.min.css' ?>">
	<link rel="stylesheet" type="text/css" href="<?= $this->base_url.'public/default/assets/css/icheck-bootstrap.min.css' ?>">
	<link rel="stylesheet" type="text/css" href="<?= $this->base_url.'public/default/assets/css/adminlte.min.css' ?>">
	<link rel="stylesheet" type="text/css" href="<?= $this->base_url.'public/default/assets/css/style.css' ?>">
	<link rel="icon" type="image/png" href="<?= $this->base_url.'public/default/assets/img/fav.png' ?>">
</head>
<body class="hold-transition login-page">
	<div class="login-box">
		<div class="card mb-2">
			<div class="card-header">
				<h3 class="card-title">Database Set-up</h3>
			</div>
			<div class="card-body">
				<form action="<?= $this->base_url.'index.php/i/step2' ?>" method="POST">
					<div class="mb-2">
						<label class="form-label">Hostname</label>
						<input type="text" name="hostname" class="form-control" value="localhost" required>
					</div>
					<div class="mb-2">
						<label class="form-label">Username</label>
						<input type="text" name="username" class="form-control" value="username" required>
					</div>
					<div class="mb-2">
						<label class="form-label">Password</label>
						<input type="text" name="password" class="form-control" value="password">
					</div>
					<div class="mb-2">
						<label class="form-label">Database</label>
						<input type="text" name="database" class="form-control" value="database" required>
					</div>
					<div class="mb-2">
						<label class="form-label">Prefix</label>
						<input type="text" name="prefix" class="form-control" value="nx_" required>
					</div>
					<?php if ($this->security->get_csrf_hash() !== NULL): ?>
						<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
					<?php endif ?>
					<div class="mb-2">
						<input type="submit" name="submit" class="btn btn-block btn-primary" value="Next">
					</div>
				</form>
			</div>
		</div>
	</div>
	<div style="position: fixed; bottom: 0; right: 0; padding: 5px; max-width: 320px;" id="hidden-area">
		<?php if($this->session->has_userdata('msg')): ?>
			<?php 
				$data = json_decode($this->session->userdata('msg'), true);
				$class = 'danger';
				if($data[0] === 1)
				{
					$class = 'success';
				}
				$message = $data[1];
			?>
			<div class="alert callout callout-<?= $class ?> alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<span style="padding-right: 1rem;"><?= $message ?></span>
			</div>
			<?php $this->session->unset_userdata('msg'); ?>
		<?php endif; ?>
	</div>
	<script type="text/javascript" src="<?= $this->base_url.'public/default/assets/js/jquery.min.js' ?>"></script>
	<script type="text/javascript" src="<?= $this->base_url.'public/default/assets/js/bootstrap.bundle.min.js' ?>"></script>
	<script type="text/javascript" src="<?= $this->base_url.'public/default/assets/js/adminlte.min.js' ?>"></script>
</body>
</html>