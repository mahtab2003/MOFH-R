<div class="hold-transition login-page">
	<div class="login-box">
		<div class="login-logo">
			<?= $this->ui->text($title) ?>
		</div>
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">
					<?= $this->ui->text('backups_text') ?>
				</h3>
			</div>
			<div class="card-body login-card-body">
				<?= form_open('p/restore') ?>
					<select class="form-control" name="file">
						<?php for($i = 0; $i < count($list); $i++){ ?>
							<option value="<?= $list[$i]['file'] ?>"><?= $list[$i]['name'] ?></option>
						<?php } ?>
					</select>
					<hr class="">
					<div class="text-center">
						<input type="submit" name="submit" class="btn btn-primary" value="<?= $this->ui->text('restore_btn_text') ?>">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>