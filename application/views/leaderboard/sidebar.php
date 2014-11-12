<div class="list-group">	

	<a
		class="list-group-item <?php if($current_page == 'overview'): ?>active<?php endif; ?>"
		href="<?=site_url('leaderboard/'.$this->uri->segment(2))?>">
		<span class="glyphicon glyphicon-stats"></span> Overview
	</a>

	<a
		class="list-group-item <?php if($current_page == 'top_posts'): ?>active<?php endif; ?>"
		href="<?=site_url('leaderboard/'.$this->uri->segment(2).'/posts')?>">
		<span class="glyphicon glyphicon-list"></span> Top Posts
	</a>

	<a
		class="list-group-item <?php if($current_page == 'top_followed'): ?>active<?php endif; ?>"
		href="<?=site_url('leaderboard/'.$this->uri->segment(2).'/top-followed')?>">
		<span class="glyphicon glyphicon-user"></span> Top Followed
	</a>

	<a
		class="list-group-item <?php if($current_page == 'top_clients'): ?>active<?php endif; ?>"
		href="<?=site_url('leaderboard/'.$this->uri->segment(2).'/top-clients')?>">
		<span class="glyphicon glyphicon-phone"></span> Top Clients
	</a>

</div>

<div class="box">
	<h4 class="text-center">Time Machine</h4>
	<hr>
	<select name="date-picker" onchange="location = this.options[this.selectedIndex].value;" style="width:100%;">
		<?php foreach($days_query->result() AS $date): ?>
			<option value="<?=base_url('leaderboard/'.$date->date)?>"
				<?php if( $date->date == $this->uri->segment(2) ): ?>selected="selected"<?php endif; ?>
				><?=$date->date?></option>
		<?php endforeach; ?>
	</select>
</div>