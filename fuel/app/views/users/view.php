    <div class="contents view user"> <!-- class="container" -->

      <div class="feed">
      
        <!-- Listitem start -->
        <div class="listitem"> 
              
            <img src="http://placehold.it/640x360" class="thumbnail hidden-xs hidden-sm">
            <a href="tutorial.php" class="title"><h2 class="pull-left">%Tutorial name%</h2></a>
            <a href="user.php" class="author">by %author name%</a>
            <p class="description">
             %description_short%
             Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
             tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
             quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
             consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
             cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
             proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
           </p>
           
           <div class="details">
              <span class="stats">
                <span class="glyphicon glyphicon-eye-open" ></span> 120
                <span class="glyphicon glyphicon glyphicon-comment" ></span> 26 
              </span>
              <span class="minibuttons">
                <button type="button" class="btn btn-default btn-sm">
                  <span class="glyphicon glyphicon-calendar"></span> Watch later
                </button>
              </span>
           </div>
        </div>


      </div>

      <div class="sidebar">
        <div class="profile">

        <img src="http://placehold.it/180x180" class="imgWrap">
        <a href="#" class="btn btn-default btn-small imgWrapText">Nomainīt bilid</a>

        <h3>%user_name%</h3>
        <p class="lead">
          %user_desc% Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
          tempor incididunt ut labore et dolore magna aliqua.
        </p>
        <div class="stats">
          <span class="glyphicon glyphicon-user"></span> Followers: 7,093
          <br>
          <span class="glyphicon glyphicon-film"></span> Tutorials: 20
        </div>
        <button type="button" class="btn btn-default btn-sm">Follow</button> <!-- Ja tas ir 'mans' profils, tad te rādās edit porga -->
        <button type="button" class="btn btn-default btn-sm">Share</button>

      </div>
      
      <div class="sidebar_right hidden-sm hidden-xs hidden-md">
        <p class="lead" style="text-align:center;">
                This seems like a nice place for your ad...
                <br>  <br>
                But i will use this for:
                <br>
                <?php echo '<b>debuging</b>'; ?>
            </p>
    </div>
      </div>

    </div> <!-- contents -->