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
	    		<div class="col-md-12">

	    			<table class="table table-hover">
					
						<thead>
							<tr>
								<th>Client Name</th>
								<th>Total Posts</th>
							</tr>
						</thead>
						
						<tbody>
						
						<?php foreach($top_clients as $row): ?>
						<tr>
							<td><?=anchor($row['source_link'], $row['source_name'])?></td>
							<td><?=number_format($row['total_posts'])?></td>
						</tr>
						<?php endforeach; ?>

						</tbody>

					</table>
	    		</div>
	    	</div>
    	</div>    
    </div>
  </div><!-- /.row -->

</div><!-- /.container -->