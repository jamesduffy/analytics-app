<div class="list-group">	

	<a
		class="list-group-item <?php if($current_page == 'about'): ?>active<?php endif; ?>"
		href="<?=site_url('pages/about')?>">
		About
	</a>

	<a 
		class="list-group-item"
		href="<?=site_url('blog/year-in-review-2013')?>">
		Year In Review
	</a>

	<a
		class="list-group-item <?php if($current_page == 'donate'): ?>active<?php endif; ?>"
		href="<?=site_url('pages/donate')?>">
		Donate
	</a>

	<a
		class="list-group-item <?php if($current_page == 'privacy'): ?>active<?php endif; ?>"
		href="<?=site_url('pages/privacy')?>">
		Privacy Statement
	</a>

	<a
		class="list-group-item <?php if($current_page == 'terms'): ?>active<?php endif; ?>"
		href="<?=site_url('pages/terms')?>">
		Terms of Service
	</a>


</div>
