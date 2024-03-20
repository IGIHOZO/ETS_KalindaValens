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
?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["upd_worker"])) {
        $userId = $_GET['userId']; 
        $fname = $_POST["fname"];
        $lname = $_POST["lname"];
        $phone = $_POST["phone"];
        $nid = $_POST["nid"];
        $supervisor = $_POST["supervisor"];
        $bank = $_POST['bank'];
        $banknumber = $_POST['banknumber'];
        $dob = $_POST['dob'];
        $gender = $_POST['gender'];
        $wrk_position = $_POST['wrk_position'];

        $ppicture = uploadImage();


        if (!preg_match("/^[0-9]{10}$/", $phone)) {
            echo "<script>alert('Invalid phone number format.');</script>";
            exit;
        }

        if (strlen($nid) !== 16) {
            echo "<script>alert('NID must be 16 characters long.');</script>";
            exit;
        }


        if ($ppicture !== false) {
            if (updateWorker($con,$userId, $fname, $lname, $phone, $nid, $ppicture, $supervisor, $bank, $banknumber, $dob, $gender, $wrk_position)) {
                echo "<script>windo.location='workers.php'</script>";
            } else {
                echo "<div class='alert alert-danger' role='alert'>Failed to update worker information. Please try again.</div>";
            }
        } else {
            echo "<div class='alert alert-danger' role='alert'>Failed to upload worker image. Please check the file format and size.</div>";
        }
    }
}

function updateWorker($con,$userId, $fname, $lname, $phone, $nid, $ppicture, $supervisor, $bank, $banknumber, $dob, $gender, $wrk_position) {
    try {
        $updateStmt = $con->prepare("UPDATE ets_workers 
                                    SET worker_fname = :fname, 
                                        worker_lname = :lname, 
                                        worker_phone = :phone, 
                                        nid = :nid, 
                                        worker_photo = :ppicture, 
                                        supervisor = :supervisor, 
                                        Bank = :bank, 
                                        BankNumber = :banknumber, 
                                        DoB = :dob, 
                                        Gender = :gender, 
                                        worker_position = :wrk_position 
                                    WHERE worker_id = :userId");
        $updateStmt->bindParam(':fname', $fname);
        $updateStmt->bindParam(':lname', $lname);
        $updateStmt->bindParam(':phone', $phone);
        $updateStmt->bindParam(':nid', $nid);
        $updateStmt->bindParam(':ppicture', $ppicture);
        $updateStmt->bindParam(':supervisor', $supervisor);
        $updateStmt->bindParam(':bank', $bank);
        $updateStmt->bindParam(':banknumber', $banknumber);
        $updateStmt->bindParam(':dob', $dob);
        $updateStmt->bindParam(':gender', $gender);
        $updateStmt->bindParam(':wrk_position', $wrk_position);
        $updateStmt->bindParam(':userId', $userId);
        $updateStmt->execute();

        if ($updateStmt->rowCount() > 0) {
            return true; 
        } else {
            return false; 
        }
    } catch (PDOException $e) {
        return false;
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
        <li class="breadcrumb-item active">Update Worker</li>

        <a href="supervisor.php" style="float: right;" class="btn btn-danger"><b>Assign New Supervisor</b></a>
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
                        <label for="supervisor" class="col-sm-4 col-form-label font-weight-bold">Supervisor:</label>
                        <div class="col-sm-8">
                            <select name="supervisor" class="form-control" required>
                                <option value="">Select Supervisor</option>
                                <?php
                                $sel_super = $con->prepare("SELECT ets_workers.* FROM ets_workers WHERE ets_workers.worker_status=1 AND 
                                ets_workers.CanSupervise=1 AND ets_workers.worker_category=3");
                                $sel_super->execute();
                                if ($sel_super->rowCount() >= 1) {
                                    while ($ft_super = $sel_super->fetch(PDO::FETCH_ASSOC)) {
                                        $usr_id = $ft_super['worker_id'];
                                        echo "<option value='" . $usr_id . "'>" . $ft_super['worker_fname'] . " " . $ft_super['worker_lname'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                  <div class="mb-3 row">
                          <label for="bankaccount" class="col-sm-4 col-form-label font-weight-bold">Position:</label>
                          <div class="col-sm-8">
                              <select name="wrk_position" class="form-control" required>
                                  <option value="">Select Position</option>
                                  <?php
                                  $sel_super = $con->prepare("SELECT * FROM ets_worker_position WHERE ets_worker_position.worker_position_status=1 AND ets_worker_position.worker_position_name<>'Supervisor'");
                                  $sel_super->execute();
                                  if ($sel_super->rowCount() >= 1) {
                                      while ($ft_super = $sel_super->fetch(PDO::FETCH_ASSOC)) {
                                          $usr_id = $ft_super['worker_position_id'];
                                          echo "<option value='" . $usr_id . "'>" . $ft_super['worker_position_name'] . "</option>";
                                      }
                                  }
                                  ?>
                              </select>
                          </div>
                      </div>
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
                            <button type="submit" name="upd_worker" class="btn btn-success">Submit</button>
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
         XLSX.writeFile(wb, fn || ('WorkersReport.' + (type || 'xlsx')));
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


</html>
