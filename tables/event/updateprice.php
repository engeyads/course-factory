<?php
    $path = dirname(__FILE__);
    include $path.'/conf.php';   
        $year = $_POST['y1'];
        $price = $_POST['price'];
        $newprice = $_POST['newprice'];
        $city = $_POST['city'];
        $class = $_POST['class'];
        $week = $_POST[$DBweekname];

        $ctyname = $_POST['ctyname'];
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

        if($week >0 && $week <=4){
            $citypricecel = 'w'.$week.'_p'.(strtolower($class) == 'a' ? '' : '_'.strtolower($class));
            $cityprice = $_POST[$citypricecel];
            $citypricecell = 'cities.w'.$week.'_p'.(strtolower($class) == 'a' ? '' : '_'.strtolower($class)).' = '.$cityprice;
        }else{
            $citypricecell = ' 1 ';
        }
        echo $sql = "UPDATE course
        LEFT JOIN course_main ON course_main.c_id = course.c_id
        LEFT JOIN course_c ON course_c.id = course_main.course_c
        LEFT JOIN cities ON cities.id = course.city
        SET course.price = $newprice
        WHERE course.city = $city
          AND course_c.class = '$class'
          AND $DBweekname = '$week'
          AND course.price=$price
          AND $citypricecell
          AND y1=$year;";
        $result = mysqli_query($conn2, $sql);
        if($result){
                $response = 'success';
                
                $newtablename = str_replace(array_keys($replacements), array_values($replacements), $tablename);
                $notificationSql = "INSERT INTO notifications (`user_id`, `message`, `uid`, `status`, `created_at`) VALUES ('$userId', '$username has updated a events prices for City: ($ctyname) Year: ($year) Class: ($class) Weeks: ($week) from old price: ($price) to new price: ($newprice) - $newtablename in db: $databasenm', '$userId', 'unread', NOW())";
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
                    $insertSql = "INSERT INTO notifications (`user_id`, `message`, `uid`, `status`, `created_at`) VALUES ('$adminUserId', '$username has updated a events prices for City: ($ctyname) Year: ($year) Class: ($class) Weeks: ($week) from old price: ($price) to new price: ($newprice) - $newtablename in db: $databasenm', '$userId', 'unread', NOW())";
                    mysqli_query($conn, $insertSql);
                }
                print_r($result);
                header("Refresh: 1; url=" . $url . "event/editprice");
        }

        // Clear any previously generated output
        ob_clean();

        // Set the appropriate headers
        header('Content-Length: ' . strlen($response));
        // Send the JSON response
        echo $response;
        // Flush the output buffer
        ob_flush();
        exit; // Stop further execution
?>
