<?php
if ($_SESSION['userlevel'] > 9 ) {
// Fetch the XML content from the sitemap
$path = dirname(__FILE__);
include $path.'/update.php'; 
}else{
?>

  ?>
  <div class="alert border-0 bg-light-warning alert-dismissible fade show py-2">
      <div class="d-flex align-items-center">
          <div class="fs-3 text-warning"><i class="bi bi-exclamation-triangle-fill"></i>
          </div>
          <div class="ms-3">
          <div class="text-warning">You are not allowed to access this page!</div>
          </div>
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <?php

}

?>