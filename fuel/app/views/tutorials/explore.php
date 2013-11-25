    <div class="contents explore"> <!-- class="container" -->

      <div class="feed">
        <div class="listitem category" style="vertical-text-align:middle;"> 
          <h2> Kategorijas </h2>
           <a href="/explore">
              <h3 <?php if($active_category<1) {echo 'class="active"';} ?> style="font-size:20px;">Visas kategorijas</h3></a>
          <?php foreach ($categories as $category) { ?>
            <a href="/explore/<?php echo $category['id']; ?>">
              <h3 <?php if($category['id']==$active_category) {echo 'class="active"';} ?> ><?php echo $category['title']; ?></h3></a>
          <?php } ?>

        </div>

        <?php 
        if($error_msg) {

          echo $error_msg;

        }
        else {
        foreach ($tutorials as $tutorial) { 
          //display under theese conditions:
          if($tutorial['is_public']||$current_user) {
        ?>

        <!-- Listitem start -->
        <div class="listitem"> 
          <img src="http://placehold.it/640x360" class="thumbnail">
            <?php if($current_user['group_id']>=50) { ?>
                <a href="/tutorials/edit/<?php echo $tutorial['id']; ?>"><span class="glyphicon glyphicon-pencil"></span></a>
                <?php } ?>
                <a href="/tutorials/<?php echo $tutorial['id']; ?>" class="title"><h2 class="pull-left;"><?php echo $tutorial['title']; ?></h2></a>
                <a href="/users/<?php echo $tutorial['author_id']; ?>" class="author">by <?php echo Helper::visual_name($tutorial['author_id']); ?></a>
                <a href="/explore/<?php echo $tutorial['category_id']; ?>" class="v_category">(<?php echo $tutorial['category']; ?>)</a>
                <p class="description">
                  <?php echo $tutorial['description']; ?>
               </p>
           
           <div class="details">
              <span class="stats">
                <span class="glyphicon glyphicon-eye-open" ></span> <?php echo $tutorial['views']; ?>
                <span class="glyphicon glyphicon glyphicon-comment" ></span> 26 
              </span>
              <span class="minibuttons">
                <?php echo date('c',$tutorial['created_at']); ?>
              </span>
           </div>
        </div>
        <!-- Listitem end -->
        
        <?php };}} ?>
      </div>

      <div class="sidebar hidden-xs hidden-sm">
        <h2>Kategorijas</h2>
        <ul>
          <a href="/explore">
              <li <?php if($active_category<1) {echo 'class="active"';} ?> style="margin-bottom:16px; ">Visas kategorijas</li>
          </a>
          <?php foreach ($categories as $category) { ?>
            <a href="/explore/<?php echo $category['id']; ?>">
              <li <?php if($category['id']==$active_category) {echo 'class="active"';} ?>><?php echo $category['title']; ?></li>
            </a>
          <?php } ?>
        </ul>
      </div>
      

    </div> <!-- /container -->