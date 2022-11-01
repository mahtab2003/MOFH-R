	</div>
	<div style="position: fixed; bottom: 0; right: 0; padding: 5px; max-width: 320px;" id="hidden-area">
		<?php if($this->session->has_userdata('msg')): ?>
			<?php 
				$data = json_decode($this->session->userdata('msg'), true);
				$class = 'danger';
				if($data[0] === 1)
				{
					$class = 'success';
				}
				$message = $data[1];
			?>
			<div class="alert callout callout-<?= $class ?> alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<span style="padding-right: 1rem;"><?= $message ?></span>
			</div>
			<?php $this->session->unset_userdata('msg'); ?>
		<?php endif; ?>
	</div>
	<script type="text/javascript" src="<?= base_url('public/default/assets/js/jquery.min.js') ?>"></script>
	<script type="text/javascript" src="<?= base_url('public/default/assets/js/bootstrap.bundle.min.js') ?>"></script>
	<script type="text/javascript" src="<?= base_url('public/default/assets/js/adminlte.min.js') ?>"></script>
</body>
</html>