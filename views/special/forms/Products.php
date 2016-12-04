<style>
    video{width:40%;margin:10px;}
    canvas{width:40%;margin:10px;}
    
</style>

<script> 
     $(document).on("change", "select[name='Products[id_category]']",function(){
	 $(this).closest("div.form-group").nextAll("div.form-group").has("select").each(function(i){$(this).remove();});
	 var obj=$(this).parent("div.form-group");
	 if($(this).val()>0){
	$.getJSON( '<?= Yii::$app->urlManager->createUrl(["categories/getparent"])?>&id='+$(this).val(), function( data ) {
	      var l=Object.keys(data).length;
	      if(l>0){
		  obj.after('<div class="form-group field-categories-name required"><label class="control-label" for="categories-parent">Sub Category</label><select id="categories-parent'+$("select").length + '" class="form-control last" name="Products[id_category]"><option value="0">Select Category</option></select><div class="help-block"></div></div>');
	      }
	      $.each( data, function( key, val ) {
		$("form select:last").append( "<option value='" + key + "'>" + val + "</option>" );
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
	    $("div.field-products-id_category").remove();
	    $("form#products-form div.row-float:last").append($("div#update-selects"));
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
	    echo "<div class='form-group'><label class='control-label' for='products-id_category'>Category</label><select class='form-control' name='Products[id_category]'><option value='0'>".__("Select Category")."</option>";
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
    <?php
	$useragent=$_SERVER['HTTP_USER_AGENT'];
	if(!preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))):
    ?>
    	$("div.capture").append('<div><video id="player" controls autoplay></video><canvas id="snapshot" width=400 height=320 style="display:none;"></canvas></div><div style="text-align:center;"><a href="#" id="capture" style="color:#fff;" onclick="$(\'div.capture\').hide();return false;"><i class="fa fa-camera fa-4x"></i></a></div>');  
	 $(".i-capture").on("click", function(){
	   $("#products-image").replaceWith($("#products-image").val('').clone(true));
	    $("div.capture").css("display", "block");
	});
	<?php
	    else:
	?>
	     $("input[type='file']").hide();
	     $("i.fa-camera").on("click", function(){ $("input[type='file']").click();});
	<?php
	    endif;
	?>
    $("input[type='file']").attr("accept","image/*").attr("capture","camera");
      
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
	  url: "upload.php",
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
