<div class="container">

	<?php $this->load->view('profiles/header', $user); ?>

    <div class="row">

        <div class="col-md-4">
        	<?php $this->load->view('profiles/sidebar'); ?>
		</div>

        <div class="col-md-8">
            <div class="inner box">
                <ul class="media-list">
                    <?php foreach ($recent_posts as $post): ?>
                        <?php $this->load->view('layouts/post', $post); ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

    </div><!-- /.row -->

</div><!-- /.container -->