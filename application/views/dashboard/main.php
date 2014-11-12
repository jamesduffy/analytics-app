<div id="dashboard" class="container" style="margin-top:15px;">

    <div class="row">

        <div class="col-md-3">
            <div class="inner" data-spy="affix" data-offset-top="0" data-offset-bottom="200" style="width:225px;">
                <div class="list-group">
                    <a href="<?=site_url('dashboard')?>" class="list-group-item active">
                        <span class="glyphicon glyphicon-home"></span> Stream
                    </a>
                    <a href="<?=site_url('dashboard/mentions')?>" class="list-group-item"><span class="glyphicon glyphicon-share-alt"></span> Mentions</a>
                    <a href="<?=site_url('dashboard/favorites')?>" class="list-group-item"><span class="glyphicon glyphicon-star"></span> Favorites</a>
                    <a href="<?=site_url('dashboard/reposts')?>" class="list-group-item"><span class="glyphicon glyphicon-random"></span> Reposts</a>
                </div>

                <div class="list-group">
                    <a href="#" class="list-group-item"><span class="glyphicon glyphicon-comment"></span> Conversations</a>
                    <a href="#" class="list-group-item"><span class="glyphicon glyphicon-picture"></span> Photos</a>
                    <a href="#" class="list-group-item"><span class="glyphicon glyphicon-fire"></span> Trending</a>
                    <a href="#" class="list-group-item"><span class="glyphicon glyphicon-globe"></span> Checkins</a>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="inner box light-bg">
                <div class="media-list">
                    <div class="media media-form">
                        <div class="pull-left no-border" href="">
                            <img class="media-object" src="<?=$this->session->userdata('avatar_url')?>" height="50" width="50">
                        </div>

                        <div class="media-body">
                            <form id="compose-post" role="form">
                                <div class="form-group">
                                    <textarea id="postBody" class="form-control" rows="3" placeholder="What's going on?"></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        <span id="characters-remaining" data-characters-remaining="256">256</span>
                                    </div>
                                    <div class="col-md-2 col-md-offset-8">
                                        <button id="submit-form" type="button" class="btn btn-default pull-right">
                                            Share
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <ul class="media-list post-stream box">
                    <?php foreach ($stream as $post): ?>
                        <?php $this->load->view('layouts/post', $post); ?>
                    <?php endforeach; ?>
                </ul>

                <button class="btn btn-large btn-more btn-primary">Load More</button>
            </div>
        </div>

    </div><!-- /.row -->

</div><!-- /.container -->