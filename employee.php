<?php
require('main/view.php'); 
require('main/drive/config.php'); 

if (@$_SESSION['utb_att_position']!='Employee') {
  ?>
<script type="text/javascript">
        window.location="login.php";
</script>
  <?php
}
$MainView = new MainView();
$MyFunctions = new MyFunctions();
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
  <div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Home</a>
        </li>
        <li class="breadcrumb-item active"> 
          <label><?= $MyFunctions->greeting_msg()." ".$MyFunctions->first_name($_SESSION['utb_att_names'])?></label>
        </li>
        <h1 id="txt" style="font-weight: bolder;float: right;color: red;text-align: right;"> <span id="txtt">**</span> <small style="font-size: 26px">-  Annual leave days</small></h1>
      </ol>
      <!-- Icon Cards-->

      <!-- Area Chart Example-->
      <div class="card mb-3">
        <div class="card-header">
          <h3>Avalailable Leaves</h3>
           <button class="btn btn-success" data-toggle="modal" data-target="#newRequestModal" style="float: right;font-weight: bold;">New Request</button>
           <a href="plan" class="btn btn-primary" style="float: right;margin-right: 4%;font-weight: bold;">My Leaves Plan</a>

        <div class="card-body">
          <br>
          <table class="table  text-center" style="font-size:10px">
            <thead>
              <tr>
                <th>#</th>
                <th>Time of Request</th>
                <th>Leave-Type</th>
                <th>From</th>
                <th>To</th>
                <th>Days</th>
                <th>AllowedDays</th>
                <th>Remaining</th>
                <th>Supervisor</th>
                <th>HR</th>
                <th>DVCPAF</th>
                <th>DVCA</th>
                <th>VC</th>
                <th colspan="3">Message</th>
              </tr>
            </thead>
            <tbody  id="respp">
              <tr>
                <td colspan="14">No Leave requested yet </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="card-footer small text-muted">Updated Now</div>
      </div>


    </div>

    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fa fa-angle-up"></i>
    </a>
    <!-- New Request Modal-->
    <div class="modal fade" id="newRequestModal" tabindex="-1" role="dialog" aria-labelledby="newRequestModalLabel" aria-hidden="false" sty>
      <div class="modal-dialog" style="min-width: 80%" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" style="margin:0 auto" id="exampleModalLabel">Request for a Leave</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="false">×</span>
            </button>
          </div>
          <input type="hidden" name="" id="leftdys" value="<?=$MainView->MyMaxmumAllowedLeaves()?>">
          <!-- <div class="modal-body"> -->
          <!-- <form action = "employee" method = "POST" name="sentMessage" id="contactForm" novalidate="novalidate"> -->

            <div class="modal-body">
              <div class="row form-group">
                <div class="col-md-6 mb-3 mb-md-0">
                  <p class="help-block text-danger" id="ress"></p>
                  <label style="font-weight: bold;" for="dateFrom">From:</label>
                  <input type="date" id="dateFrom" class="form-control">
                </div>
                <div class="col-md-6">
                  <label style="font-weight: bold;" for="dateTo">To:</label>
                  <p class="help-block text-danger"></p>
                  <input type="date" id="dateTo" class="form-control">
                </div>
              </div>
              <div class="row form-group">
                <div class="col-md-6 mb-3 mb-md-0">
                  <p class="help-block text-danger" id="ress"></p>
                  <label style="font-weight: bold;" for="dateFrom">Leave Type:</label>
                  <select class="form-control" id="leaveType">
                  </select>
                </div>
                <div class="col-md-6">
                  <label style="font-weight: bold;" for="dateTo">Supervisor:</label>
                  <p class="help-block text-danger"></p>
                  <select class="form-control" id="supervisor">
                  </select>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button class="btn btn-primary" type="button" onclick="return saveLeaveRequest();" id="save" name="save">Save changes</button>
            </div>
          <!-- </form> -->
        </div>
      </div>
    </div>

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
<script>
function startTime() {
  const today = new Date();
  let h = today.getHours();
  let m = today.getMinutes();
  let s = today.getSeconds();
  m = checkTime(m);
  s = checkTime(s);
  document.getElementById('txtt').innerHTML = document.getElementById('leftdys').value;
  setTimeout(startTime, 1000);
}

function checkTime(i) {
  if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
  return i;
}
startTime();


  $(document).ready(function(){

      var MyAllLeaves = true;
      $.ajax({url:"main/view.php",
        type:"POST",data:{MyAllLeaves:MyAllLeaves},cache:false,success:function(res){  
          var res = JSON.parse(res);
          // console.log(res.found);
          if (res.found===1) {
              $("#respp").html("");
            for (const key in res.res) {
              // console.log(key);
              var i = key;
              $("#respp").append("<tr>  <td> "+ ++i+" </td>  <td>"+res.res[key].LeaveDate+"</td>  <td> "+res.res[key].LeaveName+" </td>  <td>"+res.res[key].LeaveFrom+"</td>  <td>"+res.res[key].LeaveTo+"</td>  <td>"+res.res[key].LeaveDays+"</td>  <td>"+res.res[key].allowed+"</td>  <td>"+res.res[key].LeaveRemainig+"</td>  <td>"+res.res[key].StatusSupervisor+"</td> <td>"+res.res[key].StatusHR+"</td> <td>"+res.res[key].StatusDVCPAF+"</td> <td>"+res.res[key].StatusDVCA+"</td> <td>"+res.res[key].StatusVC+"</td> <td colspan=3><b>"+res.res[key].RejectionReason+"</b></td>  </tr>");
            }
          }else{
            $("#respp").html("<tr><td colspan='13'><center>No Leave requested yet...</center> </td>  </tr>");
          }
          }
      });

      var AllLeaveTypes = true;
      $.ajax({url:"main/view.php",
        type:"POST",data:{AllLeaveTypes:AllLeaveTypes},cache:false,success:function(res){  
          var res = JSON.parse(res);
          if (res.found===1) {
              // $("#respp").html("");
            for (const key in res.res) {
              $("#leaveType").append(" <option value='"+res.res[key].TypeId+"'>"+res.res[key].LeaveName+"</option>");
            }
          }else{
            $("#leaveType").html("<option>No Leave Type </option>");
          }
          }
      });

      var AllSupervisors = true;
      $.ajax({url:"main/view.php",
        type:"POST",data:{AllSupervisors:AllSupervisors},cache:false,success:function(res){  
          var res = JSON.parse(res);
          if (res.found===1) {
              // $("#supervisor").html("");
            for (const key in res.res) {
              $("#supervisor").append(" <option value='"+res.res[key].UserId+"'>"+res.res[key].Names+"</option>");
            }
          }else{
            $("#supervisor").html("<option>No Supervisor </option>");
          }
          }
      });
$("#sidenavToggler").click(); //===================== Minimizing Menu Section

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
