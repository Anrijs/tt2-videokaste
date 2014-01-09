<div class="contents login"> 

<h2>  </h2>

<?php echo Form::open() ?>
<input type="hidden" name="<?php echo \Config::get('security.csrf_token_key');?>" value="<?php echo \Security::fetch_token();?>" />

 <div class="form-group">
    <label for="username"><?php echo __('USER_USERNAME'); ?></label>
    <input type="text" class="form-control" id="username" name="username" placeholder="Username">
 </div>

 <div class="form-group">
    <label for="password"><?php echo __('USER_PASSWORD'); ?></label>
    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
 </div>
<button type="submit" class="btn btn-default"><?php echo __('LOG_IN'); ?></button>
<!-- <a href="#" class="pull-right"><?php //echo __('USER_FORGOT_PASSWORD'); ?></a> -->
</form>

</div>