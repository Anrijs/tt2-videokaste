    <div class="contents view user"> <!-- class="container" -->

      <div class="feed">
      
        <!-- Listitem start -->
        <?php if($user->tutorials == NULL) 
        {
            if($user == $current_user)
            {
                echo '<h1>'.__("NO_TUTORIALS_CURRENT_USER").'</h1>';
            }
            else {
                echo '<h1>'.__("NO_TUTORIALS_NOT_CURRENT_USER").'</h1>';
            }
        }
        else
        {
            foreach ($user->tutorials as $tutorial){?>
        <div class="listitem"> 
              
            <img src="http://img.youtube.com/vi/<?php echo Helper::decode_video_url($tutorial->videourl)?>/mqdefault.jpg" class="thumbnail">
            <a href="/tutorials/<?php echo $tutorial->id;?>" class="title"><h2><?php echo $tutorial->title;?></h2></a>
            <a href="/u/<?php echo $user->username;?>" class="author">by <?php echo $user->username; ?></a>
            <p class="description">
                <?php echo $tutorial->description;?>
            </p>
            
           
           
           <div class="details">
              <span class="stats">
                <span class="glyphicon glyphicon-eye-open" ></span> <?php echo $tutorial->views;?>
                <span class="glyphicon glyphicon glyphicon-comment" ></span> <?php echo count($tutorial->comments);?> 
              </span>
           </div>
        </div>
            <?php }}?>
        

      </div>

      <div class="sidebar">
        <div class="profile">

            <?php
                $email = $user->email;
                $gravatar_link = 'http://www.gravatar.com/avatar/' . md5($email) . '?s=180';
                echo '<img src="' . $gravatar_link . '" />';
             ?>
            


        <h3><?php echo Helper::visual_name($user->id);?></h3>
        <div class="stats">
          <span class="glyphicon glyphicon-user"></span> <?php echo __('FOLLOWERS'); ?>: <?php echo count($user->following); ?>
          <br>
          <span class="glyphicon glyphicon-film"></span> <?php echo __('TUTORIALS'); ?>: <?php echo count($user->tutorials); ?>
        </div>
        <?php
        if($current_user) { 
        if(!Model_Follower::find("all", array("where"=>array("following_id" => $user->id, "follower_id" => $this->current_user->id))) && 
                $user->id!= $current_user->id)
        { ?>
            <a href="/follow/<?php echo $user->username;?>" class="btn btn-default btn-sm"><?php echo __('FOLLOW'); ?></a>
        <?php
        }
        ?>
        <?php if(Model_Follower::find("all", array("where"=>array("following_id" => $user->id, "follower_id" => $this->current_user->id))))
        { ?>
            <a href="/unfollow/<?php echo $user->username;?>" class="btn btn-default btn-sm"><?php echo __('UNFOLLOW'); ?></a>
        <?php
        }}?>
        
          <!-- Ja tas ir 'mans' profils, tad te rādās edit poga, links /edit-->
      
      </div>
      
      <div class="sidebar_right hidden-sm hidden-xs hidden-md">
        <p class="lead" style="text-align:center;">
 <a href="http://www.youtube.com/datorikilv">
                <img src="https://lh5.googleusercontent.com/-69vppeBrbeU/AAAAAAAAAAI/AAAAAAAAAAA/fwDQ9cCAUcg/s200-c-k-no/photo.jpg">
                </a>
            </p>
    </div>
      </div>

    </div> <!-- contents -->