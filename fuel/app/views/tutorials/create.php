<div class="contents tutorial_form"> <!-- class="container" -->

	<h1> Izveidot pamācību </h1>
  <?php if(Session::get_flash('error')) { 
    echo '<div class="alert alert-danger">';
    echo Session::get_flash('error'); 
    echo '</div>';
 } ?>
<?php echo Form::open(array('id' => 'new_tutorial')) ?>
<fieldset>
  <div class="form-group">
    <label for="exampleInputEmail1">Virsraksts (5-60 simboli</label>
    <input type="text" class="form-control" id="title" name="title" placeholder="Pamācības virsraksts" value="<?php echo $post_data['title']; ?>">
  </div>

  <div class="form-group">
    <label for="exampleInputPassword1">Neliels apraksts par video (30-400 simboli)</label>
    <textarea style="height:120px;" class="form-control" name="description" id="description" placeholder="Šis apraksts ir tas ko varēs redzēt meklējot video"><?php echo $post_data['description']; ?></textarea>
  </div>

  <div class="form-group">
    <label for="exampleInputPassword1">Video adrese</label>
    <input type="text" class="form-control" id="videourl" name="videourl" placeholder="http://www.youtube.com/watch?v=y0utu830000" value="<?php echo $post_data['videourl']; ?>">
    </input>
  </div>

  <div class="form-group">
    <label for="exampleInputPassword1">Papildus informācija par video (neobligāts, 0-2000 simboli)</label>
    <textarea class="form-control" id="contents" name="contents" placeholder="Piebildes par video, papildus informācija, saites... Tas ko varēs redzēt pamācības lapā zem video."><?php echo $post_data['contents']; ?></textarea>
  </div>

  <div class="form-group">
    <label for="category"> Kategorija </label>
    <select class="form-control" id="category" name="category">
      <option value="" selected disabled >Izvēlies kategoriju</option>
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
    <input type="radio" name="visibility" id="public" value="1" <?php echo $post_data['visibility1']; ?> ></input><label style="margin-right:16px;" for="public" >Redzams visiem </label>
    <input type="radio" name="visibility" id="private" value="0" <?php echo $post_data['visibility0']; ?> ></input><label for="private">Redzams tikai reģistrētiem lietotājiem </label>
  </div>
  
  <button type="submit" class="btn btn-default">Submit</button>
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
        maxlength
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
        maxlength: 2000,
      }
    },
    messages: {
      title: {
        required: "Ievadiet virsrakstu",
        minlength: "Virsrakstam jābūt vismaz 5 simbolus garam",
      },
      description: {
        required: "Ievadiet aprakstu",
        minlength: "Aprakstam jābūt vismaz 30 simbolus garam",
        maxlength: "Nu jau pietiks. Limits ir 400 simboli",
      },
      videourl: {
        required: "Ievadiet video adresi",
        url: "Ievadiet pareizu video adresi (ar visu http://)",
      },
      contents: {
        maxlength: "Nepārcenties! Limits ir 3000 simboli",
      },
      category: {
        required: "Izvēlieties kategoriju",
      }
    }
  });

</script>