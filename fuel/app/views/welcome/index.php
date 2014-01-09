    <div class="contents index"> <!-- class="container" -->
        <?php if(!$current_user) { ?>
    	<div class="jumbotron">
    		<h1> Videokaste.lv </h1>
    		<div class="desc">
    		    <p>
    		    	<?php echo __('GUEST_MAIN'); ?>
    			</p>
    			<a class="btn btn-default pull-right btn-lg" href="/about"><?php echo __('ABOUT'); ?></a>
                <a href="/users/login" class="btn btn-default pull-right btn-lg" ><?php echo __('LOG_IN'); ?></a>
    		    <a href="/users/create" class="btn btn-primary pull-right btn-lg" ><?php echo __('REGISTER'); ?></a>
    		</div>
    	</div>
    	<div class="stats"><?php echo __('STATUS_REGISTRED').': '.$total_users.' | '.__('STATUS_TUTORIALS').': '.$total_tutorials; ?></div>
        <?php } ?>

    </div> <!-- /container -->