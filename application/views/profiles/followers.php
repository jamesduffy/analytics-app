<div class="container">

	<?php $this->load->view('profiles/header', $user); ?>

    <div class="row">

        <div class="col-md-4">
        	<?php $this->load->view('profiles/sidebar'); ?>
		</div>

        <div class="col-md-8">
			<table class="table box table-hover">
			
				<thead>
					<tr>
						<th>Date</th>
						<th>Follows</th>
						<th>Unfollows</th>
					</tr>
				</thead>
				
				<tbody>
				
				<?php foreach($follower_history->result() as $row): ?>
				<tr>
					<td><?=$row->day?></td>
					<td><?=$row->follows?></td>
					<td><?=$row->unfollows?></td>
				</tr>
				<?php endforeach; ?>

				</tbody>

			</table>
        </div>

    </div><!-- /.row -->

</div><!-- /.container -->