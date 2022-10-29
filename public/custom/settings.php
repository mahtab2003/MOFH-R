<div class="content-header">
	<div class="container-xl">
		<div class="row">
			<div class="col-sm-6">
				<h1 class="m-0"><?= $this->ui->text($title) ?></h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="<?= base_url('n') ?>"><?= $this->ui->text('home_link_text') ?></a></li>
					<li class="breadcrumb-item"><a href="<?= base_url('n/settings') ?>"><?= $this->ui->text('user_link_text') ?></a></li>
					<li class="breadcrumb-item active"><?= $this->ui->text('settings_link_text') ?></li>
				</ol>
			</div>
		</div>
	</div>
</div>
<div class="content">
	<div class="container-xl">
		<div class="row">
			<div class="col-md-6">
				<div class="card mb-2">
					<div class="card-header">
						<h1 class="card-title"><i class="fa fa-cog mr-2"></i><?= $this->ui->text('general_settings_text') ?></h1>
					</div>
					<div class="card-body">
						<?= form_open('n/settings?general=true') ?>
							<div class="mb-2">
								<label class="form-label"><?= $this->ui->text('name_text') ?></label>
								<input type="text" name="name" class="form-control" placeholder="<?= $this->ui->text('name_placeholder_text') ?>" value="<?= $this->user->logged_data(['name']) ?>" required>
							</div>
							<div class="mb-3">
								<label class="form-label"><?= $this->ui->text('email_text') ?></label>
								<input type="email" class="form-control" placeholder="<?= $this->ui->text('email_placeholder_text') ?>" value="<?= $this->user->logged_data(['email']) ?>" readonly>
							</div>
							<div class="mb-0">
								<input type="submit" name="submit" class="btn btn-primary" value="<?= $this->ui->text('update_settings_btn_text') ?>">
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="card mb-2">
					<div class="card-header">
						<h1 class="card-title"><i class="fa fa-desktop mr-2"></i><?= $this->ui->text('preference_settings_text') ?></h1>
					</div>
					<div class="card-body">
						<?= form_open('n/settings?pref=true') ?>
							<div class="mb-2">
								<label class="form-label"><?= $this->ui->text('theme_text') ?></label>
								<select class="form-control" name="theme">
									<?php foreach (['light', 'dark'] as $theme): ?>
										<option value="<?= $theme ?>" <?php if($theme == get_cookie('theme')): echo('selected'); endif; ?>><?= ucfirst($theme) ?></option>
									<?php endforeach ?>
								</select>
							</div>
							<div class="mb-3">
								<label class="form-label"><?= $this->ui->text('language_text') ?></label>
								<select class="form-control" name="language">
									<?php foreach ($this->ui->get_langs() as $lang): ?>
										<option value="<?= $lang['code'] ?>" <?php if($lang['code'] == get_cookie('lang')): echo('selected'); endif; ?>><?= $lang['name'] ?></option>
									<?php endforeach ?>
								</select>
							</div>
							<div class="mb-0">
								<input type="submit" name="submit" class="btn btn-primary" value="<?= $this->ui->text('update_preference_btn_text') ?>">
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="card mb-2">
					<div class="card-header">
						<h1 class="card-title"><i class="fa fa-shield-alt mr-2"></i><?= $this->ui->text('security_settings_text') ?></h1>
					</div>
					<div class="card-body">
						<?= form_open('n/settings?security=true') ?>
							<div class="mb-2">
								<label class="form-label"><?= $this->ui->text('password_text') ?></label>
								<input type="password" name="password" class="form-control" placeholder="<?= $this->ui->text('password_placeholder_text') ?>" required>
							</div>
							<div class="mb-3">
								<label class="form-label"><?= $this->ui->text('confirm_password_text') ?></label>
								<input type="password" name="confirm_password" class="form-control" placeholder="<?= $this->ui->text('password_placeholder_text') ?>" required>
							</div>
							<div class="mb-0">
								<input type="submit" name="submit" class="btn btn-primary" value="<?= $this->ui->text('update_password_btn_text') ?>">
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="card mb-2">
					<div class="card-header">
						<h1 class="card-title"><i class="fa fa-key mr-2"></i><?= $this->ui->text('2fa_settings_text') ?></h1>
					</div>
					<div class="card-body">
						<?= form_open('n/settings?2fa=true') ?>
							<div class="mb-2">
								<label class="form-label"><?= $this->ui->text('2fa_text') ?></label>
								<input type="text" name="2fa" class="form-control" placeholder="<?= $this->ui->text('password_placeholder_text') ?>" value="<?= $this->user->logged_data(['2fa_key']) ?>" readonly>
							</div>
							<div class="mb-3">
								<label class="form-label"><?= $this->ui->text('status_text') ?></label>
								<select class="form-control" name="status">
									<?php foreach (['active', 'inactive'] as $status): ?>
										<option value="<?= $status ?>" <?php if($status == $this->user->logged_data(['2fa_status'])): echo('selected'); endif; ?>><?= ucfirst($status) ?></option>
									<?php endforeach ?>
								</select>
							</div>
							<div class="mb-0">
								<input type="submit" name="submit" class="btn btn-primary" value="<?= $this->ui->text('update_settings_btn_text') ?>">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>