<div class="contents tutorial_form"> <!-- class="container" -->

	<h1> <?php echo __('CREATE_TUTORIAL'); ?> </h1>
  <?php if(Session::get_flash('error')) { 
    echo '<div class="alert alert-danger">';
    echo Session::get_flash('error'); 
    echo '</div>';
 } ?>
<?php echo Form::open(array('id' => 'new_tutorial')) ?>
<input type="hidden" name="<?php echo \Config::get('security.csrf_token_key');?>" value="<?php echo \Security::fetch_token();?>" />

<fieldset>
  <div class="form-group">
    <?php 
    echo Form::label(__('TUTORIAL_EDIT_TITLE'), 'title');
    echo Form::input('title', $post_data['title'], array('class' => 'form-control', 'placeholder' =>  __('TUTORIAL_EDIT_TITLE_PLACEHOLDER')));
    ?>
  </div>

  <div class="form-group">
    <?php 
    echo Form::label(__('TUTORIAL_EDIT_URL'), 'videourl');
    echo Form::input('videourl', $post_data['videourl'], array('class' => 'form-control', 'placeholder' =>  __('TUTORIAL_EDIT_URL_PLACEHOLDER')));
    ?>
  </div>

  <div class="form-group">
    <?php 
    echo Form::label(__('TUTORIAL_EDIT_DESCRIPTION'), 'description');
    echo Form::textarea('description', $post_data['description'], array('class' => 'form-control', 'placeholder' =>  __('TUTORIAL_EDIT_DESCRIPTION_PLACEHOLDER')));
    ?>
  </div>

  <div class="form-group">
    <?php 
    echo Form::label(__('TUTORIAL_EDIT_CONTENTS'), 'contents');
    echo Form::textarea('contents', $post_data['contents'], array('class' => 'form-control', 'placeholder' =>  __('TUTORIAL_EDIT_CONTENTS_PLACEHOLDER')));
    ?>
  </div>

  <div class="form-group">
    <label for="category"> <?php echo __('TUTORIAL_EDIT_CATEGORY'); ?> </label>
    <select class="form-control" id="category" name="category">
      <option value="" selected disabled ><?php echo __('TUTORIAL_EDIT_CATEGORY_BLANK'); ?></option>
      <?php foreach ($categories as $category) { ?>
        <option value="<?php echo $category['id']; ?>"
                <?php if($category['id']==$post_data['category']) {
                  echo ' selected';
                }?>>
                <?php echo $category['title']; ?></option>
      <?php } ?>
    </select>
  </div>

  <div class="form-group">
    <?php 
      echo Form::label('EN', 'language');
      echo Form::radio('language', 'en', $post_data['language_en'], array('style' => 'margin-right:12px; margin-left:4px;'));

      echo Form::label('LV', 'language');
      echo Form::radio('language', 'lv', $post_data['language_lv'], array('style' => 'margin-left:4px;'));
     ?>
  </div>

  <div class="form-group">
    <?php 
      echo Form::label(__('VISIBILITY_PUBLIC'), 'visibility');
      echo Form::radio('visibility', '1', $post_data['visibility1'], array('style' => 'margin-right:12px; margin-left:4px;'));

      echo Form::label(__('VISIBILITY_PRIVATE'), 'visibility');
      echo Form::radio('visibility', '0', $post_data['visibility0'], array('style' => 'margin-left:4px;'));
     ?>
  </div>
  
  <button type="submit" class="btn btn-default"><?php echo __('SUBMIT'); ?></button>
</fieldset>
<?php echo Form::close(); ?>
</div> <!-- /container -->

<!-- validate form -->

<script src="/dist/js/jquery.validate.min.js"></script>
<script type="text/javascript">
  // validate signup form on keyup and submit
  $("#new_tutorial").validate({
    rules: {
      title: {
        required: true,
        minlength: 5,
      },
      description: {
        required: true,
        minlength: 30,
        maxlength: 400,
      },
      videourl: {
        required: true,
        url: true,
      },
      category: {
        required: true,
        min: 1 
      },
      visibility: {
        required: true,
      },
      contents: {
        required: false,
        maxlength: 3000,
      }
    },
    messages: {
      title: {
        required: "<?php echo __('TUTORIAL_TITLE_REQUIRED'); ?>",
        minlength: "<?php echo __('TUTORIAL_TITLE_MINLENGTH'); ?>",
      },
      description: {
        required: "<?php echo __('TUTORIAL_DESCRIPTION_REQUIRED'); ?>",
        minlength: "<?php echo __('TUTORIAL_DESCRIPTION_MINLENGTH'); ?>",
        maxlength: "<?php echo __('TUTORIAL_DESCRIPTION_MAXLENGTH'); ?>",
      },
      videourl: {
        required: "<?php echo __('TUTORIAL_VIDEO_URL_REQUIRED'); ?>",
        url: "<?php echo __('TUTORIAL_VIDEO_URL_URL'); ?>",
      },
      contents: {
        maxlength: "<?php echo __('TUTORIAL_CONTENTS_MAXLENGTH'); ?>",
      },
      category: {
        required: "<?php echo __('TUTORIAL_CATEGORY_REQUIRED'); ?>",
      }
    }
  });

</script>