<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?= $this->ui->text($title) ?> - <?= $this->site->get(['title']) ?></title>
	<link rel="stylesheet" type="text/css" href="<?= base_url('public/default/assets/css/all.min.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('public/default/assets/css/icheck-bootstrap.min.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('public/default/assets/css/adminlte.min.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('public/default/assets/css/style.css') ?>">
	<link rel="icon" type="image/png" href="<?= base_url('public/default/assets/img/fav.png') ?>">
</head>
<body class="<?= get_cookie('theme') ?>-mode hold-transition sidebar-mini layout-fixed">