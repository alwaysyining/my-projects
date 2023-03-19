<?php echo form_open_multipart('upload/do_upload');?>
<div class="row justify-content-center my-5">
    <div class="col-md-6 col-md-offset-3 centered">
        <?php echo $error;?>
		<div class="form-group">
            <label for="formFileSingle" class="form-label">Select File(s)</label>
            <br>
            <input type="file" name="userfiles[]" size="50" multiple /> 
        </div>

        <div class="form-group">
            <input type="submit" value="upload" />
        </div>
    </div>
</div>
<?php echo form_close(); ?>
<h3></h3>
<div class="main"> </div>
