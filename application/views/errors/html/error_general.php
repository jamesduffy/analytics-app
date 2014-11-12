<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>Error - Analytics-App</title>
	<link rel="icon" type="image/png" ref="<?=base_url()?>favicon.png">
	<meta name="description" content="">

	<!-- Le styles -->
	<link href="<?=base_url()?>css/styles.css?version=20140404" rel="stylesheet">
</head>

<body>

	<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
		<div class="container">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				
				<a class="navbar-brand" href="<?=base_url()?>">
					<span class="glyphicon glyphicon-stats"></span>
					Analytics-App.net
				</a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse">

				<div class="navbar-form navbar-right">
					<div class="form-group">
	        			<a href="/" class="btn btn-default no-border">Return to homepage</a>
					</div>
				</div>

			</div><!-- /.navbar-collapse -->
		</div>
	</nav>

	<div class="container">

		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<img src="/img/tbservers.png" style="display: block; margin-left: auto; margin-right: auto; width: 800px;">
					<h1><?php echo $heading; ?></h1>
					<?php echo $message; ?>
				</div>
			</div>
		</div><!-- /.row -->

	</div><!-- /.container -->

	<footer class="container">
		<div class="row">
			<div class="col-md-12">
				<p>
					<?=anchor('http://cafeduff.com', 'CafeDuff')?> &copy; <?=date('Y')?>
				</p>
			
				<p>
		            <?=anchor('pages/about', 'About')?> &middot;
		            <?=anchor('blog/year-in-review-2013', 'Blog')?> &middot;
		            <?=anchor('pages/donate', 'Donate')?> <br>
		            <?=anchor('pages/terms', 'Terms of Service')?> &middot;
		            <?=anchor('pages/privacy', 'Privacy Statement')?>
		          </p>
			</div>
		</div>
	</footer>

	<!-- Le javascript
	================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script src="<?=base_url()?>js/jquery.js"></script>
	<script src="<?=base_url()?>js/bootstrap.min.js"></script>

	<script src="<?=base_url()?>js/site.js"></script>

	<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	ga('create', 'UA-23906018-3', 'analytics-app.net');
	ga('send', 'pageview');
	</script>

</body>
</html>