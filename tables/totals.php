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

  // durations
  $querycntpgs = "SELECT COUNT(*) AS cnt
  FROM (
      SELECT TIMESTAMPDIFF(DAY, CONCAT(y1, '-', m1, '-', d1), CONCAT(y2, '-', m2, '-', d2)) + 1 AS days_diff
      FROM `course`
      LEFT JOIN course_main ON course.c_id = course_main.c_id
      HAVING (days_diff != 5 AND days_diff != 12 AND days_diff != 18 AND days_diff != 26)
  ) AS subquery;";
  $recordscntpgs = mysqli_query($conn2, $querycntpgs);
  echo "<div id='durations'>";
  if($row_cntcrs = mysqli_num_rows($recordscntpgs)){

      echo $recordscntpgs = $recordscntpgs->fetch_object()->cnt;
  }else{

      echo $recordscntpgs = 0;
  }
  echo "</div>";

  // durationstotal
  $querycntlstpgs = "SELECT COUNT(*) AS cnt
  FROM (
      SELECT TIMESTAMPDIFF(DAY, CONCAT(y1, '-', m1, '-', d1), CONCAT(y2, '-', m2, '-', d2)) + 1 AS days_diff
      FROM `course`
      LEFT JOIN course_main ON course.c_id = course_main.c_id
      WHERE `course`.`created_at` >= DATE_SUB(NOW(), INTERVAL 1 WEEK) HAVING (days_diff != 5 AND days_diff != 12 AND days_diff != 18 AND days_diff != 26)
  ) AS subquery;";
  $recordscntlstpgs = mysqli_query($conn2, $querycntlstpgs);
  echo "<div id='durationslast'>";
  if($row_cntlstcrs = mysqli_num_rows($recordscntlstpgs)){
    // echo $recordscntlstpgs = round((($recordscntpgs > 0) ? ((32 / $recordscntcrss) * 100) : 0), 2);
    echo $recordscntlstpgs = $recordscntlstpgs->fetch_object()->cnt;
  }else{
    echo $recordscntlstpgs = 0;
  }
  echo "</div>";

  // oldevents
  $querycntpgs = "SELECT COUNT(*) AS cnt FROM `course` LEFT JOIN course_main ON course.c_id = course_main.c_id WHERE TIMESTAMP(CONCAT(y1, '-', LPAD(m1, 2, '0'), '-', LPAD(d1, 2, '0'))) <= CURDATE()";
  $recordscntpgs = mysqli_query($conn2, $querycntpgs);
  echo "<div id='oldevents'>";
  if($row_cntcrs = mysqli_num_rows($recordscntpgs)){

      echo $recordscntpgs = $recordscntpgs->fetch_object()->cnt;
  }else{

      echo $recordscntpgs = 0;
  }
  echo "</div>";

  // oldeventstotal
  $querycntlstpgs = "SELECT COUNT(*) AS cnt FROM `course` LEFT JOIN course_main ON course.c_id = course_main.c_id WHERE TIMESTAMP(CONCAT(y1, '-', LPAD(m1, 2, '0'), '-', LPAD(d1, 2, '0'))) <= CURDATE() AND course.created_at >= DATE_SUB(NOW(), INTERVAL 1 WEEK)";
  $recordscntlstpgs = mysqli_query($conn2, $querycntlstpgs);
  echo "<div id='oldeventslast'>";
  if($row_cntlstcrs = mysqli_num_rows($recordscntlstpgs)){
    // echo $recordscntlstpgs = round((($recordscntpgs > 0) ? ((32 / $recordscntcrss) * 100) : 0), 2);
    echo $recordscntlstpgs = $recordscntlstpgs->fetch_object()->cnt;
  }else{
    echo $recordscntlstpgs = 0;
  }
  echo "</div>";

    // duplicatedevents
    $querycntpgs = "SELECT count(*) as cnt, id AS ids_to_delete, id, c_id, city, price,d1, m1, y1, d2, m2, y2, DATE(TIMESTAMP(CONCAT(course.y1, '-', LPAD(course.m1, 2, '0'), '-', LPAD(course.d1, 2, '0')))) AS start_date, DATE(TIMESTAMP(CONCAT(course.y2, '-', LPAD(course.m2, 2, '0'), '-', LPAD(course.d2, 2, '0')))) AS end_date FROM course WHERE (c_id, d1, m1, y1, d2, m2, y2, city) IN ( SELECT c_id, d1, m1, y1, d2, m2, y2, city FROM course GROUP BY c_id, d1, m1, y1, d2, m2, y2, city HAVING COUNT(*) > 1)";
    $recordscntpgs = mysqli_query($conn2, $querycntpgs);
    echo "<div id='duplicatedevents'>";
    if($row_cntcrs = mysqli_num_rows($recordscntpgs)){
  
        echo $recordscntpgs = $recordscntpgs->fetch_object()->cnt/2;
    }else{
  
        echo $recordscntpgs = 0;
    }
    echo "</div>";
  
    // duplicatedeventstotal
    $querycntlstpgs = "SELECT count(*) as cnt, id AS ids_to_delete, id, c_id, city, price,d1, m1, y1, d2, m2, y2, DATE(TIMESTAMP(CONCAT(course.y1, '-', LPAD(course.m1, 2, '0'), '-', LPAD(course.d1, 2, '0')))) AS start_date, DATE(TIMESTAMP(CONCAT(course.y2, '-', LPAD(course.m2, 2, '0'), '-', LPAD(course.d2, 2, '0')))) AS end_date FROM course WHERE (c_id, d1, m1, y1, d2, m2, y2, city) IN ( SELECT c_id, d1, m1, y1, d2, m2, y2, city FROM course WHERE course.created_at >= DATE_SUB(NOW(), INTERVAL 1 WEEK) GROUP BY c_id, d1, m1, y1, d2, m2, y2, city HAVING COUNT(*) > 1)";
    $recordscntlstpgs = mysqli_query($conn2, $querycntlstpgs);
    echo "<div id='duplicatedeventslast'>";
    if($row_cntlstcrs = mysqli_num_rows($recordscntlstpgs)){
      // echo $recordscntlstpgs = round((($recordscntpgs > 0) ? ((32 / $recordscntcrss) * 100) : 0), 2);
      echo $recordscntlstpgs = $recordscntlstpgs->fetch_object()->cnt/2;
    }else{
      echo $recordscntlstpgs = 0;
    }
    echo "</div>";
    
    
?>