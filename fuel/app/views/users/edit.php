<div class="contents login"> 

<h2> Edit user </h2>

<?php echo Form::open(array('id' => 'edit')) ?>
<fieldset>

  <div class="form-group">
    <label for="email">Lietotajv</label>
    <input type="text" class="form-control" id="input2" name="input2" placeholder="input2">
 </div>

 
  <div class="form-group">
    <label for="email">Profile_fields</label>
    <input type="text" class="form-control" id="input1" name="input1" placeholder="input1">
 </div>

 <pre>
 	<?php $arr = Auth::get_profile_fields(); 
 		print_r($arr);
 	?>

 </pre>

<button type="submit" class="btn btn-default">Reģistrēties</button>
</fieldset>
</form>

</div>

<script src="/dist/js/jquery.validate.min.js"></script>