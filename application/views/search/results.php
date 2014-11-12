<div id="search-results" class="container">

	<div class="row">
		<div class="col-md-12">
			<div class="inner box">
				<div class="page-header">
					<h2>Results for "<?=set_value('query')?>"</h2>
				</div>

				<ul class="media-list">
				<?php foreach ($search_results as $result): ?>
					<li class="media box">
						<a class="pull-left" href="<?=site_url('profile/'.$result['username'])?>">
							<img class="media-object" src="<?=$result['avatar_image']['url']?>" width="60" alt="...">
						</a>

						<div class="media-body">
							<h4 class="media-heading">
								<?=anchor('profile/'.$result['username'], $result['username'])?>
							</h4>

							<?php if(isset($user['description']['html'])): ?>
							 	<?=$result['description']['html']?>
							 <?php endif; ?>

						</div>
					</li>
				<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div><!-- /.row-fluid -->

</div><!-- /.container -->