<div class="content-header">
	<div class="container-xl">
		<div class="row">
			<div class="col-sm-6">
				<h1 class="m-0"><?= $this->ui->text($title) ?></h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="<?= base_url('n') ?>"><?= $this->ui->text('home_link_text') ?></a></li>
					<li class="breadcrumb-item"><a href="<?= base_url('h') ?>"><?= $this->ui->text('account_link_text') ?></a></li>
					<li class="breadcrumb-item active"><?= $this->ui->text('create_link_text') ?></li>
				</ol>
			</div>
		</div>
	</div>
</div>
<div class="content">
	<div class="container-xl">
		<div class="card mb-2">
			<!--<div class="card-header">
				<h3 class="card-title"><?= $this->ui->text('check_domain_text') ?></h3>
			</div>-->
			<ul class="nav nav-tabs" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" id="domain-tab" data-toggle="pill" href="#domain-content" role="tab" aria-controls="domain-content" aria-selected="true"><?= $this->ui->text('domain_text') ?></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="subdomain-tab" data-toggle="pill" href="#subdomain-content" role="tab" aria-controls="subdomain-content" aria-selected="true"><?= $this->ui->text('subdomain_text') ?></a>
				</li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane fade active show" id="domain-content" aria-labelledby="domain-tab" role="tabpanel">
					<div class="form-group p-3">
						<label class="form-label"><?= $this->ui->text('domain_text') ?></label>
						<div class="input-group">
							<div class="custom-file">
								<input id="domain" type="text"class="form-control" placeholder="...">
							</div>
							<div class="input-group-append">
								<input type="submit" id="verify" class="btn btn-primary" value="<?= $this->ui->text('check_btn_text') ?>" readonly>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="subdomain-content" aria-labelledby="subdomain-tab" role="tabpanel">
					<div class="form-group p-3">
						<label class="form-label"><?= $this->ui->text('domain_text') ?></label>
						<div class="input-group">
							<div class="custom-file">
								<input id="subdomain" type="text" class="form-control" placeholder="...">
							</div>
							<div class="input-group-append">
								<select class="form-control" id="extension">
									<?php foreach ($this->mofh_ext->get() as $value): ?>
										<option value="<?= $value['domain'] ?>"><?= $value['domain'] ?></option>
									<?php endforeach ?>
								</select>
							</div>
							<div class="input-group-append">
								<input type="submit" id="verify1" class="btn btn-primary" value="<?= $this->ui->text('check_btn_text') ?>" readonly>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="card mb-2 d-none" id="config">
			<div class="card-header">
				<h3 class="card-title"><?= $this->ui->text('account_config_text') ?></h3>
			</div>
			<div class="p-3 px-4">
				<?= form_open('h/create_account?create=true') ?>
					<div class="mb-2">
						<label class="form-label"><?= $this->ui->text('domain_text') ?></label>
						<input type="text" name="domain" id="val" class="form-control" placeholder="..." required="true" readonly="true">
					</div>
					<div class="mb-2">
						<label class="form-label"><?= $this->ui->text('label_text') ?></label>
						<input type="text" name="label" class="form-control" placeholder="...">
					</div>
					<?= $this->captcha->captcha() ?>
					<div class="mb-2">
						<input type="submit" name="submit" class="btn btn-primary" placeholder="..." value="<?= $this->ui->text('create_account_btn_text') ?>">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="<?= base_url('public/default/assets/js/jquery.min.js') ?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('#verify1').click(function(){
			var domain = $('#subdomain').val();
			var extensions = $('#extension').val();
			var validomain = domain + extensions;
			$.post('<?= base_url('h/create_account?check=true') ?>', {domain : validomain, submit: "submit"}, function(data){
				if(validomain != data){
					$('#hidden-area').html('<div class="alert callout callout callout-danger" role="alert"><button class="close" data-dismiss="alert" type="button" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+data+'</div>');
						$('#config').addClass('d-none');
				}
				else{
					$('#hidden-area').html('<div class="alert callout callout callout-success" role="alert"><button class="close" data-dismiss="alert" type="button" aria-label="Close"><span aria-hidden="true">&times;</span></button>Domain is available and selected successfully.</div>');
					$('#val').val(data);
					$('#config').removeClass('d-none');
				}
			});
		});
		$('#verify').click(function(){
			var domain = $('#domain').val();
			$.post('<?= base_url('h/create_account?check=true') ?>', {domain : domain, submit: "submit"}, function(data){
				if(domain != data){
					$('#hidden-area').html('<div class="alert callout callout callout-danger" role="alert"><button class="close" data-dismiss="alert" type="button" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+data+'</div>');
						$('#config').addClass('d-none');
				}
				else{
					$('#hidden-area').html('<div class="alert callout callout callout-success" role="alert"><button class="close" data-dismiss="alert" type="button" aria-label="Close"><span aria-hidden="true">&times;</span></button>Domain is available and selected successfully.</div>');
					$('#val').val(data);
					$('#config').removeClass('d-none');
				}
			});
		});
	});
</script>