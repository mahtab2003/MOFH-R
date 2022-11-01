<div class="content-header">
	<div class="container-xl">
		<div class="row">
			<div class="col-sm-6">
				<h1 class="m-0"><?= $this->ui->text($title) ?></h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="<?= base_url('n') ?>"><?= $this->ui->text('home_link_text') ?></a></li>
					<li class="breadcrumb-item"><a href="<?= base_url('n') ?>"><?= $this->ui->text('user_link_text') ?></a></li>
					<li class="breadcrumb-item active"><?= $this->ui->text('dashboard_link_text') ?></li>
				</ol>
			</div>
		</div>
	</div>
</div>
<div class="content">
	<div class="container-xl">
		<div class="row">
			<div class="col-sm-6 col-md-3">
				<div class="info-box shadow-sm">
					<span class="info-box-icon bg-purple">
						<i class="fa fa-users"></i>
					</span>
					<div class="info-box-content">
						<span class="info-box-text"><?= $this->ui->text('client_text') ?></span>
						<span class="info-box-text">
							<?php if ($this->user->get([], ['role' => 'user'], [['role' => 'support'], ['role' => 'admin']]) !== false): ?>
								<?= count($this->user->get([], ['role' => 'user'], [['role' => 'support'], ['role' => 'admin']])) ?> 
							<?php else: ?>
								0
							<?php endif ?>
							<?= $this->ui->text('in_total_text') ?>
						</span>
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-3">
				<div class="info-box shadow-sm">
					<span class="info-box-icon bg-primary">
						<i class="fa fa-server"></i>
					</span>
					<div class="info-box-content">
						<span class="info-box-text"><?= $this->ui->text('accounts_text') ?></span>
						<span class="info-box-text"><?= count($this->hosting->get(['id'], ['status' => 'active'])) ?> <?= $this->ui->text('in_total_text') ?></span>
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-3">
				<div class="info-box shadow-sm">
					<span class="info-box-icon bg-success">
						<i class="fa fa-shield-alt"></i>
					</span>
					<div class="info-box-content">
						<span class="info-box-text"><?= $this->ui->text('ssl_text') ?></span>
						<span class="info-box-text"><?= count($this->ssl->get(['id'])) ?> <?= $this->ui->text('in_total_text') ?></span>
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-3">
				<div class="info-box shadow-sm">
					<span class="info-box-icon bg-warning">
						<i class="fa fa-question-circle"></i>
					</span>
					<div class="info-box-content">
						<span class="info-box-text"><?= $this->ui->text('ticket_text') ?></span>
						<span class="info-box-text"><?= count($this->ticket->get('ticket', ['id'], ['status' => 'open'], [['status' => 'customer']])) ?> <?= $this->ui->text('in_total_text') ?></span>
					</div>
				</div>
			</div>
		</div>
		<div class="card mb-2">
			<div class="card-header">
				<h3 class="card-title"><?= $this->ui->text('quick_setup_text') ?></h3>
			</div>
			<table class="table mb-0">
				<?php for ($i = 1; $i < 4; $i++){ ?>
					<tr>
						<td><?= $this->ui->text('setup_'.$i.'_text') ?></td>
					</tr>
				<?php } ?>
			</table>
		</div>
	</div>
</div>