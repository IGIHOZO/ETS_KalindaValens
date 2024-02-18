<?php
require('main/view.php'); 
$user = $_SESSION['utb_att_user_id'];
if (@$_SESSION['utb_att_position']!='supervisor') {
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
  }else if (@$_SESSION['utb_att_position']=='supervisor') {
    $toShow = "supervisor";
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
          <a class="nav-link" href="supervisor">
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
              <a href="imihigo_supervisers">My Goals</a>
            </li>
            <li>
              <a href="orient_supervisers">Orient Goals</a>
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
              <a href="sub_achievements_supervisor">Sub-Achievements</a>
            </li>
            <li>
              <a href="achievements_supervisor">My Achievements</a>
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
          <h3>Orient My Goals</h3>
           <!-- <button class="btn btn-success" style="float: right;" data-toggle="modal" id="newGoalBtn" data-target="#newRequestModal" >New Goal</button> -->
            <div class="modal-body">
              <div class="row form-group">
                <div class="col-md-6 mb-3 mb-md-0">
                  <p class="help-block text-danger" id="ress" style="color:red"></p>
                  <label style="font-weight: bold;" for="goalname">My Goals:</label>
                  <select class="form-control" id="ass_goal">
                    <?php 
                    $sel = $con->prepare("SELECT * FROM imihigo WHERE imihigo.ImihogoOwner='$user' ORDER BY imihigo.ImihigoName");
                    $sel->execute();
                    if ($sel->rowCount()>=1) {
                      while ($ft_sel = $sel->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='".$ft_sel['ImihigoId']."'>".$ft_sel['ImihigoName']."</option>";
                      }
                    }else{
                      echo "<option value=''>No Goal Available</option>"; 
                    }
                    ?>
                  </select>
                </div>
                <div class="col-md-6">
                  <label style="font-weight: bold;" for="goaldetails">Descendents:</label>
                  <p class="help-block text-danger"></p>
                  <select class="form-control" id="descendents">
                    <?php 
                    $sel = $con->prepare("SELECT * FROM attendance_users WHERE attendance_users.Position NOT LIKE '%DVC%' OR  attendance_users.Position<>'VC'");
                    $sel->execute();
                    if ($sel->rowCount()>=1) {
                      while ($ft_sel = $sel->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='".$ft_sel['UserId']."'>".$ft_sel['Position']." - ".$ft_sel['Names']."</option>";
                      }
                    }else{
                      echo "<option value=''>No Goal Available $user</option>"; 
                    }
                    ?>
                  </select>

                </div>
                <div class="form-group">
                <div class="col-md-12"><br>
                  <button class="btn btn-primary" id="assignUmuhigo" style="float:right">Save</button>
                </div>
                </div>

              </div>
            </div>
        <div class="card-body">
          <table class="table  text-center">
            <thead>
              <tr>
                <th>#</th>
                <th>Goal Name</th>
                <th>Orientation</th>
              </tr>
            </thead>
            <tbody  id="respp">
              <tr>
                <td colspan="3">No Goal available ... </td>
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

    <script type="text/javascript">
      $(document).ready(function(){
        $("#assignUmuhigo").click(function(){
          var goal = $("#ass_goal").val();
          var toAssign = $("#descendents").val();

        var assignUmuhigo = true;
        $.ajax({url:"main/main.php",
        type:"GET",data:{
          assignUmuhigo:assignUmuhigo,goal:goal,toAssign:toAssign
        },cache:false,success:function(res){
          window.location.reload(true);
        }
        });
        })
      })
  $(document).ready(function(){
     var MineSupervisorsOrientedGoals = true;

      $.ajax({url:"main/view.php",
        type:"POST",data:{MineSupervisorsOrientedGoals:MineSupervisorsOrientedGoals},cache:false,success:function(res){  
          var res = JSON.parse(res);
          // console.log(res.found);
          if (res.found===1) {
              $("#respp").html("");
              var i = 1;
            for (const key in res.res) {
              
              $("#respp").append("<tr>  <td> "+ i +" </td>  <td>"+res.res[key].ImihigoName+"</td>  <td>"+res.res[key].Position+"</td></tr>");
                i++;
                
            }
          }else{
            $("#respp").html("<tr><td colspan='10'>No goal available ... </td>  </tr>");
          }
          }
      });
    });
    </script>
  </div>
</body>

</html>
