<div class="container">

    <style type="text/css">
    p {
        padding-top: 1em;
    }
    </style>

    <div class="row">

        <div class="col-md-4">
        	<?php $this->load->view('pages/sidebar', array('current_page' => 'donate')); ?>
        </div>

        <div class="col-md-8">
        	<div class="inner box">
        		<h2>Donate</h2>

                <p class="pull-right" style="width:300px;">
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----">
                <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                </form>
                </p>

	            <p>Running Analytics-App takes money. Money that at the end of some months I just don’t have. 100% of the money you donate will go to keeping the server running.</p>

                <p>My name is James Duffy. I am a student based in California working towards my B.S. in Computer Information Systems at California State University, Stanislaus. I love bringing my ideas to fruition and App.net has finally made that possible.</p>

                <p>Today Analytics-App is storing every public post on App.net and the interactions on those posts. I have been able to build a leaderboard to help users find the best content and users around the network from today or the last 6+ months. You can follow and unfollow user from their profiles. Analytics-App also powers the extremely popular @MMMercury accounts.</p>

                <p>Going into the future I envision Analytics-App getting better at finding the best posts and users as well as giving insights into your daily use of the network and how to improve yourself so you can become one of the top users. Just around the corner I have a discover feature, but have been battling query performance issues. I am also working on making it possible to interact with posts through Analytics-App.</p>

                <p>I am always up for more suggestions and thank those that help keep the server’s disks spinning. </p>

        	</div>        
        </div>

    </div><!-- /.row-fluid -->

</div><!-- /.container -->