<?php
    // echo "<pre>";
    // print_r($_POST);
    // echo "</pre>";
    $id = $_POST['id'];
    $c_id = $_POST['c_id'];
    $crsname = $_POST['crsname'];
    $ctyname = $_POST['ctyname'];
    $city = $_POST['city'];
    $price = $_POST['price'];
    $start = explode('-', $_POST['startday']);
    $end = explode('-', $_POST['endday']);

    $tablename = "course";
    $userId = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $databasenm = $_SESSION['db_name'];
    $replacements = array(
        "course_main" => "Courses",
        "course_c" => "Categories",
        "course" => "Events",
        "cities" => "Cities"
    );

    $d1 = $start[2];
    $m1 = $start[1];
    $y1 = $start[0];

    $d2 = $end[2];
    $m2 = $end[1];
    $y2 = $end[0];


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

    $sql = "SELECT $DBweekname FROM course LEFT JOIN course_main ON course_main.c_id=course.c_id WHERE course.id = '$id'";
    $result = mysqli_query($conn2, $sql);
    if($result){
        $row = mysqli_fetch_assoc($result);
        switch ($row[$DBweekname]){
            case 2:
            $daysdration = 11;
            break;
            case 3:
            $daysdration = 17;
            break;
            case 4:
            $daysdration = 25;
            break;
            // default case is 1
            default: $daysdration = 4;
        }
    }
    $sql = "UPDATE course SET c_id = '$c_id', city = '$city', price = '$price', d1 = '$d1', m1 = '$m1', y1 = '$y1', 
    d2 = DAY(DATE_ADD(CONCAT($y1, '-', $m1, '-', $d1), INTERVAL $daysdration DAY)),
    m2 = MONTH(DATE_ADD(CONCAT($y1, '-', $m1, '-', $d1), INTERVAL $daysdration DAY)),
    y2 = YEAR(DATE_ADD(CONCAT($y1, '-', $m1, '-', $d1), INTERVAL $daysdration DAY)) WHERE id = '$id'";
    $result = mysqli_query($conn2, $sql);
    if($result){
        
        // $sql = "SELECT * FROM course WHERE id = '$id'";
        // $result = mysqli_query($conn2, $sql);
        // $row = mysqli_fetch_assoc($result);
        
        // $c_id = $row['c_id'];
        // $city = $row['city'];
        // $price = $row['price'];
        // $startday = $row['d1'].'-'.$row['m1'].'-'.$row['y1'];
        // $endday = $row['d2'].'-'.$row['m2'].'-'.$row['y2'];

        ?>
        <!-- <table>
            <tr>
                <td>id</td>
                <td><?php //echo $id; ?></td>
            </tr>
            <tr>
                <td>c_id</td>
                <td><?php //echo $c_id; ?></td>
            </tr>
            <tr>
                <td>city</td>
                <td><?php //echo $city; ?></td>
            </tr>
            <tr>
                <td>price</td>
                <td><?php //echo $price; ?></td>
            </tr>
            <tr>
                <td>startday</td>
                <td><?php //echo $startday; ?></td>
            </tr>
            <tr>
                <td>endday</td>
                <td><?php //echo $endday; ?></td>
            </tr>
        </table>     -->
        <?php
        
        
        $newtablename = str_replace(array_keys($replacements), array_values($replacements), $tablename);
        $notificationSql = "INSERT INTO notifications (`user_id`, `message`, `uid`, `status`, `created_at`) VALUES ('$userId', '$username has updated a duplicated event $id: $crsname at $ctyname city - $newtablename in db: $databasenm', '$userId', 'unread', NOW())";
        mysqli_query($conn, $notificationSql);
        $adminSql = "SELECT id FROM users WHERE userlevel = 10";
        $adminResult = mysqli_query($conn, $adminSql);
        $adminUserIds = array();

        if ($adminResult && mysqli_num_rows($adminResult) > 0) {
            while ($adminRow = mysqli_fetch_assoc($adminResult)) {
                if($adminRow['id'] != $userId){
                    $adminUserIds[] = $adminRow['id'];
                }
            }
        }

        // Insert the notification for admin users
        foreach ($adminUserIds as $adminUserId) {
            $insertSql = "INSERT INTO notifications (`user_id`, `message`, `uid`, `status`, `created_at`) VALUES ('$adminUserId', '$username has updated a duplicated event $id: $crsname at $ctyname city - $newtablename in db: $databasenm', '$userId', 'unread', NOW())";
            mysqli_query($conn, $insertSql);
        }

        echo "success";
    }else{
        echo "error";
    }

?>
<meta http-equiv="refresh" content="0;url=/event/fixduplicate" />
