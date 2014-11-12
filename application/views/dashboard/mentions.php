<div id="dashboard" class="container">

    <div class="row">

        <div class="col-md-8">
            <div class="inner box">
                <h3 class="page-title">Mentions</h3>

                <ul class="media-list post-stream box">
                    <?php foreach ($mentions as $post): ?>
                        <?php $this->load->view('layouts/post', $post); ?>
                    <?php endforeach; ?>
                </ul>

                <button class="btn btn-large btn-more btn-primary"><span class="glyphicon glyphicon-circle-arrow-down"></span></button>
            </div>
        </div>

        <div class="col-md-4">
            <div class="box with-bottom-margin">
                <form id="compose-post" role="form">
                    <div class="form-group">
                        <textarea id="postBody" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <span id="characters-remaining" data-characters-remaining="256">256</span>
                        </div>
                        <div class="col-md-6">
                            <button id="submit-form" type="button" class="btn btn-primary pull-right btn-disabled">
                                <span class="glyphicon glyphicon-send"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="list-group">
                <a href="#" class="list-group-item"><span class="glyphicon glyphicon-home"></span> Stream</a>
                <a href="#" class="list-group-item active"><span class="glyphicon glyphicon-share-alt"></span> Mentions</a>
                <a href="#" class="list-group-item"><span class="glyphicon glyphicon-star"></span> Favorites</a>
                <a href="#" class="list-group-item"><span class="glyphicon glyphicon-random"></span> Reposts</a>
            </div>

            <div class="list-group">
                <a href="#" class="list-group-item"><span class="glyphicon glyphicon-comment"></span> Conversations</a>
                <a href="#" class="list-group-item"><span class="glyphicon glyphicon-picture"></span> Photos</a>
                <a href="#" class="list-group-item"><span class="glyphicon glyphicon-fire"></span> Trending</a>
                <a href="#" class="list-group-item"><span class="glyphicon glyphicon-globe"></span> Checkins</a>
            </div>
        </div>

    </div><!-- /.row -->

</div><!-- /.container -->