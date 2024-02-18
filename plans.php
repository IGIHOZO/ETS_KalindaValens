<?php
require('main/view.php'); 
require('main/drive/config.php'); 

if (@$_SESSION['utb_att_position']!='VC' && @$_SESSION['utb_att_position']!='DVCA' && @$_SESSION['utb_att_position']!='DVCPAF' && @$_SESSION['utb_att_position']!='HR') {
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


<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

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
<script>
    document.write('<a class="nav-link" href="' + document.referrer + '"');
</script>
          
            <i class="fa fa-fw fa-dashboard"></i>
            <span class="nav-link-text">Home</span>
          </a>
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
          <a onclick="return window.location='logout'" class="nav-link" data-toggle="modal" data-target="#exampleModal">
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
<script>
    document.write('<a href="' + document.referrer + '">Go Back</a>');
</script>
          <!-- <a href="#">Home</a> -->
        </li>
        <li class="breadcrumb-item active"> 
          <label><?= $MyFunctions->greeting_msg()." ".$MyFunctions->first_name($_SESSION['utb_att_names'])?></label>
        </li>
        <!-- <h1 id="txt" style="font-weight: bolder;float: right;color: red;text-align: right;"> <span id="txtt">**</span> <small style="font-size: 26px">-  Annual leave days</small></h1> -->
      </ol>
      <!-- Icon Cards-->

      <!-- Area Chart Example-->
      <div class="card mb-3">
        <div class="card-header">
          <h3>Avalailable Leaves</h3>
           <button class="btn btn-success" id="newReq" data-toggle="modal" data-target="#newRequestModal" style="float: right;font-weight: bold;">New Request</button>
           <!-- <a href="plan" class="btn btn-primary" style="float: right;margin-right: 4%;font-weight: bold;">My Leaves Plan</a> -->

        <div class="card-body">
          <br>
          <table class="table  text-center" style="font-size:12px">
            <thead>
              <tr>
                <th>#</th>
                <th>Time of Request</th>
                <th>Employee Names</th>
                <th>Position</th>
                <th>Leaves-Range</th>
              </tr>
            </thead>
            <tbody  id="respp">
              <tr>
                <td colspan="5">No Leave requested yet </td>
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
      <div class="modal-dialog" style="min-width: 50%" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" style="margin:0 auto" id="exampleModalLabel">Request for leaves Plan</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="false">×</span>
            </button>
          </div>
          <input type="hidden" name="" id="leaveranges">
          <input type="hidden" name="" id="leaverangesNumbers">
            <div class="modal-body" id="respPlans">
              <div class="row form-group">
                <div class="col-8">
                  <p class="help-block text-danger" id="ress"></p>
                  <label style="font-weight: bold;font-size: 25px;" for="dateFrom">Choose days for leave <span id="leavNumberDiv" style="font-weight:bold;color: #02bf18;font-size: 30px;"> 1</span> :</label>
                  <input type="text" name="daterange" id="nextRange" class="form-control" style="font-weight: bolder;text-align: center;width: 90%;margin: 0 auto!important;" />
                </div>
                <div class="col-2">
                  <p class="help-block text-danger" id="ress"></p>
                  <label style="font-weight: bold;" for="dateFrom">&nbsp;&nbsp;&nbsp;&nbsp;</label>
                  <button class="btn btn-primary" type="button" onclick="return nextRange();" id="save" name="save">Next leave</button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </div>
                <div class="col-12">
                  <p class="help-block text-danger" id="ress"></p>
                  <label id="resppRange" style="font-weight: bold;color:#445456;font-size: 25px;font-style: oblique;" for="dateFrom"></label>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button class="btn btn-success" type="button" onclick="return saveLeaveRange();" id="save" name="save">OK, Save</button>
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
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script src="vendor/jquery/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>

function saveLeaveRange(){
  var range = document.getElementById("leaveranges").value;
  if (range=="") {
    alert("Fill all fields ...");
  }else{
    var saveLeaveRange = true;
    $.ajax({url:"main/main.php",
    type:"GET",data:{
      saveLeaveRange:saveLeaveRange,range:range
    },cache:false,success:function(res){
      window.location.reload(true);
    }
    });
  }

}
function nextRange(){
  var range = $("#nextRange").val();
  if (range!="") {
      var leaveranges = $("#leaveranges").val();
      if ($("#leaverangesNumbers").val()=="") {
        var leaverangesNumbers = 0;
      }else{
        var leaverangesNumbers = parseInt($("#leaverangesNumbers").val());
      }
      var newLeaveRange = leaveranges+" , "+range;
      var newLeaveRangeNumbers = parseInt(leaverangesNumbers+1);
      if ($("#leaverangesNumbers").val()=="") {
        document.getElementById("leaveranges").value = newLeaveRange.substring(3);
      }else{
         document.getElementById("leaveranges").value = newLeaveRange;
      }

      document.getElementById("leaverangesNumbers").value = newLeaveRangeNumbers;
      document.getElementById("nextRange").value = "";
      $("#leavNumberDiv").html(newLeaveRangeNumbers+1);
      $("#resppRange").html("<ol>");
      var newnewLeaveRangeArray = newLeaveRange.split(" , ");
      for (var i=0, l=newnewLeaveRangeArray.length; i<l; i++) {
      console.log(newnewLeaveRangeArray[i]);
      $("#resppRange").append("<li>"+newnewLeaveRangeArray[i]+"</li>");
    }
      $("#resppRange").append("</ol>");
    }else{
      alert("fill all fields ...");
    }

}

$(function() {
// $("#sidenavToggler").click(); //===================== Minimizing Menu Section
// $("#newReq").click(); //===================== Minimizing Menu Section

  $('input[name="daterange"]').html="Your range here";
  $('input[name="daterange"]').daterangepicker({
    opens: 'left'
  }, function(start, end, label) {
  });
  $('input[name="daterange"]').html="Your range here";
});

      var AllLeaveRange = true;
      $.ajax({url:"main/view.php",
        type:"POST",data:{AllLeaveRange:AllLeaveRange},cache:false,success:function(res){  
          var res = JSON.parse(res);
          // console.log(res.found);
          if (res.found===1) {
              $("#respp").html("");
            for (const key in res.res) {
              // console.log(key);
              var i = key;
              $("#respp").append("<tr>  <td> "+ ++i+" </td>  <td>"+res.res[key].RangeDate+"</td>  <td style='text-align:left'> "+res.res[key].Names+" </td> <td>"+res.res[key].Position+"</td>  <td style='text-align:left'> "+res.res[key].RangeDetails+" </td> </tr>");
            }
          }else{
            $("#respp").html("<tr><td colspan='5'><center>No Leave requested yet...</center> </td>  </tr>");
          }
          }
      });
</script>
  </div>
</body>

</html>
