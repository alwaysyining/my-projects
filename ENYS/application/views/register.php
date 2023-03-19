<div class="container my-5">
	<div class="col-6 offset-3">
		<?php echo form_open(base_url().'register/check_register'); ?>
			<h2 class="text-center">Register</h2>
			
			<div class="text-danger">
				<?php echo $error;?>
			</div>
			<div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" placeholder="Username" name="username" required="required">
			</div>
			<div class="form-group">
                <label for="password">Password:</label>
				<input type="password" class="form-control" placeholder="Password" name="password" required="required">
				<p>Your passworld should not be shorter than 6 characters, 
					and it must contain the following three categories: 
					Uppercase [A-Z], lowercase [a-z], and Digits [0-9].</p>
			</div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
				<input type="password" class="form-control" placeholder="Confirm Password" name="confirm_password" required="required">
			</div>
            <div class="form-group">
                <label for="email">Email:</label>
				<input type="email" class="form-control" placeholder="firstname.lastname@uq.net.au" name="email" required="required" >
			</div>
			<div class="form-group">
                <label for="phone">Phone Number:</label>
				<input type="text" class="form-control" placeholder="Phone Number" name="phone" required="required">
			</div>
			<div class="form-group">
                <label for="email">Birthday:</label>
				<input type="date" class="form-control" placeholder="Birthday" name="birthday" required="required">
			</div>

			<form action="?" method="POST">
      			<div class="g-recaptcha" data-sitekey="6LeJBCEgAAAAAG6EGay66J0K2d7BKjrM7RQg8xwL"></div>
					<br/>
      			<input type="submit" value="Submit">
    		</form>
	
			<!--
			<div class="form-group">
				<button type="submit" class="btn btn-primary btn-block">Register</button>
			</div>
			-->
		<?php echo form_close(); ?>
	</div>
</div>