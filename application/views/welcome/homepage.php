<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Analytics-App</title>
    <link rel="icon" type="image/png" ref="<?=base_url()?>favicon.png">
    <meta name="description" content="">

    <!-- Le styles -->
    <link href="<?=base_url()?>css/styles.css?version=20140402" rel="stylesheet">
  </head>

  <body id="welcome">

    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h1>Analytics-App</h1>
          <h2 style="text-style:italics;">Find the best people, posts and insights</h2>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <?php
            $authenticate_url = 'https://account.app.net/oauth/authenticate?';
            $authenticate_url .= 'client_id=tspP2NYqMtzb92faRNn2pbWMWk6cDSZ3';
            $authenticate_url .= '&response_type=code';
            $authenticate_url .= '&redirect_uri='.base_url();
            $authenticate_url .= '&scopes='.'basic+write_post+stream+follow+public_messages+update_profile+files';
          ?>
          <a href="<?=$authenticate_url?>" class="btn btn-primary btn-lg">Authorize with App.net</a>
        </div>
      </div>
    </div>


  <div class="container">

    <?php if($this->session->flashdata('error')): ?>

      <div class="row">  
        <div class="col-md-12">
          <div class="alert alert-danger">
            <strong><?=$this->session->flashdata('error')?>!</strong>
          </div>
        </div>
      </div>

    <?php endif; ?>

  </div><!--/.container-->


  <?php if($this->session->userdata('username') !== 'dffy'): ?>
      <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-23906018-3', 'analytics-app.net');
      ga('send', 'pageview');
      </script>
  <?php endif; ?>

  </body>
</html>