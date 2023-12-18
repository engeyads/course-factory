<?php ob_start();

function startPerformanceMonitoring(&$startTime, &$startMemory) {
  $startTime = microtime(true); // Starts the timer
  $startMemory = memory_get_usage(); // Records the initial memory usage
}

function endPerformanceMonitoring($startTime, $startMemory) {
  $endTime = microtime(true); // Ends the timer
  $endMemory = memory_get_usage(); // Records the final memory usage

  // Calculate the execution time in seconds with decimals
  $executionTime = $endTime - $startTime;

  // Calculate the memory usage in kilobytes
  $memoryUsage = ($endMemory - $startMemory) / 1024;

  // Format the output to display the time in seconds with 3 decimal places
  echo "Page generated in " . number_format($executionTime, 3) . " seconds, using " . round($memoryUsage, 2) . " KB of memory.";
}

// Usage
startPerformanceMonitoring($startTime, $startMemory);
?><!doctype html>
<html lang="en" >
<head>
<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'conf.php'; 

?>
<?php include 'layout/head.php';?>
  <title><?php echo $appname; ?></title>
</head>
<body>
<!-- Loading overlay -->
<!-- <div id="loading-overlay">
    <div class="spinner"></div>
</div> -->
<!--start wrapper-->

<!-- Loading overlay -->
<div id="loading-overlay">
  <div id="loading-content">
    <center>
    <div class="logos">
      <div>
        <img src="<?php echo $url; ?>assets/images/logo-icon.png" class="loading-logo" alt="logo icon">
      </div>
      <div>
        <h4 class="loading-text"><?php echo $appname; ?></h4>
      </div>
    </div>
    
        <div class="loading-dots">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>
      </center>
    </div>
</div>


<div class="wrapper">
<?php include 'layout/header.php';?>
<?php include 'layout/sidebar.php';?>
       <!--start content-->
       <main class="page-content">
        <?php
        
          if (isset($_POST['db'])) {
            echo $_POST['db'];
            $_SESSION['db'] = $_POST['db'];
            $query = "SELECT company FROM db WHERE id = '" . $_POST['db'] . "' LIMIT 1 ";
            $result = mysqli_query($conn, $query);
            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $_SESSION['co'] = $row['company'];
            }
          }
          if($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == 'mercuryt.mercury-training.local'){
            echo "<pre>SESSION : ";print_r( $_SESSION);echo "</pre>";
          }
          ?>
      <?php if ($view != "") include $view; ?>
			</main>
       <!--end page main-->
       <!--start overlay-->
        <div class="overlay nav-toggle-icon"></div>
       <!--end overlay-->
        <!--Start Back To Top Button-->
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->
  </div>
  <!--end wrapper-->
  <!-- Bootstrap bundle JS -->
  <script src="<?php echo $url; ?>assets/js/bootstrap.bundle.min.js"></script>
  <!--plugins-->
  <script src="<?php echo $url; ?>assets/js/jquery.min.js"></script>
  <script src="<?php echo $url; ?>assets/plugins/simplebar/js/simplebar.min.js"></script>
  <script src="<?php echo $url; ?>assets/plugins/metismenu/js/metisMenu.min.js"></script>
  <script src="<?php echo $url; ?>assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
  <script src="<?php echo $url; ?>assets/js/pace.min.js"></script>
  <script src="<?php echo $url; ?>assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo $url; ?>assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
  <script src="<?php echo $url; ?>assets/js/table-datatable.js"></script>
  <script src="<?php echo $url; ?>assets/plugins/select2/js/select2.min.js"></script>
  <script src="<?php echo $url; ?>assets/js/form-select2.js"></script>
  <script src="<?php echo $url; ?>assets/plugins/datetimepicker/js/legacy.js"></script>
	<script src="<?php echo $url; ?>assets/plugins/datetimepicker/js/picker.js"></script>
	<script src="<?php echo $url; ?>assets/plugins/datetimepicker/js/picker.time.js"></script>
	<script src="<?php echo $url; ?>assets/plugins/datetimepicker/js/picker.date.js"></script>
	<script src="<?php echo $url; ?>assets/plugins/bootstrap-material-datetimepicker/js/moment.min.js"></script>
	<script src="<?php echo $url; ?>assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.min.js"></script>
  <script src="<?php echo $url; ?>assets/js/form-date-time-pickes.js"></script>
  <script src="https://apis.google.com/js/api.js"></script>
  <!--app-->
  <script src="<?php echo $url; ?>assets/js/app.js"></script>
  <script>
    function setSessionDB(tableId) {
        // Send an AJAX request to set the session variable
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                location.reload(); // Refresh the page
            } else {
                console.error('Failed to set session variable.');
            }
        };
        xhr.send('db=' + tableId);
    }
</script>
  <?php 
  if (isset($conn)){
    CreateKeywordsTables($conn);
  }
 
  mysqli_close($conn);
  ?>
  <div class="text-right d-flex justify-content-end p-2 pt-0">
    <?php endPerformanceMonitoring($startTime, $startMemory); ?>
  </div>

</body>
</html>