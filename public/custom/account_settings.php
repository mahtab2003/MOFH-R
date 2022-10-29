<div class="content-header">
	<div class="container-xl">
		<div class="row">
			<div class="col-sm-6">
				<h1 class="m-0"><?= $this->ui->text($title) ?></h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="<?= base_url('n') ?>"><?= $this->ui->text('home_link_text') ?></a></li>
					<li class="breadcrumb-item"><a href="<?= base_url('h') ?>"><?= $this->ui->text('account_link_text') ?></a></li>
					<li class="breadcrumb-item active"><?= $this->ui->text('settings_link_text') ?></li>
				</ol>
			</div>
		</div>
	</div>
</div>
<div class="content">
	<div class="container-xl">
		<div class="card mb-2">
			<div class="card-header">
				<h3 class="card-title"><i class="fa fa-cog mr-2"></i><?= $this->ui->text('general_settings_text') ?></h3>
			</div>
			<div class="card-body">
				<?= form_open('h/account_settings/'.$info['username'].'?general=true') ?>
					<div class="mb-2">
						<label class="form-label"><?= $this->ui->text('label_text') ?></label>
						<input type="text" name="label" class="form-control" placeholder="..." value="<?= $info['label'] ?>" required>
					</div>
					<div class="mb-2">
						<input type="submit" name="submit" class="btn btn-primary" placeholder="..." value="<?= $this->ui->text('update_settings_btn_text') ?>">
					</div>
				</form>
			</div>
		</div>
		<div class="card mb-2">
			<div class="card-header">
				<h3 class="card-title"><i class="fa fa-shield-alt mr-2"></i><?= $this->ui->text('security_settings_text') ?></h3>
			</div>
			<div class="card-body">
				<?= form_open('h/account_settings/'.$info['username'].'?security=true') ?>
					<div class="mb-2">
						<label class="form-label"><?= $this->ui->text('password_text') ?></label>
						<input type="password" name="password" class="form-control" placeholder="..." value="" required>
					</div>
					<div class="mb-2">
						<label class="form-label"><?= $this->ui->text('old_password_text') ?></label>
						<input type="password" name="old_password" class="form-control" placeholder="..." value="" required>
					</div>
					<div class="mb-2">
						<input type="submit" name="submit" class="btn btn-primary" placeholder="..." value="<?= $this->ui->text('update_settings_btn_text') ?>">
					</div>
				</form>
			</div>
		</div>
		<div class="card mb-2">
			<div class="card-header">
				<h3 class="card-title"><i class="fa fa-server mr-2"></i><?= $this->ui->text('preference_settings_text') ?></h3>
			</div>
			<div class="card-body">
				<?= form_open('h/account_settings/'.$info['username'].'?pref=true') ?>
					<div class="mb-2">
						<label class="form-label"><?= $this->ui->text('reason_text') ?></label>
						<textarea name="reason" class="form-control" placeholder="..." value="" required></textarea>
					</div>
					<div class="mb-2">
						<input type="submit" name="submit" class="btn btn-primary" placeholder="..." value="<?= $this->ui->text('deactivate_btn_text') ?>">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>