<?php
    $path = dirname(__FILE__);
    include $path.'/conf.php';
        $year = $_POST['y1'];
        $price = $_POST['price'];
        $city = $_POST['city'];
        $class = $_POST['class'];
        $week = $_POST[$DBweekname];
        $citypricecell = $_POST['citypricecellnm'];
        $cityprice = $_POST['citypricecell'];
        $sql = "DELETE course
        FROM course
        LEFT JOIN course_main ON course_main.c_id = course.c_id
        LEFT JOIN course_c ON course_c.id = course_main.course_c
        LEFT JOIN cities ON cities.id = course.city
        WHERE course.city = $city
          AND course_c.class = '$class'
          AND $DBweekname = '$week'
          AND course.price=$price
          AND course.y1=$year
          AND cities.$citypricecell =$cityprice;";
        $result = mysqli_query($conn2, $sql);
        if($result){
                $response = $result;
                header("Refresh: 0; url=" . $url . "event/editprice");
        }else{
                $response = 'error';
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

