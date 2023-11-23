<?php
$arr = GetRow($_SESSION['user_id'],'uid', 'profile' , $conn);
function calculateAge($birthdate) {
  $today = new DateTime();
  $diff = $today->diff(new DateTime($birthdate));
  return $diff->y;
}
?>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile Edit</title>

  <style>
    .avatar {
      width: 150px;
      height: 150px;
      object-fit: cover;
      position: relative;
      border-radius: 50%;
      overflow: hidden;
      margin-left:50%;
      transform:translateX(-50%);
    }

    .avatar img{
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar:hover .custom-button {
      display: inline-block;
    }

    
    .file-input {
      display: none;
    }
    
    .custom-button {
        position: absolute;
        left:9px;
        bottom:0;
        display: none;
        padding: 5px 38px;
        cursor: pointer;
    }
  </style>
</head>
<body>
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center">
      <div class="breadcrumb-title pe-3 text-white">Pages</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt text-white"></i></a>
            </li>
            <li class="breadcrumb-item active text-white" aria-current="page">User Profile</li>
          </ol>
        </nav>
      </div>
      <div class="ms-auto">
        <div class="btn-group">
          <button type="button" class="btn btn-light">Settings</button>
          <button type="button" class="btn btn-light split-bg-light dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">	<span class="visually-hidden">Toggle Dropdown</span>
          </button>
          <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">	<a class="dropdown-item" href="javascript:;">Action</a>
            <a class="dropdown-item" href="javascript:;">Another action</a>
            <a class="dropdown-item" href="javascript:;">Something else here</a>
            <div class="dropdown-divider"></div>	<a class="dropdown-item" href="javascript:;">Separated link</a>
          </div>
        </div>
      </div>
    </div>
    <!--end breadcrumb-->
    
    <div class="profile-cover bg-dark"></div>

    <div class="row">
      <div class="col-12 col-lg-8">
        <div class="card shadow-sm border-0">
          <div class="card-body">
              <h5 class="mb-0">My Account</h5>
              <hr>
              <div class="card shadow-none border">
                <div class="card-header">
                  <h6 class="mb-0">USER INFORMATION</h6>
                </div>
                <div class="card-body">
                  <form class="row g-3">
                      <div class="col-6">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" value="<?php echo $_SESSION['username']; ?>">
                      </div>
                      <div class="col-6">
                      <label class="form-label">Email address</label>
                      <input type="text" class="form-control" value="<?php echo $arr['email']; ?>">
                    </div>
                      <div class="col-6">
                        <label class="form-label">First Name</label>
                        <input type="text" class="form-control" value="<?php echo $arr['name']; ?>">
                    </div>
                    <div class="col-6">
                        <label class="form-label">middle Name</label>
                        <input type="text" class="form-control" value="<?php echo $arr['middlename']; ?>">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Last Name</label>
                        <input type="text" class="form-control" value="<?php echo $arr['surename']; ?>">
                    </div>
                  </form>
                </div>
              </div>
              <div class="card shadow-none border">
                <div class="card-header">
                  <h6 class="mb-0">CONTACT INFORMATION</h6>
                </div>
                <div class="card-body">
                  <form class="row g-3">
                    <div class="col-12">
                      <label class="form-label">Address</label>
                      <input type="text" class="form-control" value="<?php echo $arr['address']; ?>">
                      </div>
                      <div class="col-6">
                        <label class="form-label">City</label>
                        <input type="text" class="form-control" value="<?php echo $arr['city']; ?>">
                      </div>
                      <div class="col-6">
                      <label class="form-label">Country</label>
                      <input type="text" class="form-control" value="<?php echo $arr['country']; ?>">
                    </div>
                      <div class="col-6">
                        <label class="form-label">Pin Code</label>
                        <input type="text" class="form-control" value="<?php echo $arr['pin']; ?>">
                    </div>
                    
                    <div class="col-12">
                      <label class="form-label">About Me</label>
                      <textarea class="form-control" rows="4" cols="4" placeholder="<?php echo $arr['bio']; ?>"><?php echo $arr['bio'] ? $arr['bio'] : ''; ?></textarea>
                      </div>
                  </form>
                </div>
              </div>
              <div class="text-start">
                <button type="button" class="btn btn-primary px-4">Save Changes</button>
              </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-4">
        <div class="card shadow-sm border-0 overflow-hidden">
          <div class="card-body">
              <div class="profile-avatar text-center">
              <div class="avatar mt-3 justify-content-center">
                  <img src="<?php echo $url; ?>assets/images/avatars/<?php echo $arr['photo'] ? $arr['photo'] : 'avatar-1.png'; ?>" alt="Avatar" class="avatar-image" hiegh=120 width=120>
                  <button class="custom-button btn btn-primary" onclick="handleButtonClick()">Change</button>
                  <input id="file-input" class="file-input" type="file" accept="image/*" onchange="handleFileSelect(event)">
              </div>
                
              </div>
              <div class="d-flex align-items-center justify-content-around mt-5 gap-3">
                  <div class="text-center">
                    <h4 class="mb-0">45</h4>
                    <p class="mb-0 text-secondary">Friends</p>
                  </div>
                  <div class="text-center">
                    <h4 class="mb-0">15</h4>
                    <p class="mb-0 text-secondary">Photos</p>
                  </div>
                  <div class="text-center">
                    <h4 class="mb-0">86</h4>
                    <p class="mb-0 text-secondary">Comments</p>
                  </div>
              </div>
              <div class="text-center mt-4">
                <h4 class="mb-1"><?php echo $arr['name'].' '.$arr['surename']; echo $arr['birthdate'] ? ', '.calculateAge($arr['birthdate']) : ''; ?></h4>
                <p class="mb-0 text-secondary"><?php echo $arr['city'] ? $arr['city'].', '.$arr['country'] : ''; ?></p>
                <div class="mt-4"></div>
                <h6 class="mb-1"><?php echo $arr['job'] ? $arr['job'].' - '.$arr['company'] : ''; ?></h6>
                <p class="mb-0 text-secondary"><?php echo $arr['faculity'] ? $arr['studylevel'].' of '.$arr['faculity'].', '.$arr['university'] : ''; ?></p>
              </div>
              <hr>
              <div class="text-start">
                <h5 class="">About</h5>
                <p class="mb-0"><?php echo $arr['bio'] ? $arr['bio'] : ''; ?>
              </div>
          </div>
          <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent border-top">
              Followers
              <span class="badge bg-primary rounded-pill">95</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent">
              Following
              <span class="badge bg-primary rounded-pill">75</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent">
              Templates
              <span class="badge bg-primary rounded-pill">14</span>
            </li>
          </ul>
        </div>
      </div>
    </div><!--end row-->

</body>
<script>
    function handleButtonClick() {
      document.getElementById('file-input').click();
    }
    function handleFileSelect(event) {
      var input = event.target;
      var file = input.files[0];
      
      if (file && file.type.startsWith('image/')) {
        var reader = new FileReader();
        
        reader.onload = function() {
          document.getElementsByClassName('avatar-image')[0].src = reader.result;
        }
        reader.readAsDataURL(file);
      }
    }
  </script>
