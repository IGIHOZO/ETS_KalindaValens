<?php
    @session_start();
    if (!isset($_SESSION['worker_id'])) {
        echo "<script>window.location='login.php'</script>";
    }else{
@require('main/view.php'); 
$MainView = new MainView();


if ($MainView->StaffPositionName()!='Receptionist') {
  ?>
<script type="text/javascript">
      //  window.location="login.php";
</script>

  <?php
  echo "Session: ".$MainView->StaffPositionName();
}else{
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" type="image/x-icon" href="img/logo.ico">
  <title>ETS - Attendance Portal</title>
  <!-- Bootstrap core CSS-->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom fonts for this template-->
  <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <!-- Page level plugin CSS-->
  <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">
  <style type="text/css">


#txt {
  font-family:arial black;
  font-size:70px;
  background-image: 
    linear-gradient(to right, red,orange,yellow,green,blue,indigo,violet, red); 
  -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;  
  animation: rainbow-animation 500s linear infinite;
}

@keyframes rainbow-animation {
    to {
        background-position: 4500vh;
    }
}
  </style>
</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <!-- Navigation-->
<?php
require("menus.php");
?>

<div class="container" style="background-color: aliceblue !important">
  <div class="card card-login mx-auto mt-5">
    <div class="card-header">
      <h4>Update Profile</h4>
    </div>
    <div class="card-body">
      <div class="text-center mt-4 mb-5" style="margin-top: -20px">
        <p style="font-weight: bolder; font-size: small; margin-top: -20px">Enter your information to update your profile.</p>
      </div>
      <form id="updateProfileForm">
        <div class="form-group">
          <input class="form-control" id="updated_firstName" type="text" placeholder="First Name" value="<?=$_SESSION['worker_fname']?>">
        </div>
        <div class="form-group">
          <input class="form-control" id="updated_lastName" type="text" placeholder="Last Name" value="<?=$_SESSION['worker_lname']?>">
        </div>
        <div class="form-group">
          <input class="form-control" id="updated_phoneNumber" type="tel" placeholder="Phone Number" value="<?=$_SESSION['worker_phone']?>">
        </div>
        <hr>
        <button type="button" class="btn btn-primary btn-block" name="updateProfile" id="updateProfileBtn">Update Profile</button>
      </form>
      <!-- Bootstrap alert messages -->
      <div class="alert alert-success mt-3" id="successAlert" style="display: none;" role="alert">
        Profile updated successfully!
      </div>
      <div class="alert alert-danger mt-3" id="errorAlert" style="display: none;" role="alert">
        Failed to update profile. Please try again later.
      </div>
    </div>
  </div>
</div>

<script>
  document.getElementById('updateProfileBtn').addEventListener('click', function(event) {
    // Prevent the default form submission behavior
    event.preventDefault();

    // Get the values of input fields
    var updated_firstName = document.getElementById('updated_firstName').value;
    var updated_lastName = document.getElementById('updated_lastName').value;
    var updated_phoneNumber = document.getElementById('updated_phoneNumber').value;

    // AJAX call to send form data
    var formData = new FormData();
    formData.append('updated_firstName', updated_firstName);
    formData.append('updated_lastName', updated_lastName);
    formData.append('updated_phoneNumber', updated_phoneNumber);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'main/main.php', true);
    xhr.onload = function() {
      if (xhr.status === 200) {
        // Success
        var response = JSON.parse(xhr.responseText);
        if (response.success) {
          // Show success message
          document.getElementById('successAlert').style.display = 'block';
          document.getElementById('successAlert').innerHTML = response.message;
          // Optionally, you can reset the form here
          document.getElementById('updateProfileForm').reset();
          setTimeout(function() {
            window.location.reload() = true;
          }, 2000);
        } else {
          // Show error message
          document.getElementById('errorAlert').style.display = 'block';
          document.getElementById('errorAlert').innerHTML = response.message;
        }
      } else {
        // Error
        document.getElementById('errorAlert').style.display = 'block';
        document.getElementById('errorAlert').innerHTML = 'An error occurred. Please try again later.';
      }
    };
    xhr.onerror = function() {
      // Error
      document.getElementById('errorAlert').style.display = 'block';
      document.getElementById('errorAlert').innerHTML = 'An error occurred. Please try again later.';
    };
    xhr.send(formData);
  });
</script>










  <footer>
  <div class="text-center mt-3" style="color:aliceblue;">
          <p style="margin-top:-10px">
            <div class="copyright">
              © Copyright <strong>ETS Kalinda</strong>. All Rights Reserved
            </div>
            <div class="credits">
              Designed by <a href="https://seveeen.rw/" target="_blank">Seveeen</a>
            </div>
          </p>
        </div>
  </footer>
</body>

</html>
<?php 

}
    }
?>