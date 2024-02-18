<?php
require('main/view.php'); 
require('main/drive/config.php'); 
if (@$_SESSION['utb_att_position']!='DVCA') {
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
  <title>UTB - Attendance Portal</title>
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
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
    <a class="navbar-brand" href="index.html">UTB </a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
          <a class="nav-link" href="dvca.php">
            <i class="fa fa-fw fa-dashboard"></i>
            <span class="nav-link-text">Home</span>
          </a>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Components">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseComponents" data-parent="#exampleAccordion">
            <i class="fa fa-fw fa-sitemap"></i>
            <span class="nav-link-text">Goals</span>
          </a>
          <ul class="sidenav-second-level collapse" id="collapseComponents">
            <li>
              <a href="imihigo_dvcs">My Goals</a>
            </li>
            <li>
              <a href="orient_dvcs">Orient Goals</a>
            </li>
          </ul>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Components">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseComponents1" data-parent="#exampleAccordion">
            <i class="fa fa-fw fa-sitemap"></i>
            <span class="nav-link-text">Achievements</span>
          </a>
          <ul class="sidenav-second-level collapse" id="collapseComponents1">
            <li>
              <a href="sub_achievements_dvcs">Sub-Achievements</a>
            </li>
            <li>
              <a href="achievements_dvcs">My Achievements</a>
            </li>

          </ul>
        </li>
      </ul>
      <ul class="navbar-nav sidenav-toggler">
        <li class="nav-item">
          <a class="nav-link text-center" id="sidenavToggler">
            <i class="fa fa-fw fa-angle-left"></i>
          </a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">

        <li class="nav-item">
          <form class="form-inline my-2 my-lg-0 mr-lg-2">
            <div class="input-group">
              <input class="form-control" type="text" placeholder="Search for...">
              <span class="input-group-btn">
                <button class="btn btn-primary" type="button">
                  <i class="fa fa-search"></i>
                </button>
              </span>
            </div>
          </form>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="modal" data-target="#exampleModal">
            <i class="fa fa-fw fa-sign-out"></i>Logout</a>
        </li>
      </ul>
    </div>
  </nav>
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
</li><h1 id="txttt" style="font-weight: bolder;float: right;color: grey;text-align: right;"> <?=ucfirst($_SESSION['utb_att_position'])?> <span id="txttt"></span> || <small style="font-size: 20px"><?=$_SESSION['utb_att_names']?></small></h1>      </ol>
      <!-- Icon Cards-->

      <!-- Area Chart Example-->
      <div class="card mb-3">
        <div class="card-header">
          <h3>Requested Leaves</h3>
           <button class="btn btn-primary" style="float: right;">
            <select class="form-control" id="sttsBtn" style="background-color: transparent;padding:0px;font-size:20px;"> 
              <option style="">Pending</option>
              <option>Approved</option>
              <option>Rejected</option>
           </select> </button>
        <div class="card-body">
          <table class="table  text-center" style="font-size:10px">
            <thead>
              <tr>
                    <th>#</th>
                    <th>Employee</th>
                    <th>Post</th>
                    <th>Time of Request</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Days</th>
                    <th>allowed</th>
                    <th>Remaining</th>
                    <th>Reason</th>
                    <th>Supervisor</th>
                    <th>HR</th>
                    <th>DVCPAF</th>
                    <th>DVCA</th>
                    <th>VC</th>
                    <th>Actions</th>

              </tr>
            </thead>
            <tbody  id="respp">
              <tr>
                <td colspan="13">No Leave requested yet </td>
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
                  <input type="date" id="dateFrom" max="8/11/2022" class="form-control">
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
  // document.getElementById('txtt').innerHTML = document.getElementById('leftdys').value;
  setTimeout(startTime, 1000);
}

function checkTime(i) {
  if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
  return i;
}
startTime();


  $(document).ready(function(){
      var AllAvailableLeavesPending = true;
      $.ajax({url:"main/view.php",
        type:"POST",data:{AllAvailableLeavesPending:AllAvailableLeavesPending},cache:false,success:function(res){  
          var res = JSON.parse(res);
          console.log(res.found);
          if (res.found===1) {
              $("#respp").html("");
            for (const key in res.res) {
              console.log(key);
              $("#respp").append("<tr>  <td> * </td>  <td>"+res.res[key].Names+"</td>  <td>"+res.res[key].Post+"</td>  <td>"+res.res[key].LeaveDate+"</td>  <td>"+res.res[key].LeaveFrom+"</td>  <td>"+res.res[key].LeaveTo+"</td>  <td>"+res.res[key].LeaveDays+"</td>  <td style='background-color:#d1cec7'>"+res.res[key].allowed+"</td> <td>"+res.res[key].LeaveRemainig+"</td> <td>"+res.res[key].Reason+"</td><td>"+res.res[key].StatusSupervisor+"</td> <td>"+res.res[key].StatusHR+"</td> <td>"+res.res[key].StatusDVCPAF+"</td> <td>"+res.res[key].StatusDVCA+"</td> <td>"+res.res[key].StatusVC+"</td> <td> <button onclick='return ApproveLeave(4,"+res.res[key].LeaveId+")' class='btn btn-success'>Approve</button> <button onclick='return RejectLeave(4,"+res.res[key].LeaveId+")' class='btn btn-danger'>Reject</button></td>  </tr>");
            }
          }else{
            $("#respp").html("<tr><td colspan='13'>No Leave requested yet... </td>  </tr>");
          }
          }
      });
      //====================================== Approved
      $("#sttsBtn").change(function(){
        var contt = $("#sttsBtn").val();

        if (contt === 'Approved') {
          var AllAvailableLeavesApproved = true;
          $.ajax({url:"main/view.php",
            type:"POST",data:{AllAvailableLeavesApproved:AllAvailableLeavesApproved},cache:false,success:function(res){  
              var res = JSON.parse(res);
              console.log(res.found);
              if (res.found===1) {
                  $("#respp").html("");
                for (const key in res.res) {
                  console.log(key);
                  $("#respp").append("<tr>  <td> * </td>  <td>"+res.res[key].Names+"</td>  <td>"+res.res[key].Post+"</td>  <td>"+res.res[key].LeaveDate+"</td>  <td>"+res.res[key].LeaveFrom+"</td>  <td>"+res.res[key].LeaveTo+"</td>  <td>"+res.res[key].LeaveDays+"</td>  <td style='background-color:#d1cec7'>"+res.res[key].allowed+"</td> <td>"+res.res[key].LeaveRemainig+"</td> <td>"+res.res[key].Reason+"</td> <td>"+res.res[key].StatusSupervisor+"</td> <td>"+res.res[key].StatusHR+"</td> <td>"+res.res[key].StatusDVCPAF+"</td> <td>"+res.res[key].StatusDVCA+"</td> <td>"+res.res[key].StatusVC+"</td> <td> <button onclick='return PendingLeave(4,"+res.res[key].LeaveId+")' class='btn btn-primary'>Pending</button> <button onclick='return RejectLeave(4,"+res.res[key].LeaveId+")' class='btn btn-danger'>Reject</button></td>  </tr>");
                }
              }else{
                $("#respp").html("<tr><td colspan='13'>No Leave requested yet... </td>  </tr>");
              }
              }
          });
        }else if (contt === 'Rejected') {
          var AllAvailableLeavesRejected = true;
          $.ajax({url:"main/view.php",
            type:"POST",data:{AllAvailableLeavesRejected:AllAvailableLeavesRejected},cache:false,success:function(res){  
              var res = JSON.parse(res);
              console.log(res.found);
              if (res.found===1) {
                  $("#respp").html("");
                for (const key in res.res) {
                  console.log(key);
                  $("#respp").append("<tr>  <td> * </td>  <td>"+res.res[key].Names+"</td>  <td>"+res.res[key].Post+"</td>  <td>"+res.res[key].LeaveDate+"</td>  <td>"+res.res[key].LeaveFrom+"</td>  <td>"+res.res[key].LeaveTo+"</td>  <td>"+res.res[key].LeaveDays+"</td>  <td style='background-color:#d1cec7'>"+res.res[key].allowed+"</td> <td>"+res.res[key].LeaveRemainig+"</td> <td>"+res.res[key].Reason+"</td> <td>"+res.res[key].StatusSupervisor+"</td> <td>"+res.res[key].StatusHR+"</td> <td>"+res.res[key].StatusDVCPAF+"</td> <td>"+res.res[key].StatusDVCA+"</td> <td>"+res.res[key].StatusVC+"</td> <td> <button onclick='return ApproveLeave(4,"+res.res[key].LeaveId+")' class='btn btn-success'>Approve</button> <button onclick='return PendingLeave(4,"+res.res[key].LeaveId+")' class='btn btn-default'>Pending</button></td>  </tr>");
                }
              }else{
                $("#respp").html("<tr><td colspan='13'>No Leave requested yet... </td>  </tr>");
              }
              }
          });
        }else if (contt === 'Pending') {
          var AllAvailableLeavesPending = true;
          $.ajax({url:"main/view.php",
            type:"POST",data:{AllAvailableLeavesPending:AllAvailableLeavesPending},cache:false,success:function(res){  
              var res = JSON.parse(res);
              console.log(res.found);
              if (res.found===1) {
                  $("#respp").html("");
                for (const key in res.res) {
                  console.log(key);
                  $("#respp").append("<tr>  <td> * </td>  <td>"+res.res[key].Names+"</td>  <td>"+res.res[key].Post+"</td>  <td>"+res.res[key].LeaveDate+"</td>  <td>"+res.res[key].LeaveFrom+"</td>  <td>"+res.res[key].LeaveTo+"</td>  <td>"+res.res[key].LeaveDays+"</td>  <td style='background-color:#d1cec7'>"+res.res[key].allowed+"</td> <td>"+res.res[key].Reason+"</td><td>"+res.res[key].LeaveRemainig+"</td> <td>"+res.res[key].StatusSupervisor+"</td> <td>"+res.res[key].StatusHR+"</td> <td>"+res.res[key].StatusDVCPAF+"</td> <td>"+res.res[key].StatusDVCA+"</td> <td>"+res.res[key].StatusVC+"</td> <td> <button onclick='return PendingLeave(4,"+res.res[key].LeaveId+")' class='btn btn-success'>Approve</button> <button onclick='return RejectLeave(4,"+res.res[key].LeaveId+")' class='btn btn-danger'>Reject</button></td>  </tr>");
                }
              }else{
                $("#respp").html("<tr><td colspan='13'>No Leave requested yet... </td>  </tr>");
              }
              }
          });
        }else{
          alert(contt);
        }
      })
    });


$("#sidenavToggler").click(); //===================== Minimizing Menu Section

</script>
  </div>
</body>

</html>
