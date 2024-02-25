<?php
session_start();
@require('main/view.php'); 
$MainView = new MainView();


if ($MainView->StaffPositionName()!='Receptionist' && isset($_SESSION['worker_id'])) {
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
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
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
        <li class="breadcrumb-item active">My Dashboard</li>
        </li><h1 id="txt" style="font-weight: bolder;float: right;color: red;text-align: right;">Time Here ...</h1>

      </ol>

      



      <?php
    $sel = $con->prepare("SELECT * FROM ets_workers WHERE ets_workers.worker_status=1 ORDER BY ets_workers.worker_fname, ets_workers.worker_lname ASC");
    $sel->execute();
    if ($sel->rowCount() >= 1) {
        $cnt = 1;
        while ($ft_se = $sel->fetch(PDO::FETCH_ASSOC)) {
            if ($MainView->WorkerPositionName($ft_se['worker_id']) == '-' && $ft_se['worker_category'] == 3) {
                $position = 'Digger';
            } else {
                $position = $MainView->WorkerPositionName($ft_se['worker_id']);
            }
            $fullNames = strtoupper($ft_se['worker_fname']) . "_" . ucfirst(strtolower($ft_se['worker_lname']));
    ?>

            <div style="position: relative;">
                <table id="card<?= $ft_se['worker_id'] ?>" style="width: 300px; height: 200px; margin: 10px auto; border-collapse: collapse; outline: 2px solid #3498db; border-radius: 10px; position: relative;">
                    <tbody>
                        <tr>
                            <td colspan="3" style="text-align: center; background-color: #3498db; color: #fff; padding: 4px; font-weight: bolder; font-size: 15px;">
                                <h4 style="margin: 0;">MINE ETS Kalinda Valens</h4>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center; padding: 20px; position: relative;">
                                <img src="<?= $ft_se['worker_photo'] ?>" alt="Employee profile picture" style="width: 80px; height: 80px; border-radius: 50%; border: 4px solid #3498db;" />
                                <h5 style=" font-size: 16px; color: #333;"><?= strtoupper($ft_se['worker_fname']) . ' ' . $ft_se['worker_lname'] ?> </h5>
                                <b><i><label style="margin: 5px 0; color: #666;"><?= $position ?></label></i></b>
                            </td>
                            <td style="width: 160px; padding: 0px 40px 0px 40px; text-align: center; color: #333;font-weight:bold">
                                <div style="position: absolute; top: 0; right: 0; bottom: 0; left: 0; background-image: url('img/logo.jpeg'); background-size:50%; background-position: center; opacity: 0.1; z-index: -1;background-repeat:no-repeat"></div>

                            <span>
                            <p style="font-size: 14px; font-weight: bold;"><b><h3><?= $ft_se['worker_unid'] ?></h3></b></p>
                                <p style="margin: 10px 0; font-size: 12px;">
                                    <i class="fa fa-phone-alt" style="color: #3498db; margin-right: 5px;"></i> +25<?= $ft_se['worker_phone'] ?>
                                </p>
                                <p style="font-size: 12px;">
                                    <i class="fa fa-envelope" style="color: #3498db; margin-right: 5px;"></i> info@etskalindavalens.com
                                </p>
                                <p style="font-size: 12px;">
                                    <i class="fa fa-globe" style="color: #3498db; margin-right: 5px;"></i> etskalindavalens.com
                                </p>
                                <p style="font-size: 12px;">
                                    <i class="fa fa-map-marker-alt" style="color: #3498db; margin-right: 5px;"></i> 3VQP+JV, Taba
                                </p>
                            </span>
                            </td>
                            <td style="text-align: center; padding: 20px; position: relative;font-size: 9px;font-weight:bold;color:#000 ">
                            <div id="qr-code-<?= $ft_se['worker_id'] ?>"></div>
                            <script>
                                var qrcode = new QRCode(document.getElementById("qr-code-<?= $ft_se['worker_id'] ?>"), {
                                    text: "https://seveeen.rw/ets/reception.php?userAttend=1&attendedUser=<?= $ft_se['worker_id'] ?>", // Fixed typo here
                                    width: 120,
                                    height: 120,
                                    colorDark: "#000000",
                                    correctLevel: QRCode.CorrectLevel.H
                                });
                            </script>



                                <small style=" display: block; margin-top: 10px; color: #666;">This card is the property of ETS</small>
                                <p class="issue-date" style="margin: 10px 0; color: #333;">Issued Date: 2024-02-21</p>
                                <p class="expiry-date" style="margin: 10px 0; color: #333;">Expiry Date: 2025-02-21</p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align: center; background-color: #3498db; color: #fff; font-weight: bolder; font-size: 16px;">
                                <label>Rubare village, Gishyeshye, Rukoma, Kamonyi District</label>
                            </td>

                        </tr>
                    </tbody>
                </table>

                <!-- Add the Bootstrap download button outside the table -->
                <button style="margin-top: 10px; padding: 8px 16px; background-color: #007bff; color: #fff; border: none; border-radius: 4px; cursor: pointer;" onclick="downloadImage('card<?= $ft_se['worker_id'] ?>','<?=$fullNames?>')">Download Card</button>
            </div>

            <script>
                function downloadImage(cardId, names) {
                    html2canvas(document.querySelector(`div #${cardId}`), { useCORS: true }).then(canvas => {
                        var link = document.createElement('a');
                        link.href = canvas.toDataURL();
                        link.download = `card_${names}.png`;
                        link.click();
                    });
                }
            </script>

    <?php
            $cnt++;
        }
    } else {
    ?>
        <tr>
            <td colspan="7">
                <center>No data found ...</center>
            </td>
        </tr>
    <?php
    }
    ?>



  

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
</script>
  </div>
</body>

</html>
