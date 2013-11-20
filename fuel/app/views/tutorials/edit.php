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

	<h1 style="display:inline;"> Rediģēt pamācību </h1>
  <?php if(Session::get_flash('error')) { 
    echo '<div class="alert alert-danger">';
    echo Session::get_flash('error'); 
    echo '</div>';
 } ?>
  <a id="confirmButton" onclick="confirmDelete();" href="#"><span class="glyphicon glyphicon-trash"></span>Dzēst pamācību</a>
  <div id="confirmDelete" style="display: none; margin:16px; padding-bottom:44px;" class="alert alert-danger">
    <h4>Vai tiešām vēlies izszēst šo pamācību? Šis process ir neatgriezenisks!</h4>
    <p class="pull-right" style="display:inline-block;">
      <a href="/tutorials/delete/<?php echo $tutorial['id']; ?>" class="btn btn-danger">JĀ</a>
      <a onclick="denyDelete();"  class="btn btn-default">NĒ</a> 
    </p>
  </div>
<?php echo Form::open(array('id' => 'new_tutorial','enctype'=>"multipart/form-data")) ?>

<fieldset>
  <div class="form-group">
    <label for="exampleInputEmail1">Virsraksts</label>
    <input type="text" class="form-control" id="title" name="title" placeholder="Pamācības virsraksts" value="<?php echo $tutorial['title']; ?>">
  </div>

  <div class="form-group">
    <label for="exampleInputPassword1">Neliels apraksts par video (30-400 simboli)</label>
    <textarea style="height:120px;" class="form-control" name="description" id="description" placeholder="Šis apraksts ir tas ko varēs redzēt meklējot video"><?php echo $tutorial['description']; ?></textarea>
  </div>

  <div class="form-group">
    <label for="exampleInputPassword1">Video adrese</label>
    <input type="text" class="form-control" id="videourl" name="videourl" placeholder="http://www.youtube.com/watch?v=y0utu830000" value="<?php echo $tutorial['videourl']; ?>">
  </div>

  <div class="form-group">
    <label for="exampleInputPassword1">Papildus informācija par video (neobligāts, 0-3000 simboli)</label>
    <textarea class="form-control" id="contents" name="contents" placeholder="Piebildes par video, papildus informācija, saites... Tas ko varēs redzēt pamācības lapā zem video."><?php echo $tutorial['contents']; ?></textarea>
  </div>

  <div class="form-group ">
    <label for="category"> Kategorija </label>
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
    <input <?php if($tutorial['is_public']) {echo 'checked';} ?> type="radio" name="visibility" id="public" value="1"></input><label style="margin-right:16px;" for="public" >Redzams visiem </label>
    <input <?php if(!$tutorial['is_public']) {echo 'checked';} ?> type="radio" name="visibility" id="private" value="0"></input><label for="private">Redzams tikai reģistrētiem lietotājiem </label>
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