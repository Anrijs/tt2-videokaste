<div class="contents view"> <!-- class="container" -->

      <div class="feed">
        <!-- Listitem start -->


            <h2>
              <?php 
                if($tutorial['author_id']==$current_user['id']||$current_user['group_id']>=50) {
                  echo '<a href="/tutorials/edit/'.$tutorial['id'].'"<span style="font-size:0.6em;" class="glyphicon glyphicon-pencil"></span></a>';
                }
                echo ' '.$tutorial['title']; 
              ?>
            </h2>
            <?php 
              if(Session::get_flash('success')) {
                ?>
                <div class="alert alert-success">
                  <?php echo Session::get_flash('success'); ?>
                </div>
            <?php
              }
            ?>
            <a href="/u/<?php echo $tutorial->user['username']; ?>" class="author">by <?php echo Helper::visual_name($tutorial['author_id']); ?></a>
              <span class="stats">
                <span class="glyphicon glyphicon-eye-open" ></span> <?php echo $tutorial['views']; ?>
              </span>
            <p class="description">
            <?php echo $tutorial['description']; ?>
              <div class="h_iframe">
                  <img class="ratio" src="http://placehold.it/16x9"/>
                  <?php 
                  	$videourl = $tutorial['videourl'];
                  ?>
                  <iframe src="//www.youtube.com/embed/<?php echo Helper::decode_video_url($videourl); ?>" frameborder="0" allowfullscreen></iframe>
                  <?php echo $tutorial['videourl']; ?>
              </div>
              <?php if($tutorial['contents']) { ?>
              <h3>Pamācības apraksts</h3>
              <?php echo $tutorial['contents'];} ?>
           </p>
            
        <!-- Listitem end -->
      </div>

      <div class="sidebar">
        <div class="profile">
        	<img src="http://placehold.it/180x180">
        	<h3><?php echo Helper::visual_name($tutorial['author_id']); ?></h3>
        	<p class="lead">
        	  <?php echo $tutorial->user['details']; ?>
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