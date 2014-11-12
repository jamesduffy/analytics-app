
    <footer class="container">
      <div class="row">
        <div class="col-md-12">
          <p>
            <?=anchor('http://cafeduff.com', 'CafeDuff')?> &copy; <?=date('Y')?><br>
          </p>

          <?php if(ENVIRONMENT == 'development'): ?>
            <p>
              {elapsed_time}
            </p>
          <?php endif; ?>

          <p>
            <?=anchor('pages/about', 'About')?> &middot;
            <?=anchor('blog/year-in-review-2013', 'Blog')?> &middot;
            <?=anchor('pages/donate', 'Donate')?> <br>
            <?=anchor('pages/terms', 'Terms of Service')?> &middot;
            <?=anchor('pages/privacy', 'Privacy Statement')?>
          </p>

        </div>
      </div>
    </footer>

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="<?=base_url()?>js/bootstrap.min.js"></script>
    
    <?php if(ENVIRONMENT == 'development'): ?>
        <script src="<?=base_url()?>js/site_dev.js"></script>
    <?php else: ?>
        <script src="<?=base_url()?>js/site.js"></script>
    <?php endif; ?>

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