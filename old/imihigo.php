<?php
require('main/view.php'); 

if (@$_SESSION['utb_att_position']!='VC' && @$_SESSION['utb_att_position']!='DVCA' && @$_SESSION['utb_att_position']!='DVCPAF') {
  ?>
<script type="text/javascript">
        window.location="login.php";
</script>
  <?php
}
  if (@$_SESSION['utb_att_position']=='VC') {
    $toShow = "VCGoals";
  }else if (@$_SESSION['utb_att_position']=='DVCA' OR @$_SESSION['utb_att_position']=='DVCPAF') {
    $toShow = "DVCsGoals";
  }else{
    $toShow = '-';
  }
$MainView = new MainView();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>UTB - Goals</title>
  <!-- Bootstrap core CSS-->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom fonts for this template-->
  <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <!-- Page level plugin CSS-->
  <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">

  <style type="text/css">

    th{
      text-align: center;
    }
    td,th{
      text-align: left;
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

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <!-- Navigation-->
  <input type="hidden" id="toShow" value="<?=$toShow?>">
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
    <a class="navbar-brand" href="index.html">UTB </a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
          <a class="nav-link" href="reception.php">
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
              <a href="imihigo">My Goals</a>
            </li>
            <li>
              <a href="orient_vc">Orient Goals</a>
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
        <li class="breadcrumb-item active">My Goals   
          
        </li><h1 id="txttt" style="font-weight: bolder;float: right;color: grey;text-align: right;"> <?=ucfirst($_SESSION['utb_att_position'])?> <span id="txttt"></span> || <small style="font-size: 20px"><?=$_SESSION['utb_att_names']?></small></h1>
      </ol>
      <!-- Icon Cards-->

      <!-- Area Chart Example-->
      <div class="card mb-3">
        <div class="card-header">
          <h3>My Goals</h3>
           <button class="btn btn-success" style="float: right;" data-toggle="modal" id="newGoalBtn" data-target="#newRequestModal" >New Goal</button>
        <div class="card-body">
          <table class="table  text-center">
            <thead>
              <tr>
                <th>#</th>
                <th>Goal Name</th>
                <th>Goal Details</th>
                <th>Date Registered</th>
                <th>Status</th>
                <th id='th_btns'>Actions</th>
              </tr>
            </thead>
            <tbody  id="respp">
              <tr>
                <td colspan="4">No Goal available ... </td>
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
      <div class="modal-dialog" style="min-width: 60%" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" style="margin:0 auto" id="exampleModalLabel">Register New Goal</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="false">×</span>
            </button>
          </div>
          <!-- <div class="modal-body"> -->
          <!-- <form action = "employee" method = "POST" name="sentMessage" id="contactForm" novalidate="novalidate"> -->

            <div class="modal-body">
              <div class="row form-group">
                <div class="col-md-6 mb-3 mb-md-0">
                  <p class="help-block text-danger" id="ress" style="color:red"></p>
                  <label style="font-weight: bold;" for="goalname">Goal Name (Target):</label>
                  <input type="text" id="goalname" class="form-control" placeholder="Goal Name (Target)">
                </div>
                <div class="col-md-6">
                  <label style="font-weight: bold;" for="goaldetails">Goal Details:</label>
                  <p class="help-block text-danger"></p>
                    <textarea class="form-control" id="goaldetails" placeholder="Write goal details here ..." rows="6"></textarea>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button class="btn btn-primary" type="button"  id="saveGoal" name="save">Save changes</button>
            </div>
          <!-- </form> -->
        </div>
      </div>
    </div>

    <!-- Orient Goal Modal-->
    <div class="modal fade" id="OrientUmuhigoModal" tabindex="-1" role="dialog" aria-labelledby="OrientUmuhigoModalLabel" aria-hidden="false" sty>
      <div class="modal-dialog" style="min-width: 60%" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" style="margin:0 auto" id="exampleModalLabel">Orient Goal</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="false">×</span>
            </button>
          </div>
          <!-- <div class="modal-body"> -->
          <!-- <form action = "employee" method = "POST" name="sentMessage" id="contactForm" novalidate="novalidate"> -->
            <input type="hidden" id="hidenUmuhigo" name="">
            <input type="hidden" id="hidenUmuhigoOwner" name="">
            <div class="modal-body">
              <div class="row form-group">
                <div class="col-md-6 mb-3 mb-md-0">
                  <p class="help-block text-danger" id="resss" style="color:red"></p>
                  <label style="font-weight: bold;" for="goalnameNew">Goal Name (Target):</label>
                  <input type="text" id="goalnameNew" class="form-control" placeholder="Goal Name (Target)">
                </div>
                <div class="col-md-6">
                  <label style="font-weight: bold;" for="goaldetailsNew">Goal Details:</label>
                  <p class="help-block text-danger"></p>
                    <textarea class="form-control" id="goaldetailsNew" placeholder="Write goal details here ..." rows="6"></textarea>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button class="btn btn-primary" type="button"  id="saveGoalNew" name="save">Save changes</button>
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
    <!-- <script src="vendor/chart.js/Chart.min.js"></script> -->
    <script src="vendor/datatables/jquery.dataTables.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin.min.js"></script>
    <!-- Custom scripts for this page-->
    <script src="js/sb-admin-datatables.min.js"></script>
    <!-- <script src="js/sb-admin-charts.min.js"></script> -->
    <script src="js/main.js"></script>

<script>
  function SetGoalModal(umuhigo,owner) {
    document.getElementById("hidenUmuhigo").value=umuhigo;
    document.getElementById("hidenUmuhigoOwner").value=owner;
  }
  $(document).ready(function(){
     var VCGoals = 'VCGoals';
      var toShow = document.getElementById("toShow").value;
      // alert(toShow);
      var diapl = '';
      if (toShow=='DVCsGoals') {
        diapl = "none";
        diaplInvs = "block";
        // $("#th_btns").css("display","none");
        $("#newGoalBtn").css("display","none");
        VCGoals = 'DVCsGoals';
        $(".btn btn-primary").css("display","block");

      }else{
        diapl = "block";
        diaplInvs = "none";
        VCGoals = 'VCGoals';

      }
      $.ajax({url:"main/view.php",
        type:"POST",data:{VCGoals:VCGoals},cache:false,success:function(res){  
          var res = JSON.parse(res);
          // console.log(res.found);
          if (res.found===1) {
              $("#respp").html("");
              var i = 1;
            for (const key in res.res) {
              
              $("#respp").append("<tr>  <td> "+ i +" </td>  <td>"+res.res[key].ImihigoName+"</td>  <td>"+res.res[key].ImihigoDetails+"</td>  <td>"+res.res[key].ImihigoDate+"</td>  <td>"+res.res[key].ImihigoStatus+"</td>   <td style='display:"+diapl+"'><button onclick='return "+res.res[key].Button+"Goal("+res.res[key].ImihigoId+");' class='btn btn-"+res.res[key].Color+"'>"+res.res[key].Button+" </button> <button class='btn btn-danger' onclick='return DeleteGoal("+res.res[key].ImihigoId+")'>Delete</button></td> <td style='display:"+diaplInvs+"'> <button class='btn btn-primary' onclick='return SetGoalModal("+res.res[key].ImihigoId+","+res.res[key].ImihigoId+")' data-toggle='modal' id='newGoalBtn' data-target='#OrientUmuhigoModal'>Set Goal</button></td> </tr>");
                i++;
                
            }
          }else{
            $("#respp").html("<tr><td colspan='10'>No goal available ... </td>  </tr>");
          }
          }
      });
    });
$("#sidenavToggler").click(); //===================== Minimizing Menu Section

if (VCGoals=='DVCsGoals') {
    $("#saveGoalNew").click(function(){   //==================================== Orient Goal
      var Umuhigo = $("#hidenUmuhigo").val();
      var UmuhigoOwner = $("#hidenUmuhigoOwner").val();
      var UmuhigoName = $("#goalnameNew").val();
      var UmuhigoDetails = $("#goaldetailsNew").val();
    if (Umuhigo!='' && UmuhigoName!='' && UmuhigoDetails!='') {
        var saveGoalNew = true;
        $.ajax({url:"main/main.php",
        type:"GET",data:{
          saveGoalNew:saveGoalNew,Umuhigo:Umuhigo,UmuhigoName:UmuhigoName,UmuhigoDetails:UmuhigoDetails,UmuhigoOwner:UmuhigoOwner
        },cache:false,success:function(res){
          window.location.reload(true);
        }
        });
      }else{
        $("#resss").html("Fill all fields ...");
      }
    // alert("clicked");
    });
}








</script>
    <script src="js/main.js"></script>
  </div>
</body>

</html>
