  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="<?php echo $url; ?>assets/images/favicon-32x32.png" type="image/png" />
  <!--plugins-->
  <link href="<?php echo $url; ?>assets/plugins/datetimepicker/css/classic.css" rel="stylesheet" />
	<link href="<?php echo $url; ?>assets/plugins/datetimepicker/css/classic.time.css" rel="stylesheet" />
	<link href="<?php echo $url; ?>assets/plugins/datetimepicker/css/classic.date.css" rel="stylesheet" />
	<link rel="stylesheet" href="<?php echo $url; ?>assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.min.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link href="<?php echo $url; ?>assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
  <link href="<?php echo $url; ?>assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
  <link href="<?php echo $url; ?>assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
  <link href="<?php echo $url; ?>assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
  <link href="<?php echo $url; ?>assets/plugins/select2/css/select2.min.css" rel="stylesheet" />
	<link href="<?php echo $url; ?>assets/plugins/select2/css/select2-bootstrap4.css" rel="stylesheet" />
  <link href="<?php echo $url; ?>assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="<?php echo $url; ?>assets/css/bootstrap-extended.css" rel="stylesheet" />
  <link href="<?php echo $url; ?>assets/css/style.css?v=1.0" rel="stylesheet" />
  <link href="<?php echo $url; ?>assets/css/icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
	<link href="<?php echo $url; ?>assets/css/pace.min.css" rel="stylesheet" />
  <link href="<?php echo $url; ?>assets/css/dark-theme.css?v=1.0" rel="stylesheet" />
  <link href="<?php echo $url; ?>assets/css/light-theme.css?v=1.0" rel="stylesheet" />
  <link href="<?php echo $url; ?>assets/css/semi-dark.css?v=1.0" rel="stylesheet" />
  <link href="<?php echo $url; ?>assets/plugins/notifications/css/lobibox.css" rel="stylesheet" />
  <link href="<?php echo $url; ?>assets/plugins/notifications/css/lobibox.min.css" rel="stylesheet" />
  <link href="<?php echo $url; ?>assets/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
  <link href="<?php echo $url; ?>assets/plugins/smart-wizard/css/smart_wizard_all.min.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/dompurify@2"></script>
<script src="https://cdn.jsdelivr.net/npm/showdown@1"></script>
<script src="<?php echo $url; ?>assets/plugins/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
<script src="<?php echo $url; ?>assets/plugins/notifications/js/lobibox.js"></script>
  <script src="<?php echo $url; ?>assets/plugins/notifications/js/lobibox.min.js"></script>
  <script src="<?php echo $url; ?>assets/plugins/notifications/js/messageboxes.js"></script>
  <script src="<?php echo $url; ?>assets/plugins/notifications/js/messageboxes.min.js"></script>
  <script src="<?php echo $url; ?>assets/plugins/notifications/js/notification-custom-script.js"></script>
  <script src="<?php echo $url; ?>assets/plugins/notifications/js/notifications.js"></script>
  <script src="<?php echo $url; ?>assets/plugins/notifications/js/notifications.min.js"></script>

 <!-- cropper -->
  <link rel="stylesheet" href="https://unpkg.com/cropperjs/dist/cropper.min.css">
  <script src="https://unpkg.com/cropperjs/dist/cropper.min.js"></script>
  <script type="text/javascript">
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "i90bdtdw8n");


    function updateServerTime() {
    // AJAX request to get the server time from the PHP script
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Parse the JSON response
            var response = JSON.parse(this.responseText);

            // Display the server time
            document.getElementById('liveTime').textContent = response.serverTime;
        }
    };
    xhr.open("GET", "<?php echo $url; ?>tables/time.php", true);
    xhr.send();
}

// Initial update
// on document readt
$(document).ready(function() {
  
  updateServerTime();
});
</script>
<?php $appname = "Course Factory"; ?>