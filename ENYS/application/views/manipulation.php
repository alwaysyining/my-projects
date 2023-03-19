<div class="container my-5">

    <form action="<?php echo base_url('zip/download')?>">
        <input type="submit" value="Download Processed images" />
    </form>

<script>
// Show select image using file input.
function readURL(input) {
$('#default_img').show();
if (input.files && input.files[0]) {
var reader = new FileReader();

reader.onload = function(e) {
$('#select')
.attr('src', e.target.result)
.width(300)
.height(200);

};

reader.readAsDataURL(input.files[0]);
}
}
</script>

<div class="main">
<div class="data">
<div class="page">
<div id="content">
<h2 id="form_head">Codelgniter Image Manipulation</h2><br/>
<hr>
<div id="form_input">
<?php
$data = array(
'enctype' => 'multipart/form-data'
);
// Form open
echo form_open('manipulation/value', $data);

// File input field.
$file = array(
'type' => 'file',
'name' => 'userfile',
'required' => '',
'onchange' => 'readURL(this);'
);
echo form_input($file);
echo "<br>";
echo "<br>";
?>
<?php // show image which we choose in file input ?>
<div id='default_img'>
<img id="select" src="#" alt="your image" />
</div>
<br>
<br>
<?php
// Radio Button "rotate" field.
$radio = array(
'type' => 'radio',
'name' => 'mode',
'value' => 'rotate',
'id' => 'rotate_button'
);
echo form_input($radio);
echo form_label('Rotate');
echo "<br>";
echo "<br>";

// Radio Button "resize" field.
$radio = array(
'type' => 'radio',
'name' => 'mode',
'value' => 'resize',
'id' => 'resize_button'
);
echo form_input($radio);
echo form_label('Resize');
echo "<br>";
echo "<br>";

// Radio Button "crop" field.
$radio = array(
'type' => 'radio',
'name' => 'mode',
'value' => 'crop',
'id' => 'crop_button'
);
echo form_input($radio);
echo form_label('Crop');
echo "<br>";
echo "<br>";

// Radio Button "watermark" field.
$radio = array(
'type' => 'radio',
'name' => 'mode',
'value' => 'watermark',
'id' => 'watermark_button'
);
echo form_input($radio);
echo form_label(' Water Mark');
?>
<div id="form_button">
<?php
// Submit Button.
echo form_submit('submit', 'Upload', "class='submit'");
?>
</div>
</div>

<?php // Input fields for resize option. ?>
<div id='resize' style='display: none'>
<div id='content_result'>
<?php
echo "<h3 id='result_id'>Enter width & height for resize image</h3><br/><hr>";
echo "<div id='result_show'>";
echo "<label class='label_output'>Width :</label>";
$data_width = array(
'name' => 'width',
'class' => 'input_box',
'value' => '200',
'id' => 'width'
);
echo form_input($data_width);
echo "<br>";
echo "<br>";
echo "<label class='label_output'>Height:</label>";
$data_height = array(
'name' => 'height',
'class' => 'input_box',
'value' => '200',
'id' => 'height'
);
echo form_input($data_height);
?>
</div>
</div>
</div>

<?php // Result image will show on here. ?>
<div id='img_resize'>

<?php
if (isset($img_src)) {
echo "<p>Success..</p>";
echo "<img src='" . $img_src . "'/>";
}
?>
<?php
if (isset($rot_image)) {
echo "<p>Success..</p>";
echo "<img src='" . $rot_image . "'/>";
}
?>
<?php
if (isset($watermark_image)) {
echo "<p>Success..</p>";
echo "<img src='" . $watermark_image . "'/>";
}
?>
<?php
if (isset($crop_image)) {
echo "<p>Success..</p>";
echo "<img src='" . $crop_image . "'/>";
}
?>

</div>

<?php // Input fields for watermark option. ?>
<div id='water_mark' style='display: none'>
<div id='water_result'>
<?php
echo "<h3 id='result_id'>Enter text for watermark image</h3><br/><hr>";
echo "<div id='result_show'>";
echo "<label class='label_output'>Text :</label>";
$data_text = array(
'name' => 'text',
'class' => 'input_box',
'value' => 'Formget.com',
'id' => 'watermark_text'
);
echo form_input($data_text);
?>
</div>
</div>
</div>

<?php // Input fields for crop option. ?>
<div id='crop' style='display: none'>
<div id='crop_result'>
<?php
echo "<h3 id='result_id'>Enter Cordinates</h3><br/><hr>";
echo "<div id='result_show'>";
echo "<label class='label_output'>X-axis (left) </label>";
$data = array(
'name' => 'x1',
'class' => 'input_box',
'value' => '100 ',
'id' => 'x1'
);
echo form_input($data);
echo "<br>";
echo "<br>";
echo "<label class='label_output'>Y-axis (top) </label>";
$data = array(
'name' => 'y1',
'class' => 'input_box',
'value' => '100',
'id' => 'y1'
);
echo form_input($data);
echo "<br>";
echo "<br>";
echo "<label class='label_output'>Width (right) </label>";
$data = array(
'name' => 'width_cor',
'class' => 'input_box',
'value' => '400',
'id' => 'width_cor'
);
echo form_input($data);
echo "<br>";
echo "<br>";
echo "<label class='label_output'>Height (bottom)</label>";
$data = array(
'name' => 'height_cor',
'class' => 'input_box',
'value' => '350',
'id' => 'height_cor'
);
echo form_input($data);
?>
</div>
</div>
</div>
<?php // Input fields for rotate option. ?>
<div id='rotate' style='display: none' >
<div id='rotate_result'>
<?php
echo "<h3 id='result_id'>Enter Angle For Rotate Image</h3><br/><hr>";
echo "<div id='result_show'>";
$radio = array(
'type' => 'radio',
'name' => 'degree',
'value' => '90',
'id' => 'degree_90',
'checked' => 'checked'
);
echo form_input($radio);
echo form_label('90&deg;');
echo "<br>";
echo "<br>";
$radio = array(
'type' => 'radio',
'name' => 'degree',
'value' => '180',
'id' => 'degree_180'
);
echo form_input($radio);
echo form_label('180&deg;');
echo "<br>";
echo "<br>";
$radio = array(
'type' => 'radio',
'name' => 'degree',
'value' => '270',
'id' => 'degree_270'
);
echo form_input($radio);
echo form_label('270&deg;');
echo "<br>";
echo "<br>";
$radio = array(
'type' => 'radio',
'name' => 'degree',
'value' => '360',
'id' => 'degree_360'
);
echo form_input($radio);
echo form_label('360&deg;');
?>
</div>
</div>
<?php echo form_close(); ?>
</div>

<script>
$("#resize_button").click(function() {
$('div#img_resize').hide();
$('div#crop').hide();
$('div#water_mark').hide();
$('div#rotate').hide();
$('div#resize').show();
});
$("#watermark_button").click(function() {
$('div#img_resize').hide();
$('div#resize').hide();
$('div#crop').hide();
$('div#rotate').hide();
$('div#water_mark').show();
});
$("#crop_button").click(function() {
$('div#img_resize').hide();
$('div#resize').hide();
$('div#water_mark').hide();
$('div#rotate').hide();
$('div#crop').show();
});
$("#rotate_button").click(function() {
$('div#img_resize').hide();
$('div#resize').hide();
$('div#water_mark').hide();
$('div#crop').hide();
$('div#rotate').show();
});
</script>

<style>
body {
    font-family: 'Raleway', sans-serif;
}
.main
{
width: 1015px;
position: absolute;
top: 10%;
left: 20%;
}
#form_head
{
text-align: center;
background-color: #FEFFED;
height: 66px;
margin: 0 0 -29px 0;
padding-top: 35px;
border-radius: 8px 8px 0 0;
color: rgb(97, 94, 94);
}
#content {
position: absolute;
width: 450px;
height: auto;
border: 2px solid gray;
border-radius: 10px;
}
#content_result{
position: absolute;
width: 450px;
height: 240px;
border: 2px solid gray;
border-radius: 10px;
margin-left: 559px;
margin-top: -465px;
}

#crop_result{
position: absolute;
width: 450px;
height: 362px;
border: 2px solid gray;
border-radius: 10px;
margin-left: 559px;
margin-top: -465px;
}
#water_result{
position: absolute;
width: 450px;
height: 175px;
border: 2px solid gray;
border-radius: 10px;
margin-left: 559px;
margin-top: -465px;
}
#rotate_result{
position: absolute;
width: 450px;
height: 240px;
border: 2px solid gray;
border-radius: 10px;
margin-left: 559px;
margin-top: -465px;
}
#form_input
{
margin-left: 50px;
margin-top: 36px;
}
label
{
margin-right: 6px;
font-weight: bold;
}

#form_button{
padding: 0 21px 15px 15px;
position: absolute;
bottom: -13px;
width: 414px;
background-color: #FEFFED;
border-radius: 0px 0px 8px 8px;
border-top: 1px solid #9A9A9A;
position: relative;
margin-left: -50;
}
.submit{
font-size: 16px;
background: linear-gradient(#ffbc00 5%, #ffdd7f 100%);
border: 1px solid #e5a900;
color: #4E4D4B;
font-weight: bold;
cursor: pointer;
width: 300px;
border-radius: 5px;
padding: 10px 0;
outline: none;
margin-top: 20px;
margin-left: 15%;
}
.submit:hover{
background: linear-gradient(#ffdd7f 5%, #ffbc00 100%);
}
.label_output
{
color:#4A85AB;
margin-left: 10px;
}
#result_id
{
text-align: center;
background-color: #FCD6F4;
height: 47px;
margin: 0 0 -29px 0;
padding-top: 12px;
border-radius: 8px 8px 0 0;
color: rgb(97, 94, 94);
}
#result_show
{
margin-top: 35px;
margin-left: 45px;
}
.input_box{
height:40px;
width:240px;
padding:20px;
border-radius:3px;
background-color:#FEFFED;
margin-left:30px;
}
img {
margin-left: 97px;
}
input#name {
margin-left: 45px;
}
input#email {
margin-left: 50px;
}
img#ref_symbol {
margin-left: 275px;
margin-top: -36px;
}
div#default_img {
display: none;
}
#img_resize{
position: absolute;
margin-left: 520px;
margin-top: -450px;
}
div#water_mark {
position: absolute;
}
div#rotate {
position: absolute;
}
input#height_cor{
margin-left: -2px;
}
input#width_cor {
margin-left: 20px;
}
p {
margin-left: 100;
color: green;
}

</style>

</div>
