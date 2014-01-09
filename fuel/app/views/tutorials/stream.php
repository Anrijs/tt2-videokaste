    <div class="contents index"> <!-- class="container" -->
        <div class="feed">
          <?php
              if(Session::get_flash('error')) {
              ?>
                <div class="alert alert-warning">
                  <?php echo Session::get_flash('error'); ?>
                </div>
              <?php
            } ?>
          <?php if(empty($followers)) { ?>

            <h1> <?php echo __('NOT_FOLLOWING'); ?> </h1>
            <p class="lead"> <?php echo __('START_FOLLOWING'); ?>
            </p>
          <?php } ?>

          <?php foreach ($followers as $follower) {  ?>
          <?php foreach ($follower->user->tutorials as $tutorial) {  ?>
          <?php  { ?>
        <div class="listitem"> 
    
          <img src="http://img.youtube.com/vi/<?php echo Helper::decode_video_url($tutorial->videourl)?>/mqdefault.jpg" class="thumbnail">
                <?php if($current_user['group_id']>=50) { ?>
                <a href="/tutorials/edit/<?php echo $tutorial->id; ?>"><span class="glyphicon glyphicon-pencil"></span></a>
                <?php } ?>
                <a href="/tutorials/<?php echo $tutorial->id; ?>" class="title"><h2><?php echo $tutorial->title; ?></h2></a>
                <a href="/u/<?php echo $tutorial->user->username; ?>" class="author">by <?php echo Helper::visual_name($tutorial->author_id); ?></a>
                <a href="/explore/<?php echo $tutorial->category_id; ?>" class="v_category">(<?php echo $tutorial->category->title; ?>)</a>
                <p class="description">
                  <?php echo $tutorial->description; ?>
               </p>
               
               <div class="details">
                  <span class="stats">
                    <span class="glyphicon glyphicon-eye-open" ></span> <?php echo $tutorial->views; ?>
                    <span class="glyphicon glyphicon glyphicon-comment" ></span> <?php echo count($tutorial->comments);?> 
                  </span>
                  <span class="minibuttons">
                    <?php echo date('c',$tutorial->created_at); ?>
                  </span>
               </div>
            </div>   

            <?php } } }

              if(isset($_GET['page'])) {
                $current_page = $_GET['page'];
              }
              else {
                $current_page = 1;
              }
            ?>

        </div> <!-- end of feed --> 

        <div class="sidebar_right hidden-sm hidden-xs">
            <p class="lead" style="text-align:center;">
            <a href="http://www.youtube.com/datorikilv">
                <img src="https://lh5.googleusercontent.com/-69vppeBrbeU/AAAAAAAAAAI/AAAAAAAAAAA/fwDQ9cCAUcg/s200-c-k-no/photo.jpg">
                </a>
            </p>

        </div>  

    </div> <!-- /container -->