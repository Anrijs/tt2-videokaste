<div class="contents login"> 

<h2> Edit user </h2>

<?php echo Form::open(array('id' => 'edit')) ?>
<input type="hidden" name="<?php echo \Config::get('security.csrf_token_key');?>" value="<?php echo \Security::fetch_token();?>" />

<fieldset>
 <div class="form-group">
    <?php 
    	echo Form::label(__('USER_USERNAME'), 'username');
    	echo Form::input('username', $current_user['username'], array('class' => 'form-control', 'placeholder' =>  __('USER_USERNAME_PLACEHOLDER')));
    ?>
 </div>

  <div class="form-group">
    <?php 
    	echo Form::label(__('USER_EMAIL'), 'email');
    	echo Form::input('email', $current_user['email'], array('class' => 'form-control', 'placeholder' =>  __('USER_EMAIL_PLACEHOLDER')));
    ?>
 </div>

  <div class="form-group">
    <?php 
    	echo Form::label(__('USER_OLD_PASSWORD'), 'old_password');
    	echo Form::password('old_password', '', array('class' => 'form-control', 'placeholder' =>  __('USER_OLD_PASSWORD')));
    ?>
 </div>

 <div class="form-group">
    <?php 
    	echo Form::label(__('USER_NEW_PASSWORD'), 'password');
    	echo Form::password('password', '', array('class' => 'form-control', 'placeholder' =>  __('USER_NEW_PASSWORD')));
    ?>
 </div>

  <div class="form-group">
    <?php 
    	echo Form::label(__('USER_CONFIRM_NEW_PASSWORD'), 'c_password');
    	echo Form::password('c_password', '', array('class' => 'form-control', 'placeholder' =>  __('USER_CONFIRM_NEW_PASSWORD')));
    ?>
 </div>

<?php if(Session::get_flash('error')) { ?>
 <div class="alert alert-warning">
 	<?php 
 		echo Session::get_flash('error');
 	?>
 </div>
 <?php } ?>

<?php echo Form::submit(__('SAVE'), __('SAVE'), array('class="btn btn-default"')); ?>
</fieldset>
<?php echo Form::close(); ?>

</div>

<script src="/dist/js/jquery.validate.min.js"></script>
<script type="text/javascript">
	// validate signup form on keyup and submit
	$("#register").validate({
		rules: {
			username: {
				required: true,
				minlength: 3,
				maxlength: 20
			},
			password: {
				required: true,
				minlength: 6
			},
			c_password: {
				required: true,
				minlength: 6,
				equalTo: "#form_password"
			},
			email: {
				required: true,
				email: true
			},
			language: {
				required: true,
			},
		},
		messages: {
			username: {
				required: "<?php echo __('USER_USERNAME_REQUIRED'); ?>",
				minlength: "<?php echo __('USER_USERNAME_MINLENGTH'); ?>"
			},
			current_password: {
				required: "<?php echo __('USER_PASSWORD_REQUIRED'); ?>"
			},
			new_password: {
				required: "<?php echo __('USER_PASSWORD_REQUIRED'); ?>",
				minlength: "<?php echo __('USER_PASSWORD_MINLENGTH'); ?>"
			},
			c_password: {
				required: "<?php echo __('USER_CONFIRM_PASSWORD_REQUIRED'); ?>",
				minlength: "<?php echo __('USER_CONFIRM_PASSWORD_MINLENGTH'); ?>",
				equalTo: "<?php echo __('USER_CONFIRM_PASSWORD_EAQUALTO'); ?>"
			},
			email: "<?php echo __('USER_EMAIL_EMAIL'); ?>",
			language: {
				required: "<?php echo __('USER_LANGUAGE_REQUIRED'); ?>",
			},
		}
	});

</script>
