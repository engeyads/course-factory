<?php 
// $prompt = $_GET['prompt'];
$prompt = $_POST['prompt'];
$type = $_POST['type'];
$httpurl = $_POST['httpurl'];
$system = $_POST['system'];
$txtval = $_POST['txtval'];
 $prompt = str_replace(array('{NAME}'), $txtval, $prompt);
?>
 
<div class="input-group mb-3"> 
  <span class="input-group-text">System</span>
  <textarea class="form-control" name="system" id="system" aria-label="With textarea"><?php echo $system ;?></textarea>
</div>
<div class="input-group"> 
  <span class="input-group-text">The Prompt</span>
  <textarea class="form-control" name="prompt" id="prompt" aria-label="With textarea"><?php echo $prompt ;?></textarea>
</div>

<button id="startButton" class="btn btn-secondary px-5 mt-3"  type="button">Try</button>
<button  id="loadingMessage"  class="btn btn-secondary px-5 mt-3" type="button" disabled="" style="display: none;"> <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>Loading...</button>

<div class="input-group mt-3 mb-3" style="display: none;" id="resgroup" > 
  <span class="input-group-text">Result</span>
  <textarea id="ai" name="ai" class="form-control" aria-label="With textarea"></textarea>
</div>



<script type="text/javascript">
$(document).ready(function(){
  $("#startButton").click(function(){
    var prompttextareavalue = $("#prompt").val();
    var systemtextareavalue = $("#system").val();

    var aiprocessurl = '<?php echo $httpurl; ?>'+'tables/aiprocess.php';
<?php if(strpos($httpurl, "localhost")){ ?>console.log(aiprocessurl);<?php } ?>
    $.ajax({
      url: aiprocessurl,
      type: 'POST',
      data: {
        httpurl: '<?php echo $httpurl; ?>',
        prompt: prompttextareavalue.replace(/\n/g, '\\n'),
        system: systemtextareavalue,
        type: '<?php echo $type; ?>',
        txtval: '<?php echo preg_replace('/<\/?[^>]+(>|$)/', '', $txtval); ?>',
      },
      beforeSend: function() {
        // Hide the button and show the loading message before the request is made
        $("#startButton").hide();
        $("#loadingMessage").show();
      },
      success: function(data) {
        // Update your textarea with the response
        $("#ai").val(data);
        // Show the textarea
        $("#resgroup").show();
      },
      complete: function() {
        // Show the button and change its text to "Try Again" after the request completes
        $("#startButton").text("Try Again");
        $("#startButton").show();
        $("#loadingMessage").hide();
      },
      error: function(error) {
        console.log("Error: ", error);
      }
    });
  });
});
</script>
