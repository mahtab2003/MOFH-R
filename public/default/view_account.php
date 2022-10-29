<div class="content-header">
	<div class="container-xl">
		<div class="row">
			<div class="col-sm-6">
				<h1 class="m-0"><?= $this->ui->text($title) ?>(<?= $info['label'] ?>)</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="<?= base_url('n') ?>"><?= $this->ui->text('home_link_text') ?></a></li>
					<li class="breadcrumb-item"><a href="<?= base_url('h') ?>"><?= $this->ui->text('account_link_text') ?></a></li>
					<li class="breadcrumb-item active"><?= $this->ui->text('view_link_text') ?></li>
				</ol>
			</div>
		</div>
	</div>
</div>
<div class="content">
	<div class="container-xl">
		<?php if ($info['status'] === 'active' AND $info['time'] + 14400 > time()): ?>
			<div class="callout callout-success">
				<?= $this->ui->text('account_note_text') ?>
			</div>
		<?php elseif($info['status'] !== 'active'): ?>
			<div class="callout callout-<?php if ($info['status'] === 'processing'): ?>warning <?php else: ?>danger<?php endif ?>">
				<?= $this->ui->text('account_'.$info['status'].'_text') ?>
			</div>
		<?php endif ?>
		<div class="card mb-2">
			<div class="card-header">
				<h3 class="card-title"><?= $this->ui->text('account_action_text') ?></h3>
			</div>
			<div class="card-body px-3 py-1">
				<div class="row">
					<div class="col-md-4 px-2 py-2">
						<a href="<?= base_url('h/view_account/'.$info['username'].'?cpanel_redirect=true') ?>" class="btn d-block btn-success <?php if ($info['status'] !== 'active'): ?> disabled<?php endif ?>"><?= $this->ui->text('cpanel_btn_text') ?></a>
					</div>
					<div class="col-md-4 px-2 py-2">
						<a href="https://filemanager.ai/new/#/c/ftpupload.net/<?= $info['username'] ?>/<?= base64_encode(json_encode(['t' => 'ftp', 'c' => ['v' => 1, 'p' => $info['password'], 'i' => '/htdocs/']])) ?>" class="btn d-block btn-warning <?php if ($info['status'] !== 'active'): ?> disabled<?php endif ?>"><?= $this->ui->text('file_manager_btn_text') ?></a>
					</div>
					<div class="col-md-4 px-2 py-2">
						<?php if ($info['status'] === 'deactivated'): ?>
							<a href="<?= base_url('h/view_account/'.$info['username'].'?reactivate=true') ?>" class="btn d-block btn-danger"><?= $this->ui->text('reactivate_btn_text') ?></a>
						<?php elseif ($info['status'] === 'suspended' AND get_cookie('role') === 'admin' OR $info['status'] === 'suspended' AND get_cookie('role') === 'root'): ?>
							<a href="<?= base_url('h/view_account/'.$info['username'].'?reactivate=true') ?>" class="btn d-block btn-danger"><?= $this->ui->text('reactivate_btn_text') ?></a>
						<?php else: ?>
							<a href="<?= base_url('h/account_settings/'.$info['username']) ?>" class="btn d-block btn-primary <?php if ($info['status'] !== 'active'): ?> disabled<?php endif ?>"><?= $this->ui->text('settings_btn_text') ?></a>
						<?php endif ?>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="card mb-2">
					<div class="card-header">
						<h3 class="card-title"><?= $this->ui->text('view_account_text') ?></h3>
					</div>
					<table class="table card-table mb-0">
						<tr>
							<td width="50%"><?= $this->ui->text('username_text') ?>: </td>
							<td width="50%">
								<?php if ($info['status'] === 'active'): ?>
									<?= $info['username'] ?>
								<?php else: ?>
									<?= $this->ui->text('loading_text') ?>
								<?php endif ?>
							</td>
						</tr>
						<tr>
							<td><?= $this->ui->text('password_text') ?>: </td>
							<td class="d-flex justify-content-between">
								<?php $length = strlen($info['password']) ?>
								<?php $placeholder = str_repeat('*', $length) ?>
								<code id="passwordHide1" class=""><?= $placeholder ?></code>
								<code id="passwordShow1" class="d-none">
									<?php if ($info['status'] === 'active'): ?>
										<?= $info['password'] ?>
									<?php else: ?>
										<?= $placeholder ?>
									<?php endif ?>
								</code>
								<a class="btn btn-outline-primary btn-sm rounded trigger" data-hide="passwordHide1" data-show="passwordShow1">
									<?= $this->ui->text('show_hide_btn_text') ?>
								</a>
							</td>
						</tr>
						<tr>
							<td><?= $this->ui->text('status_text') ?>: </td>
							<td>
								<?php if ($info['status'] === 'active'): ?>
									<?= $this->ui->text($info['status'].'_bdg_text') ?>
								<?php else: ?>
									<?= $this->ui->text('loading_text') ?>
								<?php endif ?>
							</td>
						</tr>
						<tr>
							<td><?= $this->ui->text('main_domain_text') ?>: </td>
							<td>
								<?php if ($info['status'] === 'active'): ?>
									<?= $info['domain'] ?>
								<?php else: ?>
									<?= $this->ui->text('loading_text') ?>
								<?php endif ?>
							</td>
						</tr>
							<td><?= $this->ui->text('created_on_text') ?>: </td>
							<td>
								<?php if ($info['status'] === 'active'): ?>
									<?= date('d.m.Y', $info['time']) ?>
								<?php else: ?>
									<?= $this->ui->text('loading_text') ?>
								<?php endif ?>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="col-md-6">
				<div class="card mb-2">
					<div class="card-header">
						<h3 class="card-title"><?= $this->ui->text('additional_information_text') ?></h3>
					</div>
					<?php $cpanel_url = $this->mofh->get(['cpanel_url']); ?>
					<?php $cpanel_url = str_replace('https://', '', $cpanel_url); ?>
					<?php $cpanel_url = str_replace('http://', '', $cpanel_url); ?>
					<table class="table card-table mb-0">
						<tr>
							<td width="50%"><?= $this->ui->text('website_ip_text') ?>:</td>
							<td width="50%">
								<?php if ($info['status'] === 'active'): ?>
									<?= gethostbyname($info['main']) ?>
								<?php else: ?>
									<?= $this->ui->text('loading_text') ?>
								<?php endif ?>
							</td>
						</tr>
						<tr>
							<td><?= $this->ui->text('mysql_hostname_text') ?>: </td>
							<td>
								<?php if ($info['status'] === 'active'): ?>
									<?= str_replace('cpanel', $info['sql'], $cpanel_url) ?>
								<?php else: ?>
									<?= $this->ui->text('loading_text') ?>
								<?php endif ?>
							</td>
						</tr>
						<tr>
							<td><?= $this->ui->text('mysql_port_text') ?>:</td>
							<td>
								<?php if ($info['status'] === 'active'): ?>
									3306
								<?php else: ?>
									<?= $this->ui->text('loading_text') ?>
								<?php endif ?>
							</td>
						</tr>
						<tr>
							<td><?= $this->ui->text('ftp_hostname_text') ?>: </td>
							<td>
								<?php if ($info['status'] === 'active'): ?>
									<?= str_replace('cpanel', 'ftp', $cpanel_url) ?>
								<?php else: ?>
									<?= $this->ui->text('loading_text') ?>
								<?php endif ?>
							</td>
						</tr>
						<tr>
							<td><?= $this->ui->text('ftp_port_text') ?>:</td>
							<td>
								<?php if ($info['status'] === 'active'): ?>
									21
								<?php else: ?>
									<?= $this->ui->text('loading_text') ?>
								<?php endif ?>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<div class="card mb-2">
			<div class="card-header">
				<h3 class="card-title"><?= $this->ui->text('linked_domains_text') ?></h3>
			</div>
			<table class="table mb-0 card-table">
				<tr>
					<th width="5%"><?= $this->ui->text('id_tab_text') ?></th>
					<th width="90%"><?= $this->ui->text('domain_tab_text') ?></th>
					<th width="5%"><?= $this->ui->text('action_tab_text') ?></th>
				</tr>
				<?php $domains = $this->hosting->domains($info['username']) ?>
				<?php if (count($domains) > 0): ?>
					<?php for ($i = 0; $i < count($domains); $i++) { ?>
						<tr>
							<td><?= $i + 1 ?></td>
							<td><?= $domains[$i]['domain'] ?></td>
							<td><a href="<?= $domains[$i]['file_manager'] ?>" class="btn btn-sm btn-success btn-square" target="_blank"><i class="fa fa-upload"></i></a></td>
						</tr>
					<?php } ?>
				<?php else: ?>
					<tr>
						<td colspan="3" class="text-center"><?= $this->ui->text('nothing_found_text') ?></td>
					</tr>
				<?php endif ?>
			</table>
		</div>
	</div>
</div>
<script type="text/javascript">
	var coll = document.getElementsByClassName("trigger");
	var i;
	for (i = 0; i < coll.length; i++) {
		coll[i].addEventListener("click", function() {
			var hide = this.getAttribute("data-hide");
			var show = this.getAttribute("data-show");
			
			show = document.getElementById(show);
			hide = document.getElementById(hide);
			
			show.classList.toggle('d-none');
			hide.classList.toggle('d-none');
		});
	}
</script>