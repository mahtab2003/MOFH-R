<div class="content-header">
	<div class="container-xl">
		<div class="row">
			<div class="col-sm-6">
				<h1 class="m-0"><?= $this->ui->text($title) ?></h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="<?= base_url('n') ?>"><?= $this->ui->text('home_link_text') ?></a></li>
					<li class="breadcrumb-item"><a href="<?= base_url('n/emails') ?>"><?= $this->ui->text('email_link_text') ?></a></li>
					<li class="breadcrumb-item active"><?= $this->ui->text('edit_link_text') ?></li>
				</ol>
			</div>
		</div>
	</div>
</div>
<div class="content">
	<div class="container-xl">
		<div class="card mb-2">
			<div class="card-header">
				<h3 class="card-title"><?= $this->ui->text('edit_email_text') ?></h3>
			</div>
			<div class="card-body">
				<?= form_open('n/edit_email/'.$info['id']) ?>
					<div class="mb-2">
						<label class="form-label"><?= $this->ui->text('subject_text') ?></label>
						<input type="text" name="subject" class="form-control" value="<?= $info['subject'] ?>">
					</div>
					<div class="mb-2">
						<label class="form-label"><?= $this->ui->text('content_text') ?></label>
						<textarea name="content" class="form-control" style="min-height: 250px;"><?= $info['content'] ?></textarea>
					</div>
					<div class="mb-2">
						<label class="form-label"><?= $this->ui->text('docs_text') ?></label>
						<textarea class="form-control" readonly="true"><?= $info['docs'] ?></textarea>
					</div>
					<div class="mb-2">
						<input type="submit" name="submit" class="btn btn-primary" value="<?= $this->ui->text('update_settings_btn_text') ?>">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>