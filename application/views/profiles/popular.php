<div class="container">

	<?php $this->load->view('profiles/header', $user); ?>

    <div class="row">

        <div class="col-md-4">
        	<?php $this->load->view('profiles/sidebar'); ?>
		</div>

        <div class="col-md-8">
            <div class="inner box">
                <?php if( $popular_posts->num_rows() ): ?>
                    <ul class="media-list">
                        <?php foreach ($popular_posts->result() as $post): ?>
                            <?php $this->load->view('layouts/post', $post); ?>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="alert alert-block alert-info">
                        <h4>Oh Snap!</h4>
                        <?=$user['username']?> does not have any recently popular posts.
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div><!-- /.row -->

</div><!-- /.container -->