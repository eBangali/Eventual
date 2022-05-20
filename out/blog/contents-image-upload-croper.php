<?php include_once (dirname(dirname(dirname(__FILE__))).'/initialize.php'); ?>
<?php include_once (dirname(dirname(dirname(__FILE__))).'/error-testing.php'); ?>
<?php
include_once(ebbd.'/dbconfig.php');
$adMin = new ebapps\dbconnection\dbconfig();
if(isset($adMin->eBAdminUserIsSet))
{
?>
<?php include_once (eblogin.'/session.inc.php'); ?>
<?php include_once (eblayout.'/a-common-header-icon.php'); ?>
<?php include_once (eblayout.'/a-common-header-title-one.php'); ?>
<?php include_once (eblayout.'/a-common-header-meta-noindex.php'); ?>
<?php include_once (eblayout.'/a-common-header-meta-scripts-croper.php'); ?>
<?php include_once (eblayout.'/a-common-page-id-start.php'); ?>
<?php include_once (eblayout.'/a-common-header.php'); ?>
<nav>
  <div class='container'>
    <div>
      <?php include_once (eblayout.'/a-common-navebar.php'); ?>
      <?php include_once (eblayout.'/a-common-navebar-index-blog.php'); ?>
    </div>
  </div>
</nav>
<?php include_once (eblayout.'/a-common-page-id-end.php'); ?>
<?php include_once (ebaccess."/access_permission_online_minimum.php"); ?>
<div class='container'>
  <div class='row row-offcanvas row-offcanvas-right'>
    <div class='col-xs-12 col-md-2'>

    </div>
    <div class='col-xs-12 col-md-7 sidebar-offcanvas'>
      <div class='well'>
        <h2 title='Profile Image:'>Profile Image:</h2>
        <p>Profile Image: .jpg NB: Image dimensions must be 1366 x 956 in pixels</p>
      </div>
      <?php include_once (ebblog.'/blog.php'); ?>
      <?php $merchant = new ebapps\blog\blog(); $merchant -> select_image_from_contents(); ?>
      <?php  if($merchant->eBData >= 1) { foreach($merchant->eBData as $val){ extract($val); ?>
      <div class='col-md-4'>
        <div class='image_area'>
          <form method='post'>
            <label for='upload_image'>
            <img src='<?php echo themeResource."/images/upload.png"; ?>' id='uploaded_image' class='img-responsive img-circle' />
            <div class='overlay'>
              <div class='text'>Change Image</div>
            </div>
            <input type='hidden' id='contents_id' value='<?php echo $contents_id; ?>'>
            <input type='file' name='image' class='image' id='upload_image' style='display:none'>
            </label>
          </form>
        </div>
      </div>
      <div class='modal fade' id='modal' tabindex='-1' role='dialog' aria-labelledby='modalLabel' aria-hidden='true'>
        <div class='modal-dialog modal-lg' role='document'>
          <div class='modal-content'>
            <div class='modal-header'>
              <h5 class='modal-title' id='modalLabel'>Crop Image Before Upload</h5>
              <button type='button' class='close' data-dismiss='modal' aria-label='Close'> <span aria-hidden="true">×</span> </button>
            </div>
            <div class='modal-body'>
              <div class='img-container'>
                <div class='row'>
                  <div class='col-md-8'> <img src='' id='sample_image' /> </div>
                  <div class='col-md-4'>
                    <div class='preview'></div>
                  </div>
                </div>
              </div>
            </div>
            <div class='modal-footer'>
              <button type='button' class='btn btn-secondary' data-dismiss='modal'>Cancel</button>
              <button type='button' class='btn btn-primary' id='crop'>Crop</button>
            </div>
          </div>
        </div>
      </div>
      <?php } } ?>
    </div>
    <div class='col-xs-12 col-md-3 sidebar-offcanvas'>
      <?php include_once ("contents-my-account.php"); ?>

    </div>
  </div>
</div>
<script>
$(document).ready(function(){
var $modal = $('#modal');
var image = document.getElementById('sample_image');
var contents_id = document.getElementById('contents_id').value;
var cropper;
$('#upload_image').change(function(event){
var files = event.target.files;
var done = function (url) {
image.src = url;
$modal.modal('show');
};
if (files && files.length > 0)
{
reader = new FileReader();
reader.onload = function (event) {
done(reader.result);
};
reader.readAsDataURL(files[0]);
}
});
$modal.on('shown.bs.modal', function() {
cropper = new Cropper(image, {
aspectRatio: 1.428172942817294,
viewMode: 3,
preview: '.preview'
});
}).on('hidden.bs.modal', function() {
cropper.destroy();
cropper = null;
});

$("#crop").click(function(){
canvas = cropper.getCroppedCanvas({
width: 1366,
height: 956,
});
canvas.toBlob(function(blob) {
var reader = new FileReader();
reader.readAsDataURL(blob); 
reader.onloadend = function() {
var base64data = reader.result;  
$.ajax({
url: "contents-image-upload-croper-url.php",
method: "POST",                	
data: {image: base64data, contents_id: contents_id},
success: function(data){
/*
console.log(data);
*/
$modal.modal('hide');
/*
$('#uploaded_image').attr('src', data);
*/
window.location.replace('contents-items-status.php');
}
});
}
});
});	
});
</script>
<?php include_once (eblayout.'/a-common-footer-edit.php'); ?>
<?php
}
else
{
header("Location: ".outLink."/access/admin-register.php");
}
?>

