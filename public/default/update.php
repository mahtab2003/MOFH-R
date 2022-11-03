<div class="hold-transition login-page">
	<div class="login-box">
		<div class="login-logo">
			<?= $this->ui->text($title) ?>
		</div>
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">
					<?= $this->ui->text('changelogs_text') ?>
				</h3>
			</div>
			<div class="card-body login-card-body">
				<ul class="mb-0">
					<?php for($i = 0; $i < count($list); $i++){ ?>
						<li><?= $list[$i] ?></li>
					<?php } ?>
				</ul>
				<hr class="">
				<div class="text-center">
					<a href="?update=true" class="btn btn-primary"><?= $this->ui->text('update_btn_text') ?></a>
				</div>
			</div>
		</div>
	</div>
</div>