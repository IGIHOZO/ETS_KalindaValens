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

if ($_SERVER["REQUEST_METHOD"] == "GET") {

    if (isset($_GET['supervise'])) {
        $userid_sup = $_GET['supervise'];
        $upd = $con->prepare("UPDATE ets_workers SET ets_workers.CanSupervise=1 WHERE ets_workers.worker_id='$userid_sup'");
        $upd_ok = $upd->execute();
        if($upd_ok){
            echo "<script>window.location='supervisor.php';</script>";
        }
        
    }elseif(isset($_GET['unsupervise'])){
        $userid_sup = $_GET['unsupervise'];
        $upd = $con->prepare("UPDATE ets_workers SET ets_workers.CanSupervise=0 WHERE ets_workers.worker_id='$userid_sup'");
        $upd_ok = $upd->execute();
        
        if($upd_ok){
            echo "<script>window.location='supervisor.php';</script>";
        }
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

        <!-- <a href="supervisor.php" style="float: right;" class="btn btn-danger"><b>Assign New Supervisor</b></a> -->
      </ol>
      <!-- Icon Cards-->
      <form method="post" action="" enctype="multipart/form-data">
        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    <form action="POST">
                        <div class="mb-12 row">
                            <label for="fname" class="col-sm-10 col-form-label font-weight-bold">
                                <input type="text" class="form-control" placeholder="Search Worker names or Unique ID Here" name="work_key" required>
                            </label>
                            <div class="col-sm-2">
                                <button type="submit" name="search_worker" class="btn btn-success">Search</button>
                            </div>
                        </div>
                    </form>

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
          <?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the form is submitted
    if (isset($_POST["search_worker"])) {
        // Retrieve form data
        $key = $_POST["work_key"];
        $search = $con->prepare("SELECT * FROM ets_workers WHERE (ets_workers.worker_fname LIKE '%$key%' OR
         ets_workers.worker_lname LIKE '%$key%' OR ets_workers.worker_unid LIKE '%$key%' OR ets_workers.worker_phone LIKE '%$key%') AND ets_workers.worker_category=3");
         $search->execute();
         ?>
         <table class="table table-bordered" id="tbl_exporttable_to_xls" width="100%" cellspacing="0">
            <thead>
                <tr>
                <th>#</th>
                <th>Namee</th>
                <th>Position</th>
                <th>Phone</th>
                <th>UNIQUE-ID</th>
                <th>Category</th>
                <th>Supervisor</th>
                <th>Image</th>
                <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                         if ($search->rowCount()>=1) {
                            $cntt = 1;
                            while($ft_serach = $search->fetch(PDO::FETCH_ASSOC)){
                                $wrkId = $ft_serach['worker_id'];
                                $CanSupervise = $ft_serach['CanSupervise'];
                                ?>
                                <tr>
                                  <td>  <?=$cntt.". "?>  </td>
                                  <td>  <?=strtoupper($ft_serach['worker_fname']).' '.$ft_serach['worker_lname']?>   </td>
                                  <td>  <?=$MainView->WorkerPositionName($ft_serach['worker_id'])?>   </td>
                                  <td>   <?=$ft_serach['worker_phone']?>  </td>
                                  <td>   <?=$ft_serach['worker_unid']?>  </td>
                                  <td>   <?=$MainView->WorkerCategory($ft_serach['worker_id'])?>  </td>
                                  <td>   <?=$MainView->WorkerSupervisor($ft_serach['supervisor'])?>  </td>
                                  <td>   <img src="<?=$ft_serach['worker_photo']?>" alt="<?=$ft_serach['worker_unid']?>" style="hight: 80px;width:60px">  </td>
                                  <?php 
                                    if ($CanSupervise==1) {
                                        ?>
                                          <td>   <a href="supervisor.php?unsupervise=<?=$wrkId?>" class="btn btn-danger">Remove</a> </td>
                                        <?php
                                    }else{
                                        ?>
                                          <td>   <a href="supervisor.php?supervise=<?=$wrkId?>" class="btn btn-success">Assign</a> </td>
                                        <?php
                                    }
                                  ?>
                                </tr>
                                <?php
                                $cntt++;
                            }
                         }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Phone</th>
                    <th>UNIQUE-ID</th>
                    <th>Category</th>
                    <th>Supervisor</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
                </tfoot>
         </table>
        <?php

    }
}else{
    ?>
    
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
                  <th>Image</th>
                  <!-- <th>Gender</th> -->
                </tr>
              </thead>
              <?php 
              $sel = $con->prepare("SELECT * FROM ets_workers WHERE ets_workers.worker_category=3 AND ets_workers.worker_status=1 AND ets_workers.CanSupervise=1 ORDER BY ets_workers.worker_id DESC");
              $sel->execute();
              if ($sel->rowCount()>=1) {
                $cnt = 1;
                while ($ft_se = $sel->fetch(PDO::FETCH_ASSOC)) {
                  ?>
                  <tr>
                    <td>  <?=$cnt.". "?>  </td>
                    <td>  <?=strtoupper($ft_se['worker_fname']).' '.$ft_se['worker_lname']?>   </td>
                    <td>  <?=$MainView->WorkerPositionName($ft_se['worker_id'])?>   </td>
                    <td>   <?=$ft_se['worker_phone']?>  </td>
                    <td>   <?=$ft_se['worker_unid']?>  </td>
                    <td>   <?=$MainView->WorkerCategory($ft_se['worker_id'])?>  </td>
                    <td>   <?=$MainView->WorkerSupervisor($ft_se['supervisor'])?>  </td>
                    <td>   <img src="<?=$ft_se['worker_photo']?>" alt="<?=$ft_se['worker_unid']?>" style="hight: 80px;width:60px">  </td>

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
                  <th>Image</th>
                  <!-- <th>Gender</th> -->
                </tr>
              </tfoot>
              <tbody>
                <?=$MainView->todays_attendance_detailed();?>
              </tbody>
            </table>

    <?php
}
?>

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
         XLSX.writeFile(wb, fn || ('SupervisorsReport.' + (type || 'xlsx')));
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
