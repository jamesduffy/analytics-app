<div class="container" style="margin-top: 15px;">

    <div class="row">

        <div class="col-md-4">
            <form class="navbar-form" role="search" method="post" action="<?=base_url('search')?>">
                <div class="form-group">
                    <input name="query" type="text" class="form-control" placeholder="Search">
                </div>
            </form>

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

        <div class="col-md-8">
            <div class="inner">
                <ul class="media-list activity-stream">
                    <?php foreach($discover_stream as $event): ?>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <?php $user = $this->people->getUser($event['user_id']); ?>
                                    <?=anchor('profile/'.$user['username'], $user['username'])?> 

                                    <?php switch ($event['event_type']) {
                                        case 'star':
                                            print 'starred';
                                            break;
                                        
                                        case 'repost':
                                            print 'reposted';
                                            break;
                                    } ?>
                                </h3>
                            </div>
                            
                            <div class="panel-body">
                                <?=$this->load->view('layouts/post', $event['post'])?>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </ul>                

            </div>
        </div>

    </div><!-- /.row -->

</div><!-- /.container -->