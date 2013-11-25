<div class="contents login"> 

<h2> Jauna lietotāja reģistrācija </h2>

<?php echo Form::open(array('id' => 'register')) ?>
<fieldset>
 <div class="form-group">
    <label for="username">Lietotājvārds</label>
    <input type="text" class="form-control" id="username" name="username" placeholder="Username">
 </div>

  <div class="form-group">
    <label for="email">E-pasts</label>
    <input type="email" class="form-control" id="email" name="email" placeholder="email">
 </div>

 <div class="form-group">
    <label for="password">Parole</label>
    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
 </div>

  <div class="form-group">
    <label for="c_password">Atkārtojiet paroli</label>
    <input type="password" class="form-control" id="c_password" name="c_password" placeholder="Confirm Password">
 </div>

<?php if(Session::get_flash('error')) { ?>
 <div class="alert alert-warning">
 	<?php 

 		echo Session::get_flash('error');

 	?>
 </div>
 <?php } ?>

<button type="submit" class="btn btn-default">Reģistrēties</button>
</fieldset>
</form>

</div>

<script src="/dist/js/jquery.validate.min.js"></script>
<script type="text/javascript">
	// validate signup form on keyup and submit
	$("#register").validate({
		rules: {
			username: {
				required: true,
				maxlength: 20
			},
			password: {
				required: true,
				minlength: 6
			},
			c_password: {
				required: true,
				minlength: 6,
				equalTo: "#password"
			},
			email: {
				required: true,
				email: true
			},
		},
		messages: {
			username: {
				required: "Ievadiet lietotājvādru",
				minlength: "Vārdam jābūt vismaz 3 simbolus garam"
			},
			password: {
				required: "Ievadiet paroli",
				minlength: "Parolei jābūt vismaz 6 simbolus garai"
			},
			c_password: {
				required: "Ievadiet paroli",
				minlength: "Parolei jābūt vismaz 6 simbolus garai",
				equalTo: "Abām parolēm ir jāsakrīt"
			},
			email: "Ievadiet pareizu e-pasta adresi",
		}
	});

</script>
