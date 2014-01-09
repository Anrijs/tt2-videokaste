<script type="text/javascript">
  function confirmDelete() {
    $("#confirmDelete").show();
    $("#confirmButton").hide();
  }
  function denyDelete() {
    $("#confirmDelete").hide();
    $("#confirmButton").show();
  }
</script>

<div class="contents tutorial_form"> <!-- class="container" -->

	<h1 style="display:inline;"> <?php echo __('EDIT_TUTORIAL'); ?> </h1>
  <?php if(Session::get_flash('error')) { 
    echo '<div class="alert alert-danger">';
    echo Session::get_flash('error'); 
    echo '</div>';
 } ?>
  <a id="confirmButton" onclick="confirmDelete();" href="#"><span class="glyphicon glyphicon-trash"></span><?php echo __('DELETE_TUTORIAL'); ?></a>
  <div id="confirmDelete" style="display: none; margin:16px; padding-bottom:44px;" class="alert alert-danger">
    <h4><?php echo __('DELETE_TUTORIAL_CONFIRMATION'); ?></h4>
    <p class="pull-right" style="display:inline-block;">
      <a href="/tutorials/delete/<?php echo $tutorial['id']; ?>" class="btn btn-danger"><?php echo __('YES'); ?></a>
      <a onclick="denyDelete();"  class="btn btn-default"><?php echo __('NO'); ?></a> 
    </p>
  </div>
<?php echo Form::open(array('id' => 'new_tutorial','enctype'=>"multipart/form-data")) ?>
<input type="hidden" name="<?php echo \Config::get('security.csrf_token_key');?>" value="<?php echo \Security::fetch_token();?>" />

<fieldset>
<div class="form-group">
    <?php 
    echo Form::label(__('TUTORIAL_EDIT_TITLE'), 'title');
    echo Form::input('title', $tutorial['title'], array('class' => 'form-control', 'placeholder' =>  __('TUTORIAL_EDIT_TITLE_PLACEHOLDER')));
    ?>
  </div>

  <div class="form-group">
    <?php 
    echo Form::label(__('TUTORIAL_EDIT_URL'), 'videourl');
    echo Form::input('videourl', $tutorial['videourl'], array('class' => 'form-control', 'placeholder' =>  __('TUTORIAL_EDIT_URL_PLACEHOLDER')));
    ?>
  </div>

  <div class="form-group">
    <?php 
    echo Form::label(__('TUTORIAL_EDIT_DESCRIPTION'), 'description');
    echo Form::textarea('description', $tutorial['description'], array('class' => 'form-control', 'placeholder' =>  __('TUTORIAL_EDIT_DESCRIPTION_PLACEHOLDER')));
    ?>
  </div>

  <div class="form-group">
    <?php 
    echo Form::label(__('TUTORIAL_EDIT_CONTENTS'), 'contents');
    echo Form::textarea('contents', $tutorial['contents'], array('class' => 'form-control', 'placeholder' =>  __('TUTORIAL_EDIT_CONTENTS_PLACEHOLDER')));
    ?>
  </div>

  <div class="form-group ">
    <label for="category"> <?php echo __('TUTORIAL_EDIT_CATEGORY'); ?> </label>
    <select class="form-control" id="category" name="category">
      <?php foreach ($categories as $category) { ?>
        <option value="<?php echo $category['id']; ?>"
                <?php if($category['id']==$tutorial['category_id']) {
                  echo ' selected';
                }?>>
                <?php echo $category['title']; ?></option>
      <?php } ?>
    </select>
  </div>

    <div class="form-group">
    <?php 
      echo Form::label('EN', 'language');
      echo Form::radio('language', 'en', $tutorial['language'], array('style' => 'margin-right:12px; margin-left:4px;'));

      echo Form::label('LV', 'language');
      echo Form::radio('language', 'lv', !$tutorial['language'], array('style' => 'margin-left:4px;'));
     ?>
  </div>

  <div class="form-group">
    <?php 
      echo Form::label(__('VISIBILITY_PUBLIC'), 'visibility');
      echo Form::radio('visibility', '1', $tutorial['is_public'], array('style' => 'margin-right:12px; margin-left:4px;'));

      echo Form::label(__('VISIBILITY_PRIVATE'), 'visibility');
      echo Form::radio('visibility', '0', !$tutorial['is_public'], array('style' => 'margin-left:4px;'));
     ?>
  </div>

  <button type="submit" class="btn btn-default"><?php echo __('SUBMIT'); ?></button>
</fieldset>
</form>
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