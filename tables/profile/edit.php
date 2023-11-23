
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile Edit</title>

  <style>
    /* Same as before */

    .error {
      color: red;
    }

    .password-container {
      position: relative;
    }

    #password {
      padding-right: 40px;
    }

    .toggle-password {
      position: absolute;
      top: 50%;
      right: 10px;
      transform: translateY(-50%);
      cursor: pointer;
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


    .profile-picture-container {
        position: relative;
        width: 150px; /* Adjust container size as needed */
        height: 150px;
        overflow: hidden;
        border-radius: 50%;
        margin: 0 auto; /* Center the container */
        border: 2px solid #3498db; /* Border color */
    }

    .profile-picture {
        position: relative;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }

    .profile-picture img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    .edit-button {
        position: absolute;
        top: 90%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #3498db;
        color: #fff;
        border: none;
        padding: 8px 16px;
        cursor: pointer;
        opacity: 0;
        transition: opacity 0.3s ease;
        border-radius: 8px; /* Rounded corners for the button */
    }

    .profile-picture-container:hover .edit-button {
        opacity: 1;
    }
  </style>
</head>
<body>

<div class="profile-cover bg-dark"></div>

<div class="row">
  <center>

    <div class="col-4 ">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <h5 class="mb-0">My Account</h5>
          <hr>
          <div class="card shadow-none border">
            <div class="card-header">
              <h6 class="mb-0">USER INFORMATION</h6>
            </div>
            <div>
            <div class="profile-picture-container">
        <div class="profile-picture" id="profilePicture" onclick="openFileInput()">
            <!-- Display the user's current profile picture -->
            <img src="<?php echo $userphoto; ?>" alt="Profile Picture" id="profileImage">
            <button class="edit-button" onclick="openFileInput()">Edit</button>
        </div>
        <input type="file" id="fileInput" style="display: none;" accept="image/*" onchange="handleFileSelect()">
    </div>
            </div>
            <div class="card-body">
              <form id="form" class=" g-3" action="<?php echo $url; ?>profile/update" method="POST">
                <div class="row col-8">
                  <label class="form-label">Username</label>
                  <input type="text" class="form-control" value="<?php echo $_SESSION['username']; ?>" disabled>
                </div>
                <div class="row col-8">
                  <label class="form-label">New Password</label>
                  <div class="password-container">
                    <input id="password" type="password" name="password" class="form-control" value="">
                    <i class="toggle-password lni lni-emoji-cool" id="togglePassword"></i>
                  </div>
                  <p id="errorText" class="error"></p>
                </div><br />
                <div class="row col-8 text-start">
                  <button id="sbmt" type="submit" class="btn btn-primary px-4" disabled>Save Changes</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </center>
</div>

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
  $('#password').on('keyup', function() {
    if ($(this).val() == '') {
      $('#sbmt').attr('disabled', true);
    } else {
      $('#sbmt').attr('disabled', false);
    }
  })

  const togglePassword = document.getElementById("togglePassword");
const passwordField = document.getElementById("password");
const Form = document.getElementById("form");
const errorText = document.getElementById("errorText");

togglePassword.addEventListener("click", () => {
  if (passwordField.type === "password") {
    passwordField.type = "text";
    togglePassword.classList.remove("lni-emoji-cool");
    togglePassword.classList.add("lni-emoji-smile");
  } else {
    passwordField.type = "password";
    togglePassword.classList.remove("lni-emoji-smile");
    togglePassword.classList.add("lni-emoji-cool");
  }
});

Form.addEventListener("submit", (event) => {
  const password = passwordField.value;
  const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/;

  if (!regex.test(password)) {
    event.preventDefault(); // Prevent the default form submission
    errorText.textContent = "Password must be at least 6 characters long and contain a special character, a number, an uppercase letter, and a lowercase letter.";
  } else {
    errorText.textContent = "";
    // Continue with form submission
  }
});


// JavaScript
function openFileInput() {
    document.getElementById('fileInput').click();
}

function handleFileSelect() {
    const fileInput = document.getElementById('fileInput');
    const profileImage = document.getElementById('profileImage');

    const file = fileInput.files[0];

    if (file) {
        // Perform any additional checks or processing here
        // For now, just update the profile picture with the selected image
        const reader = new FileReader();
        reader.onload = function (e) {
            profileImage.src = e.target.result;
        };
        reader.readAsDataURL(file);

        // Upload the selected image immediately
        uploadImage(file);
    }
}

function uploadImage(file) {
    // You can use AJAX, Fetch API, or any other method to upload the image to your server
    // Example using Fetch API:
    const formData = new FormData();
    formData.append('file', file);
    formData.append('photo', 'selected-avatar'); // Adjust as needed

    fetch('upload', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log('Upload successful:', data);
        // You can update the database or perform other actions as needed
    })
    .catch(error => {
        console.error('Upload failed:', error);
    });
}

</script>