<div id="profile-stats" class="container">

    <div class="row">

        <div class="col-md-8 col-md-offset-2">
            <div class="inner box">

            <ul class="media-list">
            	<?php $this->load->view('layouts/post', $posts); ?>

            	<li class="replies">
            		<?php foreach ($replies as $reply): ?>
						<?php $this->load->view('layouts/replies', $reply); ?>
					<?php endforeach; ?>
            	</li>
            </ul>

            <?=$this->pagination->create_links()?>

            </div>

        </div>

    </div><!-- /.row -->

</div><!-- /.container -->