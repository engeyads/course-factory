<?php
use Google\Service\Analytics;
use Google\Auth\CredentialsLoader;
use Google\Auth\OAuth2;
// use Google\Client;
use Google\Service\Webmasters;

use GuzzleHttp\Client;
use Google\Auth\Credentials\ServiceAccountCredentials;
use GuzzleHttp\Exception\RequestException;


$openai_api_key = "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";
function call_openai($prompt, $openai_api_key,$system){

  global $gptmode;

    $url = "https://api.openai.com/v1/chat/completions";
    $ch = curl_init($url);
    $postData = json_encode([
        'model' => "gpt-4-1106-preview", 
        'messages' => [
              ["role" => "system", "content" => $system],
              ["role" => "user", "content" => $prompt],
          ]
    ]);
    $http_headers = array(
        "Content-Type: application/json",
        "Authorization: Bearer " . $openai_api_key
    );
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, $http_headers);
    $result = curl_exec($ch);
    if(curl_errno($ch)){
        echo 'Curl error: ' . curl_error($ch);
    }
    $result_array = json_decode($result, true);
        return $result_array;
}
function createAdminLogTable($conn) {
    // Check connection
    if ($conn->connect_error ) {
        die("Connection failed: " . $conn->connect_error);
    }
    // Check if the connection is valid
    if ($conn === null) {
      die("Database connection is not valid.");
  }
    // Check if table exists
    $result = $conn->query("SHOW TABLES LIKE 'admin_log'");
    if($result->num_rows == 0) {
        // Table does not exist, so create it.
        $sql = "CREATE TABLE `admin_log` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `user` varchar(50) NOT NULL,
          `tablename` varchar(50) DEFAULT NULL,
          `columnname` varchar(50) DEFAULT NULL,
          `row_id` varchar(50) DEFAULT NULL,
          `oldData` text DEFAULT NULL,
          `newData` text DEFAULT NULL,
          `action` varchar(50) NOT NULL,
          `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8" ; 
        if ($conn->query($sql) === TRUE) {
          echo "Table admin_log created successfully";
        } else {
          echo "Error creating table: " . $conn->error;
        }
    }
}
 function CreateKeywordsTables($conn) {
    // Create table : keywords
    $sql = "CREATE TABLE IF NOT EXISTS keywords (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    keyword VARCHAR(255) COLLATE  utf8_general_ci  NOT NULL UNIQUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    published_at TIMESTAMP NULL DEFAULT NULL,
    deleted_at TIMESTAMP NULL DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE= utf8_general_ci ";
    if ($conn->query($sql) !== TRUE) {
        echo "Error creating table: keywords " . $conn->error;
    }
    // Create table: tags
    $sql = "CREATE TABLE IF NOT EXISTS tags (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        tag VARCHAR(255) COLLATE utf8_general_ci NOT NULL UNIQUE,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NULL DEFAULT NULL,
        published_at TIMESTAMP NULL DEFAULT NULL,
        deleted_at TIMESTAMP NULL DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ";
    if ($conn->query($sql) !== TRUE) {
        echo "Error creating table: tags " . $conn->error;
    }
    // Create table: keyword_tag_relation
    $sql = "CREATE TABLE IF NOT EXISTS keyword_tag (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        keyword_id INT(6) UNSIGNED NOT NULL,
        tag_id INT(6) UNSIGNED NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NULL DEFAULT NULL,
        published_at TIMESTAMP NULL DEFAULT NULL,
        deleted_at TIMESTAMP NULL DEFAULT NULL,
        FOREIGN KEY (keyword_id) REFERENCES keywords(id),
        FOREIGN KEY (tag_id) REFERENCES tags(id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ";
    if ($conn->query($sql) !== TRUE) {
        echo "Error creating table: keyword_tag_relation " . $conn->error;
    }
}
function generateTableSelect($conn) {
    $userlevel = $_SESSION['userlevel']; // Get the user level from the session
    $username = $_SESSION['username']; // Get the username from the session
    // Check if the user level is 10 or above
    if ($userlevel >= 10) {
        $query = "SELECT id, name FROM db WHERE deleted_at IS NULL";
    } else {
        $query = "SELECT d.id, d.name FROM db AS d 
                  INNER JOIN user_db AS ud ON d.id = ud.db_id 
                  INNER JOIN users AS u ON u.id = ud.user_id 
                  WHERE u.username = '" . mysqli_real_escape_string($conn, $username) . "' AND d.deleted_at IS NULL";
    }
    $result = mysqli_query($conn, $query);
    // Check if query execution was successful
    if ($result && mysqli_num_rows($result) > 0) {
        $options = ''; // Variable to store the HTML for select options
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $name = $row['name'];
            $options .= "<option value=\"$id\"";
            if (isset($_SESSION['db']) && $_SESSION['db'] == $id) {
                $options .= " selected";
            }
            $options .= ">$name</option>";
        }
        // Generate the HTML for the select element
        $selectHTML = <<<HTML
        <div> <select class="single-select" name="db" onchange="setSessionDB(this.value)">
                <option value="">Select a Website</option>
                $options
            </select>
        </div>
HTML;
        return $selectHTML;
    } else {
        return "No tables found.";
    }
}
function GetRow($id,$fieldname, $tablename , $conn2){
    if ($id) {
        $Query = "SELECT * FROM $tablename WHERE  `$fieldname` = '$id' ";
     
        $Result = mysqli_query($conn2, $Query);
        if ($Result) {
            $row = mysqli_fetch_assoc($Result);
            return $row;
        } else {
            $row = [];
            return "Error: " . mysqli_error($conn2);
        }
    }
}

// function select whole table return array of rows
function GetForSelect($tablename , $conn2, $idname='id', $name='name' , $order= '',$page=0,$limit=''){
  $limit = $limit ? " LIMIT $page , $limit " : '';
  $Query = "SELECT $idname , $name FROM $tablename  $order $limit";
  $Result = mysqli_query($conn2, $Query);
  $options = array();
  if ($Result) {
      while ($row = mysqli_fetch_assoc($Result)) {
          $options[$row[$idname]] = $row[$name];
      }
  } else {
      error_log("Error: " . mysqli_error($conn2));
  }
  return $options;
}



function JsonArrayAsText($json) {
  if ($json != null) {
      $array = json_decode($json, true);
  }else{
      $array = [];
  }
  
    $text = '';
    // Check if json_decode was able to parse the JSON
    if ($array === null && json_last_error() !== JSON_ERROR_NONE) {
        //echo 'Unable to parse JSON: ' . json_last_error_msg();
        return;
    }else{
        foreach ($array as $item) {
            // Check if the 'value' key exists in the current item
            if (isset($item['value'])) {
                $text .= $item['value'] . "\n";
            }
        }
        return trim($text);
    }
}
function TextasJsonArray($text) {
    $array = explode("\n", $text);
    $json = [];
    foreach ($array as $item) {
        $item = trim($item);
        $json[] = ['value' => $item];
    }
    return json_encode($json);
}
function FormsStart() {
    global $id , $url , $folderName,$tabletitle,$updateurl;
    if (isset($_POST['message'])) {
      $response = json_decode($_POST['message'], true);
      if ($response) {
          if ($response['success']) {
              echo '<div class="alert alert-success">' . $response['message'] . '</div>';
          } else {
              echo '<div class="alert alert-danger">' . $response['message'] . '</div>';
          }
      }
  }
    echo '<div class="row">
    <div id="messageDiv"></div>  
        <div class="  mx-auto">
            <h1>' . ($id ? "Edit " : "Add ") . $tabletitle . '</h1>
            <hr/>
            <div class="card">
                <div class="card-body"> ';
    echo '<script>var validate = true;</script><form id="theform" method="post" action=" '. $url.$folderName.'/'.$updateurl.'" enctype="multipart/form-data" class="row g-3">';
    if ($id) {
        echo '<input type="hidden" name="id" value="' . $id . '">';
    }
}
function FormsInput($name, $title, $type, $required = false, $class = '', $count = false, $countmin = 20, $countmax = 25,$validby='', $ai = false, $prompt ='',$system ='',$editable = true,$nullable = true) {
    global $row, $Columns, $folderName,$httpurl,$gptmode;
    $value = $row[$name] ?? '';
    if(!$editable){
      $editable = 'readonly';
    }
    if ($required) {
        $required = 'required';
    }
    if($validby!== ''){
        $validbyclass = 'validby';
    }else{
        $validbyclass = '';
    }
    if (in_array($name, $Columns)) {
        $countspan = '';
        if($count ) {
            $countspan = '
            <div class="progress">
                <div id="charCount'.$name.'" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                    <span class="current-value " data-valid="'.($required!=='required' ? "true" : "false").'">0%</span>
                </div>
            </div>';
        }
        $modalLink = $ai ? '<a href="#" class="" data-bs-toggle="modal" data-bs-target="#myModal'.$name.'" id="openModalLink'.$name.'">AI</a>' : '';
        echo '
            <div class="'.$class.' mb-3">
                <label class="form-label">'.$title.'
                    '.$modalLink.'
                </label>
                <input class="form-control mb-3 '.$validbyclass.'" type="'.$type.'" placeholder="'.$title.'" name="'.$name.'" id="'.$name.'" value="'.$value.'" '.$required.' '.$editable.'>
                '.$countspan.'
            </div>';

            if($ai ? "true" : "false") {
            echo '
            <!-- Modal HTML -->
            <div class="modal fade" id="myModal'.$name.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">'.$title.' AI</h5>
                        </div>
                        <div class="modal-body" id="modalBodyContent'.$name.'">
                            <!-- Content will be filled via AJAX -->
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"  id="changeInput'.$name.'" data-bs-dismiss="modal">Change Input</button>  <!-- New button -->
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                        <small class="text-right d-flex justify-content-end p-2 pt-0">'.$gptmode.'</small>
                    </div>
                </div>
            </div>';

            echo '
    <script>
    
    $(document).ready(function() {
        if(' . ($ai ? 'true' : 'false') . ') {
            $("#openModalLink'.$name.'").click(function(e){
                e.preventDefault();
                var txtval = $("#'.$name.'").val();
                $.ajax({
                  url: "'.$httpurl.'tables/aimodal.php",
                  type: "POST",
                  data: {
                    httpurl: "'.$httpurl.'",
                    prompt: `'.str_replace('<br />', '', nl2br($prompt)).'`,
                    system: "'.$system.'",
                    type: "'.$type.'",
                    txtval: txtval.replace(/<\/?[^>]+(>|$)/g, ""),
                  },
                  success: function(result){
                        if (result.includes("Error:")) {
                            $("#modalBodyContent'.$name.'").html(result);
                        } else {
                            
                        }
                    },
                    error: function() {
                        $("#modalBodyContent'.$name.'").html("Error: Unable to load content.");
                    }
                });
            });
            '.($count ? '
            $(document).on("click", "#changeInput'.$name.'", function(){
                var textareaContent = $("#ai").val();
                $("#'.$name.'").val(textareaContent).trigger("input");
                if('.$count.') {
                    window.updateCount'.$name.'();
                    '.(!$nullable ? 'if(textareaContent !== ""){' : '').'
                      var confirmation = confirm("Are you sure you want to change? ");
                      if (confirmation) {
                          $("#theform").submit();
                      }
                    '.(!$nullable ? '}else{
                      $(this).parent().parent().find("#modalBodyContenttitle #loadingMessage").after("<div class=\"alert alert-danger\">'.$title.' is required.</div>");
                      
                    }' : '').'
                }
            });
            ' : '').'
        }
    });
    </script>';
        }
        echo '<script>
        $(document).ready(function() {
          var fnc = "updateCount'.$name.'";
          window[fnc] = function() {
          };
            if(' . ($count ? 'true' : 'false') . ') {
                  window[fnc] = function() {
                    let validbyval = "";
                    '.($validby !== '' ? 'if($("#'.$validby.'")) { validbyval = $("#'.$validby.'").val();}else{validbyval =""}' : '').'
                    if(validbyval !== ""){
                        if($("#'.$name.'").val().length < '.$countmin.' || $("#'.$name.'").val().length > '.$countmax.') {
                        //document.getElementById("messageDiv").innerHTML = "<div class=\"alert alert-danger\">Length not within the allowable range.</div>";
                        $("#charCount'.$name.' .current-value").addClass("notvalid");
                    }else{$("#charCount'.$name.' .current-value").removeClass("notvalid");}}
    
                    '.($required === "required" ? '
                    if($("#'.$name.'").val().length < '.$countmin.' || $("#'.$name.'").val().length > '.$countmax.') {
                        //document.getElementById("messageDiv").innerHTML = "<div class=\"alert alert-danger\">Length not within the allowable range.</div>";
                        $("#charCount'.$name.' .current-value").addClass("notvalid");
                    }else{$("#charCount'.$name.' .current-value").removeClass("notvalid");}':'').'
    
                    var box = $("#'.$name.'").val();
                    var main = box.length * '.$countmax.';
                    var value = (main / '.$countmax.');
                    var count = 0 + box.length;
                    var count1 = 0 + (box.length/'.$countmax.')*100;
                    var reverse_count = '.$countmax.' - box.length;
                    if(box.length >= 0){
                        $("#charCount'.$name.'").css("width", count1 + "%");
                        $("#charCount'.$name.' .current-value").text(count);
                        $(".count'.$name.'").text(reverse_count);
                        if (count >= '.$countmin.' && count <= '.$countmax.'){
                            $("#charCount'.$name.'").removeClass("progress-bar-warning");
                            $("#charCount'.$name.'").removeClass("progress-bar-danger");
                            $("#charCount'.$name.'").addClass("progress-bar-success");
                        }
                        if (count > '.$countmax.'){
                            $("#charCount'.$name.'").removeClass("progress-bar-warning");
                            $("#charCount'.$name.'").removeClass("progress-bar-success");
                            $("#charCount'.$name.'").addClass("progress-bar-danger");
                        }
                        if(count >= 0 && count < '.$countmin.'){
                            $("#charCount'.$name.'").removeClass("progress-bar-danger");
                            $("#charCount'.$name.'").removeClass("progress-bar-success");
                            $("#charCount'.$name.'").addClass("progress-bar-warning");
                        }
                    }
                };
                $("#'.$name.'").on("keyup input", window[fnc]);
                $("#'.$name.'").keyup();
            }
        });
        </script>';
        
  } 
}
function FormsImg($name, $title,  $class , $url, $colnm,$tblname,$remoteDirectory,$ftpfolderName,$ratio='1.5',$imgWidth=400,$maxkb=40) {
    global $row, $Columns;
    
    $value = $row[$name] ?? '';
    echo '
    <script>
        var col = "' . $colnm . '";
        var cropRatio'.$name.' = '.$ratio.';
    </script>';
    
        if ($value == '') {
            echo '
            <div class="  '.$class.'  mb-1">
                <label class="form-label">Upload ' . $title . '</label>
                <img id="imagePrev' . $name . '"  src="" alt=""  class="img-responsive"  title="" style="border:none">
                <div class=" mt-2 "> 
                    <span id="viewImagesButton' . $name . '" onclick="" class="btn btn-secondary"  type="button"  >Select ' . $title . '</span> 
                </div>
                
                <input aria-hidden="true" type="hidden" type="text" id="selectedImage' . $name . '" class="form-control hidden '.$class.' " name="' . $name . '">
                <div class="input-group-append ">
                </div>
            </div>
            <input type="hidden" name="old' . $name . '" id="' . $name . '" value="NULL" >
            <div id="imageModal' . $name . '" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
                <div id="modal-dialog' . $name . '" class="modal-dialog modal-xl" role="document">
                    <div id="modal-content' . $name . '" class="modal-content ">
                        <div class="modal-header">
                        '.$remoteDirectory.'/'.str_replace("-", "/",$ftpfolderName).'
                            <h5 class="modal-title" id="imageModalLabel">Upload or select Image</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="m-3">
                            <input type="file" class="form-control" id="imageInput' . $name . '" accept="image/*">
                            <input type="hidden" id="imageName">
                            <br>
                            <div  class="mt-3 row">
                                <img id="imagePreview' . $name . '"  class="img-responsive"  src="" alt="Preview1">
                            </div>
                            <canvas id="croppedCanvas' . $name . '" style="display: none;"></canvas>
                            <img id="convertedPreview' . $name . '" class="img-responsive" src="" alt="Converted Preview" style="display: none;">
                            <br>
                            <div>
                                <span id="uploadButton' . $name . '" class="btn btn-secondary"  type="button"  disabled>Upload</span>
                            </div>
                        </div>
                        <div class="selectimages">
                        </div>
                    </div>
                </div>
            </div>
            <script>
          $("#uploadButton'.$name.'").hide();
          $("#imagePreview'.$name.'").hide();
          let cropper'.$name.';
          function updateCropRatio'.$name.'(ratio) {
            cropper'.$name.' && cropper'.$name.'.setAspectRatio(ratio);
          }
          function b64toBlob'.$name.'(base64, type, chunkSize) {
            type = type || "";
            chunkSize = chunkSize || 512;
            const binary'.$name.' = atob(base64);
            const blobArray'.$name.' = [];
            for (let offset = 0; offset < binary'.$name.'.length; offset += chunkSize) {
              const slice'.$name.' = binary'.$name.'.slice(offset, offset + chunkSize);
              const uint8Array'.$name.' = new Uint8Array(slice'.$name.'.length);
              for (let i = 0; i < slice'.$name.'.length; i++) {
                uint8Array'.$name.'[i] = slice'.$name.'.charCodeAt(i);
              }
              const blobPart'.$name.' = new Blob([uint8Array'.$name.'], { type: type });
              blobArray'.$name.'.push(blobPart'.$name.');
            }
            return new Blob(blobArray'.$name.', { type: type });
          }
          $("#imageInput'.$name.'").on("change", function (event) {
            var file'.$name.' = event.target.files[0];
            var reader'.$name.' = new FileReader();
            reader'.$name.'.onload = function (event) {
                $("#imagePreview'.$name.'").attr("src", event.target.result);
              var img'.$name.' = new Image();
              img'.$name.'.onload = function () {
                if (cropper'.$name.') {
                  cropper'.$name.'.destroy(); // Destroy the existing Cropper instance
                }
                cropper'.$name.' = new Cropper($("#imagePreview'.$name.'")[0], {
                  aspectRatio: cropRatio'.$name.',
                  viewMode: 2
                });
                $(".cropper-modal").hide();
                //$("#croppedCanvas'.$name.'").css("display", "none");
                $("#uploadButton'.$name.'").show();
                // Resize the image to fit within maxKB
              resizeImageToMaxKB(img'.$name.', '.$maxkb.') // Replace 20 with maxKB value
              .then((resizedBlob) => {
                var resizedImage'.$name.' = new Image();
                resizedImage'.$name.'.src = URL.createObjectURL(resizedBlob);
                // Update the image preview with the resized image
                $("#imagePreview'.$name.'").attr("src", resizedImage'.$name.'.src);
              })
              .catch((error) => {
                console.error(error);
              });
            };
            img'.$name.'.src = event.target.result;
            };
            reader'.$name.'.readAsDataURL(file'.$name.');
          });
          function updateCropRatio'.$name.'(ratio) {
            cropRatio'.$name.' = ratio;
            if (cropper'.$name.') {
              cropper'.$name.'.setAspectRatio(ratio);
            }
          }
          function resizeImageToMaxKB(image, maxKB) {
          return new Promise((resolve, reject) => {
            const maxPixels = image.width * image.height;
            const qualityStep = 0.05;
            let quality = 1;
        
            const canvas = document.createElement(\'canvas\');
            const ctx = canvas.getContext(\'2d\');
        
            const resizeImage = (q) => {
              canvas.width = image.width;
              canvas.height = image.height;
        
              ctx.clearRect(0, 0, canvas.width, canvas.height);
              ctx.drawImage(image, 0, 0, canvas.width, canvas.height);
        
              canvas.toBlob((blob) => {
                if (blob.size <= maxKB * 1024 || quality <= 0) {
                  resolve(blob);
                } else {
                  quality -= qualityStep;
                  resizeImage(quality);
                }
              }, \'image/jpeg\', q);
            };
        
            resizeImage(quality);
          });
        }
          function resizeImage(image, width, maxSizeKB) {
            var canvas = document.createElement("canvas");
            var ctx = canvas.getContext("2d");
            var aspectRatio = image.width / image.height;
            var height = width / aspectRatio;
          
            canvas.width = width;
            canvas.height = height;
            ctx.drawImage(image, 0, 0, width, height);
          
            var resizedImage = new Image();
            resizedImage.src = canvas.toDataURL(\'image/jpeg\', 0.7); // Adjust quality (0.0 - 1.0) as needed

            // Convert the data URL to a Blob object
            var blobDataUrl = canvas.toDataURL(\'image/jpeg\', 0.7); // Adjust quality (0.0 - 1.0) as needed
            var blob = dataURLtoBlob(blobDataUrl);

            // Perform additional compression if required
            if (blob.size / 1024 > maxSizeKB) {
              var compressedImage = compressImage(blob, maxSizeKB);
              resizedImage.src = URL.createObjectURL(compressedImage);
            }

            return resizedImage;
          }
          $("#uploadButton'.$name.'").click(function () {
            const croppedImage'.$name.' = cropper'.$name.'.getCroppedCanvas().toDataURL("image/webp");
            const convertedImage'.$name.' = croppedImage'.$name.';
            const canvasData'.$name.' = convertedImage'.$name.'.split(",")[1];
            const blob'.$name.' = b64toBlob'.$name.'(canvasData'.$name.', "image/webp");
            const timestamp'.$name.' = Date.now();
            const imageName'.$name.' = timestamp'.$name.' + ".webp";
            const formData'.$name.' = new FormData();
            formData'.$name.'.append("file", blob'.$name.', imageName'.$name.');
            const xhr'.$name.' = new XMLHttpRequest();
            xhr'.$name.'.open("POST", "/uploadimage/" + imageName'.$name.' + "/'.$remoteDirectory.'/'.$ftpfolderName.'", true);
            xhr'.$name.'.setRequestHeader("X-Requested-With", "XMLHttpRequest");
            xhr'.$name.'.onload = function () {
              if (xhr'.$name.'.status === 200) {
                console.log(xhr'.$name.'.responseText);
                const response'.$name.' = JSON.parse(xhr'.$name.'.responseText);
                if (response'.$name.'.success) {
                  const imageUrl'.$name.' = response'.$name.'.imageUrl;
                  $(".cropper-container").hide();
                  handleImageClick'.$name.'(response'.$name.'.image, imageUrl'.$name.')
                  $("#imageInput'.$name.'").val("");
                  $("#imagePreview'.$name.'").attr({"src": ""});
                  $("#imagePreview'.$name.'").hide();
                  $("#uploadButton'.$name.'").hide();
                } else {
                  alert("Upload failed: " + response'.$name.'.message);
                }
              } else {
                alert("Upload failed. Please try again.");
              }
            };
            xhr'.$name.'.send(formData'.$name.');
          });
          function b64toBlob'.$name.'(b64Data, contentType, sliceSize) {
            contentType = contentType || "";
            sliceSize = sliceSize || 512;
            const byteCharacters'.$name.' = atob(b64Data);
            const byteArrays'.$name.' = [];
            for (let offset = 0; offset < byteCharacters'.$name.'.length; offset += sliceSize) {
              const slice'.$name.' = byteCharacters'.$name.'.slice(offset, offset + sliceSize);
              const byteNumbers'.$name.' = new Array(slice'.$name.'.length);
              for (let i = 0; i < slice'.$name.'.length; i++) {
                byteNumbers'.$name.'[i] = slice'.$name.'.charCodeAt(i);
              }
              const byteArray'.$name.' = new Uint8Array(byteNumbers'.$name.');
              byteArrays'.$name.'.push(byteArray'.$name.');
            }
            const blob'.$name.' = new Blob(byteArrays'.$name.', { type: contentType });
            return blob'.$name.';
          }
          var modalContent'.$name.' = $("<div class=\'selectimages'.$name.'\'></div>");
          var modalBody'.$name.' = $("<div class=\' row modal-body mt-1 '.$name.' \'></div>");
          function modaldata'.$name.'() {
            $(".modal-body.'.$name.'").html("<div class=\' row modal-body mt-1 '.$name.' \'></div>");
            modalContent'.$name.'.append(modalBody'.$name.');
            $.ajax({
              url: "/getimageurl/",
            type: "GET",
            data:{
              t : "'.$tblname.'",
              c : "'.$colnm.'",
              "col" : "'.$name.'",
              url : "'.$url.'",
            },
              dataType: "json",
              success: function (response) {
                if (response && response.length > 0) {
                  for (var i = 0; i < response.length; i++) {
                    var imageUrl'.$name.' = response[i].url;
                    var used'.$name.' = response[i].selected;
                    var table'.$name.' = $("<table class=\'table table-bordered\'></table>");
                  for (var j = 0; j < used'.$name.'.length; j++) {
                    var row'.$name.' = $("<tr></tr>");
                    var cell'.$name.' = $("<td></td>").text(used'.$name.'[j]);
                    row'.$name.'.append(cell'.$name.');
                    table'.$name.'.append(row'.$name.');
                  }
                  var usedin'.$name.' = $("<div id=\'usedin" + imgID'.$name.' + "\' class=\'usedin\'></div>");
                  usedin'.$name.'.append(table'.$name.');
                    var imageName'.$name.' = imageUrl'.$name.'.substring(imageUrl'.$name.'.lastIndexOf("/") + 1);
                    var imgID'.$name.' = "img" + imageName'.$name.'.substring(0, imageName'.$name.'.lastIndexOf("."));
                    var imgdiv'.$name.' = $("<div id=\'" + imgID'.$name.' + "\' class=\'imgs'.$name.' col-2 " + imgID'.$name.' + "\'></div>");
                    var delbtn'.$name.' = "<div id=\'delete'.$name.'Container" + imgID'.$name.' + "\'></div><div id=\'deletebtn" + imgID'.$name.' + "\' onclick=\'deleteImage'.$name.'(\"" + imageName'.$name.' + "\",\"" + imgID'.$name.' + "\")\' class=\' btn-delete delete-button mb-3 \' data-image-path=\'" + imageName'.$name.' + "\'><i class=\'bi bi-trash\'></i></div>";
                    var imageElement'.$name.' = $("<img  class=\'img-responsive\' >").attr({
                      src: "/tables/image.php?img=" + imageUrl'.$name.',
                      alt: imageName'.$name.',
                      "data-image-path": imageName'.$name.'
                    });
                    imgdiv'.$name.'.append(imageElement'.$name.');
                    imgdiv'.$name.'.append(usedin'.$name.');
                    imgdiv'.$name.'.append(delbtn'.$name.');
                    //imgdiv'.$name.'.append(selbtn'.$name.');
                    modalBody'.$name.'.append(imgdiv'.$name.');
                  }
                  $("#imageModal'.$name.' .modal-dialog .modal-content .selectimages").replaceWith(modalContent'.$name.');
                  $("#imageModal'.$name.'").modal("show");
                } else {
                  $("#imageModal'.$name.' .modal-dialog .modal-content .selectimages").replaceWith("No images found.");
                  $("#imageModal'.$name.'").modal("show");
                }
              },
              error: function () {
                $("#imageModal'.$name.' .modal-dialog .modal-content .selectimages").replaceWith("Faild to fetch images.");
                $("#imageModal'.$name.'").modal("show");
              }
            });
          }
          $("#viewImagesButton'.$name.'").click(function () {
            modaldata'.$name.'();
          });
          function deleteImage'.$name.'(image, id) {
            var btn'.$name.' = $("#deletebtn" + id);
            btn'.$name.'.hide();
            // Replace delete button with confirm buttons
            var confirmButtons'.$name.' = $("<span id=\'text" + id + "\' class=\'small\'>Are you Sure?</span>" +
              "<div id=\'confirmButtons" + id + "\' class=\' confirm-grid\' role=\'group\' aria-label=\'Confirm Buttons\'>" +    
              "<span id=\'confirmYes" + id + "\' class=\'btn btn-danger confirm-grid-item\'>Yes</span>" +
              "<span id=\'confirmNo" + id + "\' class=\'btn btn-secondary confirm-grid-item\'>No</span>" +
              "</div>");
            $("#delete'.$name.'Container" + id).html(confirmButtons'.$name.');
            // Attach click event handlers to confirm buttons
            $("#confirmYes" + id).click(function () {
              var xhr'.$name.' = new XMLHttpRequest();
              xhr'.$name.'.open("GET", "/deleteimage/" + image + "/'.$name.'/'.$tblname.'/'.$remoteDirectory.'/'.$ftpfolderName.'" , true);
              xhr'.$name.'.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
              xhr'.$name.'.onload = function () {
                if (xhr'.$name.'.status === 200) {
                  var response'.$name.' = JSON.parse(xhr'.$name.'.responseText);
          
                  if (response'.$name.'.success === true) {
                    var imageClass'.$name.' = response'.$name.'.image.substring(0, response'.$name.'.image.lastIndexOf("."));
                    console.log(imageClass'.$name.');
                    $(".img" + imageClass'.$name.').remove();
                  } else {
                  }
                } else {
                  console.log("Error deleting image");
                }
              };
              xhr'.$name.'.onerror = function () {
                console.log("Request failed");
              };
              xhr'.$name.'.send("image=" + encodeURIComponent(image));
          
            });
            $("#confirmNo" + id).click(function () {
              // Restore delete button
              $("#delete'.$name.'Container" + id).html("");
              btn'.$name.'.show();
          
            });
          }
          // Function to handle image click event
          function handleImageClick'.$name.'(imageName, imagePreview1) {
            // Set the value of the input field outside the modal to the clicked image name
            $("#selectedImage'.$name.'").val(imageName);
            $("#imagePrev'.$name.'").attr("src", imagePreview1);
            // Hide the modal after clicking on the image
            $("#imageModal'.$name.'").modal("hide");
          }
          $(document).on("click", ".imgs'.$name.' img", function () {
            var imageName'.$name.' = $(this).attr("data-image-path"),
              imagePreview1'.$name.' = $(this).attr("src");
            handleImageClick'.$name.'(imageName'.$name.', imagePreview1'.$name.');
            if (cropper'.$name.') {
              cropper'.$name.'.destroy(); // Destroy the existing Cropper instance
            }
          });
          $("#imageModal'.$name.' .close").click(function () {
            $("#imageModal'.$name.'").modal("hide");
          });
          $(document).ready(function () {
          });
          </script>';
        }else{
        $img = $url .  $value ;
        echo '
        <div class="  '.$class.'  mb-1">
            <label class="form-label">Upload ' . $title . '</label>
            <img id="imagePrev' . $name . '"  src="'.$img .'" alt="'. $value .'"  class="img-responsive"  title="'.$value .'" style="border:none">
            <div class=" mt-2 "> 
                <span id="viewImagesButton' . $name . '" onclick="" class="btn btn-secondary"  type="button"  >Select ' . $title . '</span> 
            </div>
            
            <input aria-hidden="true" type="hidden" type="text" id="selectedImage' . $name . '" value="' . $value . '" class="form-control hidden '.$class.' " name="' . $name . '">
            <div class="input-group-append ">
            </div>
        </div>
        <input type="hidden" name="old' . $name . '" id="' . $name . '" value="' . $value . '" >
        <div id="imageModal' . $name . '" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div id="modal-dialog' . $name . '" class="modal-dialog modal-xl" role="document">
            
                <div id="modal-content' . $name . '" class="modal-content ">
                    <div class="modal-header">
                    '.$remoteDirectory.'/'. str_replace("-", "/",$ftpfolderName).'
                        <h5 class="modal-title" id="imageModalLabel' . $name . '">Upload or select Image</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="m-3">
                        <input type="file" class="form-control" id="imageInput' . $name . '" accept="image/*">
                        <input type="hidden" id="imageName">
                        <br>
                        <div  class="mt-3 row">
                            <img id="imagePreview' . $name . '"  class="img-responsive"  src="" alt="Preview1">
                        </div>
                        <canvas id="croppedCanvas' . $name . '" style="display: none;"></canvas>
                        <img id="convertedPreview' . $name . '" class="img-responsive" src="" alt="Converted Preview" style="display: none;">
                        <br>
                        <div>
                            <span id="uploadButton' . $name . '" class="btn btn-secondary"  type="button"  disabled>Upload</span>
                        </div>
                    </div>
                    <div class="selectimages">
                    </div>
                </div>
            </div>
        </div>
        <script>
        $("#uploadButton'.$name.'").hide();
        $("#imagePreview'.$name.'").hide();
        let cropper'.$name.';
        function updateCropRatio'.$name.'(ratio) {
          cropper'.$name.' && cropper'.$name.'.setAspectRatio(ratio);
        }
        function b64toBlob'.$name.'(base64, type, chunkSize) {
          type = type || "";
          chunkSize = chunkSize || 512;
          const binary'.$name.' = atob(base64);
          const blobArray'.$name.' = [];
          for (let offset = 0; offset < binary'.$name.'.length; offset += chunkSize) {
            const slice'.$name.' = binary'.$name.'.slice(offset, offset + chunkSize);
            const uint8Array'.$name.' = new Uint8Array(slice'.$name.'.length);
            for (let i = 0; i < slice'.$name.'.length; i++) {
              uint8Array'.$name.'[i] = slice'.$name.'.charCodeAt(i);
            }
            const blobPart'.$name.' = new Blob([uint8Array'.$name.'], { type: type });
            blobArray'.$name.'.push(blobPart'.$name.');
          }
          return new Blob(blobArray'.$name.', { type: type });
        }
        $("#imageInput'.$name.'").on("change", function (event) {
          var file'.$name.' = event.target.files[0];
          var reader'.$name.' = new FileReader();
          reader'.$name.'.onload = function (event) {
              $("#imagePreview'.$name.'").attr("src", event.target.result);
            var img'.$name.' = new Image();
            img'.$name.'.onload = function () {
              if (cropper'.$name.') {
                cropper'.$name.'.destroy(); // Destroy the existing Cropper instance
              }
              cropper'.$name.' = new Cropper($("#imagePreview'.$name.'")[0], {
                aspectRatio: cropRatio'.$name.',
                viewMode: 2
              });
              $(".cropper-modal").hide();
              //$("#croppedCanvas'.$name.'").css("display", "none");
              $("#uploadButton'.$name.'").show();
              // Resize the image to fit within maxKB
              resizeImageToMaxKB(img'.$name.', '.$maxkb.') // Replace 20 with maxKB value
              .then((resizedBlob) => {
                var resizedImage'.$name.' = new Image();
                resizedImage'.$name.'.src = URL.createObjectURL(resizedBlob);
                // Update the image preview with the resized image
                $("#imagePreview'.$name.'").attr("src", resizedImage'.$name.'.src);
              })
              .catch((error) => {
                console.error(error);
              });
            };
            img'.$name.'.src = event.target.result;
          };
          reader'.$name.'.readAsDataURL(file'.$name.');
        });
        function updateCropRatio'.$name.'(ratio) {
          cropRatio'.$name.' = ratio;
          if (cropper'.$name.') {
            cropper'.$name.'.setAspectRatio(ratio);
          }
        }

        function resizeImageToMaxKB(image, maxKB) {
          return new Promise((resolve, reject) => {
            const maxPixels = image.width * image.height;
            const qualityStep = 0.05;
            let quality = 1;
        
            const canvas = document.createElement(\'canvas\');
            const ctx = canvas.getContext(\'2d\');
        
            const resizeImage = (q) => {
              canvas.width = image.width;
              canvas.height = image.height;
              ctx.clearRect(0, 0, canvas.width, canvas.height);
              ctx.drawImage(image, 0, 0, canvas.width, canvas.height);
              canvas.toBlob((blob) => {
                if (blob.size <= maxKB * 1024 || quality <= 0) {
                  resolve(blob);
                } else {
                  quality -= qualityStep;
                  resizeImage(quality);
                }
              }, \'image/jpeg\', q);
            };
        
            resizeImage(quality);
          });
        }
        function resizeImage(image, width, maxSizeKB) {
          var canvas = document.createElement("canvas");
          var ctx = canvas.getContext("2d");
          var aspectRatio = image.width / image.height;
          var height = width / aspectRatio;
          canvas.width = width;
          canvas.height = height;
          ctx.drawImage(image, 0, 0, width, height);
          var resizedImage = new Image();
          resizedImage.src = canvas.toDataURL(\'image/jpeg\', 0.7); // Adjust quality (0.0 - 1.0) as needed
          // Convert the data URL to a Blob object
          var blobDataUrl = canvas.toDataURL(\'image/jpeg\', 0.7); // Adjust quality (0.0 - 1.0) as needed
          var blob = dataURLtoBlob(blobDataUrl);
          // Perform additional compression if required
          if (blob.size / 1024 > maxSizeKB) {
            var compressedImage = compressImage(blob, maxSizeKB);
            resizedImage.src = URL.createObjectURL(compressedImage);
          }
          return resizedImage;
        }

        $("#uploadButton'.$name.'").click(function () {
          const croppedImage'.$name.' = cropper'.$name.'.getCroppedCanvas().toDataURL("image/webp");
          const convertedImage'.$name.' = croppedImage'.$name.';
          const canvasData'.$name.' = convertedImage'.$name.'.split(",")[1];
          const blob'.$name.' = b64toBlob'.$name.'(canvasData'.$name.', "image/webp");
          const timestamp'.$name.' = Date.now();
          const imageName'.$name.' = timestamp'.$name.' + ".webp";
          const formData'.$name.' = new FormData();
          formData'.$name.'.append("file", blob'.$name.', imageName'.$name.');
          const xhr'.$name.' = new XMLHttpRequest();
          xhr'.$name.'.open("POST", "/uploadimage/" + imageName'.$name.'+"/'.$remoteDirectory.'/'.$ftpfolderName.'", true);
          xhr'.$name.'.setRequestHeader("X-Requested-With", "XMLHttpRequest");
          xhr'.$name.'.onload = function () {
            if (xhr'.$name.'.status === 200) {
              console.log(xhr'.$name.'.responseText);
              const response'.$name.' = JSON.parse(xhr'.$name.'.responseText);
              if (response'.$name.'.success) {
                const imageUrl'.$name.' = response'.$name.'.imageUrl;
                $(".cropper-container").hide();
                handleImageClick'.$name.'(response'.$name.'.image, imageUrl'.$name.')
                $("#imageInput'.$name.'").val("");
                $("#imagePreview'.$name.'").attr({"src": ""});
                $("#imagePreview'.$name.'").hide();
                $("#uploadButton'.$name.'").hide();
              } else {
                alert("Upload failed: " + response'.$name.'.message);
              }
            } else {
              alert("Upload failed. Please try again.");
            }
          };
          xhr'.$name.'.send(formData'.$name.');
        });
        function b64toBlob'.$name.'(b64Data, contentType, sliceSize) {
          contentType = contentType || "";
          sliceSize = sliceSize || 512;
          const byteCharacters'.$name.' = atob(b64Data);
          const byteArrays'.$name.' = [];
          for (let offset = 0; offset < byteCharacters'.$name.'.length; offset += sliceSize) {
            const slice'.$name.' = byteCharacters'.$name.'.slice(offset, offset + sliceSize);
            const byteNumbers'.$name.' = new Array(slice'.$name.'.length);
            for (let i = 0; i < slice'.$name.'.length; i++) {
              byteNumbers'.$name.'[i] = slice'.$name.'.charCodeAt(i);
            }
            const byteArray'.$name.' = new Uint8Array(byteNumbers'.$name.');
            byteArrays'.$name.'.push(byteArray'.$name.');
          }
          const blob'.$name.' = new Blob(byteArrays'.$name.', { type: contentType });
          return blob'.$name.';
        }
        var modalContent'.$name.' = $("<div class=\'selectimages'.$name.'\'></div>");
        var modalBody'.$name.' = $("<div class=\' row modal-body mt-1 '.$name.' \'></div>");
        function modaldata'.$name.'() {
          $(".modal-body.'.$name.'").html("<div class=\' row modal-body mt-1 '.$name.' \'></div>");
          modalContent'.$name.'.append(modalBody'.$name.');
          $.ajax({
            url: "/getimageurl/",
            type: "GET",
            data:{
              t : "'.$tblname.'",
              c : "'.$colnm.'",
              "col" : "'.$name.'",
              url : "'.$url.'",
            },
            dataType: "json",
            success: function (response) {
              if (response && response.length > 0) {
                for (var i = 0; i < response.length; i++) {
                  var imageUrl'.$name.' = response[i].url;
                  var used'.$name.' = response[i].selected;
                  var table'.$name.' = $("<table class=\'table table-bordered\'></table>");
                for (var j = 0; j < used'.$name.'.length; j++) {
                  var row'.$name.' = $("<tr></tr>");
                  var cell'.$name.' = $("<td></td>").text(used'.$name.'[j]);
                  row'.$name.'.append(cell'.$name.');
                  table'.$name.'.append(row'.$name.');
                }
                var usedin'.$name.' = $("<div id=\'usedin" + imgID'.$name.' + "\' class=\'usedin\'></div>");
                usedin'.$name.'.append(table'.$name.');
                  var imageName'.$name.' = imageUrl'.$name.'.substring(imageUrl'.$name.'.lastIndexOf("/") + 1);
                  var imgID'.$name.' = "img" + imageName'.$name.'.substring(0, imageName'.$name.'.lastIndexOf("."));
                  var imgdiv'.$name.' = $("<div id=\'" + imgID'.$name.' + "\' class=\'imgs'.$name.' col-2 " + imgID'.$name.' + "\'></div>");
                  var delbtn'.$name.' = "<div id=\'delete'.$name.'Container" + imgID'.$name.' + "\'></div><div id=\'deletebtn" + imgID'.$name.' + "\' onclick=\'deleteImage'.$name.'(\"" + imageName'.$name.' + "\",\"" + imgID'.$name.' + "\")\' class=\' btn-delete delete-button mb-3 \' data-image-path=\'" + imageName'.$name.' + "\'><i class=\'bi bi-trash\'></i></div>";
                  var imageElement'.$name.' = $("<img  class=\'img-responsive\' >").attr({
                    src: "/tables/image.php?img=" + imageUrl'.$name.',
                    alt: imageName'.$name.',
                    "data-image-path": imageName'.$name.'
                  });
                  imgdiv'.$name.'.append(imageElement'.$name.');
                  imgdiv'.$name.'.append(usedin'.$name.');
                  imgdiv'.$name.'.append(delbtn'.$name.');
                  //imgdiv'.$name.'.append(selbtn'.$name.');
                  modalBody'.$name.'.append(imgdiv'.$name.');
                }
                $("#imageModal'.$name.' .modal-dialog .modal-content .selectimages").replaceWith(modalContent'.$name.');
                $("#imageModal'.$name.'").modal("show");
              } else {
                $("#imageModal'.$name.' .modal-dialog .modal-content .selectimages").replaceWith("No images found.");
                $("#imageModal'.$name.'").modal("show");
              }
            },
            error: function () {
              $("#imageModal'.$name.' .modal-dialog .modal-content .selectimages").replaceWith("Faild to fetch images.");
              $("#imageModal'.$name.'").modal("show");
            }
          });
        }
        $("#viewImagesButton'.$name.'").click(function () {
          modaldata'.$name.'();
        });
        function deleteImage'.$name.'(image, id) {
          var btn'.$name.' = $("#deletebtn" + id);
          btn'.$name.'.hide();
          // Replace delete button with confirm buttons
          var confirmButtons'.$name.' = $("<span id=\'text" + id + "\' class=\'small\'>Are you Sure?</span>" +
            "<div id=\'confirmButtons" + id + "\' class=\' confirm-grid\' role=\'group\' aria-label=\'Confirm Buttons\'>" +    
            "<span id=\'confirmYes" + id + "\' class=\'btn btn-danger confirm-grid-item\'>Yes</span>" +
            "<span id=\'confirmNo" + id + "\' class=\'btn btn-secondary confirm-grid-item\'>No</span>" +
            "</div>");
          $("#delete'.$name.'Container" + id).html(confirmButtons'.$name.');
          // Attach click event handlers to confirm buttons
          $("#confirmYes" + id).click(function () {
            var xhr'.$name.' = new XMLHttpRequest();
            xhr'.$name.'.open("GET", "/deleteimage/" + image + "/'.$name.'/'.$tblname.'/'.$remoteDirectory.'/'.$ftpfolderName.'" , true);
            xhr'.$name.'.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr'.$name.'.onload = function () {
              if (xhr'.$name.'.status === 200) {
                var response'.$name.' = JSON.parse(xhr'.$name.'.responseText);
        
                if (response'.$name.'.success === true) {
                  var imageClass'.$name.' = response'.$name.'.image.substring(0, response'.$name.'.image.lastIndexOf("."));
                  console.log(imageClass'.$name.');
                  $(".img" + imageClass'.$name.').remove();
                } else {
                }
              } else {
                console.log("Error deleting image");
              }
            };
            xhr'.$name.'.onerror = function () {
              console.log("Request failed");
            };
            xhr'.$name.'.send("image=" + encodeURIComponent(image));
          });
          $("#confirmNo" + id).click(function () {
            // Restore delete button
            $("#delete'.$name.'Container" + id).html("");
            btn'.$name.'.show();
          });
        }
        // Function to handle image click event
        function handleImageClick'.$name.'(imageName, imagePreview1) {
          // Set the value of the input field outside the modal to the clicked image name
          $("#selectedImage'.$name.'").val(imageName);
          $("#imagePrev'.$name.'").attr("src", imagePreview1);
          // Hide the modal after clicking on the image
          $("#imageModal'.$name.'").modal("hide");
        }
        $(document).on("click", ".imgs'.$name.' img", function () {
          var imageName'.$name.' = $(this).attr("data-image-path"),
            imagePreview1'.$name.' = $(this).attr("src");
          handleImageClick'.$name.'(imageName'.$name.', imagePreview1'.$name.');
          if (cropper'.$name.') {
            cropper'.$name.'.destroy(); // Destroy the existing Cropper instance
          }
        });
        $("#imageModal'.$name.' .close").click(function () {
          $("#imageModal'.$name.'").modal("hide");
        });
        $(document).ready(function () {
        });
          </script>';
    }
}
function FormsText($name, $title, $type,$validby, $required = false, $class = '', $rows = 3, $count = false, $countmin = 20, $countmax = 25, $ai = false, $prompt ='',$system ='',$nullable=true) {
    global $row, $Columns,$folderName,$httpurl,$gptmode;
    // global $row, $Columns, $folderName,$httpurl;

    $value = $row[$name] ?? '';
    if ($required) {
        $required = 'required';
    }
    if($validby!== ''){
      $validbyclass = 'validby';
    }else{
        $validbyclass = '';
    }
    if (in_array($name, $Columns)) {
      $countspan = '';
        if ($count) {
            $countspan = '
            <div class="progress">
                <div id="charCount'.$name.'" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                    <span class="current-value " data-valid="'.($required!=='required' ? "true" : "false").'">0%</span>
                </div>
            </div>
            ';
        }

        $modalLink = $ai ? '<a href="#" class="" data-bs-toggle="modal" data-bs-target="#myModal'.$name.'" id="openModalLink'.$name.'">AI</a>' : '';
        echo '
            <div class="'.$class.' mb-3">
                <label class="form-label">'.$title.'
                    '.$modalLink.'
                </label>
                <textarea class="form-control mb-3 '.$validbyclass.'"  rows="'.$rows.'" type="'.$type.'" placeholder="'.$title.'" name="'.$name.'" id="'.$name.'" '.$required.'>'.$value.'</textarea>
                '.$countspan.'
            </div>';

            if($ai ? "true" : "false") {
            echo '
            <!-- Modal HTML -->
            <div class="modal fade" id="myModal'.$name.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">'.$title.' AI</h5>
                        </div>
                        <div class="modal-body" id="modalBodyContent'.$name.'">
                            <!-- Content will be filled via AJAX -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"  id="changeInput'.$name.'" data-bs-dismiss="modal">Change Input</button>  <!-- New button -->
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                        <small class="text-right d-flex justify-content-end p-2 pt-0">'.$gptmode.'</small>
                    </div>
                </div>
            </div>';
        }
        if ($count) {
        echo '<script>
        $(document).ready(function() {
          var fnct = "updateCount'.$name.'";
          window.window[fnct] = function() {
          };
          if(' . ($count ? 'true' : 'false') . ') {
            window.window[fnct] = function() {
              let validbyval = "";
              '.($validby !== '' ? 'if($("#'.$validby.'")) { validbyval = $("#'.$validby.'").val();}else{validbyval =""}' : '').'
              if(validbyval !== ""){
                  if($("#'.$name.'").val().length < '.$countmin.' || $("#'.$name.'").val().length > '.$countmax.') {
                  //document.getElementById("messageDiv").innerHTML = "<div class=\"alert alert-danger\">Length not within the allowable range.</div>";
                  $("#charCount'.$name.' .current-value").addClass("notvalid");
              }else{$("#charCount'.$name.' .current-value").removeClass("notvalid");}}
              '.($required === "required" ? '
              if($("#'.$name.'").val().length < '.$countmin.' || $("#'.$name.'").val().length > '.$countmax.') {
                  //document.getElementById("messageDiv").innerHTML = "<div class=\"alert alert-danger\">Length not within the allowable range.</div>";
                  $("#charCount'.$name.' .current-value").addClass("notvalid");
              }else{$("#charCount'.$name.' .current-value").removeClass("notvalid");}':'').'
              var box = $("#'.$name.'").val();
              var main = box.length * '.$countmax.';
              var value = (main / '.$countmax.');
              var count = 0 + box.length;
              var count1 = 0 + (box.length/'.$countmax.')*100;
              var reverse_count = '.$countmax.' - box.length;
              if(box.length >= 0){
                  $("#charCount'.$name.'").css("width", count1 + "%");
                  $("#charCount'.$name.' .current-value").text(count);
                  $(".count'.$name.'").text(reverse_count);
                  if (count >= '.$countmin.' && count <= '.$countmax.'){
                      $("#charCount'.$name.'").removeClass("progress-bar-warning");
                      $("#charCount'.$name.'").removeClass("progress-bar-danger");
                      $("#charCount'.$name.'").addClass("progress-bar-success");
                  }
                  if (count > '.$countmax.'){
                      $("#charCount'.$name.'").removeClass("progress-bar-warning");
                      $("#charCount'.$name.'").removeClass("progress-bar-success");
                      $("#charCount'.$name.'").addClass("progress-bar-danger");
                  }
                  if(count >= 0 && count < '.$countmin.'){
                      $("#charCount'.$name.'").removeClass("progress-bar-danger");
                      $("#charCount'.$name.'").removeClass("progress-bar-success");
                      $("#charCount'.$name.'").addClass("progress-bar-warning");
                  }
              }
          };
          $("#'.$name.'").on("keyup input", window.window[fnct]);
          $("#'.$name.'").keyup();
      }
        });
        </script>';
      }
      echo '
      <script>
    
    $(document).ready(function() {
        if(' . ($ai ? 'true' : 'false') . ') {
            $("#openModalLink'.$name.'").click(function(e){
                e.preventDefault();
                var txtval = $("#'.$name.'").val();
                $.ajax({
                  url: "'.$httpurl.'tables/aimodal.php",
                  type: "POST",
                  data: {
                    httpurl: "'.$httpurl.'",
                    prompt: `'.str_replace('<br />', '', nl2br($prompt)).'`,
                    system: "'.$system.'",
                    type: "'.$type.'",
                    txtval: txtval.replace(/<\/?[^>]+(>|$)/g, ""),
                  },
                  success: function(result){
                        if (result.includes("Error:")) {
                            $("#modalBodyContent'.$name.'").html(result);
                        } else {
                            
                        }
                    },
                    error: function() {
                        $("#modalBodyContent'.$name.'").html("Error: Unable to load content.");
                    }
                });
            });
            '.($count ? '
            $(document).on("click", "#changeInput'.$name.'", function(){
                var textareaContent = $("#ai").val();
                $("#'.$name.'").val(textareaContent).trigger("input");
                if('.$count.') {
                    window.updateCount'.$name.'();
                    '.(!$nullable ? 'if(textareaContent !== ""){' : '').'
                      var confirmation = confirm("Are you sure you want to change? ");
                      if (confirmation) {
                          $("#theform").submit();
                      }
                    '.(!$nullable ? '}else{
                      $(this).parent().parent().find("#modalBodyContenttitle #loadingMessage").after("<div class=\"alert alert-danger\">'.$title.' is required.</div>");
                      
                    }' : '').'
                }
            });
            ' : '').'
        }
    });
    </script>';
        
    }
}
function FormsEditor($name, $title, $type, $required = false, $class = '' , $ai = false, $prompt ='',$system ='',$nullable=true) {
    global $row, $Columns,$gptmode,$httpurl;
    $value = $row[$name] ?? '';
    if ($required) {
        $required = 'required';
    }
    if (in_array($name, $Columns)) {
      $modalLink = $ai ? '<a href="#" class="" data-bs-toggle="modal" data-bs-target="#myModal'.$name.'" id="openModalLink'.$name.'">AI</a>' : '';
        
        echo '<div class="'.$class.' mb-3">
        <label class="form-label">' . $title . '
        '.$modalLink.'</label>
        <textarea class="form-control mb-3 my-textarea" placeholder="' . $title . '"  name="' . $name . '" id="' . $name . '" >' . $value . '</textarea>
        </div>';
        
        if($ai ? "true" : "false") {
          echo '
          <!-- Modal HTML -->
          <div class="modal fade" id="myModal'.$name.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-xl" role="document">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">'.$title.' AI</h5>
                      </div>
                      <div class="modal-body" id="modalBodyContent'.$name.'">
                          <!-- Content will be filled via AJAX -->
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-secondary"  id="changeInput'.$name.'" data-bs-dismiss="modal">Change Input</button>  <!-- New button -->
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      </div>
                      <small class="text-right d-flex justify-content-end p-2 pt-0">'.$gptmode.'</small>
                  </div>
              </div>
          </div>';
      }


        // ai script
        echo '    
        <script>
      
      $(document).ready(function() {
          if(' . ($ai ? 'true' : 'false') . ') {
              $("#openModalLink'.$name.'").click(function(e){
                  e.preventDefault();
                  var txtval = $("#'.$name.'").val();
                  $.ajax({
                      url: "'.$httpurl.'tables/aimodal.php",
                      type: "POST",
                      data: {
                        httpurl: "'.$httpurl.'",
                        prompt: `'.str_replace('<br />', '', nl2br($prompt)).'`,
                        system: "'.$system.'",
                        type: "'.$type.'",
                        txtval: "",
                      },
                      success: function(result){
                          if (result.includes("Error:")) {
                              $("#modalBodyContent'.$name.'").html(result);
                          } else {
                              
                          }
                      },
                      error: function(error) {
                        console.log(error);
                          $("#modalBodyContent'.$name.'").html("Error: Unable to load content.");
                      }
                  });
              });
              
              $(document).on("click", "#changeInput'.$name.'", function(){
                  var textareaContent = $("#ai").val();
                  $("#'.$name.'").val(textareaContent).trigger("input");


                      '.(!$nullable ? 'if(textareaContent !== ""){' : '').'
                        var confirmation = confirm("Are you sure you want to change? ");
                        if (confirmation) {

                        // Convert Markdown to HTML using marked.js
                        var htmlContent = marked.parse(textareaContent);

                            updateTinyMCE(htmlContent, "'.$name.'");
                            $("#theform").submit();
                        }
                      '.(!$nullable ? '}else{
                        $(this).parent().parent().find("#modalBodyContenttitle #loadingMessage").after("<div class=\"alert alert-danger\">'.$title.' is required.</div>");
                        
                      }' : '').'
                  
              });
               
          }
      });
      </script>';
      }
}
function FormsSelect($field_name, $title, $options, $required = false, $class = '', $multiple = false ) {
    global $row, $Columns;
    $value = $row[$field_name] ?? '';
    if ($multiple) { $multiple = 'multiple';  }  else { $multiple = ''; }
    if ($required) { $required = 'required';  }  else { $required = ''; }
    
    if (in_array($field_name, $Columns)) { 
        $emptyselect = '<option value="">Select '.$title.'</option>';
        echo '<div class="' . $class . ' mb-3">
        <label class="form-label">' . $title . '</label>
        <select class="single-select" name="' . $field_name . '" id="' . $field_name . '" ' . $required . ' ' . $multiple .'>
            ' . $emptyselect;
      
        foreach ($options as $option_value => $option_name) {
            if (isset($row[$field_name]) && $option_value == $row[$field_name]) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            echo "<option value='$option_value' $selected>$option_name</option>";
        }
        echo '</select></div>';
    }
}
function FormsDateTime($field_name, $title, $required = false, $class = '') {
    global $row, $Columns;
    $value = $row[$field_name] ?? '';
    if ($required) { $required = 'required'; }
    if (in_array($field_name, $Columns)) {
        echo '<div  class="' . $class . ' mb-3">
        <div class="row">
        <div class="col-11">
        <label class="form-label">' . $title . '</label>
        <input  class="result form-control  date-time" type="text"  placeholder="' . $title . '" aria-label="' . $field_name . '" name="' . $field_name . '" id="' . $field_name . '" value="' . $value . '"  ' . $required . ' >
        </div>
        <div class="col-1">
        <button id="button_'.$field_name.'" type="button"  class="btn btn-secondary px-1 mt-4" onclick="" type="button" >Clear</button>
        </div>
        </div>
        </div>
        <script>
        $(document).ready(function() {
          
     
          $("#button_'.$field_name.'").click(function() {
            document.getElementById(\'' . $field_name . '\').value = \'\';
              $(".validby").each(function() {
                //console.log($(this).parent().find(".current-value").data().valid);
                var functionName = "updateCount"+$(this).prop("id");
                window[functionName]();
                
                $(this).parent().find(".current-value").removeClass("notvalid");
              });
          });
          
          // $("#' . $field_name . '").change(function() {
          //   checkonclear();
          // });
        });
            function checkonclear(){
                document.getElementById(\'' . $field_name . '\').value = \'\';
                $(".validby").each(function() {
                  //console.log($(this).parent().find(".current-value").data().valid);
                  var functionName = "updateCount"+$(this).prop("id");
                  window.window[functionName]();
                  $(this).keyup();
                  $(this).parent().find(".current-value").data.valid="true";
                });
            }
        </script>
        ';
    }
}
function FormsDateTimeNew($field_name, $title, $required = false, $class = '') {
  global $row, $Columns;
  $value = $row[$field_name] ?? '';
  if ($required) { $required = 'required'; }
  if (in_array($field_name, $Columns)) {
      echo '
      <div class="' . $class . ' mb-3">
        <div class="row">
          <div class="col-11">
            <label class="form-label">Start Date</label>
            <input class="result form-control" type="text" placeholder="' . $title . '" aria-label="' . $field_name . '" name="' . $field_name . '" id="' . $field_name . '" value="' . $value . '" ' . $required . '> 
            </div><div class="col-1">
              <button id="button_'.$field_name.'" type="button"  class="btn btn-secondary px-1 mt-4" onclick="" type="button" >Clear</button>
            </div>
          </div>
        </div>
        <script>
        $(document).ready(function() {
          var $'.$field_name.' = $("#'.$field_name.'").pickadate({
              selectMonths: true,
              selectYears: true,
              format: "yyyy-mm-dd"
              
          });
          $("#button_'.$field_name.'").click(function() {
            document.getElementById(\'' . $field_name . '\').value = \'\';
              $(".validby").each(function() {
                //console.log($(this).parent().find(".current-value").data().valid);
                var functionName = "updateCount"+$(this).prop("id");
                window[functionName]();
                
                $(this).parent().find(".current-value").removeClass("notvalid");
              });
          });
        });
        function checkonclear(){
          document.getElementById(\'' . $field_name . '\').value = \'\';
          $(".validby").each(function() {
            //console.log($(this).parent().find(".current-value").data().valid);
            var functionName = "updateCount"+$(this).prop("id");
            window.window[functionName]();
            $(this).keyup();
            $(this).parent().find(".current-value").data.valid="true";
          });
      }
        </script>
      ';
  }
}
function FormsCheck($name, $title, $type, $required = false,$vl='1', $class = '' ) {
  global $row, $Columns;
  $value = $row[$name] ?? '';
  if ($required) {
      $required = 'required';
  }
  if (in_array($name, $Columns)) {
      echo '<div class="'.$class.' p-4 mb-3 form-check">
      <label class="form-label p-2">' . $title . '</label>
      <input class="form-control p-3 mb-3 form-check-input" type="'.$type.'"  id="' . $name . 'val" ' . ($value === $vl ? 'checked':'') . ' '. $required.'>
      <input id="' . $name . '" class="text-l" type="hidden" name="' . $name . '" value="'.(isset($value) && $value!='' ? $value : 0).'">
      <script>
        $(document).ready(function() {
          $("#' . $name . 'val").change(function() {
            if ($(this).is(":checked")) {
              $("#' . $name . '").val("'. $vl .'");
            } else {
              $("#' . $name . '").val("0");
            }
          });
        });
      </script> 
      </div>';
  }
}

 function FormsEnd() {
  global $url,$id,$folderName,$viewslug,$editslug;
    $title = $id ? 'Save' : 'Add';
    echo "<div class='row'><div class='col-2 mb-3'><button type='submit' class='btn btn-secondary px-5'>".$title."</button></div></div></form>
    </div>
        </div>
    </div>
</div>
<script src='".$url."assets/js/edit.js'></script>
<script>
  document.getElementById('theform').addEventListener('submit', function(event) {
  event.preventDefault();
  tinymce.triggerSave();
  var form = this; // Reference to the form element
  // this is to validate count of characters in required fields that have character limit
  var hasInvalidValue = $(\".current-value.notvalid\").length > 0;
  if (!hasInvalidValue) {
    form.submit();
  } else {
    var messageDiv = document.getElementById('messageDiv');
    messageDiv.innerHTML = '<div class=\'alert alert-danger\'>There are some fields that are not valid! Please fix them and try again.</div>';
  }
});
</script>
    ";
}
function GetTableColumns($tablename,$conn2) {
  if(strpos($tablename, ',') !== false){

    // Initialize an empty array to store column names
    $columns = array();

    // Check if the table name contains commas
    if (strpos($tablename, ',') === false) {
        // Table name does not contain commas, proceed to fetch column names
        // Assuming you have a valid database connection established

        // Prepare the SQL statement
        $sql = "SHOW COLUMNS FROM `" . $tablename . "`";

        // Execute the query
        $result = mysqli_query($conn2, $sql);

        if ($result) {
            // Fetch column names and store them in the $columns array
            while ($row = mysqli_fetch_assoc($result)) {
                $columns[] = $row['Field'];
            }

            // Free the result set
            mysqli_free_result($result);
        } else {
            // Handle the error if the query fails
            echo "Error executing the query: " . mysqli_error($conn2);
        }

        // Close the database connection
        mysqli_close($conn2);
    } else {
        // Table name contains commas, do not proceed and return an empty array
        echo "The table name contains commas, please provide a single table name.";
    }

    // Return the array of column names
    return $columns;
}else{
  $Query = "SHOW COLUMNS FROM $tablename";
  $Result = mysqli_query($conn2, $Query);
  if ($Result) {
      $columns = [];
      while ($row = mysqli_fetch_assoc($Result)) {
          $columns[] = $row['Field'];
      }
      return $columns;
  } else {
      echo "Error: " . mysqli_error($conn2);
  }
}


}

//////here

function generateSlug($string) {
  $separator = '-';
  $maxLength = 90;

  // Trim leading/trailing whitespace from the title
  $string = trim($string);

  // Check if the title is empty
  if (empty($string)) {
      exit('error on create slug');
  }

  // Convert the string to lowercase using mb_strtolower for UTF-8 support
  $string = mb_strtolower($string, 'UTF-8');

  // Replace non-alphanumeric characters with separator
  $string = preg_replace('/[^\p{L}0-9' . preg_quote($separator) . ']+/u', $separator, $string);

  // Replace ampersand with "and"
  $string = str_replace('&', 'and', $string);

  // Remove leading/trailing separator
  $string = trim($string, $separator);

  // Remove consecutive separators
  $string = preg_replace('/' . preg_quote($separator) . '{2,}/', $separator, $string);

  // Limit the slug length
  if (mb_strlen($string, 'UTF-8') > $maxLength) {
      $string = mb_substr($string, 0, $maxLength, 'UTF-8');
  }

  return $string;
}


function connectToFtp($ftpServer, $ftpUsername, $ftpPassword)
{
  
  $connection = ftp_connect($ftpServer);

  if (!$connection) {
      die("Could not connect to $ftpServer");
  }
  echo "Successfully connected to $ftpServer\n";
  // Login
  if (@ftp_login($connection, $ftpUsername, $ftpPassword)) {
      echo "Logged in as $ftpUsername@$ftpServer\n";
  } else {
      die("Couldn't log in as $ftpUsername\n");
  }
  ftp_pasv($connection, true);

    return $connection;
}

// Function to disconnect from FTP server
function disconnectFromFtp($ftpConnection)
{
    ftp_close($ftpConnection);
}

// Function to get the list of images in the upload folder
function getImagesInFolderpst($ftpServer,$ftpConnection,$column ,$colnm,$tablename,$url, $conn2)
{
    $path = parse_url($url, PHP_URL_PATH);
    $path = rtrim($path, '/');
    $parts = explode('/', $path);
    if (count($parts) >= 4) {
      $remoteDirectory = $parts[2];
      echo $lastPart = end($parts);
    } else {
      $remoteDirectory = $parts[1];
      echo $lastPart = end($parts);
    }
    
    $images = array();
    $fileList = ftp_nlist($ftpConnection, $remoteDirectory.'/'.$lastPart);
    if ($fileList) {
        foreach ($fileList as $file) {
            if (preg_match('/\.webp$/', $file)) {
                $selected = [];
                $filename = pathinfo($file, PATHINFO_FILENAME).'.webp';

                $stmt = $conn2->prepare("SELECT `$colnm` FROM `$tablename` WHERE `$column` = ?");
                $stmt->bind_param("s", $filename);
                $stmt->execute();
                $result = $stmt->get_result();
              
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $names = $row[$colnm];
                
                        if (is_array($names)) {
                            $selected = array_merge($selected, $names);
                        } else {
                            $selected[] = $names;
                        }
                    }
                }

                $stmt->close();

                $file = parse_url($file, PHP_URL_PATH);
                $file = rtrim($file, '/');
                $file = end(explode('/', $file));

                $images[] = ['selected' => $selected, 'url' => $url.$file ];
            }
        }
    }
    return $images;
}

// Function to get the list of images in the upload folder
function getImagesInFolder($ftpServer,$ftpConnection,$column ,$tablename,$remoteDirectory,$dir,$colnm, $conn2,$lnk='')
{
    
    $remoteDirectory = '/'.$remoteDirectory.'/'.str_replace("-", "/", $dir)."/";
    $images = array();
    $fileList = ftp_nlist($ftpConnection, $remoteDirectory);
    if ($fileList) {
        foreach ($fileList as $file) {
            if (preg_match('/\.webp$/', $file)) {
                $selected = [];
                $filename = pathinfo($file, PATHINFO_FILENAME).'.webp';

                $stmt = $conn2->prepare("SELECT `$colnm` FROM `$tablename` WHERE `$column` = ?");
                $stmt->bind_param("s", $filename);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $names = $row[$colnm];
                
                        if (is_array($names)) {
                            $selected = array_merge($selected, $names);
                        } else {
                            $selected[] = $names;
                        }
                    }
                }

                $stmt->close();

                
                $images[] = ['selected' => $selected, 'url' => $lnk ];
            }
        }
    }
    return $images;
}

function ajaxtable($tablename,$conn2){

}

function delete_image($column ,$tablename,$imageName, $conn2){
    $stmt = $conn2->prepare("UPDATE $tablename SET $column = NULL WHERE $column = ?");
    $stmt->bind_param("s", $imageName);

    // Execute the statement
    if ($stmt->execute()) {
        // Image deleted successfully
        return "Image deleted.";
    } else {
        // Failed to delete image
        return "Failed to delete image.";
    }

    // Close the statement and database connection
    $stmt->close();
    $conn->close();
}

function time_ago($datetime, $full = false) {
    if (empty($datetime)) {
        return 'N/A'; // Or any other default value you prefer
    }

    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );

    $diffInWeeks = floor($diff->d / 7);
    $diff->d -= $diffInWeeks * 7;

    if ($diff->y) {
        $string['y'] = $diff->y . ' ' . $string['y'] . ($diff->y > 1 ? 's' : '');
    } else {
        unset($string['y']);
    }

    if ($diff->m) {
        $string['m'] = $diff->m . ' ' . $string['m'] . ($diff->m > 1 ? 's' : '');
    } else {
        unset($string['m']);
    }

    if ($diffInWeeks) {
        $string['w'] = $diffInWeeks . ' ' . $string['w'] . ($diffInWeeks > 1 ? 's' : '');
    } else {
        unset($string['w']);
    }

    if ($diff->d) {
        $string['d'] = $diff->d . ' ' . $string['d'] . ($diff->d > 1 ? 's' : '');
    } else {
        unset($string['d']);
    }

    if ($diff->h) {
        $string['h'] = $diff->h . ' ' . $string['h'] . ($diff->h > 1 ? 's' : '');
    } else {
        unset($string['h']);
    }

    if ($diff->i) {
        $string['i'] = $diff->i . ' ' . $string['i'] . ($diff->i > 1 ? 's' : '');
    } else {
        unset($string['i']);
    }

    if ($diff->s) {
        $string['s'] = $diff->s . ' ' . $string['s'] . ($diff->s > 1 ? 's' : '');
    } else {
        unset($string['s']);
    }

    if (!$full) $string = array_slice($string, 0, 1);
    
    if ($now < $ago) {
        return $string ? 'In ' . implode(', ', $string) : 'Soon';
    } else {
        return $string ? implode(', ', $string) . ' ago' : 'Just now';
    }
}

function getNext($occurrences, $dayOfWeek) {
  // Set the timezone to Istanbul
  date_default_timezone_set('Europe/Istanbul');

  // Get the current date
  $currentDate = new DateTime();

  // Array to store the next occurrences
  $result = array();

  // Loop to find the next occurrences
  while (count($result) < $occurrences) {
      // Check if the current date is the desired day of the week
      if (strtolower($currentDate->format('l')) == strtolower($dayOfWeek)) {
          $result[] = $currentDate->format('Y-m-d');
      }

      // Move to the next day
      $currentDate->modify('+1 day');
  }

  return $result;
}


$response= '';
function getPageSpeedInsightsData($url) {
    global $gapi;
    $apiEndpoint = "https://www.googleapis.com/pagespeedonline/v5/runPagespeed";
    $url = urlencode($url);
    $gapi = urlencode($gapi);
    $requestUrl = "$apiEndpoint?url=$url&key=$gapi";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $requestUrl);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    $result = json_decode($response, true);
    // Return the performance score
    return $result['lighthouseResult']['categories']['performance']['score'];
}

function getAccessToken($client) {
  // Implementation for the getAccessToken function
  // ...

  return $client->getAccessToken();
}

function is_indexed($siteUrl,$inspectionUrl){
  
  $jsonKey = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/assets/plugins/google-api-php-client-main/course-factory-3659eef69891.json');
    $credentials = new ServiceAccountCredentials(
        ['https://www.googleapis.com/auth/webmasters.readonly'],
        json_decode($jsonKey, true) // Parse the JSON content to an array
    );

    $authToken = $credentials->fetchAuthToken();

    $client = new Client([
        'base_uri' => 'https://searchconsole.googleapis.com',
        'headers' => ['Authorization' => 'Bearer ' . $authToken['access_token']],
    ]);

  // Prepare the request body with the URL you want to inspect and the site URL
  $requestBody = [
    'inspectionUrl' => $inspectionUrl,
    'siteUrl' => $siteUrl
  ];

// Make a POST request to the API's urlTestingTools.mobileFriendlyTest.run method
  try {
    $response = $client->post('/v1/urlInspection/index:inspect', [
        'json' => $requestBody
    ]);

    // Handle the API response
    $statusCode = $response->getStatusCode();
    if ($statusCode === 200) {
        $data = json_decode($response->getBody(), true);
        // Process the API response data
        // Your code goes here
        return $data;
    } else {
        echo 'Error: Unexpected response from the API.';
    }
  } catch (RequestException $e) {
      return 'Error: ' . $e->getMessage();
  }
}

// function submitGoogleSitemap($siteUrl, $sitemapFeedPath) {
//   $jsonKey = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/assets/plugins/google-api-php-client-main/course-factory-3659eef69891.json');
  
//   $credentials = new ServiceAccountCredentials(
//     ['https://www.googleapis.com/auth/webmasters.readonly'],
//     json_decode($jsonKey, true) // Parse the JSON content to an array
// );

// $authToken = $credentials->fetchAuthToken();

//   // Initialize the Guzzle client
//   $client = new Client([
//       'base_uri' => 'https://www.googleapis.com/webmasters/v3/sites/',
//       'headers' => [
//           'Authorization' => 'Bearer ' . $authToken,
//           'Content-Type' => 'application/atom+xml',
//       ],
//   ]);

//   // URL for the PUT request
//   $url = $siteUrl . '/sitemaps/' . urlencode($sitemapFeedPath);

//   // Content of the sitemap file
//   $sitemapContent = file_get_contents($sitemapFeedPath);

//   // Make the PUT request
//   $response = $client->put($url, [
//       'body' => $sitemapContent,
//   ]);

//   // Get the response body
//   $responseBody = $response->getBody()->getContents();

//   return $responseBody;
// }

function yandex_index($siteUrl,$inspectionUrl){
  // Set the Yandex API URL
  $url = 'https://yandex.com/search/xml';

  // Set the Yandex API parameters
  $params = array(
      'user' => 'eyadmercury',
      'key' => '03.1880066746:305bf185427411f0d8438717edd05ce3',
      'l10n' => 'en',
      'filter' => 'strict'
  );

  // Set the XML request body
  $xml = '<?xml version="1.0" encoding="utf-8"?>
  <request>
    <query>
      '.urlencode($inspectionUrl).'
    </query>
    <groupings>
      <groupby attr="d" mode="deep" groups-on-page="1" docs-in-group="1" />
    </groupings>
  </request>';

  // Initialize a new cURL session
  $ch = curl_init();

  // Set the cURL options
  curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($params));
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/xml'
  ));

  // Execute the cURL request
  $response = curl_exec($ch);

  // Close the cURL session
  curl_close($ch);

  // Output the API response
  echo $response;
}

function is_indexedbulk($url, $engine) {
  switch ($engine) {
    case 'bing':
      // begin bing api
      $api_key = 'b71ec264c00b4f4496d0d69bc08b67d6';
      $website_id = 'agile4training.com';
      $query = "site:" . urlencode($url);
      // Define the API endpoint URL
      $api_url = "https://api.cognitive.microsoft.com/bing/v7.0/search?q={$query}";

      // Initialize cURL session
      $ch = curl_init();

      // Set cURL options for a GET request
      curl_setopt($ch, CURLOPT_HTTPHEADER, ["Ocp-Apim-Subscription-Key: $api_key"]);
      curl_setopt($ch, CURLOPT_URL, $api_url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      // Execute the cURL request
      $response = curl_exec($ch);
print_r($response);
      // Check for cURL errors
      if (curl_errno($ch)) {
          echo 'cURL Error: ' . curl_error($ch);
      } else {
          // Decode the JSON response
          $result = json_decode($response, true);

          // Check if there's an error message in the response
          if (isset($result['Error'])) {
              echo 'API Error: ' . $result['Error']['Message'];
          } else {
              // Check if the URL is indexed
              if (isset($result['d']) && isset($result['d']['UrlInfo'])) {
                  $urlInfo = $result['d']['UrlInfo'];
                  if ($urlInfo['Indexed']) {
                      echo 'The page is indexed.';
                  } else {
                      echo 'The page is not indexed.';
                  }
              } else {
                  echo 'Unable to determine indexing status.';
              }
          }

          // Close cURL session
          curl_close($ch);
      }

      /// end bing api
      break;
      case 'google':
        // Create a new Google_Client instance
        $client = new Google_Client();

        // Set the credentials using the JSON key file
        $client->setAuthConfig($_SERVER['DOCUMENT_ROOT'] . '/assets/plugins/google-api-php-client-main/course-factory-3659eef69891.json');

        // Set the scopes for the Google Search Console API
        $client->setScopes(['https://www.googleapis.com/auth/webmasters']);

        // Create a new Google_Service_Webmasters instance
        $webmasters_service = new Webmasters($client);
  
        // Get the list of sites
        $sites = $webmasters_service->sites->listSites();

        // Find the site that matches the URL
        $site_url = parse_url($url, PHP_URL_HOST);
        $site = null;
        foreach ($sites->getSiteEntry() as $site_entry) {

          if ($site_entry->siteUrl === "sc-domain:$site_url") {
            $site = $site_entry;
            break;
          }
        }

        // If the site was not found, set $indexed to 0
        if (!$site) {
          $indexed = 0;
        } else {
          // Get the list of indexed pages
          $query = new Google_Service_Webmasters_SearchAnalyticsQueryRequest();
          $query->setStartDate(date('Y-m-d', strtotime('2017-1-1')));
          $query->setEndDate(date('Y-m-d'));
          $query->setDimensions(['page']);
          $query->setAggregationType('byPage');
          $query->setRowLimit(10000);
          $indexed_pages = $webmasters_service->searchanalytics->query($site->siteUrl, $query);
  
          // Check if the URL is indexed
          $indexed =[];
          return $indexed_pages->getRows() ;
        }
        break;
    case 'yandex':
      // Yandex API token
      //this key is available for one year from now yandex key expires in 15/08/2024
    $api_token = 'y0_AgAAAABwD4q6AApWbgAAAADqQ4JYeOZi8iQFRsOx-IQStyOhLdiawhw';
    $host_id = 'https:'.parse_url($url, PHP_URL_HOST).':443';
    // Yandex API endpoint
    $api_endpoint = 'https://api.webmaster.yandex.net/v4/user/';

    // Construct the API URL
    $api_url = $api_endpoint . '1880066746/hosts/' . $host_id . '/search-urls/in-search/samples/';

    // Initialize cURL session
    $ch = curl_init($api_url);

    // Set cURL options
    $headers = [
        'Authorization: OAuth ' . $api_token,
        'Accept: application/json',
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute cURL session and get response
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        echo 'cURL error: ' . curl_error($ch);
    } else {
    // Parse the JSON response
    $responseData = json_decode($response, true);
    // print_r($responseData);
    // Check if the response contains an error
    if (isset($responseData['error'])) {
        echo 'API Error: ' . $responseData['error']['message'];
    } else {
        // Check the indexing status
        if (isset($responseData['samples'])) {
          return $responseData['samples'];
          
        } else {
            echo 'Unable to determine indexing status.';
        }
    }
}

// Close cURL session
curl_close($ch);
      break;
    default:
      $indexed = false;
      break;
  }

  return intval($indexed);
}

// function checkIndexingStatusold($theurl,$searchengine)
// {
//   if($searchengine == 'yandex'){

//   // Yandex API token
//   $api_token = 'y0_AgAAAABwD4q6AApWbgAAAADqQ4JYeOZi8iQFRsOx-IQStyOhLdiawhw';
//   $host_id = 'https:'.parse_url($theurl, PHP_URL_HOST).':443';
//   // Yandex API endpoint
// $api_endpoint = 'https://api.webmaster.yandex.net/v4/user/';

// // Construct the API URL
// $api_url = $api_endpoint . '1880066746/hosts/' . $host_id . '/search-urls/in-search/samples/';

// // Initialize cURL session
// $ch = curl_init($api_url);

// // Set cURL options
// $headers = [
//     'Authorization: OAuth ' . $api_token,
//     'Accept: application/json',
// ];
// curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// // Execute cURL session and get response
// $response = curl_exec($ch);
// // Check for cURL errors
// if (curl_errno($ch)) {
//     echo 'cURL error: ' . curl_error($ch);
// } else {
//     // Parse the JSON response
//     $responseData = json_decode($response, true);
//     // print_r($responseData);
//     // Check if the response contains an error
//     if (isset($responseData['error'])) {
//         echo 'API Error: ' . $responseData['error']['message'];
//     } else {
//         // Check the indexing status
//         if (isset($responseData['samples'])) {
//           if(in_array($theurl, $responseData['samples'])){
//             return 1;
//           }else{
//             return 0;
//           }
//         } else {
//             echo 'Unable to determine indexing status.';
//         }
//     }
// }
// // Close cURL session
// curl_close($ch);
//   }else{
//     switch($searchengine){
//       case 'google':
//         $surl = 'https://www.google.com/search?q=site:';
//         break;
//       case 'bing':
//         $surl = 'https://www.bing.com/search?q=site:';
//         break;
//     }
//     $targetURL = $theurl;
//     $searchQuery = 'site:' . urlencode($targetURL);
//     $searchURL = $surl . $searchQuery;
//     $ch = curl_init($searchURL);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     $searchResults = curl_exec($ch);
//     curl_close($ch);
//     echo $searchResults;
//     if (strpos($searchResults, $targetURL) !== false) {
//       return 1;
//     } else {
//       return 0;
//     }
//   }
// }

function tableExists($connection, $tableName) {
  $sql = "SHOW TABLES LIKE '$tableName'";
  $result = mysqli_query($connection, $sql);
  return mysqli_num_rows($result) > 0;
}

?>