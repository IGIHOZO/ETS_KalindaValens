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
    .overlay{
      position: fixed;
      width: 100vw;
      height: 100vh;
      background: rgb(0, 0, 0,.9);
      z-index: 10000;
      top: 0;
      display: none;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }
    .overlay span{
      color: #fff;
      font-size: 20px;
    }
    .lds-roller {
  display: inline-block;
  position: relative;
  width: 80px;
  height: 80px;
}
.lds-roller div {
  animation: lds-roller 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
  transform-origin: 40px 40px;
}
.lds-roller div:after {
  content: " ";
  display: block;
  position: absolute;
  width: 7px;
  height: 7px;
  border-radius: 50%;
  background: #fff;
  margin: -4px 0 0 -4px;
}
.lds-roller div:nth-child(1) {
  animation-delay: -0.036s;
}
.lds-roller div:nth-child(1):after {
  top: 63px;
  left: 63px;
}
.lds-roller div:nth-child(2) {
  animation-delay: -0.072s;
}
.lds-roller div:nth-child(2):after {
  top: 68px;
  left: 56px;
}
.lds-roller div:nth-child(3) {
  animation-delay: -0.108s;
}
.lds-roller div:nth-child(3):after {
  top: 71px;
  left: 48px;
}
.lds-roller div:nth-child(4) {
  animation-delay: -0.144s;
}
.lds-roller div:nth-child(4):after {
  top: 72px;
  left: 40px;
}
.lds-roller div:nth-child(5) {
  animation-delay: -0.18s;
}
.lds-roller div:nth-child(5):after {
  top: 71px;
  left: 32px;
}
.lds-roller div:nth-child(6) {
  animation-delay: -0.216s;
}
.lds-roller div:nth-child(6):after {
  top: 68px;
  left: 24px;
}
.lds-roller div:nth-child(7) {
  animation-delay: -0.252s;
}
.lds-roller div:nth-child(7):after {
  top: 63px;
  left: 17px;
}
.lds-roller div:nth-child(8) {
  animation-delay: -0.288s;
}
.lds-roller div:nth-child(8):after {
  top: 56px;
  left: 12px;
}
@keyframes lds-roller {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}


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
<div class="overlay"><div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
<span>Loading, please wait ...</span>
</div>
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
        <li class="breadcrumb-item active">Overall Payroll</li>
        </li><h1 id="txt" style="font-weight: bolder;float: right;color: red;text-align: right;">Time Here ...</h1>

      </ol>
      <!-- Icon Cards-->
      <div class="row">
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-primary o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-comments"></i>
              </div>
              <div class="mr-5"><?=$MainView->todays_attendance()?> Attended Today</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="#">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-warning o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-list"></i>
              </div>
              <div class="mr-5"><?=$MainView->early_attendance()?> Todays's Early Risers!</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="#">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-success o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-shopping-cart"></i>
              </div>
              <div class="mr-5"><?=$MainView->todays_right_arrival()?> Todays Right Arrivals!</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="#">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-danger o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-support"></i>
              </div>
              <div class="mr-5"><?=$MainView->todays_lates()?> Lates Today !</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="#">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
      </div>
      <!-- Example DataTables Card-->
      <div class="card mb-3">
        <div class="card-header">
          
          <div class="row">
            <div class="col-2">
              <i class="fa fa-table"></i> <b>Payroll Report</b>
            </div>
            <div class="col-2">
              <label style="font-weight: bolder;" for="#ddate"> <u>Supervisor: </u> </label>
              <select class="form-control" id="supervisor">
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
            <div class="col-2">
              <label style="font-weight: bolder;" for="#ddate"> <u>From</u>&nbsp;&nbsp;&nbsp;&nbsp;(Date): </label>
              <input type="date" id="ddate" class="form-control" name="">
            </div>
            <div class="col-2">
              <label style="font-weight: bolder;" for="#ddate_to"> <u>To</u>&nbsp;&nbsp;&nbsp;&nbsp;(Date): </label>
              <input type="date" id="ddate_to" class="form-control" name="">
            </div>
            <div class="col-2" style="display:none"> 
              <label style="font-weight: bolder;" for="#att_categry"> Emplyee Category: </label>
              <select class="form-control" id="att_categry">
                <option value="">Select Category</option>
                <option value="0" selected>Right Arrivers</option>
                <option value="1">Early Risers</option>
              </select>
            </div>
            <div class="col-2">
              <button id="srch_payroll" style="float:right;margin-top: 20px;" class="btn btn-success">Search</button>
            </div>
            <div class="col-2">
              <button style="float:right;margin-top: 20px;" class="btn btn-primary"  onclick="ExportToExcel('xlsx')">Download</button>
            </div>

          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive"> 
            <table class="table table-bordered" id="tbl_exporttable_to_xls" width="100%" cellspacing="0" style="font-size:12px">
                  <thead>
                    <tr id="trSupervisorName"></tr>
                    <tr>
                      <th>#</th>
                      <th>AMAZINA</th>
                      <th>ACOUNT</th>
                      <th>KUWA1</th>
                      <th>KUWA2</th>
                      <th>KUWA3</th>
                      <th>KUWA4</th>
                      <th>KUWA5</th>
                      <th>KUWA6</th>
                      <th>JOB</th>
                      <th>YOSE HAMWE</th>
                    </tr>
                  </thead>
                  <tbody id="resspp">
                    
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
            <a class="btn btn-primary" href="logout.php">Logout</a>
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
    var wb = XLSX.utils.book_new();
    var ws = XLSX.utils.table_to_sheet(elt);

    // Example: Set background color for cell A1 to red
    ws.A1.s = { fill: { bgColor: { indexed: 64 }, fgColor: { rgb: "FF0000" } } };

    XLSX.utils.book_append_sheet(wb, ws, "sheet1");

    return dl ?
        XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
        XLSX.writeFile(wb, fn || ('PayrollReport.' + (type || 'xlsx')));
}



$("#sidenavToggler").click(); //===================== Minimizing Menu Section




//================== Search Payroll
$("#srch_payroll").click(function(){
  var srchSupervisor = document.getElementById('supervisor').value;
  var srchDate = document.getElementById('ddate').value;
  var srchDateTo = document.getElementById('ddate_to').value;
  var srchCategory = document.getElementById('att_categry').value;
  if (srchSupervisor=='' || srchCategory=='' || srchCategory == null || srchDate=='' == null || srchDateTo == null) {
    alert("Please fill all forms ...");
  }else{
    var searchPayroll = true;
    $.ajax({url:"main/main.php",
    type:"GET",data:{
      searchPayroll:searchPayroll,srchDate:srchDate,srchCategory:srchCategory,srchDateTo:srchDateTo,srchSupervisor:srchSupervisor
    },cache:false,
    beforeSend(){
      $('.overlay').css('display','flex');
    },
    success:function(res){
      $('.overlay').css('display','none');

      if (res=='null') {
              alert("Please fill all forms ...");
      }else{
        $("#resspp").html(res);
        // $("#respp").css("background-color","red");
      }
    }
    });
    var UserNames_tr_sponsor = true;
    $.ajax({url:"main/view.php",
    type:"GET",data:{
      UserNames_tr_sponsor:UserNames_tr_sponsor,userId:srchSupervisor
    },cache:false,
    beforeSend(){
      $('.overlay').css('display','flex');
    },
    success:function(res){
      $('.overlay').css('display','none');

        $("#trSupervisorName").html(res);

    }
    });
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
