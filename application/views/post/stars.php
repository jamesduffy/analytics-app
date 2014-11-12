<div id="profile-stats" class="container">

    <div class="row">

        <div class="col-md-8 col-md-offset-2">
            <div class="inner box">
                <ul class="media-list">
                    <?php $this->load->view('layouts/post', $posts); ?>
                </ul>

                <h3>Recently Favorited By</h3>
                <ul class="media-list">
                    <?php foreach($stars as $user): ?>
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