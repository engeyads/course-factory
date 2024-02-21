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
// priceerrors



$querycntpgs = "SELECT *,cities.name as city_name, course.price as pric,count(*) as cnt1,course_main.c_id as cid,course.id as eid,course_main.$DBweekname
FROM course
LEFT JOIN course_main on course_main.c_id=course.c_id
LEFT JOIN course_c on course_c.id=course_main.course_c
LEFT JOIN cities on cities.id=course.city
WHERE 1 
GROUP BY pric,course.city,course_c.class,course_main.$DBweekname,y1";

$recordscntpgs = mysqli_query($conn2, $querycntpgs);
echo "<div id='priceerrors'>";
$recordscntspgs = 0;
if($row_cntcrs = mysqli_num_rows($recordscntpgs)){

    while($cntcrs = mysqli_fetch_assoc($recordscntpgs)){
        $citypricecell = ($cntcrs[$DBweekname] > 4 || $cntcrs[$DBweekname] <= 0 ? '' : 'w'.$cntcrs[$DBweekname].'_p'.(strtolower((isset($cntcrs['class']) ? $cntcrs['class'] : ' ')) == 'a'? '' : '_'.strtolower((isset($cntcrs['class']) ? $cntcrs['class'] : 'Undefined'))));
        if($citypricecell != ''){

            if($cntcrs[$citypricecell] != $cntcrs['pric']){
                $recordscntspgs++;
            }
        }
    }
    // echo $recordscntpgs = $recordscntpgs->fetch_object()->cnt;
}
echo $recordscntspgs;
echo "</div>";

// priceerrorstotal
$querycntlstpgs = "SELECT *,cities.name as city_name, course.price as pric,count(*) as cnt1,course_main.c_id as cid,course.id as eid,course_main.$DBweekname
FROM course
LEFT JOIN course_main on course_main.c_id=course.c_id
LEFT JOIN course_c on course_c.id=course_main.course_c
LEFT JOIN cities on cities.id=course.city
WHERE 1 AND course.created_at > DATE_SUB(NOW(), INTERVAL 1 WEEK)
GROUP BY pric,course.city,course_c.class,course_main.$DBweekname,y1";
$recordscntlstpgs = mysqli_query($conn2, $querycntlstpgs);
echo "<div id='priceerrorslast'>";
$recordscntlstspgs = 0;
if($row_cntlstcrs = mysqli_num_rows($recordscntlstpgs)){
 
    while($cntcrs = mysqli_fetch_assoc($recordscntlstpgs)){
        $citypricecell = ($cntcrs[$DBweekname] > 4 || $cntcrs[$DBweekname] <= 0 ? '' : 'w'.$cntcrs[$DBweekname].'_p'.(strtolower((isset($cntcrs['class']) ? $cntcrs['class'] : ' ')) == 'a'? '' : '_'.strtolower((isset($cntcrs['class']) ? $cntcrs['class'] : 'Undefined'))));
        if($citypricecell != ''){
            if($cntcrs[$citypricecell] != $cntcrs['pric']){
                $recordscntlstspgs++;
            }
        }
    }
}
echo $recordscntlstspgs;
echo "</div>";

?>