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
          <?php if(count($tutorials)<1) { ?>

            <h1> Izskatās ka Tu vēl neseko nevienam lietotājam. </h1>
            <p class="lead">Ir vairākas iespējas kā atrast lietotājus kam sekot:
              <ul class="lead">
                <li>
                  Dodies uz <a href="/users">lietotāju</a> lapu
                </li>
                <li>
                  Dodies uz <a href="/explore">pamācību</a> lapu
                </li>
                <li>
                  Izmanto meklēšanas rīku navigācijas joslā
                </li>
                <!-- <li>
                  Izvēlies kādu lietotāju no saraksta, kas atrodams zemāk 
                </li> -->
              </ul>
            </p>
          <?php } ?>
          <?php foreach ($tutorials as $tutorial) { ?>
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

            <?php } 

              if(isset($_GET['page'])) {
                $current_page = $_GET['page'];
              }
              else {
                $current_page = 1;
              }
            ?>
            <?php if(count($tutorials)>0) { ?>
              <ul class="pager">
                <li class="<?php if($current_page==1) {echo 'disabled'; } ?>">
                  <a href="<?php 
                      if($current_page==1) {echo '#';} 
                      else if($current_page>$page_count) {echo '/stream?page='.($page_count);} 
                      else {echo '/stream?page='.($current_page-1);} ?>">Previous</a>
                </li>
                <li class="<?php if($current_page>=$page_count) {echo 'disabled';} ?>">
                  <a href="<?php if($current_page>=$page_count) {echo '#';} else {echo '/stream?page='.($current_page+1);} ?>">Next</a>
                </li>
              </ul>
              <?php } ?>

        </div> <!-- end of feed --> 

        <div class="sidebar_right hidden-sm hidden-xs">
            <p class="lead" style="text-align:center;">
                This seems like a nice place for your ad.
                <br>  <br>
                But i will use this for debuging
                <br> <br>
                <?php echo $page_count; ?>
            </p>

        </div>  

    </div> <!-- /container -->