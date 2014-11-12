<div id="profile-stats" class="container">

	<?php $this->load->view('profiles/header', $user); ?>

    <div class="row">

        <div class="col-md-4">
        	<?php $this->load->view('profiles/sidebar'); ?>
		</div>

        <div class="col-md-8">
            <div class="inner box">
                <div id="totals" class="row">
                    <div class="col-md-4">
                        <div class="stat">
                        <h4>Total Posts</h4>
                        <p class="number"><?=number_format($total_posts)?></p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="stat">
                        <h4>Follower/Following</h4>
                        <p class="number"><?=$follower_to_following?></p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="stat">
                            <h4>Follower Trend</h4>
                            <p class="number">
                                <?php if($follower_trend > 0): ?><span class="text-success">+<?php endif; ?> 
                                <?php if($follower_trend < 0): ?><span class="text-warning"><?php endif; ?> 

                                <?=number_format($follower_trend)?>
                                </span>
                            </p>
                        </div>
                    </div>
                </div> 
            </div> 

            <?php if(isset($user['description']['html'])): ?>
            <div class="inner box">
                <div id="about" class="row">    
                    <div class="col-md-12">
                        <h2>About</h2>

                        <p><?=$user['description']['html']?></p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if( $most_popular_post ): ?>
            <div class="inner box">
                <div id="most_popular_post" class="row">
                    <div class="col-md-12">
                        <h2>Top Post This Week</h2>

                        <ul class="media-list">
                            <?php foreach ($most_popular_post as $post): ?>
                                <?php $this->load->view('layouts/post', $post); ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        </div>

    </div><!-- /.row -->

</div><!-- /.container -->
