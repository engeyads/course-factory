<?php
  session_start();


  include '../include/functions.php';
  include '../include/db.php';
  if (!isset($_SESSION['db']) || empty($_SESSION['db'])) {
      die("Error: Database session not set or empty.");
  }

  switch($_SESSION['db_name']){
      case 'agile4training':
          $DBweekname = 'week';
          $newdbstype = true;
      break;
      case 'agile4training ar':
          $DBweekname = 'week';
          $newdbstype = true;
      break;
      case 'blackbird-training':
          $DBweekname = 'weeks';
          $newdbstype = false;
      break;
      case 'blackbird-training.co.uk':
          $DBweekname = 'week';
          $newdbstype = true;
      break;
      case 'mercury english':
          $DBweekname = 'week';
          $newdbstype = false;
      break;
      case 'mercury arabic':
          $DBweekname = 'week';
      break;
      case 'Euro Wings En':
          $DBweekname = 'weeks';
          $newdbstype = false;
      break;
      case 'Euro Wings Ar':
          $DBweekname = 'weeks';
          $newdbstype = false;
      break;
      default:
      $DBweekname = 'week';
      $newdbstype = false;
      break;
  }
  //error_reporting(E_ALL);
  $lvl = $_SESSION['userlevel'];

  // events
  $querycntcrs = "SELECT count(*) as cnt FROM course";
  $recordscntcrs = mysqli_query($conn2, $querycntcrs);
  echo "<div id='coursecount'>";
  if($row_cntcrs = mysqli_num_rows($recordscntcrs)){

      echo $recordscntcrs = $recordscntcrs->fetch_object()->cnt;
  }else{

      echo $recordscntcrs = 0;
  }
  echo "</div>";

  $querycntlstcrs = "SELECT count(*) as cnt FROM course WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 WEEK) AND created_at <= NOW();";
  $recordscntlstcrs = mysqli_query($conn2, $querycntlstcrs);
  echo "<div id='coursecountlast'>";
  if($row_cntlstcrs = mysqli_num_rows($recordscntlstcrs)){
    // echo $recordscntlstcrs = round((($recordscntcrs > 0) ? ((32 / $recordscntcrs) * 100) : 0), 2);
    echo $recordscntlstcrs = $recordscntlstcrs->fetch_object()->cnt;
  }else{
    echo $recordscntlstcrs = 0;
  }
  echo "</div>";

  // courses
  $querycntcrss = "SELECT count(*) as cnt FROM course_main";
  $recordscntcrss = mysqli_query($conn2, $querycntcrss);
  echo "<div id='coursescount'>";
  if($row_cntcrs = mysqli_num_rows($recordscntcrss)){

      echo $recordscntcrss = $recordscntcrss->fetch_object()->cnt;
  }else{

      echo $recordscntcrss = 0;
  }
  echo "</div>";

  $querycntlstcrss = "SELECT count(*) as cnt FROM course_main WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 WEEK) AND created_at <= NOW();";
  $recordscntlstcrss = mysqli_query($conn2, $querycntlstcrss);
  echo "<div id='coursescountlast'>";
  if($row_cntlstcrs = mysqli_num_rows($recordscntlstcrss)){
    // echo $recordscntlstcrss = round((($recordscntcrss > 0) ? ((32 / $recordscntcrss) * 100) : 0), 2);
    echo $recordscntlstcrss = $recordscntlstcrss->fetch_object()->cnt;
  }else{
    echo $recordscntlstcrss = 0;
  }
  echo "</div>";

  // categories
  $querycntctgr = "SELECT count(*) as cnt FROM course_c";
  $recordscntctgr = mysqli_query($conn2, $querycntctgr);
  echo "<div id='categoriescount'>";
  if($row_cntcrs = mysqli_num_rows($recordscntctgr)){

      echo $recordscntctgr = $recordscntctgr->fetch_object()->cnt;
  }else{

      echo $recordscntctgr = 0;
  }
  echo "</div>";

  $querycntlstctgr = "SELECT count(*) as cnt FROM course_c WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 WEEK) AND created_at <= NOW();";
  $recordscntlstctgr = mysqli_query($conn2, $querycntlstctgr);
  echo "<div id='categoriescountlast'>";
  if($row_cntlstcrs = mysqli_num_rows($recordscntlstctgr)){
    // echo $recordscntlstctgr = round((($recordscntctgr > 0) ? ((32 / $recordscntctgr) * 100) : 0), 2);
    echo $recordscntlstctgr = $recordscntlstctgr->fetch_object()->cnt;
  }else{
    echo $recordscntlstctgr = 0;
  }
  echo "</div>";

  // pages
  $querycntpgs = "SELECT count(*) as cnt FROM seo";
  $recordscntpgs = mysqli_query($conn2, $querycntpgs);
  echo "<div id='pagescount'>";
  if($row_cntcrs = mysqli_num_rows($recordscntpgs)){

      echo $recordscntpgs = $recordscntpgs->fetch_object()->cnt;
  }else{

      echo $recordscntpgs = 0;
  }
  echo "</div>";

  $querycntlstpgs = "SELECT count(*) as cnt FROM seo WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 WEEK) AND created_at <= NOW();";
  $recordscntlstpgs = mysqli_query($conn2, $querycntlstpgs);
  echo "<div id='pagescountlast'>";
  if($row_cntlstcrs = mysqli_num_rows($recordscntlstpgs)){
    // echo $recordscntlstpgs = round((($recordscntpgs > 0) ? ((32 / $recordscntcrss) * 100) : 0), 2);
    echo $recordscntlstpgs = $recordscntlstpgs->fetch_object()->cnt;
  }else{
    echo $recordscntlstpgs = 0;
  }
  echo "</div>";
?>