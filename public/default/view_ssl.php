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
					<li class="breadcrumb-item active"><?= $this->ui->text('view_link_text') ?></li>
				</ol>
			</div>
		</div>
	</div>
</div>
<div class="content">
	<div class="container-xl">
		<div class="card mb-2">
			<div class="card-header">
				<h3 class="card-title"><?= $this->ui->text('view_ssl_text') ?></h3>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-6">
						<div class="d-flex justify-content-between align-items-center">
							<span><?= $this->ui->text('provider_text') ?>:</span>
							<span><?= $this->ui->text($info['provider'].'_text') ?></span>
						</div>
					</div>
					<div class="col-6">
						<div class="d-flex justify-content-between align-items-center">
							<span><?= $this->ui->text('status_text') ?>:</span>
							<span><?= $this->ui->text($info['status'].'_bdg_text') ?></span>
						</div>
					</div>
					<div class="col-6">
						<div class="d-flex justify-content-between align-items-center">
							<span><?= $this->ui->text('start_date_text') ?>:</span>
							<span><?= str_replace('-', '.', $info['start_date']) ?></span>
						</div>
					</div>
					<div class="col-6">
						<div class="d-flex justify-content-between align-items-center">
							<span><?= $this->ui->text('end_date_text') ?>:</span>
							<span><?= str_replace('-', '.', $info['end_date']) ?></span>
						</div>
					</div>
				</div>
			</div>
			<div class="card-footer m-0">
				<div class="d-flex justify-content-between align-items-center">
					<span><?= $this->ui->text('domain_text') ?>:</span>
					<span><?= $info['domain'] ?></span>
				</div>
			</div>
		</div>
		<div class="card mb-2">
			<div class="card-header">
				<?php if ($info['status'] === 'active'): ?>
					<?= $this->ui->text('certificate_text') ?>
				<?php else: ?>
					<?= $this->ui->text('verification_required_text') ?>
				<?php endif ?>
			</div>
			<div class="card-body">
				<?php if ($info['status'] === 'active'): ?>
					<div class="mb-2">
						<label class="form-label"><?= $this->ui->text('csr_text') ?>:</label>
						<textarea class="form-control" readonly="true" style="min-height: 250px"><?= trim($info['csr']) ?></textarea>
					</div>
					<div class="mb-2">
						<label class="form-label"><?= $this->ui->text('privkey_text') ?>:</label>
						<textarea class="form-control" readonly="true" style="min-height: 250px"><?= trim($info['privkey']) ?></textarea>
					</div>
					<div class="mb-2">
						<label class="form-label"><?= $this->ui->text('crt_text') ?>:</label>
						<textarea class="form-control" readonly="true" style="min-height: 250px"><?= trim($info['crt']) ?></textarea>
					</div>
				<?php else: ?>
					<div class="mb-2">
						<label class="form-label"><?= $this->ui->text('csr_text') ?>:</label>
						<textarea class="form-control" readonly="true" style="min-height: 250px"><?= trim($info['csr']) ?></textarea>
					</div>
					<div class="mb-2">
						<label class="form-label"><?= $this->ui->text('record_text') ?>:</label>
						<?php $record = explode(' ', $info['dns']) ?>
						<input type="text" class="form-control mb-2" value="<?= trim($record[0]) ?>" readonly="true">
						<label class="form-label"><?= $this->ui->text('content_text') ?>:</label>
						<input type="text" class="form-control" value="<?= trim($record[2]) ?>" readonly="true">
					</div>
				<?php endif ?>
			</div>
		</div>
	</div>
</div>