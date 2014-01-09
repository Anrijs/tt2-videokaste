<?php echo Asset::js('//code.jquery.com/jquery-1.10.2.min.js'); ?>
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
              <h3><?php echo __('TUTORIAL_DESCRIPTION'); ?></h3>
              <p class="view-tutorial">
                <?php echo $tutorial['contents'];} ?>
              </p>
           </p>
        

            <?php if (Auth::has_access("comment.create")) : ?>
            <?php echo Form::open('comment/create');?>
            <input type="hidden" name="<?php echo \Config::get('security.csrf_token_key');?>" value="<?php echo \Security::fetch_token();?>" />

            <fieldset>
                <div class="clearfix">
                    <h3><?php echo __('TUTORIAL_COMMENT'); ?></h3>
                    <div class="input">
                        <?php
                        echo Form::textarea('comment', Input::post('comment'), array("id" => "comment", "rows" => 4, "class" => "col-sm-8"));
                        ?>
                    </div>
                </div>
            </fieldset>
            <div class="actions">
                <?php echo Form::hidden("tutorial_id", $tutorial->id); ?>
                <?php echo Form::submit('submit', __('SAVE'), array('class' => 'btn btn-primary')); ?>
            </div>
            <?php echo Form::close();  ?>
           <?php endif; ?>
           
            
        <!-- Listitem end -->
            <?php foreach ($tutorial->comments as $key => $comment) { ?>
            <div style="border-bottom:solid 1px #ccc; margin-bottom:24px; margin-right:32px;">
            <h4 style="display:inline;"><?php echo $comment->user->username; ?></h4>
            <span style="color:#999">
            <?php 
                if($comment->updated_at) { 
                    echo Date::time_ago($comment->updated_at);
                }
                else {
                    echo Date::time_ago($comment->created_at);
                }
            ?>            
            </span>
            <?php
            if($current_user)
            {
                if($comment->user_id == $current_user->id || Auth::member(100)) {
                    echo Html::anchor('#', __('COMMENT_EDIT'), array('class'=>'edit'));
                    echo ' ';
                    echo Html::anchor('comment/delete/' . $comment->id, __('COMMENT_DELETE'), array("onclick"=>"return confirm('Really?');"));
                }
            }
             ?>
            <div style="white-space:normal;" class="col-xs-12"><?php echo $comment->comment; ?></div>
            
            
            <?php echo Form::open(array('class'=>'hidden','action'=>'/comment/edit/' . $comment->id)); ?>
            <input type="hidden" name="<?php echo \Config::get('security.csrf_token_key');?>" value="<?php echo \Security::fetch_token();?>" />

            <label for="comment-msg">Edit this message:</label>
           <textarea  rows="4" name="msg" id="comment-msg" style="display:block;" ><?php echo $comment->comment; ?></textarea>
            
             <div><button class="btn"><i class="icon-asterisk"></i><?php echo __('SUBMIT_CHANGES'); ?></button></div>
            </div>
            <?php echo Form::close(); } ?>
            
         


      </div>

      <div class="sidebar">
        <div class="profile">

            <?php
                $email = $tutorial->user->email;
                $gravatar_link = 'http://www.gravatar.com/avatar/' . md5($email) . '?s=180';
                echo '<img src="' . $gravatar_link . '" />';
             ?>
            


            <h3><a href="/u/<?php echo $tutorial->user->username;?>"><?php echo Helper::visual_name($tutorial->user->id);?></a></h3>
        <div class="stats">
          <span class="glyphicon glyphicon-user"></span> <?php echo __('FOLLOWERS'); ?>: <?php echo count($tutorial->user->followers); ?>
          <br>
          <span class="glyphicon glyphicon-film"></span> <?php echo __('TUTORIALS'); ?>: <?php echo count($tutorial->user->tutorials); ?>
        </div>
            
            <?php 
            if($current_user) {
              if(!Model_Follower::find("all", array("where"=>array("following_id" => $tutorial->user->id, "follower_id" => $current_user->id))) && 
                       $tutorial->user->id!= $current_user->id)
              { ?>
                  <a href="/follow/<?php echo  $tutorial->user->username;?>" class="btn btn-default btn-sm"><?php echo __('FOLLOW'); ?></a>
              <?php
              }
              ?>
              <?php if(Model_Follower::find("all", array("where"=>array("following_id" =>  $tutorial->user->id, "follower_id" => $this->current_user->id))))
              { ?>
                  <a href="/unfollow/<?php echo  $tutorial->user->username;?>" class="btn btn-default btn-sm"><?php echo __('UNFOLLOW'); ?></a>
              <?php
              }}?>
      </div>
      
      <div class="sidebar_right hidden-sm hidden-xs hidden-md">
        <p class="lead" style="text-align:center;">
        	 <a href="http://www.youtube.com/datorikilv">
                <img src="https://lh5.googleusercontent.com/-69vppeBrbeU/AAAAAAAAAAI/AAAAAAAAAAA/fwDQ9cCAUcg/s200-c-k-no/photo.jpg">
                </a>
      	</p>
      </div>
          
    </div>
    
    
    
</div> <!-- /container -->

    <script>
        $('.edit').click(function(e) {
            event.preventDefault();
            $(this).next().next().next().toggleClass('hidden');
        })
    </script>
    