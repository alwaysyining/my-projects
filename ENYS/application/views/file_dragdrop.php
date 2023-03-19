<?php echo form_open_multipart('upload/do_upload');?>
<div class="row justify-content-center my-5">
    <div class="col-md-6 col-md-offset-3 centered">
        <?php echo $error;?>

        <div class="form-group">
            <label for="formDragDrop" class="form-label">Drag and Drop File(s)</label>
            <input type="file" name="userfiles[]" size="50" id="input-file-now-custom" class="file-upload" multiple/>
            <script>
                $('.file-upload').file_upload();
            </script>
        </div>
        <div class="form-group">
            <input type="submit" value="upload" />
        </div>  
        
        <style>
            #input-file-now-custom {
                border: 2px solid blue;
                height: 200px;
                width: 100%
            }
        </style>

    </div>
</div>
<?php echo form_close(); ?>
<h3></h3>
<div class="main"> </div>
