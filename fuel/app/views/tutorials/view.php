<div class="contents view"> <!-- class="container" -->

      <div class="feed">
        <!-- Listitem start -->


            <h2 class="pull-left;"><?php echo $tutorial['title']; ?></h2>
            <?php 
              if(Session::get_flash('success')) {
                ?>
                <div class="alert alert-success">
                  <?php echo Session::get_flash('success'); ?>
                </div>
            <?php
              }
            ?>
            <a href="user.php" class="author">by <?php echo Helper::visual_name($author['id']); ?></a>
              <span class="stats">
                <span class="glyphicon glyphicon-eye-open" ></span> <?php echo $tutorial['views']; ?>
              </span>
            <p class="description">
              <div class="h_iframe">
                  <img class="ratio" src="http://placehold.it/16x9"/>
                  <?php 
                  	$videourl = $tutorial['videourl'];
                  		$index = strpos($tutorial['videourl'], 'watch?v=') + strlen('watch?v=');
						$videourl = substr($tutorial['videourl'], $index);
                   ?>
                  <iframe src="//www.youtube.com/embed/<?php echo $videourl; ?>" frameborder="0" allowfullscreen></iframe>
                  <?php echo $tutorial['videourl']; ?>
              </div>
             <?php echo $tutorial['contents']; ?>
           </p>
            
        <!-- Listitem end -->
        <div class="well"> 
        	<p>
            <pre>
              <?php 

                print_r(Cookie::get());
               ?>
            </pre>
        	</p>
        </div>
      </div>

      <div class="sidebar">
        <div class="profile">
        	<img src="http://placehold.it/180x180">
        	<h3><?php echo Helper::visual_name($author['id']); ?></h3>
        	<p class="lead">
        	  <?php echo $author['details']; ?>
        	  Nothing here....
        	</p>
        	<div class="stats">
        	  <span class="glyphicon glyphicon-user"></span> Followers: NAN
        	  <br>
        	  <span class="glyphicon glyphicon-film"></span> Tutorials: NAN
        	</div>
        	<button type="button" class="btn btn-default btn-sm">Follow</button>
        	<button type="button" class="btn btn-default btn-sm">Share</button>
      	</div>
      
      <div class="sidebar_right hidden-sm hidden-xs hidden-md">
        <p class="lead" style="text-align:center;">
        	<span class="glyphicon glyphicon-random icobtn"></span>
      	</p>
      </div>
    </div>

</div> <!-- /container -->