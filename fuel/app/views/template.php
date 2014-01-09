<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="viewport" content="width=device-width, user-scalable=no" />
    <link rel="shortcut icon" href="../../assets/ico/favicon.png">

    <title><?php echo $title; ?></title>

    <!-- Bootstrap core CSS -->
    <link href="/dist/css/bootstrap.css" rel="stylesheet">
    <link href="/dist/css/custom.css" rel="stylesheet">

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <!-- Custom styles for this template -->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../../assets/js/html5shiv.js"></script>
      <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>


    <?php 
    if($current_user) {
      Config::set('language', $current_user->language);
    }
    else {
      if(!Cookie::get("videokaste_language")=='lv') {$lang = 'en';
        $head = Input::headers( 'Accept-Language');
        if (strpos($head, 'lv')) {
          $lang = 'lv';
        }

        Config::set('language', $lang);
        $cookie_time=Config::get('cookie_language_time');
        Cookie::set('language', $lang, $cookie_time);
      }
      else {
        Config::set('language', Cookie::get("videokaste_language"));
      }
    }

     ?>
        <!-- Static navbar -->
    <div class="navbar navbar-default navbar-fixed-top">
      <div > <!-- class="container" -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/" style="line-height:44px;color:#fefefe;font-size:20px;">
            Videokaste.lv
          </a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class='<?php echo Arr::get($navbar, "stream" ); ?>'><a href="/"><?php if($current_user) {echo __("STREAM");} else {echo __("HOME");} ?></a></li>
            <li class='<?php echo Arr::get($navbar, "explore" ); ?>'><a href="/explore"><?php echo __("TUTORIALS"); ?></a></li>
          </ul>
          <div class="col-sm-3 col-sm-3">
            <form class="navbar-form" role="search">
            <input type="hidden" name="<?php echo \Config::get('security.csrf_token_key');?>" value="<?php echo \Security::fetch_token();?>" />
              <div class="input-group">
                <input type="text" class="form-control" placeholder="<?php echo __("SEARCH_PLACEHOLDER"); ?>" name="srch-term" id="srch-term">
                <div class="input-group-btn">
                  <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                </div>
              </div>
            </form>
          </div>

          <ul class="nav navbar-nav pull-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo __("LANGUAGE"); ?></a>
              <ul class="dropdown-menu">
                <li><a href="/lang/lv">LV</a></li>
                <li><a href="/lang/en">EN</a></li>
              </ul>
            </li>
            <?php if($current_user): ?>
            <li><a href="/u/<?php echo $current_user->username; ?>">
              <?php 
                echo  Helper::visual_name_nav($current_user->id);
              ?>
            </a>
            </li>
            
            <li><a href="/tutorials/create"><span class="glyphicon glyphicon-plus-sign"></span></a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-cog"></span></a>
              <ul class="dropdown-menu">
                <li><a href="/u/<?php echo $current_user['username']; ?>"><?php echo __("MY_TUTORIALS"); ?></a></li>
                <li class="divider"></li>
                <li><a href="/about"><?php echo __("ABOUT"); ?></a></li>
                <li><a href="/edit/"><?php echo __("SETTINGS"); ?></a></li>
                <li><a href="/users/logout"><?php echo __("LOG_OUT"); ?></a></li>
              </ul>
            </li>
          <?php else: ?>
            <li class='<?php echo Arr::get($navbar, "login" ); ?>'><a href="/users/login"><?php echo __("LOG_IN"); ?></a></li>
          <?php endif ?>
          </ul>
          <!--- 
          <ul class="nav navbar-nav navbar-right">
            <li><a href="../navbar/">Default</a></li>
            <li class="active"><a href="./">Static top</a></li>
            <li><a href="../navbar-fixed-top/">Fixed top</a></li>
          </ul> -->
        </div> <!--/.nav-collapse -->
      </div>

    </div>
    
    <?php echo $content; ?>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/dist/js/bootstrap.min.js"></script>
    <script src="/dist/js/alert.js"></script>

        <script type="text/javascript">
       $('button.expand').click(function(){
        var element = $(this).prev('.description');
        var h = element.css("height");
        if(h=='100px') {
          element.css( "height", "100%" );
        }
        else {
          element.css( "height", "100px" );
        }
       });

    </script>
  </body>
</html>
