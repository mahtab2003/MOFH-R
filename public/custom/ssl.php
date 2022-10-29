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
					<li class="breadcrumb-item active"><?= $this->ui->text('list_link_text') ?></li>
				</ol>
			</div>
		</div>
	</div>
</div>
<div class="content">
	<div class="container-xl">
		<div class="card mb-2">
			<div class="card-header">
				<h3 class="card-title"><?= $this->ui->text('ssl_text') ?></h3>
			</div>
			<div class="card-body p-0 table-responsive">
				<table class="table">
					<thead>
						<tr>
							<th width="5%"><?= $this->ui->text('id_tab_text') ?></th>
							<th width="20%"><?= $this->ui->text('provider_tab_text') ?></th>
							<th width="65%"><?= $this->ui->text('domain_tab_text') ?></th>
							<th width="10%"><?= $this->ui->text('action_tab_text') ?></th>
						</tr>
					</thead>
					<tbody>
						<?php if (is_array($list) AND count($list) > 0): ?>
							<?php $start = $this->input->get('page') ?? 0 ?>
							<?php $start *= 5 ?>
							<?php $end = $start; ?>
							<?php 
								for($i = $start; $i < $start + 5; $i++)
								{
									if(isset($list[$i]))
									{
										$end += 1;
									}
								}
							?>
							<?php for($i = $start; $i < $end; $i++){ ?>
								<tr>
									<td class="text-truncate"><?= $i + 1 ?></td>
									<td class="text-truncate"><?= $this->ui->text($list[$i]['provider'].'_text') ?></td>
									<td class="text-truncate"><?= $list[$i]['domain'] ?></td>
									</td>
									<td class="text-truncate">
										<a href="<?= base_url('s/view_ssl/'.$list[$i]['key']) ?>" class="btn btn-primary btn-sm"><?= $this->ui->text('manage_btn_text') ?></a>
									</td>
								</tr>
							<?php } ?>
						<?php else: ?>
							<tr>
								<td colspan="5" class="text-center"><?= $this->ui->text('nothing_found_text') ?></td>
							</tr>
						<?php endif ?>
					</tbody>
				</table>
			</div>
			<div class="card-footer">
				<div class="d-flex justify-content-between align-items-center">
					<div><?= $this->ui->text('showing_text') ?> <?= $start ?? 0 ?> <?= $this->ui->text('to_text') ?> <?= $end ?? 0 ?> <?= $this->ui->text('of_text') ?> <?= count($list) ?> <?= $this->ui->text('entites_text') ?></div>
					<ul class="pagination pagination-sm float-right m-0">
						<?php $page = $this->input->get('page') ?? 0 ?>
						<?php $i = count($list); ?>
						<?php $i = $i / 5; ?>
						<?php $i = number_format($i, 1); ?>
						<?php $i = explode('.', $i)[0] ?>
						<?php
						if ($page > 0):
							$page1 = $page - 1;
							$link1 = base_url('s/ssl?page='.$page1);
							$status1 = '';
						else:
							$link1 = '#';
							$status1 = 'disabled';
						endif;
						if($page < $i):
							$page2 = $page + 1;
							$link2 = base_url('s/ssl?page='.$page2);
							$status2 = '';
						else:
							$link2 = '#';
							$status2 = 'disabled';
						endif;
						?>
						<li class="page-item <?= $status1 ?>">
							<a href="<?= $link1 ?>" class="page-link"><</a>
						</li>
						<li class="page-item <?= $status2 ?>">
							<a href="<?= $link2 ?>" class="page-link">></a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>