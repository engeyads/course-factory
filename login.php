<?php
error_reporting(E_ALL);
require 'vendor/autoload.php';
require 'include/db.php';
function login($username, $password) {
    global $conn;
    // Sanitize the username
    $username = mysqli_real_escape_string($conn, $username);
    // Fetch the user's row from the database
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $storedPasswordHash = $row['password'];
        $userlevel = $row['userlevel']; // Assign the userlevel value to a variable
        // Calculate the MD5 hash of the provided password
        $passwordHash = md5($password);
        // Compare the stored MD5 hash with the calculated MD5 hash
        if ($passwordHash === $storedPasswordHash) {
            // Password is correct
            return $userlevel; // Return the userlevel value
        }
    }
    // Password is incorrect or user not found
    return false;
}

// Process login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the submitted username and password
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Attempt login
    $userlevel = login($username, $password);

    // Check login result
    if ($userlevel !== false) {
        // Start the session
        session_start();

        // Set session variables
        $_SESSION['username'] = $username;
        $_SESSION['userlevel'] = $userlevel;

        // Close the session for writing
        //session_write_close();
        
        
          
          header("Location: /course-factory/");
    
        // Redirect to the desired page
        
        exit();
    } else {
          $loginError = "Invalid username or password";
    }
}
?>

<!doctype html>
<html lang="en" class="dark-theme">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="assets/images/favicon-32x32.png" type="image/png" />
  <!-- Bootstrap CSS -->
  <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="assets/css/bootstrap-extended.css" rel="stylesheet" />
  <link href="assets/css/style.css" rel="stylesheet" />
  <link href="assets/css/icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

  <!-- loader-->
	<link href="assets/css/pace.min.css" rel="stylesheet" />
    <link href="assets/css/dark-theme.css" rel="stylesheet" />
    <?php $appname = "Course Factory"; ?>
  <title><?php echo $appname; ?></title>
</head>

<body class=" ">

  <!--start wrapper-->
  <div class="wrapper ">

 
       <!--start content-->
       <main class="authentication-content">
        <div class="container">
          <div class="mt-5">
            <div class="card rounded-0 overflow-hidden shadow-none  mb-5 mb-lg-0">
              <div class="row g-0">
                <div class="col-12 order-1 col-xl-8 d-flex align-items-center justify-content-center border-end">
                  <img src="assets/images/error/auth-img-7.png" class="img-fluid" alt="">
                </div>
                <div class="col-12 col-xl-4 order-xl-2">
                  <div class="card-body p-4 p-sm-5">
                    <h5 class="card-title">Sign In</h5>
                    <p class="card-text mb-4"> 
                    <?php if (isset($loginError)) : ?>
                    <p><?php echo $loginError; ?></p>
                    <?php endif; ?>
                    </p>
                     <form class="form-body" method="post" >
                      
                        <div class="row g-3">
                          <div class="col-12">
                            <label for="inputEmailAddress" class="form-label">User Name</label>
                            <div class="ms-auto position-relative">
                              <div class="position-absolute top-50 translate-middle-y search-icon px-3"><i class="bi bi-envelope-fill"></i></div>
                              <input type="text" class="form-control radius-30 ps-5" id="username" placeholder="User Name" name="username">
                            </div>
                          </div>
                          <div class="col-12">
                            <label for="inputChoosePassword" class="form-label">Enter Password</label>
                            <div class="ms-auto position-relative">
                              <div class="position-absolute top-50 translate-middle-y search-icon px-3"><i class="bi bi-lock-fill"></i></div>
                              <input type="password" class="form-control radius-30 ps-5" id="inputChoosePassword" placeholder="Password" name="password">
                            </div>
                          </div>
             
                          <div class="col-12">
                            <div class="d-grid">
                              <button type="submit" class="btn btn-primary radius-30">Sign In</button>
                            </div>
                          </div>
                    
                    
                          </div>
                        </div>
                    </form>
                 </div>
                </div>
              </div>
            </div>
          </div>
        </div>
       </main>
        
       <!--end page main-->

       <footer class="bg-white border-top p-3 text-center fixed-bottom">
        <p class="mb-0">Copyright © 2021. All right reserved.</p>
      </footer>

  </div>
  <!--end wrapper-->


  <!-- Bootstrap bundle JS -->
  <script src="assets/js/bootstrap.bundle.min.js"></script>

  <!--plugins-->
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/pace.min.js"></script>


</body>

</html>