<?php
    $path = dirname(__FILE__);
    include $path.'/conf.php';   
        $year = $_POST['y1'];
        $price = $_POST['price'];
        $newprice = $_POST['newprice'];
        $city = $_POST['city'];
        $class = $_POST['class'];
        $week = $_POST[$DBweekname];
        $citypricecell = 'w'.$week.'_p'.(strtolower($class) == 'a' ? '' : '_'.strtolower($class));
        $cityprice = $_POST[$citypricecell];
        echo $sql = "UPDATE course
        LEFT JOIN course_main ON course_main.c_id = course.c_id
        LEFT JOIN course_c ON course_c.id = course_main.course_c
        LEFT JOIN cities ON cities.id = course.city
        SET course.price = $newprice
        WHERE course.city = $city
          AND course_c.class = '$class'
          AND $DBweekname = '$week'
          AND course.price=$price
          AND cities.$citypricecell = $cityprice
          AND y1=$year;";
        $result = mysqli_query($conn2, $sql);
        if($result){
                $response = 'success';
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
