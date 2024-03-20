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
    // Check if the form is submitted
    if (isset($_POST["reg_worker"])) {
        // Retrieve form data
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

        

        // File upload handling
        $ppicture = uploadImage();

        // Perform further processing or validation as needed

        // Example: Validate phone number format
        if (!preg_match("/^[0-9]{10}$/", $phone)) {
            echo "<script>alert('Invalid phone number format.');</script>";
            exit;
        }

        // Example: Validate NID length
        if (strlen($nid) !== 16) {
            echo "<script>alert('NID must be 16 characters long.');</script>";
            exit;
        }

        // Save data to the database
        saveToDatabase($con, $fname, $lname, $phone, $nid, $ppicture, $supervisor, $bank, $banknumber, $dob, $gender, $wrk_position);

        // Output a message or redirect after processing
        // echo "Form submitted successfully!";
    }
}

function uploadImage() {
    $targetDir = "img/workers/"; // Specify your target directory
    $imageFileType = strtolower(pathinfo($_FILES["ppicture"]["name"], PATHINFO_EXTENSION));

    // Encrypt the file name
    $encryptedFileName = hash('sha256', uniqid() . $_FILES["ppicture"]["name"]) . '.' . $imageFileType;
    $targetFileEncrypted = $targetDir . $encryptedFileName;

    // Check if image file is a valid image
    $check = getimagesize($_FILES["ppicture"]["tmp_name"]);
    if ($check === false) {
        echo "<script>alert('File is not a valid image.');</script>";
        exit;
    }

    // Check file size
    if ($_FILES["ppicture"]["size"] > 5000000) {
        echo "<script>alert('Sorry, your file is too large.');</script>";
        exit;
    }

    // Allow certain file formats
    $allowedFormats = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowedFormats)) {
        echo "<script>alert('Sorry, only JPG, JPEG, PNG, and GIF files are allowed.');</script>";
        exit;
    }

    // Move the uploaded file to the target directory with an encrypted filename
    if (move_uploaded_file($_FILES["ppicture"]["tmp_name"], $targetFileEncrypted)) {
        // echo "The file " . htmlspecialchars(basename($encryptedFileName)) . " has been uploaded.";
        return $targetFileEncrypted;
    } else {
        echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
        exit;
    }
}

function generateRandomUniqueId($con) {
    // Generate a random number between 100000 and 999999
    $randomNumber = mt_rand(1, 9999);

    // Create the unique ID by combining prefix and the random number
    $uniqueId = 'ETS-A-' . str_pad($randomNumber, 4, '0', STR_PAD_LEFT);

    // Check if the generated ID already exists in the database
    $checkSql = "SELECT COUNT(*) as count FROM ets_workers WHERE worker_unid = :worker_unid";
    $checkStmt = $con->prepare($checkSql);
    $checkStmt->bindParam(':worker_unid', $uniqueId);
    $checkStmt->execute();
    $row = $checkStmt->fetch(PDO::FETCH_ASSOC);

    // If the ID already exists, generate a new one
    if ($row['count'] > 0) {
        return generateRandomUniqueId($con);
    }

    return $uniqueId;
}

function saveToDatabase($con, $fname, $lname, $phone, $nid, $ppicture, $supervisor, $bank, $banknumber, $dob, $gender, $wrk_position) {
    try {
        // Validate and sanitize input parameters
        $fname = filter_var($fname, FILTER_SANITIZE_STRING);
        $lname = filter_var($lname, FILTER_SANITIZE_STRING);
        $phone = filter_var($phone, FILTER_SANITIZE_STRING);
        $nid = filter_var($nid, FILTER_SANITIZE_STRING);
        $ppicture = filter_var($ppicture, FILTER_SANITIZE_STRING);
        $supervisor = filter_var($supervisor, FILTER_SANITIZE_STRING);
        $bank = filter_var($bank, FILTER_SANITIZE_STRING);
        $banknumber = filter_var($banknumber, FILTER_SANITIZE_STRING);
        $dob = filter_var($dob, FILTER_SANITIZE_STRING);
        $gender = filter_var($gender, FILTER_SANITIZE_STRING);
        $wrk_position = filter_var($wrk_position, FILTER_SANITIZE_STRING);


        // Perform the SQL query to insert data into the database
        $sql = "INSERT INTO ets_workers (worker_fname, worker_lname, worker_phone, nid, worker_photo, supervisor, worker_category, worker_unid, Bank, BankNumber, DoB, Gender, worker_position)
              VALUES (:fname, :lname, :phone, :nid, :ppicture, :supervisor, :worker_category, :worker_unid, :Bank, :BankNumber, :DoB, :Gender, :wrk_position)";
        $worker_category = 3;

        // Generate a random unique ID
        $worker_unid = generateRandomUniqueId($con);

        $stmt = $con->prepare($sql);
        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':lname', $lname);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':nid', $nid);
        $stmt->bindParam(':ppicture', $ppicture);
        $stmt->bindParam(':supervisor', $supervisor);
        $stmt->bindParam(':worker_category', $worker_category);
        $stmt->bindParam(':worker_unid', $worker_unid);
        $stmt->bindParam(':Bank', $bank);
        $stmt->bindParam(':BankNumber', $banknumber);
        $stmt->bindParam(':DoB', $dob);
        $stmt->bindParam(':Gender', $gender);
        $stmt->bindParam(':wrk_position', $wrk_position);

        // Execute the insert query
        $ok_in = $stmt->execute();

        if ($ok_in) {
            // echo "Record added to database successfully. Unique ID: " . $worker_unid;
        } else {
            // Display or log more information about the failure
            echo "<script>alert('Failed to add record to the database. Details: " . implode(", ", $stmt->errorInfo())."')</script>";
        }

    } catch (PDOException $e) {
        echo "<script>alert('Database Error: " . $e->getMessage()."')</script>";
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
        <li class="breadcrumb-item active">Add new Worker</li>

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
                            <input type="text" class="form-control" placeholder="First Name" name="fname" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="lname" class="col-sm-4 col-form-label font-weight-bold">Last Name:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="Last Name" name="lname" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="phone" class="col-sm-4 col-form-label font-weight-bold">Phone:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="078......." name="phone" maxlength="10" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="nid" class="col-sm-4 col-form-label font-weight-bold">NID:</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" placeholder="(16 characters)" name="nid" maxlength="16" required>
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
                            <input type="text" class="form-control" placeholder="Bank Account Number" name="banknumber" required>
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
                            <input type="date" class="form-control" name="dob" required>
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




      <!-- Example DataTables Card-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> Available Workers    <button style="float:right;" class="btn btn-primary"  onclick="ExportToExcel('xlsx')">Download</button></div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="tbl_exporttable_to_xls" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Position</th>
                  <th>Phone</th>
                  <th>UNIQUE-ID</th>
                  <th>Category</th>
                  <th>Supervisor</th>
                  <th>Age</th>
                  <th>Gender</th>
                  <th></th>
                </tr>
              </thead>
              <?php 
              $sel = $con->prepare("SELECT * FROM ets_workers,ets_worker_position WHERE ets_workers.worker_category=3 AND ets_worker_position.worker_position_id=ets_workers.worker_position
               AND ets_workers.worker_status=1 ORDER BY ets_workers.worker_id DESC");
              $sel->execute();
              if ($sel->rowCount()>=1) {
                $cnt = 1;
                while ($ft_se = $sel->fetch(PDO::FETCH_ASSOC)) {
                  $userid = $ft_se['worker_id'];
                  ?>
                  <tr>
                    <td>  <?=$cnt.". "?>  </td>
                    <td>  <?=strtoupper($ft_se['worker_fname']).' '.$ft_se['worker_lname']?>   </td>
                    <td>  <?=$ft_se['worker_position_name']?>   </td>
                    <td>   <?=$ft_se['worker_phone']?>  </td>
                    <td>   <?=$ft_se['worker_unid']?>  </td>
                    <td>   <?=$MainView->WorkerCategory($ft_se['worker_id'])?>  </td>
                    <td>   <?=$MainView->WorkerSupervisor($ft_se['supervisor'])?>  </td>
                    <td>   <?=$MainView->ageFromDate($ft_se['DoB'])?>  </td>
                    <td>   <?=$ft_se['Gender']?>  </td>
                    <td>  
                      <button class="btn btn-link" style="color: red; cursor: pointer;" onclick="return deleteWorker(<?=$userid?>);"> <i class="fa fa-fw fa-trash"></i> </button>
                      <button class="btn btn-link" style="color: blue; cursor: pointer;" onclick="return updateWorker(<?=$userid?>);"> <i class="fa fa-fw fa-edit"></i> </button>
                    </td>
                  </tr>
                  <?php
                  $cnt++;
                }
              }else{
                ?>
                <tr>
                  <td colspan="7">  <center>No data found ...</center>  </td>
                </tr>
                <?php
              }
              ?>
              <tfoot>
              <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Position</th>
                  <th>Phone</th>
                  <th>UNIQUE-ID</th>
                  <th>Category</th>
                  <th>Supervisor</th>
                  <th>Age</th>
                  <th>Gender</th>
                  <th></th>
                </tr>
              </tfoot>
              <tbody>
                <?=$MainView->todays_attendance_detailed();?>
              </tbody>
            </table>
          </div>
        </div>
        <div class="card-footer small text-muted">Updated now</div>
      </div>


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

function updateWorker(userId){
  window.location = "update-worker.php?userId=" + userId;
}


</script>

</html>
