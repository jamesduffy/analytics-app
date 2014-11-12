<div class="list-group">
	<a
		class="list-group-item <?php if($current_page == 'stats'): ?>active<?php endif; ?>"
		href="<?=site_url('profile/'.$this->uri->segment(2))?>">
		About
	</a>
	<a
		class="list-group-item <?php if($current_page == 'recent_posts'): ?>active<?php endif; ?>"
		href="<?=site_url('profile/'.$this->uri->segment(2).'/recent')?>">
		Recent Posts
	</a>
	<a
		class="list-group-item <?php if($current_page == 'popular'): ?>active<?php endif; ?>"
		href="<?=site_url('profile/'.$this->uri->segment(2).'/popular')?>">
		Recently Popular Posts
	</a>
	<a
		class="list-group-item <?php if($current_page == 'followers'): ?>active<?php endif; ?>"
		href="<?=site_url('profile/'.$this->uri->segment(2).'/followers')?>">
		Follower History
	</a>
</div>