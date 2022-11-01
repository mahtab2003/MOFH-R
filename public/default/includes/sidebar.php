<aside class="main-sidebar sidebar-dark-primary elevation-4">
	<a href="" class="brand-link">
		<img src="<?= base_url('public/default/assets/img/logo.png') ?>" class="brand-image img-circle elevation-3" style="opacity: .8">
		<span class="brand-text font-weight-light"><?= $this->site->get(['title']) ?></span>
	</a>
	<div class="sidebar">
		<div class="user-panel mt-3 pb-3 mb-3 d-flex">
			<div class="image">
				<img src="<?= base_url('public/default/assets/img/avatar.png') ?>" class="rounded elevation-2" alt="User Image">
			</div>
			<div class="info">
				<a href="<?= base_url('n/settings') ?>" class="d-block"><?= $this->user->logged_data(['name']) ?></a>
			</div>
		</div>
		<nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
				<?php if (get_cookie('role') !== 'user'): ?>
					<li class="nav-item">
						<a href="#" class="nav-link">
							<i class="nav-icon fas fa-globe"></i>
							<p>
								<?= $this->ui->text('admin_nav_text') ?>
								<i class="right fas fa-angle-left"></i>
							</p>
						</a>
						<ul class="nav nav-treeview">
							<?php if (get_cookie('role') === 'admin' OR get_cookie('role') === 'root'): ?>
								<li class="nav-item">
									<a href="<?= base_url('n/xdashboard') ?>" class="nav-link">
										<i class="far fa-circle nav-icon"></i>
										<?= $this->ui->text('xdashboard_nav_text') ?>
									</a>
								</li>
							<?php endif ?>
							<?php if (get_cookie('role') === 'admin' OR get_cookie('role') === 'root'): ?>
								<li class="nav-item">
									<a href="<?= base_url('n/clients') ?>" class="nav-link">
										<i class="far fa-circle nav-icon"></i>
										<?= $this->ui->text('clients_nav_text') ?>
									</a>
								</li>
							<?php endif ?>
							<?php if (get_cookie('role') === 'admin' OR get_cookie('role') === 'root'): ?>
								<li class="nav-item">
									<a href="<?= base_url('h/all_accounts') ?>" class="nav-link">
										<i class="far fa-circle nav-icon"></i>
										<?= $this->ui->text('account_nav_text') ?>
									</a>
								</li>
							<?php endif ?>
							<?php if (get_cookie('role') === 'admin' OR get_cookie('role') === 'root'): ?>
								<li class="nav-item">
									<a href="<?= base_url('s/all_ssl') ?>" class="nav-link">
										<i class="far fa-circle nav-icon"></i>
										<?= $this->ui->text('ssl_nav_text') ?>
									</a>
								</li>
							<?php endif ?>
							<li class="nav-item">
								<a href="<?= base_url('t/all_tickets') ?>" class="nav-link">
									<i class="far fa-circle nav-icon"></i>
									<?= $this->ui->text('support_nav_text') ?>
								</a>
							</li>
							<?php if (get_cookie('role') === 'root'): ?>
								<li class="nav-item">
									<a href="<?= base_url('n/site_settings') ?>" class="nav-link">
										<i class="far fa-circle nav-icon"></i>
										<?= $this->ui->text('site_settings_nav_text') ?>
									</a>
								</li>
							<?php endif ?>
						</ul>
					</li>
				<?php endif; ?>
				<li class="nav-item">
					<a href="<?= base_url('n/dashboard') ?>" class="nav-link">
						<i class="nav-icon fa fa-home"></i>
						<p>
							<?= $this->ui->text('dashboard_nav_text') ?>
						</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="nav-icon fa fa-server"></i>
						<p>
							<?= $this->ui->text('hosting_nav_text') ?>
							<i class="right fas fa-angle-left"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="<?= base_url('h/create_account') ?>" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<?= $this->ui->text('create_account_nav_text') ?>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?= base_url('h/accounts') ?>" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<?= $this->ui->text('account_list_nav_text') ?>
							</a>
						</li>
					</ul>
				</li>
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="nav-icon fa fa-shield-alt"></i>
						<p>
							<?= $this->ui->text('ssl_nav_text') ?>
							<i class="right fas fa-angle-left"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="<?= base_url('s/create_ssl') ?>" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<?= $this->ui->text('create_ssl_nav_text') ?>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?= base_url('s/ssl') ?>" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<?= $this->ui->text('ssl_list_nav_text') ?>
							</a>
						</li>
					</ul>
				</li>
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-question-circle"></i>
						<p>
							<?= $this->ui->text('need_help_nav_text') ?>
							<i class="right fas fa-angle-left"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="<?= base_url('t/create_ticket') ?>" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<?= $this->ui->text('create_ticket_nav_text') ?>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?= base_url('t/tickets') ?>" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<?= $this->ui->text('ticket_list_nav_text') ?>
							</a>
						</li>
					</ul>
				</li>
				<li class="nav-item">
					<a href="<?= base_url('n/settings') ?>" class="nav-link">
						<i class="nav-icon fa fa-cog"></i>
						<p>
							<?= $this->ui->text('settings_nav_text') ?>
						</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="<?= base_url('f/logout') ?>" class="nav-link">
						<i class="nav-icon fa fa-arrow-circle-left"></i>
						<p>
							<?= $this->ui->text('logout_nav_text') ?>
						</p>
					</a>
				</li>
			</ul>
		</nav>
	</div>
</aside>
<div class="content-wrapper">