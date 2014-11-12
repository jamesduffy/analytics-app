<div id="leaderboard" class="container">

  <div class="row">
  	<div class="col-md-3">
  		<?php $this->load->view('leaderboard/sidebar', $sidebar); ?>
  	</div>

    <div class="col-md-9">
    	<div class="inner box">
    		<div class="row">
	    	  	<div class="col-md-12">
					<h2 class="page-header">
						<?php
						switch ($sidebar['current_page']) {
							case 'overview':
								print 'Overview<br>';
								break;

							case 'top_posts':
								print 'Top Posts<br>';
								break;

							case 'top_conversations':
								print 'Top Conversations<br>';
								break;

							case 'top_followed':
								print 'Top Followed<br>';
								break;

							case 'top_clients':
								print 'Top Clients<br>';
								break;
							
						}

						print date('l, F jS, Y', strtotime($this->uri->segment(2)));

						?>
					</h2>
				</div>
			</div>
		
			<ul class="media-list">
				<?php foreach($top_followed as $result): ?>
				<?php $user = $this->people->getUser($result['follows_user_id']); ?>
				<li class="media">
					<a class="pull-left" href="<?=site_url('profile/'.$user['username'])?>">
						<img class="media-object" src="<?=$user['avatar_image']['url']?>" width="60" alt="...">
					</a>
					
					<div class="media-body">
						<h4 class="media-heading">
							<?=anchor('profile/'.$user['username'], $user['username'])?>
						</h4>
						
						 <?php if(isset($user['description']['html'])): ?>
						 	<?=$user['description']['html']?>
						 <?php endif; ?>
					</div>
				</li>
				<?php endforeach; ?>
			</ul>
    	</div>
    </div>
  </div><!-- /.row -->

</div><!-- /.container -->