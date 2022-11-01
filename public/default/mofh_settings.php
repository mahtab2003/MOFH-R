<div class="content-header">
	<div class="container-xl">
		<div class="row">
			<div class="col-sm-6">
				<h1 class="m-0"><?= $this->ui->text($title) ?></h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="<?= base_url('n') ?>"><?= $this->ui->text('home_link_text') ?></a></li>
					<li class="breadcrumb-item"><a href="<?= base_url('n/mofh_settings') ?>"><?= $this->ui->text('mofh_link_text') ?></a></li>
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
				<h3 class="card-title"><?= $this->ui->text('mofh_text') ?></h3>
			</div>
			<div class="card-body">
				<?= form_open('n/mofh_settings?mofh=true') ?>
					<div class="row">
						<?php $mofh = $this->mofh->get(['username', 'password', 'cpanel_url', 'ns_1', 'ns_2', 'plan']); ?>
						<div class="col-md-6 mb-2">
							<label class="form-label"><?= $this->ui->text('username_text') ?></label>
							<input type="text" name="username" class="form-control" placeholder="..." value="<?= $mofh['username'] ?>">
						</div>
						<div class="col-md-6 mb-2">
							<label class="form-label"><?= $this->ui->text('password_text') ?></label>
							<input type="text" name="password" class="form-control" placeholder="..." value="<?= $mofh['password'] ?>">
						</div>
						<div class="col-md-6 mb-2">
							<label class="form-label"><?= $this->ui->text('cpanel_url_text') ?></label>
							<input type="text" name="cpanel_url" class="form-control" placeholder="..." value="<?= $mofh['cpanel_url'] ?>">
						</div>
						<div class="col-md-6 mb-2">
							<label class="form-label"><?= $this->ui->text('plan_text') ?></label>
							<input type="text" name="plan" class="form-control" placeholder="..." value="<?= $mofh['plan'] ?>">
						</div>
						<div class="col-md-6 mb-2">
							<label class="form-label"><?= $this->ui->text('ns_1_text') ?></label>
							<input type="text" name="ns_1" class="form-control" placeholder="..." value="<?= $mofh['ns_1'] ?>">
						</div>
						<div class="col-md-6 mb-2">
							<label class="form-label"><?= $this->ui->text('ns_2_text') ?></label>
							<input type="text" name="ns_2" class="form-control" placeholder="..." value="<?= $mofh['ns_2'] ?>">
						</div>
						<div class="col-md-6 mb-2">
							<label class="form-label"><?= $this->ui->text('shared_ip_text') ?></label>
							<input type="text" class="form-control" placeholder="..." value="<?= gethostbyname($_SERVER['HTTP_HOST']) ?>" readonly>
						</div>
						<div class="col-md-6 mb-2">
							<label class="form-label"><?= $this->ui->text('callback_text') ?></label>
							<input type="text" class="form-control" placeholder="..." value="<?= base_url('c/mofh') ?>" readonly>
						</div>
						<div class="col-md-12 mb-2">
							<input type="submit" name="submit" class="btn btn-primary" value="<?= $this->ui->text('update_settings_btn_text') ?>" readonly>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="card mb-2">
			<div class="card-header">
				<h3 class="card-title"><?= $this->ui->text('domain_ext_text') ?></h3>
			</div>
			<div class="card-body pb-0">
				<?= form_open('n/mofh_settings?domain=true') ?>
					<div class="form-group">
						<label class="form-label"><?= $this->ui->text('domain_text') ?></label>
						<div class="input-group">
							<div class="custom-file">
								<input type="text" name="domain" class="form-control" placeholder="...">
							</div>
							<div class="input-group-append">
								<input type="submit" name="submit" class="btn btn-primary" value="<?= $this->ui->text('create_btn_text') ?>" readonly>
							</div>
						</div>
					</div>
              	</form>
			</div>
			<div class="table-responsive">
				<table class="table mb-0">
					<tr>
						<th width="5%"><?= $this->ui->text('id_tab_text') ?></th>
						<th width="85%"><?= $this->ui->text('domain_tab_text') ?></th>
						<th width="10%"><?= $this->ui->text('action_tab_text') ?></th>
					</tr>
					<?php $domains = $this->mofh_ext->get(); ?>
					<?php if (count($domains) > 0): ?>
						<?php for($i = 0; $i < count($domains); $i++){ ?>
							<tr>
								<td><?= $i + 1 ?></td>
								<td><?= $domains[$i]['domain'] ?></td>
								<td>
									<a href="<?= base_url('n/mofh_settings?delete=true&domain='.$domains[$i]['domain']) ?>" class="btn btn-danger btn-sm"><?= $this->ui->text('delete_btn_text') ?></a>
								</td>
							</tr>
						<?php } ?>
					<?php else: ?>
						<tr>
							<td class="text-center" colspan="3"><?= $this->ui->text('nothing_found_text') ?></td>
						</tr>
					<?php endif ?>
				</table>
			</div>
		</div>
	</div>
</div>