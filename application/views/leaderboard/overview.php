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

			<div class="row">

				<div id="totals" class="row">
	                <div class="col-md-4">
	                    <div class="stat first">
	                    	<h4>Total Posts</h4>
	                    	<p class="number">
	                    		<?=number_format($leaderboard_data['total_posts'])?><br>
							</p>
							<p style="font-style:italic">
								~<?=round($leaderboard_data['total_posts'] / $discovered_users, 2)?>
								per discovered user
	                    	</p>
	                    </div>
	                </div>

	                <div class="col-md-4">
	                    <div class="stat">
	                    	<h4>Unique Users</h4>
	                    	<p class="number">
	                    		<?=number_format($leaderboard_data['total_users'])?>
							</p>
							<p style="font-style:italic">
								~<?=round($leaderboard_data['total_users'] / $discovered_users, 2)?>% of discovered users
	                    	</p>
	                    </div>
	                </div>

	                <div class="col-md-4">
	                    <div class="stat">
	                        <h4>Unique Clients</h4>
	                        <p class="number">
	                            <?=number_format($leaderboard_data['total_clients'])?>
	                        </p>
							<p style="font-style:italic">
								~<?=round($leaderboard_data['total_clients'] / $discovered_clients, 2)?>% of discovered clients
	                    	</p>
	                    </div>
	                </div>
	            </div>

			</div>	

			<div class="row">
				<div class="col-md-12">
					<h4>Post Volume</h4>
					<table class="table table-hover">
						<tr>
							<th>Hour</th>
							<th>Total Posts</th>
						</tr>
						<?php foreach($leaderboard_data['post_volume'] AS $row): ?>
							<tr>
								<td><?=$row['hour']?></td>
								<td><?=number_format($row['total_posts'])?></td>
							</tr>
						<?php endforeach; ?>
					</table>
				</div>
			</div>

    	</div>
    </div>
  </div><!-- /.row -->

</div><!-- /.container -->