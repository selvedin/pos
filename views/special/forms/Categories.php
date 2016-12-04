<style>
    video{width:40%;margin:10px;}
    canvas{width:40%;margin:10px;}
    
</style>

<script>
    
     $(document).on("change", "select[name='Categories[parent]']",function(){
	 $(this).closest("div.form-group").nextAll("div.form-group").has("select").each(function(i){$(this).remove();});
	 var obj=$(this).parent("div.form-group");
	 if($(this).val()>0){
	$.getJSON( '<?= Yii::$app->urlManager->createUrl(["categories/getparent"])?>&id='+$(this).val(), function( data ) {
	      var l=Object.keys(data).length;
	      if(l>0){
		  obj.after('<div class="form-group field-categories-name required"><label class="control-label" for="categories-parent">Sub Category</label><select id="categories-parent'+$("select").length + '" class="form-control last" name="Categories[parent]"><option value="0">Select Category</option></select><div class="help-block"></div></div>');
	      }
	      $.each( data, function( key, val ) {
		$("select:last").append( "<option value='" + key + "'>" + val + "</option>" );
	      });

	    });
	    }
	    else{
		//$(this).parent("div.form-group").remove();
	    }
    });
    
    $("form").on("submit", function(){
	$("select").each(function(i){if($(this).val()==0){$(this).parent("div.form-group").remove();}});
    });
   <?php
	if(!$model->isNewRecord):
    ?>
	$(document).ready(function(){
	    $("div.field-categories-parent").remove();
	    $("div.new-added:last").remove();
	    $("label.new-label:first").text('<?= __("Category")?>');
	    $("div.field-categories-name").before($("div#update-selects"));
	});
    <?php
	endif;
    ?>
</script>

<?php
if(!$model->isNewRecord){   
    echo "<div id='update-selects'>";
	getSelect($model->id_category);
    echo "</div>";
    
}
  function getSelect($id)
    {	
	$next=\app\models\Categories::findOne($id);
	if(count($next)){
	    getSelect($next->parent);
	    echo "<div class='form-group new-added'><label class='control-label new-label' for='categories-parent'>".__("Sub Category")."</label><select class='form-control' name='Categories[parent]'><option value='0'>".__("Select Category")."</option>";
	    $opt = \app\models\Categories::find()->where("parent=$next->parent")->all();
	    foreach ($opt as $o) {
		$sel="";
		if($id==$o->id_category)
		    $sel="selected";
		echo "<option value='$o->id_category' $sel>$o->name</option>";
	    }
	    echo "</select><div class='help-block'></div></div>";
	}
    }
?>
<script>
    	$("div.capture").append('<div><video id="player" controls autoplay></video><canvas id="snapshot" width=400 height=320 style="display:none;"></canvas></div><div style="text-align:center;"><a href="#" id="capture" style="color:#fff;" onclick="$(\'div.capture\').hide();return false;"><i class="fa fa-camera fa-4x"></i></a></div>');  
	$("input[type='file']").attr("accept","image/*").attr("capture","camera");
       $(".i-capture").on("click", function(){
	   $("#categories-image").replaceWith($("#categories-image").val('').clone(true));
	    $("div.capture").css("display", "block");
	});
  var player = document.getElementById('player'); 
  var snapshotCanvas = document.getElementById('snapshot');
  var captureButton = document.getElementById('capture');

  var handleSuccess = function(stream) {
    // Attach the video stream to the video element and autoplay.
    player.src = URL.createObjectURL(stream);
  };

  captureButton.addEventListener('click', function() {
    var context = snapshot.getContext('2d');
    // Draw the video frame to the canvas.
    context.drawImage(player, 0, 0, snapshotCanvas.width, snapshotCanvas.height);
    var dataURL = snapshot.toDataURL();
    $("img.form-image").attr("src", dataURL);
    $.ajax({
	  type: "POST",
	  url: "upload.php?category=<?= $model->id_category?>",
	  data: { 
	     imgBase64: dataURL
	  }
	}).done(function(o) {
	  console.log('saved'); 
	  // If you want the file to be visible in the browser 
	  // - please modify the callback in javascript. All you
	  // need is to return the url to the file, you just saved 
	  // and than put the image in your browser.
	});
  });

  navigator.mediaDevices.getUserMedia({ audio: false, video: true }).then(handleSuccess);
</script>