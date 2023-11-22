<?php
function checkTableColumns($tableName, $conn) {
// if($_SESSION['db_name'] == 'Euro Wings Ar'){
//         $query1 = "ALTER TABLE cities
// ADD COLUMN code VARCHAR(3) CHARACTER SET utf8 COLLATE utf8_bin NULL;";
// mysqli_query($conn, $query1);
//     $sqlQueries = array(
//         "UPDATE cities SET code = 'DXB' WHERE id = 1;",
//         "UPDATE cities SET code = 'CAI' WHERE id = 4;",
//         "UPDATE cities SET code = 'CAS' WHERE id = 5;",
//         "UPDATE cities SET code = 'AMM' WHERE id = 6;",
//         "UPDATE cities SET code = 'LON' WHERE id = 7;",
//         "UPDATE cities SET code = 'SSH' WHERE id = 8;",
//         "UPDATE cities SET code = 'KUL' WHERE id = 10;",
//         "UPDATE cities SET code = 'IST' WHERE id = 11;",
//         "UPDATE cities SET code = 'PAR' WHERE id = 12;",
//         "UPDATE cities SET code = 'GVA' WHERE id = 111;",
//         "UPDATE cities SET code = 'BOS' WHERE id = 112;",
//         "UPDATE cities SET code = 'AMS' WHERE id = 113;",
//         "UPDATE cities SET code = 'SIN' WHERE id = 117;",
//         "UPDATE cities SET code = 'BAH' WHERE id = 119;",
//         "UPDATE cities SET code = 'ROM' WHERE id = 121;",
//         "UPDATE cities SET code = 'KWI' WHERE id = 124;",
//         "UPDATE cities SET code = 'MCT' WHERE id = 125;",
//         "UPDATE cities SET code = 'BKK' WHERE id = 126;",
//         "UPDATE cities SET code = 'MAD' WHERE id = 129;",
//         "UPDATE cities SET code = 'BCN' WHERE id = 238;",
//         "UPDATE cities SET code = 'TZX' WHERE id = 240;",
//         "UPDATE cities SET code = 'LIS' WHERE id = 241;",
//         "UPDATE cities SET code = 'MUC' WHERE id = 242;",
//         "UPDATE cities SET code = 'VIE' WHERE id = 247;",
//         "UPDATE cities SET code = 'TBS' WHERE id = 248;",
//         "UPDATE cities SET code = 'MXP' WHERE id = 249;",
//         "UPDATE cities SET code = 'GYD' WHERE id = 252;",
//         "UPDATE cities SET code = 'CGK' WHERE id = 253;"
//     );
    
//     foreach ($sqlQueries as $query) {
//         if ($conn->query($query) === TRUE) {
//             echo "Record updated successfully<br>";
//         } else {
//             echo "Error updating record: " . $conn->error . "<br>";
//         }
//     }
// }
// $query1 = 'DESCRIBE course_main;';
//              mysqli_query($conn, $query1);
// $query1 = "ALTER TABLE course_main
// DROP COLUMN done;";
// mysqli_query($conn, $query1);
// $query1 = "ALTER TABLE course_main 
// MODIFY done  DATE NULL;";
// mysqli_query($conn, $query1);
//     $query1 = "ALTER TABLE course_main
//   MODIFY week VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci,
//   MODIFY name VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci,
//   MODIFY name_file VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci;";
            // mysqli_query($conn, $query1);
     
    //  $createTableSql = "CREATE TABLE IF NOT EXISTS sitekeywords (
    //      id INT AUTO_INCREMENT PRIMARY KEY,
    //      name varchar(200) NULL,
    //      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    //      updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    //      published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    //      deleted_at TIMESTAMP NULL
    //  )";
    //  mysqli_query($conn, $createTableSql);

    //  $createTableSql = "CREATE TABLE IF NOT EXISTS sitekeywords_coursemain (
    //      id SERIAL PRIMARY KEY,
    //      course_main_id INT ,
    //      sitekeywords_id INT ,
    //      FOREIGN KEY (course_main_id) REFERENCES course_main(id) ON DELETE CASCADE ,
    //      FOREIGN KEY (sitekeywords_id) REFERENCES sitekeywords(id) ON DELETE CASCADE,
    //      CONSTRAINT unique_coursekeywords UNIQUE (course_main_id, sitekeywords_id)
    //  )";
    //  mysqli_query($conn, $createTableSql);

    // SQL query to fetch all table names in the database

    // ---------------------- uncomment this part to check all tables ----------------------
    // global $db;
    // $dbname= $db['db_name'];

    // $tableName1 = 'course';
    // $query = "SELECT COLUMN_NAME, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH
    //           FROM INFORMATION_SCHEMA.COLUMNS
    //           WHERE TABLE_SCHEMA = '$dbname' AND TABLE_NAME = '$tableName'";
    // $result = mysqli_query($conn, $query);

    // if ($result) {
    //     echo "Columns and structure for table '$tableName': <br>";
    //     while ($row = mysqli_fetch_assoc($result)) {
    //         echo "Column Name: " . $row['COLUMN_NAME'] . "<br>";
    //         echo "Data Type: " . $row['DATA_TYPE'] . "<br>";
    //         if ($row['CHARACTER_MAXIMUM_LENGTH'] !== null) {
    //             echo "Max Length: " . $row['CHARACTER_MAXIMUM_LENGTH'] . "<br>";
    //         }
    //         echo "<br>";
    //     }
    // } else {
    //     echo "Error: " . mysqli_error($conn);
    // }
    // ---------------------------- End of check all tables --------------------------------
    $createTableSql = "CREATE TABLE IF NOT EXISTS seo (
        id INT AUTO_INCREMENT PRIMARY KEY,
        link TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
        updated_at TIMESTAMP NULL,
        published_at TIMESTAMP NULL,
        deleted_at TIMESTAMP NULL,
        speed_test float NULL,
        speed_date date NULL,
        google_index int NULL,
        indexingState text NULL,
        verdict text NULL,
        coverageState text NULL,
        robotsTxtState text NULL,
        lastCrawlTime TIMESTAMP NULL,
        pageFetchState text NULL,
        googleCanonical text NULL,
        google_date date NULL,
        yandex_index int NULL,
        yandex_date date NULL,
        bing_index int NULL,
        bing_date date NULL,
        created_at TIMESTAMP NULL
    )";
    mysqli_query($conn, $createTableSql);

    $tableExistsQuery = "SHOW TABLES LIKE '$tableName'";
    $tableExistsResult = $conn->query($tableExistsQuery);
        
    if ($tableExistsResult->num_rows > 0) {
        $columnsToCheck = [
            "created_at" => ["type" => "TIMESTAMP", "nullable" => false, "default" => "CURRENT_TIMESTAMP"],
            "updated_at" => ["type" => "TIMESTAMP", "nullable" => true, "default" => null],
            "published_at" => ["type" => "TIMESTAMP", "nullable" => true, "default" => null],
            "deleted_at" => ["type" => "TIMESTAMP", "nullable" => true, "default" => null]
        ];

        $columnsModified = [];

        foreach ($columnsToCheck as $columnName => $columnDetails) {
            $columnExistsQuery = "SHOW COLUMNS FROM $tableName LIKE '$columnName'";
            $columnExistsResult = $conn->query($columnExistsQuery);

            if ($columnExistsResult->num_rows === 0) {
                // If column is nullable and default is null
                if($columnDetails["nullable"] == true && $columnDetails["default"] == null) {
                    $alterQuery = "ALTER TABLE $tableName ADD $columnName " . $columnDetails["type"] . " NULL";
                } else {
                    $alterQuery = "ALTER TABLE $tableName ADD $columnName " . $columnDetails["type"] . " NOT NULL DEFAULT " . $columnDetails["default"];
                }
                $conn->query($alterQuery);
                $columnsModified[] = $columnName;
            } else {
                // add code to handle already existing columns here
            }
        }

        if (!empty($columnsModified)) {
            return "Columns " . implode(", ", $columnsModified) . " have been modified.";
        } else {
            return "";
        }
    } else {
        return "Table does not exist.";
    }
    
    
}



?>
