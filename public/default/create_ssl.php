<div class="content-header">
	<div class="container-xl">
		<div class="row">
			<div class="col-sm-6">
				<h1 class="m-0"><?= $this->ui->text($title) ?></h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="<?= base_url('n') ?>"><?= $this->ui->text('home_link_text') ?></a></li>
					<li class="breadcrumb-item"><a href="<?= base_url('s') ?>"><?= $this->ui->text('ssl_link_text') ?></a></li>
					<li class="breadcrumb-item active"><?= $this->ui->text('create_link_text') ?></li>
				</ol>
			</div>
		</div>
	</div>
</div>
<div class="content">
	<div class="container-xl">
		<div class="card mb-2">
			<div class="card-header">
				<h3 class="card-title"><?= $this->ui->text('create_ssl_text') ?></h3>
			</div>
			<div class="card-body">
				<?= form_open('s/create_ssl') ?>
					<div class="mb-2">
						<label class="form-label"><?= $this->ui->text('domain_text') ?></label>
						<input type="text" name="domain" class="form-control" placeholder="<?= $this->ui->text('domain_text') ?>">
					</div>
					<div class="mb-2">
						<label class="form-label"><?= $this->ui->text('provider_text') ?></label>
						<select name="provider" class="form-control">
							<option value="selfsigned"><?= $this->ui->text('selfsigned_text') ?></option>
							<?php if ($this->gogetssl->get(['status'])['status'] === 'active'): ?>
								<option value="gogetssl"><?= $this->ui->text('gogetssl_text') ?></option>
							<?php endif ?>
						</select>
					</div>
					<?= $this->captcha->captcha() ?>
					<div class="mb-2">
						<input type="submit" name="submit" class="btn btn-primary" value="<?= $this->ui->text('request_btn_text') ?>">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>