<div class="content-header">
	<div class="container-xl">
		<div class="row">
			<div class="col-sm-6">
				<h1 class="m-0"><?= $this->ui->text($title) ?></h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="<?= base_url('n') ?>"><?= $this->ui->text('home_link_text') ?></a></li>
					<li class="breadcrumb-item"><a href="<?= base_url('t') ?>"><?= $this->ui->text('support_link_text') ?></a></li>
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
				<h3 class="card-title"><i class="fa fa-info-circle"></i> <?= $this->ui->text('ticket_information_text') ?></h3>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<div class="d-flex justify-content-between align-items-center">
							<span><?= $this->ui->text('status_text') ?>:</span>
							<span><?= $this->ui->text($info[0]['status'].'_bdg_text') ?></span>
						</div>
					</div>
					<div class="col-md-6">
						<div class="d-flex justify-content-between align-items-center">
							<span><?= $this->ui->text('open_at_text') ?>:</span>
							<span><?= date('d.m.Y', $info[0]['date']) ?></span>
						</div>
					</div>
					<div class="col-md-6">
						<div class="d-flex justify-content-between align-items-center">
							<span><?= $this->ui->text('open_by_text') ?>:</span>
							<span><?= $this->user->get(['name'], ['key' => $info[0]['for']])[0]['name'] ?></span>
						</div>
					</div>
					<div class="col-md-6">
						<div class="d-flex justify-content-between align-items-center">
							<span><?= $this->ui->text('replies_text') ?>:</span>
							<span><?= count($list) ?></span>
						</div>
					</div>
				</div>
			</div>
			<div class="card-footer">
				<div class="d-flex justify-content-between align-items-center">
					<span><?= $this->ui->text('subject_text') ?>:</span>
					<span><?= $info[0]['subject'] ?></span>
				</div>
			</div>
		</div>
		<div class="card mb-2">
			<div class="card-header">
				<div class="d-flex justify-content-between align-items-center">
					<span><?= $this->user->get(['name'], ['key' => $info[0]['for']])[0]['name'] ?></span>
					<span><?= date('d M h:i A', $info[0]['date']) ?></span>
				</div>
			</div>
			<div class="card-body reply">
				<?= $info[0]['content'] ?>
			</div>
		</div>
		<?php if (count($list) > 0): ?>
			<?php for ($i = count($list); $i > 0; $i--){ ?>
				<div class="card mb-2">
					<div class="card-header">
						<div class="d-flex justify-content-between align-items-center">
							<span><?= $this->user->get(['name'], ['key' => $list[$i-1]['for']])[0]['name'] ?></span>
							<span><?= date('d M h:i A', $list[$i-1]['date']) ?></span>
						</div>
					</div>
					<div class="card-body reply">
						<?= $list[$i-1]['content'] ?>
					</div>
				</div>
			<?php } ?>
		<?php else: ?>
			<div class="card card-body mb-2">
				<?= $this->ui->text('no_reply_text') ?>
			</div>
		<?php endif ?>
		<?php if ($info[0]['status'] !== 'closed'): ?>
			<div class="card">
				<div class="card-header">
					<div class="card-title"><?= $this->ui->text('follow_up_text') ?></div>
				</div>
				<div class="card-body">
					<?= form_open('t/view_ticket/'.$info[0]['key']) ?>
						<div class="mb-2">
							<textarea id="editor" class="form-control" name="content" placeholder="<?= $this->ui->text('content_text') ?>"></textarea>
						</div>
						<?= $this->captcha->captcha() ?>
						<div class="mb-2">
							<input type="submit" name="submit" class="btn btn-primary" value="<?= $this->ui->text('submit_reply_btn_text') ?>">
							<a class="btn btn-danger" href="<?= base_url('t/view_ticket/'.$info[0]['key'].'?close=true') ?>"><?= $this->ui->text('close_btn_text') ?></a>
						</div>
					</form>
				</div>
			</div>
		<?php else: ?>
			<div class="card card-body d-block">
				<?= $this->ui->text('ticket_closed_box_text') ?> <a href="<?= base_url('t/view_ticket/'.$info[0]['key'].'?reopen=true') ?>"><?= $this->ui->text('reopen_text') ?></a>
			</div>
		<?php endif ?>
	</div>
</div>
<script type="text/javascript" src="<?= base_url('public/'.$this->ui->template_dir().'/assets/ckeditor/ckeditor.js') ?>"></script>
<script type="text/javascript">
	ClassicEditor
		.create( document.querySelector( '#editor' ), {
			 toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'save' ]
		} )
		.then( editor => {
			window.editor = editor;
		} )
		.catch( err => {
			console.error( err.stack );
		} );
</script>