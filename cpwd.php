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

<div class="container" style="background-color:aliceblue !important">
  <div class="card card-login mx-auto mt-5">
    <div class="card-header"><h4>Change Password</h4></div>
    <div class="card-body">
      <div class="text-center mt-4 mb-5" style="margin-top:-20px">
        <p style="font-weight: bolder;font-size:small;margin-top:-20px">Enter current password and set new password (twice). New password must be at least 8 characters long and contain at least one number, letter, and special character.</p>
      </div>
      <form id="changePasswordForm">
        <div class="form-group">
          <input class="form-control" id="currentPassword" type="password" placeholder="Current Password">
        </div>
        <hr>
        <div class="form-group">
          <input class="form-control" id="newPassword" type="password" placeholder="New Password">
        </div>
        <div class="form-group">
          <input class="form-control" id="confirmPassword" type="password" placeholder="Confirm New Password">
        </div>
        <hr>
        <button type="button" class="btn btn-primary btn-block" id="changePasswordBtn">Change Password</button>
      </form>
      <div class="alert alert-danger" id="passwordAlert" style="display: none; margin-top: 10px;">
        New password must be at least 8 characters long and contain at least one number, letter, and special character.
      </div>
      <div class="alert alert-danger" id="matchAlert" style="display: none; margin-top: 10px;">
        New password and confirm password do not match.
      </div>
      <div class="alert alert-success" id="successAlert" style="display: none; margin-top: 10px;">
        Password changed successfully!
      </div>
      <div class="alert alert-danger" id="errorAlert" style="display: none; margin-top: 10px;">
        Error occurred. Please try again later.
      </div>
    </div>
  </div>
</div>

<script>
  document.getElementById('changePasswordBtn').addEventListener('click', function(event) {
    event.preventDefault();

    var currentPassword = document.getElementById('currentPassword').value;
    var newPassword = document.getElementById('newPassword').value;
    var confirmPassword = document.getElementById('confirmPassword').value;

    document.querySelectorAll('.alert').forEach(function(alert) {
      alert.style.display = 'none';
    });

    if (currentPassword.trim() === '') {
      document.getElementById('currentPasswordError').style.display = 'block';
      return;
    }

    if (newPassword.length < 8 || !(/[0-9]/.test(newPassword)) || !(/[a-zA-Z]/.test(newPassword)) || !(/[!@#$%^&*(),.?":{}|<>]/.test(newPassword))) {
      document.getElementById('passwordAlert').style.display = 'block';
      return;
    }

    if (newPassword !== confirmPassword) {
      document.getElementById('matchAlert').style.display = 'block';
      return;
    }

    var formData = new FormData();
    formData.append('currentPassword', currentPassword);
    formData.append('newPassword', newPassword);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'main/main.php', true);
    xhr.onload = function() {
      if (xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        if (response.success) {
          document.getElementById('successAlert').style.display = 'block';
          document.getElementById('changePasswordForm').reset();
          setTimeout(function() {
            window.location.href = 'logout.php';
          }, 2000);
        } else {
          document.getElementById('errorAlert').innerText = response.message;
          document.getElementById('errorAlert').style.display = 'block';
        }
      } else {
        document.getElementById('errorAlert').innerText = 'An error occurred. Please try again later.';
        document.getElementById('errorAlert').style.display = 'block';
      }
    };
    xhr.onerror = function() {
      document.getElementById('errorAlert').innerText = 'An error occurred. Please try again later.';
      document.getElementById('errorAlert').style.display = 'block';
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