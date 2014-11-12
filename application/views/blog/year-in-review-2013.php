<style type="text/css">
    .stat.box {
        margin-bottom: 15px;
    }    

    .number {
        font-size: 2em;
    }
</style>

<div class="container">

    <div class="row">

        <div class="col-md-12">
        	<div class="inner box">
        		<h1 class="page-header">Year End Review of App.net in 2013</h1>
        	</div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="inner box">
                <p class="lead">I cannot do a entire year in review due in large part to that fact Analytics-App has only been running for the past 8 months, but I have taken the time to search out the top posts, clients, and users.</p>

                <p>In the last few months Analytics-App has gone from hackathon idea into something I personally check every morning to find the best content from App.net. In the next month I plan to release a slew of updates that I hope will help people find great stuff too.</p>

                <p>It started with the second App.net Hackathon (my first) where I was juggling the hackathon and my college newspaper's conference in a nearby hotel. I would play with the API and leave to attend a lecture for my newspaper only to return to play around some more. </p>

                <p>At the next hackathon a few months later I was prepared to build a web based client to compete with Alpha, but halfway through the day I abandoned my efforts to create an app that helps users find great content.</p>

                <small class="text-center">All the data that is presented here was calculated on Sunday, December 29, 2013.</small>
            </div>
        </div>

        <div class="col-md-4 leaderboard">
            <div id="totals" class="row">
                <div class="col-md-12">
                    <div class="stat box">
                        <h3 class="text-center">Total Posts</h3>
                        <p class="number text-center">
                            <?=number_format('9927327')?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="stat box">
                        <h3 class="text-center">Unique Users</h3>
                        <p class="number text-center">
                            <?=number_format('39639')?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="stat box">
                        <h3 class="text-center">Unique Clients</h3>
                        <p class="number text-center">
                            <?=number_format('721')?>
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="row">

        <div class="col-md-7">
            <div class="inner box">
                <h3 class="page-header">Top 50 Posts</h3>

                <ul class="media-list">
                <?php foreach ($top_posts as $post): ?>
                    <?php $this->load->view('layouts/post', $post); ?>
                <?php endforeach; ?>
                </ul>
            </div>

            <div class="inner box">
                <h3 class="page-header">Top 50 Clients</h3>

                <table class="table table-hover">
                    
                        <thead>
                            <tr>
                                <th>Client Name</th>
                                <th>Total Posts</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <?php foreach($top_clients as $client): ?>
                            <tr>
                                <td><?=anchor($client['source_link'], $client['source_name'])?></td>
                                <td><?=number_format($client['total_posts'])?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>

                    </table>
            </div>
        </div>

        <div class="col-md-5">
            <div class="inner box">
                <h3 class="page-header">Top 100 Users</h3>

                <ul class="media-list">
                    <?php foreach($top_users_sorted as $user): ?>
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

    </div>

    <div class="row">

        
    </div>

</div><!-- /.container -->


