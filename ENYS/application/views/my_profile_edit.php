<div class="container my-5">
	<div class="col-6 offset-3">
		<?php echo form_open(base_url().'register/edit_profile'); ?>
			<h2 class="text-center">My Profile</h2>
			
			<div class="text-danger">
				<?php echo $error;?>
			</div>
			<div class="form-group">
                <label for="username">Username:</label>
                <?php echo $user["username"]; ?>
			</div>
            <!--
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
            -->
            <div class="form-group">
                <label for="email">Email:</label>
				<?php echo $user["email"]; ?>
			</div>
			<div class="form-group">
                <label for="phone">Phone Number:</label>
				<input type="text" class="form-control" placeholder="Phone Number" name="phone" value="<?php echo $user["phone"]; ?>"required="required">
			</div>
			<div class="form-group">
                <label for="email">Birthday:</label>
				<input type="date" class="form-control" placeholder="Birthday" name="birthday" value="<?php echo $user["birthday"]; ?>" required="required">
			</div>
	
			<div class="form-group">
				<button type="submit" class="btn btn-warning" name="edit_cancel">Cancel</button>
                <button type="submit" class="btn btn-secondary" name="profile_update">Submit</button>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>