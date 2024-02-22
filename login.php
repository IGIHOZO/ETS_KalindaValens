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
  <!-- Custom styles for this template-->
  <style>
    body {
      background-color: #f8f9fa;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 94vh; /* Reduced height to avoid scrolling */
      margin: 0;
    }

    .container {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh; /* Adjusted height to 100vh */
      }

    .card-login {
      max-width: 400px;
      width: 100%;
      padding: 30px;
      border: none;
      border-radius: 10px;
      background-color: #fff;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    .logo-container {
      text-align: center;
      margin-bottom: 30px;
    }

    .logo-container img {
      max-width: 100%;
      max-height: 150px; /* Adjusted max-height */
      height: auto;
    }

    .brand-logo {
      text-align: center;
      margin-left: 40px; /* Adjusted margin */
    }

    .brand-logo img {
      max-width: 100%;
      height: auto;
      border-radius: 40%;
    }

    .brand-text {
  font-size: 20px;
  font-weight: bold;
  color: #007bff; /* ETS Blue color */
  margin-top: 15px;
  text-align: center;
  line-height: 1.5;
  text-transform: uppercase; /* Uppercase text */
}



    .card-header {
      font-size: 24px;
      font-weight: bold;
      text-align: center;
      margin-bottom: 30px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-check-label {
      font-weight: normal;
    }

    #login {
      font-size: 18px;
      font-weight: bold;
      background-color: #007bff;
      color: #fff;
      border: none;
    }

    #login:hover {
      background-color: #0056b3;
    }

    .text-center a {
      font-size: 14px;
    }

    .loading-text {
      color: #333;
      font-weight: bold;
    }
  </style>
</head>

<body>
  <div class="container">
      <div class="card card-login">
      <div class="logo-container">
        <img src="img/logo.jpeg" alt="Company Logo">
      </div>
      <div class="card-header">Login</div>
      <div id="respp"></div>
      <div class="card-body">
        <form>
          <div class="form-group">
            <label for="exampleInputEmail1">Username</label>
            <input class="form-control" id="exampleInputEmail1" type="email" aria-describedby="emailHelp" placeholder="Enter email">
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input class="form-control" id="exampleInputPassword1" type="password" placeholder="Password">
          </div>
          <div class="form-group">
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="checkbox"> Remember Password
              </label>
            </div>
          </div>
          <button class="btn btn-primary btn-block" type="button" id="login">Login</button>
        </form>
        <div class="text-center mt-3">
          <a class="d-block small" href="#">Forgot Password?</a>
        </div>
      </div>
    </div>

  </div>
  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/popper/popper.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/main.js"></script>
  <script>
    // Get the input field
    var input = document.getElementById("exampleInputPassword1");
    var input2 = document.getElementById("exampleInputEmail1");

    // Execute a function when the user presses a key on the keyboard
    input.addEventListener("keypress", function (event) {
      // If the user presses the "Enter" key on the keyboard
      if (event.key === "Enter") {
        // Cancel the default action, if needed
        event.preventDefault();
        // Trigger the button element with a click
        document.getElementById("login").click();
      }
    });
    input2.addEventListener("keypress", function (event) {
      // If the user presses the "Enter" key on the keyboard
      if (event.key === "Enter") {
        // Cancel the default action, if needed
        event.preventDefault();
        // Trigger the button element with a click
        document.getElementById("login").click();
      }
    });
  </script>
</body>

</html>
