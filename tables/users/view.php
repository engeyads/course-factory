<?php
if ($_SESSION['userlevel'] > 8 ) {
    $path = dirname(__FILE__);
    include $path.'/conf.php';    
    $ignoredColumns = ['password'];
    $tooltips = [];
    $popups = [];
    $jsonarrays = [];
    $urlPaths = ['username' => 'username'];
    $fieldTitles = ['userlevel' => ' User Level', 'username' => 'User Name'];
    $dateColumns = ['created_at', 'updated_at','published_at'];
    $imagePaths = ['photo' => $url.'assets/images/avatars/'];

    //$gsc = [];
    $lvls = ["10" => "Admin", "9" => "Manager", "8" => "Auditor", "2" => "Viewer", "1" => "User"];

    $dataArrays = [
        'userlevel' => $lvls,
    ];
    $additionalColumns = ['db_count', 'db_names'];


    $custom_select = " SELECT *, COUNT(user_db.db_id) AS db_count, GROUP_CONCAT(db.name) AS db_names ";
    $custom_from = " FROM users ";
    $custom_where = "LEFT JOIN user_db ON users.id = user_db.user_id LEFT JOIN db ON user_db.db_id = db.id GROUP BY users.id, users.username, users.userlevel";

    $costumeQuery = "SELECT *, users.id as id, COUNT(user_db.db_id) AS db_count, GROUP_CONCAT('<span class=\"badge bg-secondary\">', db.name, '</span>') AS db_names
    FROM users
    LEFT JOIN user_db ON users.id = user_db.user_id
    LEFT JOIN db ON user_db.db_id = db.id
    GROUP BY users.id, users.username, users.userlevel";

    include 'include/view.php';
    include 'include/logview.php';
}else{
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
