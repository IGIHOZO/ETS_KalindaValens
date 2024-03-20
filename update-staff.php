<?php
session_start();
@require('main/view.php'); 
$MainView = new MainView();


if ($MainView->StaffPositionName()!='Receptionist') {
  ?>
<script type="text/javascript">
       window.location="login.php";
</script>

  <?php
  echo "Session: ".$MainView->StaffPositionName();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the form is submitted
    if (isset($_POST["reg_worker"])) {
        if (isset($_GET['userId'])) {
            $userId = $_GET['userId'];
            $fname = $_POST["fname"];
            $lname = $_POST["lname"];
            $phone = $_POST["phone"];
            $nid = $_POST["nid"];
            $staffposition = $_POST["staffposition"];
            $bank = $_POST["bank"];
            $account_number = $_POST["banknumber"];
            $dob = $_POST["dob"];
            $gender = $_POST["gender"];
            $ppicture = uploadImage();

            if ($ppicture === false) {
                echo "<script>alert('Failed to upload profile picture.');</script>";
                exit;
            }

            if (!preg_match("/^[0-9]{10}$/", $phone)) {
                echo "<script>alert('Invalid phone number format.');</script>";
                exit;
            }

            if (strlen($nid) !== 16) {
                echo "<script>alert('NID must be 16 characters long.');</script>";
                exit;
            }

            updateDatabase($con, $userId, $fname, $lname, $phone, $nid, $ppicture, $staffposition, $bank, $account_number, $dob, $gender);
        } else {
            echo "<script>alert('User ID not provided.');</script>";
        }
    }
}

function updateDatabase($con, $userId, $fname, $lname, $phone, $nid, $ppicture, $staffposition, $bank, $account_number, $dob, $gender) {
    try {
        // Validate and sanitize input parameters
        $fname = filter_var($fname, FILTER_SANITIZE_STRING);
        $lname = filter_var($lname, FILTER_SANITIZE_STRING);
        $phone = filter_var($phone, FILTER_SANITIZE_STRING);
        $nid = filter_var($nid, FILTER_SANITIZE_STRING);
        $ppicture = filter_var($ppicture, FILTER_SANITIZE_STRING);
        $bank = filter_var($bank, FILTER_SANITIZE_STRING);
        $account_number = filter_var($account_number, FILTER_SANITIZE_STRING);
        $dob = filter_var($dob, FILTER_SANITIZE_STRING);
        $gender = filter_var($gender, FILTER_SANITIZE_STRING);

        // Perform the SQL query to update data in the database
        $sql = "UPDATE ets_workers 
                SET worker_fname = :fname, 
                    worker_lname = :lname, 
                    worker_phone = :phone, 
                    nid = :nid, 
                    worker_photo = :ppicture, 
                    worker_position = :staff_position, 
                    Bank = :bank, 
                    BankNumber = :account_number, 
                    DoB = :dob, 
                    Gender = :gender 
                WHERE userId = :userId";

        $stmt = $con->prepare($sql);
        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':lname', $lname);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':nid', $nid);
        $stmt->bindParam(':ppicture', $ppicture);
        $stmt->bindParam(':staff_position', $staffposition);
        $stmt->bindParam(':bank', $bank);
        $stmt->bindParam(':account_number', $account_number);
        $stmt->bindParam(':dob', $dob);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':userId', $userId);

        // Execute the update query
        $ok_update = $stmt->execute();

        if ($ok_update) {
            echo "<script>window.location='staff.php'</script>";
        } else {
            echo "<script>alert('Failed to update record in the database.');</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Database Error: " . $e->getMessage() . "');</script>";
    }
}

function uploadImage() {
    $targetDir = "img/workers/";

    $imageFileType = strtolower(pathinfo($_FILES["ppicture"]["name"], PATHINFO_EXTENSION));

    $encryptedFileName = hash('sha256', uniqid() . $_FILES["ppicture"]["name"]) . '.' . $imageFileType;
    $targetFileEncrypted = $targetDir . $encryptedFileName;

    $check = getimagesize($_FILES["ppicture"]["tmp_name"]);
    if ($check === false) {
        return false; 
    }

    if ($_FILES["ppicture"]["size"] > 5000000) {
        return false; 
    }

    $allowedFormats = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowedFormats)) {
        return false; 
    }

    if (move_uploaded_file($_FILES["ppicture"]["tmp_name"], $targetFileEncrypted)) {
        return $targetFileEncrypted; 
    } else {
        return false; 
    }
}



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
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
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
  <div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Home</a>
        </li>
        <li class="breadcrumb-item active">Update Staff</li>
        <!-- </li><h1 id="txt" style="font-weight: bolder;float: right;color: red;text-align: right;">Time Here ...</h1> -->

      </ol>
      <!-- Icon Cards-->
      <form method="post" action="" enctype="multipart/form-data">
        <div class="container">
            <div class="row">
            <div class="col-md-4">
                    <div class="mb-3 row">
                        <label for="fname" class="col-sm-4 col-form-label font-weight-bold">First Name:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="First Name" name="fname" value="<?=$MainView->getFName($_GET['userId'])?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="lname" class="col-sm-4 col-form-label font-weight-bold">Last Name:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="Last Name" name="lname" value="<?=$MainView->getLName($_GET['userId'])?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="phone" class="col-sm-4 col-form-label font-weight-bold">Phone:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="078......." name="phone" maxlength="10" value="<?=$MainView->getPhone($_GET['userId'])?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="nid" class="col-sm-4 col-form-label font-weight-bold">NID:</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" placeholder="(16 characters)" name="nid" maxlength="16"  value="<?=$MainView->getNID($_GET['userId'])?>" required>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3 row">
                        <label for="ppicture" class="col-sm-4 col-form-label font-weight-bold">Picture:</label>
                        <div class="col-sm-8">
                            <input type="file" class="form-control" name="ppicture" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="bankaccount" class="col-sm-4 col-form-label font-weight-bold">Bank:</label>
                        <div class="col-sm-8">
                            <select name="bank" class="form-control" required>
                                <option value="">Select Bank Account</option>
                                <?php
                                $sel_super = $con->prepare("SELECT * FROM ets_banks WHERE ets_banks.BankStatus=1");
                                $sel_super->execute();
                                if ($sel_super->rowCount() >= 1) {
                                    while ($ft_super = $sel_super->fetch(PDO::FETCH_ASSOC)) {
                                        $usr_id = $ft_super['BankID'];
                                        echo "<option value='" . $usr_id . "'>" . $ft_super['BankName'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="banknumber" class="col-sm-4 col-form-label font-weight-bold">Account:</label>
                        <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="Bank Account Number" name="banknumber" value="<?=$MainView->getBankNumber($_GET['userId'])?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="staffposition" class="col-sm-4 col-form-label font-weight-bold">Position:</label>
                        <div class="col-sm-8">
                            <select name="staffposition" class="form-control" required>
                                <option value="">Select Position</option>
                                <?php
                                $sel_super = $con->prepare("SELECT * FROM ets_staff_position WHERE ets_staff_position.PositionStatus=1 ORDER BY ets_staff_position.PositionName ASC");
                                $sel_super->execute();
                                if ($sel_super->rowCount() >= 1) {
                                    while ($ft_super = $sel_super->fetch(PDO::FETCH_ASSOC)) {
                                        $usr_id = $ft_super['PositionID'];
                                        echo "<option value='" . $usr_id . "'>" . $ft_super['PositionName'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                </div>

                <div class="col-md-4">
                    <div class="mb-3 row">
                        <label for="dob" class="col-sm-4 col-form-label font-weight-bold">Birth:</label>
                        <div class="col-sm-8">
                        <input type="date" class="form-control" name="dob" value="<?= date('Y-m-d', strtotime($MainView->getDoB($_GET['userId']))) ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="gender" class="col-sm-4 col-form-label font-weight-bold">Gender:</label>
                        <div class="col-sm-8">
                            <select name="gender" id="gender" class="form-control" required>
                                <option>Male</option>
                                <option>Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-sm-8 offset-sm-4">
                            <button type="submit" name="reg_worker" class="btn btn-success">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </form>



    </div>

    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fa fa-angle-up"></i>
    </a>
    <!-- Logout Modal-->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="logout">Logout</a>
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
    <!-- Page level plugin JavaScript-->
    <script src="vendor/chart.js/Chart.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin.min.js"></script>
    <!-- Custom scripts for this page-->
    <script src="js/sb-admin-datatables.min.js"></script>
    <script src="js/sb-admin-charts.min.js"></script>
    <script src="js/main.js"></script>
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>

<script>
function startTime() {
  const today = new Date();
  let h = today.getHours();
  let m = today.getMinutes();
  let s = today.getSeconds();
  m = checkTime(m);
  s = checkTime(s);
  document.getElementById('txt').innerHTML =  h + ":" + m + ":" + s;
  setTimeout(startTime, 1000);
}

function checkTime(i) {
  if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
  return i;
}
startTime();

function ExportToExcel(type, fn, dl) {
       var elt = document.getElementById('tbl_exporttable_to_xls');
       var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
       return dl ?
         XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
         XLSX.writeFile(wb, fn || ('DailyAttendance.' + (type || 'xlsx')));
    }

    document.addEventListener('keydown', function (e) {
    // Check if the key combination is Ctrl+R or Cmd+R
    if ((e.ctrlKey || e.metaKey) && e.keyCode == 82) {
        e.preventDefault(); // Prevent the default reload behavior
        // You can add additional logic here based on your requirements
        console.log('Reload is restricted');
    }
});

</script>
  </div>
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
<script>
function deleteWorker(userId) {
    if (confirm("Are you sure you want to delete this worker?")) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'main/main.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    window.location.reload();
                } else {
                    alert("Failed to delete worker. Please try again later.");
                }
            }
        };
        xhr.send("userId=" + userId + "&deleteWorker=true");
    }
    return false;
}


function updateStaff(userId){
  window.location = "update-staff.php?userId=" + userId;
}
</script>
</html>
