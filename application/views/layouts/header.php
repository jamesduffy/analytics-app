<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title><?=$page_title?> - Analytics</title>
		<link rel="icon" type="image/png" ref="<?=base_url()?>favicon.png">
		<meta name="description" content="">

		<!-- Le styles -->
		<link href="<?=base_url()?>css/styles.css?version=20140402" rel="stylesheet">
	</head>

	<body>

		<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
			<div class="container">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>

					<a class="navbar-brand" href="<?=base_url()?>">
						<span class="glyphicon glyphicon-stats"></span>
						App.net Analytics
					</a>
				</div>

				<!-- Collect the nav links, forms, and other content for toggling -->
				<div id="navbar-collapse" class="collapse navbar-collapse">
					<?php if($this->session->userdata('access_token')): ?>
						<ul class="nav navbar-nav">
							<?php if(ENVIRONMENT == 'development'): ?>
							<li <?php if($page_title == 'Dashboard'): ?>class="active"<?php endif; ?> >
								<?=anchor('dashboard', ' <i class="fa fa-dashboard"></i> Dashboard')?>
							</li>
							<?php endif; ?>
							<li <?php if($this->session->userdata('username') == $this->uri->segment(2)): ?>class="active"<?php endif; ?> >
								<?=anchor('profile/'.$this->session->userdata('username'), '<i class="fa fa-user"></i> Profile')?>
							</li>
							<?php if(ENVIRONMENT == 'development'): ?>
							<li <?php if($page_title == 'Discover'): ?>class="active"<?php endif; ?> >
								<?=anchor('discover', '<i class="fa fa-bolt"></i> Discover')?>
							</li>
							<?php endif; ?>
							<li <?php if($page_title == 'Leaderboard'): ?>class="active"<?php endif; ?> >
								<?=anchor('leaderboard', '<i class="fa fa-bar-chart-o"></i> Leaderboard')?>
							</li>
						</ul>

						<ul class="nav navbar-nav navbar-right">
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><img class="" src="<?=$this->session->userdata('avatar_url')?>" width="34" > <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li><?=anchor('settings/logout', '<span class="glyphicon glyphicon-log-out"></span> Logout')?></li>
								</ul>
							</li>
						</ul>

					<?php else: ?>

						<div class="navbar-form navbar-right">
							<div class="form-group">
								<?php $authenticate_url = $this->appdotnet->getAuthUrl( $this->config->item('base_url'), $this->config->item('scopes') ) ?>
			        			<a href="<?=$authenticate_url?>" class="btn btn-default no-border">Authorize with App.net</a>
							</div>
						</div>

					<?php endif; ?>
				</div><!-- /.navbar-collapse -->
			</div>
		</nav>
