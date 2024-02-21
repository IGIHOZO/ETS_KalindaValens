<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('Africa/Kigali');

// require("drive/config.php");
// $MyFunctions = new MyFunctions();
class DbConnectt
{

    private $host='localhost';
    private $dbName = 'mpjusdko_seveeen_web';
    private $user = 'mpjusdko';
    private $pass = 'z0HpWFx1%@48';


    // private $host='localhost';
    // private $dbName = 'seveeen_web';
    // private $user = 'root';
    // private $pass = '';
    // public $conn;



    public function connect()
    {
        try {
         $conn = new PDO('mysql:host='.$this->host.';dbname='.$this->dbName, $this->user, $this->pass);
         return $conn;
        } catch (PDOException $e) {
            echo "Database Error ".$e->getMessage();
            return null;
        }
    }
}


class MyFunctions extends DbConnectt{
function split_name($name) {
    $name = trim($name);
    $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
    $first_name = trim( preg_replace('#'.preg_quote($last_name,'#').'#', '', $name ) );
    return array($first_name, $last_name);
}
public function UserName($userr)         // FROM (attendance_users)
{
    $con = parent::connect();
    $sel = $con->prepare("SELECT * FROM attendance_users WHERE attendance_users.UserId='$userr' OR attendance_users.Lfid='$userr'");
    $sel->execute();
    if ($sel->rowCount()==1) {
        $ft_sel = $sel->fetch(PDO::FETCH_ASSOC);
        $name = $ft_sel['Names'];
    }else{
            $name = "-";
        }
    return $name;
}

public function UserPhone($userr)         // FROM (attendance_users)
{
    $con = parent::connect();
    $sel = $con->prepare("SELECT * FROM attendance_users WHERE attendance_users.UserId='$userr' OR attendance_users.Lfid='$userr'");
    $sel->execute();
    if ($sel->rowCount()==1) {
        $ft_sel = $sel->fetch(PDO::FETCH_ASSOC);
        $name = $ft_sel['Phone'];
    }else{
            $name = "-";
        }
    return $name;
}

function greeting_msg() {
    $hour = date('H');
    if ($hour >= 18) {
       $greeting = "Good Evening";
    } elseif ($hour >= 12) {
        $greeting = "Good Afternoon";
    } elseif ($hour < 12) {
       $greeting = "Good Morning";
    }
    return $greeting;
}
public function CheckAmINLeave($user)        //==================================== TO CHEKIF SOMEONE IS IN LEAVE TODAY
{
  $con = parent::connect();
  $status1 = 0;
  $status2 = 0;
  $all = 0;

  // $user = $_SESSION['utb_att_user_id'];
  $sel = $con->prepare("SELECT * FROM leaves WHERE leaves.LeaveEmployee='$user' AND leaves.LeaveStatus=1");
  $sel->execute();
  if ($sel->rowCount()>=1) {
    while ($ft_sel = $sel->fetch(PDO::FETCH_ASSOC)) {
      $from =  $ft_sel['LeaveFrom'];
      $to =  $ft_sel['LeaveTo'];
      $today = date("Y-m-d");
      if ($today>=$from) {
        // echo "yes 1 <br>";
        $status1 = true;
      }

      if ($today<=$to) {
        // echo "yes 2 <br>";
        $status2 = true;
      }
      // echo "-------------------------- <br>";
    }
    if ($status1 AND $status2) {
      $all = true;
    }
  }
  return $all;
}

function sendSmsAttendanceLatnessOnce($phone,$name){        // SENDING LATENESS SMS For Once
    $nname = strtok($name, ' ');
    $data
    =
    array(
    "sender"=>'UTB',
    "recipients"=>"+250".$phone,
    "message"=>'Late Coming Notification : 
    
Dear '.$nname.',

This is to inform you that, you are late for the work today.
You are therefore requested to keep time.');
    $url
    =
    "https://www.intouchsms.co.rw/api/sendsms/.json";
    $data
    =
    http_build_query
    ($data);
    $username="utb"; 
    $password="utb.tech";
    $ch
    =
    curl_init();
    curl_setopt($ch,CURLOPT_URL,
    $url);
    curl_setopt($ch,
    CURLOPT_USERPWD,
    $username
    .
    ":"
    .
    $password);
    curl_setopt($ch,CURLOPT_POST,true);
    curl_setopt($ch,
    CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,
    CURLOPT_SSL_VERIFYPEER,
    0);
    curl_setopt($ch,CURLOPT_POSTFIELDS,
    $data);
    $result
    =
    curl_exec($ch);
    $httpcode
    =
    curl_getinfo($ch,
    CURLINFO_HTTP_CODE);
    curl_close($ch);
}

}

/**
 * ====================================== MAIN OPERATIONS
 */
class MainOpoerations extends DbConnectt
{
    public function MaxmumAllowedLeaves()
    {
        $con = parent::connect();
        $sel = $con->prepare("SELECT LeaveValues FROM max_leaves");
        $sel->execute();
        $ft_sel = $sel->fetch(PDO::FETCH_ASSOC);
        return $ft_sel['LeaveValues'];
    }
    function login($email,$pass){
        $con = parent::connect();
        $reg = $con->prepare("SELECT * FROM ets_workers,ets_workers_category WHERE ets_workers_category.category_id=ets_workers.worker_category AND 
        ets_workers.worker_phone=? AND ets_workers.worker_password=? AND ets_workers.HasToLogin=? AND ets_workers.worker_password IS NOT NULL");
        $reg->bindValue(1,$email);
        $reg->bindValue(2,md5($pass));
        $reg->bindValue(3,1);
        $reg->execute();
        if ($reg->rowCount()==1) {
            $ft_reg = $reg->fetch(PDO::FETCH_ASSOC);
            $_SESSION['worker_fname'] = $ft_reg['worker_fname'];
            $_SESSION['worker_lname'] = $ft_reg['worker_lname'];
            $_SESSION['worker_phone'] = $ft_reg['worker_phone'];
            $_SESSION['worker_category'] = $ft_reg['worker_category'];
            $_SESSION['worker_photo'] = $ft_reg['worker_photo'];
            $_SESSION['worker_id'] = $ft_reg['worker_id'];
            switch ($ft_reg['category_name']) {
                case 'Staff':
                    echo "success-reception";
                    break;
                default:
                    echo "$email";
                    break;
            }

        }else{
            echo "Zero:  ".md5($pass);
        }
    }

    function scan_card($user_id){
$MyFunctions = new MyFunctions();
$att_phone = $MyFunctions->UserPhone($user_id);
$att_name = $MyFunctions->UserName($user_id);
if ($MyFunctions->CheckAmINLeave($user_id)) {
                ?><script type="text/javascript">
                    $("#respp").css("background-color","yellow");
                </script>
                <h1 style="font-family: Palatino Linotype;color: #fff;"><span style="color:black"><?= $MyFunctions->UserName($user_id)?> <span style="color:red"> is in Leave  </span>.</h1>
                <?php
}else{

        $MorningTime ="08:30:00";
        $TimeStatus = '-';
        if (time() <= strtotime($MorningTime)) {
            // echo "Early";
            $TimeStatus = "<h1 style='color:#fcdf03;font-weight:bolder'>On-Time</h1>";
          }else{
            // echo "Late";
            // $MyFunctions->sendSmsAttendanceLatnessOnce($att_phone,$att_name);
            $TimeStatus = "<h1 style='color:red;font-weight:bolder'>You're Late</h1>";
          }
        $con = parent::connect();
        $user = $con->prepare("SELECT * FROM attendance_users WHERE attendance_users.UserId='$user_id' OR attendance_users.Lfid='$user_id'");
        $user->execute();
        if ($user->rowCount()==1) {
            $ft_user = $user->fetch(PDO::FETCH_ASSOC);
            $dept = $ft_user['Class'];
            $attender = $ft_user['UserId'];
            $passport = '../img/users/'.$attender.'.jpg';
            // $passport = '../img/users/1.jpg';

            $firstname = strtok($ft_user['Names'], ' ');
            $staff_dept = "";
            switch ($dept) {
                case 0:
                    $staff_dept = "Administration Staff";
                    break;
                
                default:
                    $staff_dept = "Teaching Staff";
                    break;
            }
        // $sel_att = $con->prepare("SELECT * FROM attendance_records,attendance_users WHERE attendance_users.UserId=attendance_records.RecordUser AND (attendance_records.RecordUser='54545' OR attendance_users.Lfid='54545') AND CAST(attendance_records.RecordTime AS DATE) = CAST( curdate() AS DATE)");
        $sel_att = $con->prepare("SELECT * FROM attendance_records,attendance_users WHERE attendance_users.UserId=attendance_records.RecordUser AND (attendance_records.RecordUser='$user_id' OR attendance_users.Lfid='$user_id') AND CAST(attendance_records.RecordTime AS DATE) = CAST( curdate() AS DATE)");
        $sel_att->execute();
        $time_now = (int)date('H');
        if ($sel_att->rowCount()>=1) {
            $ft_sel_att = $sel_att->fetch(PDO::FETCH_ASSOC);
            $cnt_row = $sel_att->rowCount();
            $timme=(int)substr($ft_sel_att['RecordTime'], 11,2);
            $diff = $time_now-$timme;
        }else{
            $cnt_row = 0;
        }

        if ($cnt_row==0 AND $time_now>17) {
            $status = 'shift';
        }elseif ($cnt_row==0 AND $time_now<=17) {
            $status = 'IN';
        }elseif ($cnt_row==1 AND $diff<1 AND $time_now<=17) {
            $status = 'arleady';
        }else{
            if ($cnt_row==1 AND $diff>=1) {
                $status = 'OUT';
            }else{
                $status = 'arleady';
            }            
        }

        }else{
            $status = "no_user";
        }

        switch ($status) {
            case 'shift':
                ?>
                <script type="text/javascript">
                        $("#respp").css("background-color","red");
                    </script>
                    <h1 style="font-family: Palatino Linotype;color: #fff;"><span>Attendance time ended, wait for <u>onother shift</u> ...</span></h1>
                    <?php
                break;
            case 'IN':
                $noww = date('Y-m-d H:i:s');
                // $ins = $con->prepare("INSERT INTO attendance_records(RecordUser,RecordTime) VALUES('0','$noww')");
                $ins = $con->prepare("INSERT INTO attendance_records(RecordUser,RecordTime) VALUES('$attender','$noww')");

                $ok_ins = $ins->execute();
                if ($ok_ins) {
                    ?><script type="text/javascript">
                    $("#respp").css("background-color","green");
                </script>
                <span style="float:left;"><?=$TimeStatus;?></span>
                    <center>
                        <table>
                            <tr>
                                <td>
                                        <center>
                                <table>
                                    <tr>
                                        <!-- <td>
                                            <span style="float:left;"><?=$TimeStatus;?></span>
                                        </td> -->
                                        <td>
                                            <b style="color:#7df;font-size:30px;font-weight:bolder;"><?=$ft_user['Position']?></b>
                                        </td>
                                        <td>
                                            <h1 style="font-size:100px;font-weight:bolder;color: #000;">IN </h1>
                                        </td>
                                        <td>
                                            <img src="img/out.png" style="height: 100px;width: 100px;">
                                        </td>
                                    </tr>
                                    <!-- <tr>
                                        <td colspan='3'>

                                        </td>
                                    </tr> -->

                                </table>
                            </center>
                            <?php
                                    if (time() <= strtotime($MorningTime)) {
                                        // echo "Early";
                                        // $TimeStatus = "<h1 style='color:#fcdf03;font-weight:bolder'>On-Time</h1>";
                                    }else{
                                        // echo "Late";
                                        $MyFunctions->sendSmsAttendanceLatnessOnce($att_phone,$att_name);
                                        // $TimeStatus = "<h1 style='color:red;font-weight:bolder'>You're Late</h1>";
                                    }
                            ?>
                                <center>
                                    <h1 style="font-family: Palatino Linotype;color: #fff;text-align:left;float:left;margin-left:-100px"><?=$MyFunctions->greeting_msg()?> <span style="color:black"><?=$firstname?>,</span> <span>Your're checked-in !</span></h1>
                                </center>                        
                                </td>
                                <!-- <td> -->
                                    <?php
                                    if (file_exists($passport)) {
                                        ?>
                                        <!-- <img src="img/users/<?=$attender.'.jpg'?>" style="height: 220px;width:180px;border-radius:20%;float:right;margin-left:50px"> -->
                                        <?php
                                    }else{
                                        ?>
                                        <!-- <img src="img/users/default_user.png" style="height: 220px;width:180px;border-radius:20%;float:right;margin-left:50px;"> -->
                                        <?php
                                    }
                                    ?>
                                <!-- </td> -->
                            </tr>
                        </table>
                    </center>
   
                <?php
                }else{
                    //print_r($ok_ins->errorMessage());
                    print_r($ins->errorInfo());
                }

                // echo $status;
                break;
            case 'OUT':
                $noww = date('Y-m-d H:i:s');
                $ins = $con->prepare("INSERT INTO attendance_records(RecordUser,RecordTime) VALUES('$attender','$noww')");
                $ok_ins = $ins->execute();
                if ($ok_ins) {
                    ?><script type="text/javascript">
                    $("#respp").css("background-color","#0c6e82");
                </script>
                <center>
                <table>
                    <tr>
                        <td>
                            <center>
                                <table>
                                    <tr>
                                        <td>
                                            <b style="color:#7df;font-size:30px;font-weight:bolder;"><?=$ft_user['Position']?></b>
                                        </td>
                                        <td>
                                            <h1 style="font-size:100px;font-weight:bolder;color: #000;">OUT </h1>
                                        </td>
                                        <td>
                                            <img src="img/out.png" style="height: 100px;width: 100px;">
                                        </td>
                                    </tr>
                                </table>
                            </center>
                            <center>
                                <h1 style="font-family: Palatino Linotype;color: #fff;"><?=$MyFunctions->greeting_msg()?> <span style="color:black"><?=$firstname?>,</span> <span>You're Checked-Out!</span></h1>
                            </center>
                        </td>
                        <!-- <td> -->
                        <?php
                                if (file_exists($passport)) {
                                     ?>
                                      <!-- <img src="img/users/<?=$attender.'.jpg'?>" style="height: 220px;width:180px;border-radius:20%;float:right;margin-left:50px"> -->
                                     <?php
                                   }else{
                                       ?>
                                       <!-- <img src="img/users/default_user.png" style="height: 220px;width:180px;border-radius:20%;float:right;margin-left:50px;"> -->
                                    <?php
                                   }
                                ?>
                        <!-- </td> -->
                    </tr>
                </table>
                </center>

                <?php
                }else{
                    // print_r($con->errorMessage());
                    print_r($ins->errorInfo());
                }

                break;
            case 'arleady':
                ?><script type="text/javascript">
                    $("#respp").css("background-color","yellow");
                </script>
                <h1 style="font-family: Palatino Linotype;color: #fff;"><span style="color:black"><?=$firstname?> <span style="color:red">arleady attended  </span>.</h1>
                <?php
                break;
            case 'not':
                ?><script type="text/javascript">
                    $("#respp").css("background-color","red");
                </script>
                <h1 style="font-family: Palatino Linotype;color: #fff;"></span> <span>Try again later ...</span></h1>
                <?php
                break;
            case 'no_user':
                ?><script type="text/javascript">
                    $("#respp").css("background-color","red");
                </script>
                <h1 style="font-family: Palatino Linotype;color: #fff;"><span>Not attended ...</span></h1>
                <?php
                break;
            
            default:
                ?><script type="text/javascript">
                    $("#respp").css("background-color","red");
                </script>
                <h1 style="font-family: Palatino Linotype;color: #fff;"></span> <span>Failed, Try again later ...</span></h1>
                <?php
                break;
        }

}
    }


    function print_card($user_id){
        $con = parent::connect();
        $sel = $con->prepare("SELECT * FROM attendance_users WHERE attendance_users.UserId='$user_id' OR attendance_users.Lfid='$user_id'");
        $sel->execute();
        if ($sel->rowCount()==1) {
            while ($ft_sel = $sel->fetch(PDO::FETCH_ASSOC)) {
                $user_id = $ft_sel['UserId'];
                $user_photo = $user_id;
    ?> 
    <center>
        <table>
            <tr>
                <td><button class="btn btn-primary" id="download<?=$user_photo?>" onclick="downloadimage('imageDIV<?=$user_photo?>','<?=$ft_sel['Names']?>','card');downloadimage('qr_print<?=$user_photo?>','<?=$ft_sel['Names']?>','qqr');">Download Card</button><hr></td>
                <!-- <td><button class="btn btn-secondary" onclick="return downloadimage('qrcode<?=$user_photo?>','<?=$ft_sel['Names']?>','qqr');">Download QR</button><hr></td> -->
            </tr>
            <tr>
                <td>
                    <div style="display: none;" id="previewImage<?=$user_photo?>"></div>
                    <div id="imageDIV<?=$user_photo?>" style="width: 500px;height: 780px;background-image: url('img/card.jpg');background-size: 100%;background-repeat: no-repeat;">
                        <center>
                            <img src="img/users/<?=$user_photo?>.jpg" style="width: 200px;height: 200px;margin-top: 41.4%;margin-left: -10.5px;">
                        </center>
                        <br><br>
                        <table style="width: 100%;text-align:left;">
                            <tr>
                                <td style="font-size: 25px;color: #cfcf30;font-weight: bold;">&nbsp;&nbsp;&nbsp;Name:</td>
                                <td style="float:left;color: #eee;font-size: 20px;font-weight: bold;margin-top: 15px;">
                                    <label><?=$ft_sel['Names']?></label>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size: 25px;color: #cfcf30;font-weight: bold;">&nbsp;&nbsp;&nbsp;Position:</td>
                                <td style="float:left;color: #eee;font-size: 20px;font-weight: bold;margin-top: 15px;">
                                    <label><?=$ft_sel['Position']?></label>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size: 25px;color: #cfcf30;font-weight: bold;">&nbsp;&nbsp;&nbsp;ID <ss style="font-weight: lighter;">NO</ss>:</td>
                                <td style="float:left;color: #eee;font-size: 20px;font-weight: bold;margin-top: 15px;">
                                    <label>UTB-<?=$filled_int = sprintf("%04d", $ft_sel['UserId']) ?></label>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
                <td><?php 

                    $vv = "https://utb.ac.rw/attendance/scan.php?content=".$ft_sel['UserId'];
                ?>
                    <input id="text<?=$user_photo?>" style="display: none;" type="text" value="<?=$vv?>" style="width:80%" /><br />
                    <table id="qr_print<?=$user_photo?>">
                        <tr>
                            <td>
                                <center><div id="qrcode<?=$user_photo?>"></div></center>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p style="color:black;font-style: italic;"><br>
                                    <center>
                                        <i style="font-size: 10.5px;font-weight: bolder;">
                                        This card is the property of UTB if found, 
                                        please <br> return it to the address below:
                                        </i>
                                    <span style="font-size: 12px;">
                                        P.O.BOX:350 Kigali-Rwanda<br>
                                        <b>Tel:</b> (250) 788314252<br>
                                        <b>Email:</b> info@utb.ac.rw<br>
                                        <b>Website:</b> www.utb.ac.rw<br>
                                    </span>
                                        
                                    </center>
                                </p>
                            </td>
                        </tr>
                    </table>
                    <script type="text/javascript">
                    var qrcode = new QRCode("qrcode<?=$user_photo?>");

                    function makeCode () {    
                      var elText = document.getElementById("text<?=$user_photo?>");
                      
                      if (!elText.value) {
                        alert("Input a text");
                        elText.focus();
                        return;
                      }
                      
                      qrcode.makeCode(elText.value);
                    }

                    makeCode();

                    $("#text<?=$user_photo?>").
                      on("blur", function () {
                        makeCode();
                      }).
                      on("keydown", function (e) {
                        if (e.keyCode == 13) {
                          makeCode();
                        }
                      });
                    </script>
                </td>

            </tr>
        </table>
    </center>
    <?php
            }
        }else{
            echo 'not_found';
        }
    }

    function print_qr($user_id){
        $con = parent::connect();
        $sel = $con->prepare("SELECT * FROM attendance_users WHERE attendance_users.UserId='$user_id' OR attendance_users.Lfid='$user_id'");
        $sel->execute();
        if ($sel->rowCount()==1) {
            while ($ft_sel = $sel->fetch(PDO::FETCH_ASSOC)) {
                $user_id = $ft_sel['UserId'];
                $user_photo = $user_id;
    ?> 
    <center>
        <table>
            <tr>
                <td><button class="btn btn-primary" id="download<?=$user_photo?>" onclick="downloadimage('imageDIV<?=$user_photo?>','<?=$ft_sel['Names']?>','card');downloadimage('qr_print<?=$user_photo?>','<?=$ft_sel['Names']?>','qqr');">Download Card</button><hr></td>
                <!-- <td><button class="btn btn-secondary" onclick="return downloadimage('qrcode<?=$user_photo?>','<?=$ft_sel['Names']?>','qqr');">Download QR</button><hr></td> -->
            </tr>
            <tr>
                <td>

                </td>
                <td><?php 

                    $vv = "https://utb.ac.rw/attendance/scan.php?content=".$ft_sel['UserId'];
                ?>
                    <input id="text<?=$user_photo?>" style="display: none;" type="text" value="<?=$vv?>" style="width:80%" /><br />
                    <br><br>
                    <table id="qr_print<?=$user_photo?>">
                        <tr>
                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            <td>
                                <center><br><br><div style="width: 240px;height: 240px" id="qrcode<?=$user_photo?>"></div></center>
                            </td>
                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

                        </tr>
                        <tr>
                            <td colspan="3">
                                <p style="color:black;font-style: italic;font-weight: bold;font-family: Arial;"><br>
                                    <center>
                                        <i style="font-size: 14px;font-weight: bolder;font-family: Arial;">
                                        This card is the property of UTB if found, please<br> 
                                         return it to the address below:<br><br>
                                        </i>
                                    <span style="font-size: 14px;font-weight: bold;font-family: Arial;">
                                        P.O.BOX:350 Kigali-Rwanda<br>
                                        <b>Tel:</b> (+250) 788314252<br>
                                        <b>Email:</b> info@utb.ac.rw<br>
                                        <b>Website:</b> www.utb.ac.rw<br>
                                    </span>
                                        
                                    </center>
                                </p>
                            </td>
                        </tr>
                    </table>
                    <script type="text/javascript">
                    var qrcode = new QRCode("qrcode<?=$user_photo?>");

                    function makeCode () {    
                      var elText = document.getElementById("text<?=$user_photo?>");
                      
                      if (!elText.value) {
                        alert("Input a text");
                        elText.focus();
                        return;
                      }
                      
                      qrcode.makeCode(elText.value);
                    }

                    makeCode();

                    $("#text<?=$user_photo?>").
                      on("blur", function () {
                        makeCode();
                      }).
                      on("keydown", function (e) {
                        if (e.keyCode == 13) {
                          makeCode();
                        }
                      });
                    </script>
                </td>

            </tr>
        </table>
    </center>
    <?php
            }
        }else{
            echo 'not_found';
        }
    }

function scan_card_phone($user_id){
        $con = parent::connect();
        $sel_att = $con->prepare("SELECT * FROM attendance_records WHERE attendance_records.RecordUser='$user_id' AND CAST(attendance_records.RecordTime AS DATE) = CAST( curdate() AS DATE)");
        $sel_att->execute();
        if ($sel_att->rowCount()>=1) {
            echo "<h1>arleady</h1>";
        }else{
            $noww = date('Y-m-d H:i:s');
            $ins = $con->prepare("INSERT INTO attendance_records(RecordUser,RecordTime) VALUES('$user_id','$noww')");
            $ok_ins = $ins->execute();
            if ($ok_ins) {
                $sel = $con->prepare("SELECT * FROM attendance_users WHERE attendance_users.UserId='$user_id'");
                $sel->execute();
                if ($sel->rowCount()==1) {
                    $ft_sel = $sel->fetch(PDO::FETCH_ASSOC);
                    $user_photo = $user_id;
                    ?>
                    <center>
                    <div style="width: 100%;height: 1000px;background-image: url('img/card.jpg');background-repeat: no-repeat;background-size: 100%;">
                        <img src="img/users/<?=$user_photo?>.jpg" style="width: 36%;height: 20%;margin-top: 41.5%;margin-left: -2.5%;">
                        <!-- <img src="img/aa.jpeg" style="width: 36%;height: 20%;margin-top: 41.5%;margin-left: -2.5%;"> -->

                        <h3 style="margin-top: 13.5%;font-weight: bold;color: #fff;text-align: left;margin-left: 24%;font-size: 12px;"><?=$ft_sel['Names']?></h3>
                        <h3 style="margin-top: -1%;font-weight: bold;color: #fff;text-align: left;margin-left: 30%;font-size: 12px"><?=$ft_sel['Position']?></h3>
                        <h3 style="margin-top: -2%;font-weight: bold;color: #fff;text-align: left;margin-left: 24%;font-size: 12px">0<?=$ft_sel['Phone']?></h3>
                        <h3 style="margin-top: 0%;font-weight: bold;color: #fff;text-align: left;margin-left: 24%;font-size: 12px"><?=$ft_sel['Email']?></h3>
                        <h3 style="margin-top: 0%;font-weight: bold;color: #fff;text-align: left;margin-left: 24%;font-size: 12px">UTB-<?=$filled_int = sprintf("%04d", $ft_sel['UserId']) ?></h3>
                    </div>
                    </center>
                    <?php

                }else{
                    echo "<h1>Nooo</h1>";
                }
            }else{
                echo "<h1>Errorrrrrr</h1>";
            }
       }
}

function saveLfid($code,$lfid){
    if ($code=='' OR $lfid=='') {
        echo "null";
    }else{
        $con = parent::connect();
        $save = $con->prepare("UPDATE attendance_users SET attendance_users.Lfid='$lfid' WHERE attendance_users.UserId='$code'");
        $ok_save = $save->execute();
        if ($ok_save) {
            echo "<h3 style='color:green'>saved</h3>".$code;
        }else{
            echo "failed";
        }
    }
}

function shift_in($user_id,$srchDate){
    $con = parent::connect();
    $sel = $con->prepare("SELECT * FROM attendance_records WHERE attendance_records.RecordUser='$user_id' AND attendance_records.RecordTime LIKE '$srchDate%'");
    $sel->execute();
    $timme='-';
    if ($sel->rowCount()>=1) {
        $cnt = 1;
        while ($ft_sel = $sel->fetch(PDO::FETCH_ASSOC)) {
            if ($cnt==1) {
                $timme=substr($ft_sel['RecordTime'], 10,6);
            }
            $cnt++;
        }
    }
    return $timme;
}

function shift_out($user_id,$srchDate){
    $con = parent::connect();
    $sel = $con->prepare("SELECT * FROM attendance_records WHERE attendance_records.RecordUser='$user_id' AND attendance_records.RecordTime LIKE '$srchDate%'");
    $sel->execute();
    $timme='-';
    if ($sel->rowCount()>=1) {
        $cnt = 1;
        while ($ft_sel = $sel->fetch(PDO::FETCH_ASSOC)) {
            if ($cnt==2) {
                $timme=substr($ft_sel['RecordTime'], 10,6);
            }
            $cnt++;
        }
    }
    return $timme;
}

function observationIn($user_id,$date_time){
    $cd = $date_time;
    $con = parent::connect();
    $sel = $con->prepare("SELECT * FROM attendance_users WHERE attendance_users.UserId='$user_id'");
    $sel->execute();
    $ft_sel = $sel->fetch(PDO::FETCH_ASSOC);
    $category = $ft_sel['Class'];
    $timme_hour=(int)substr($date_time, 1,2);
    $hrs_diff = $timme_hour-8;
    (int)$timme_miniutes=substr($cd,4,2);
    if ($timme_hour==7 AND $timme_miniutes>=40) {
        $status = 'On Time';
    }elseif ($timme_hour==7 AND $timme_miniutes<40) {
        $status = 'earlier';
    }elseif ($timme_hour<7) {
        $status = 'Too early';
    }elseif ($timme_hour==8 AND $timme_miniutes>20) {
        switch ($category) {
            case 0:
                $status = 'Too Late';
                break;
            
            default:
                $status = 'Too Late (Depends on teaching schedule)';
                break;
        }
    }elseif ($timme_hour==8 AND $timme_miniutes<=20) {
        switch ($category) {
            case 0:
                $status = 'Late';
                // $status = $timme_hour." - ".$timme_miniutes;
                break;
            
            default:
                $status = 'Late (Depends on teaching schedule)';
                break;
        }
    }elseif ($timme_hour>8) {
        switch ($category) {
            case 0:
                $status = "Too Late (About $hrs_diff hour 's')";
                break;
            
            default:
                $status = "Too Late (About $hrs_diff hour 's' (Depends on teaching schedule)";
                break;
        }
    }
    return $status;
}

function observationOut($user_id,$date_time){
    $con = parent::connect();
    $sel = $con->prepare("SELECT * FROM attendance_users WHERE attendance_users.UserId='$user_id'");
    $sel->execute();
    $ft_sel = $sel->fetch(PDO::FETCH_ASSOC);
    $category = $ft_sel['Class'];
    $timme_hour=(int)substr($date_time, 1,2);
    $hrs_diff = $timme_hour-8;
    $timme_miniutes=(int)substr($date_time, 3,2);
    if ($date_time=='-') {
        $status = 'Not Checked-Out';
    }elseif ($category==0 AND $timme_hour<17) {
        $status = 'Early';
    }elseif ($category==1 AND $timme_hour<20) {
        $status = 'Early';
    }elseif ($category==1 AND $timme_hour==20 AND $timme_miniutes<50) {
        $status = 'Early';
    }else{
        $status = 'On Time';
    }
    return $status;

}

function generalObservation($in_time,$out_time){
    $hour_in = (int)substr($in_time, 1,2);
    $hour_out = (int)substr($out_time, 1,2);
    if ($out_time=='-') {
        $resp = 'No Check-Out.';
    }else{
        $diff = $hour_out-$hour_in;
        $resp = 'Worked <u>'.$diff.'</u> hours.';
    }
    
    return $resp;

}

function searchAttendanceByDateAndCategory($srchDate,$srchCategory,$srchDateTo){

    $begin = (string)$srchDate;
    $begin = new DateTime($begin);
    $end = (string)$srchDateTo;
    $end = new DateTime($end);
     $interval = DateInterval::createFromDateString('1 day');
     // var_dump($interval);
    $period = new DatePeriod($begin, $interval, $end);
    $cnt =1;
    foreach ($period as $dt) {
        $dattte = $dt->format("Y-m-d");
        if ($srchDate=='' OR $srchCategory=='' OR $srchDateTo=='') {
            echo "null";
        }else{
            $arr = [];
            $con = parent::connect();
            $sel = $con->prepare("SELECT * FROM attendance_records,attendance_users WHERE attendance_users.UserId=attendance_records.RecordUser AND attendance_records.RecordTime LIKE '$dattte%' AND attendance_users.Class='$srchCategory' ORDER BY attendance_records.RecordTime");
            $sel->execute();
            if ($sel->rowCount()>=1) {
                
                while ($ft_sel = $sel->fetch(PDO::FETCH_ASSOC)) {
                    $user = $ft_sel['UserId'];
                    if (in_array($user, $arr)) {
                        continue;
                    }else{
                        array_push($arr, $user);
                        
                        $datetime = $ft_sel['RecordTime'];
                        switch ($ft_sel['Class']) {
                            case 0:
                                $category = 'Administration Staff';
                                break;
                            
                            default:
                                $category = 'Teaching Staff';
                                break;
                        }
                     ?>
                        <tr>
                            <td><?=$cnt++?></td> 
                            <td><?=substr($ft_sel['RecordTime'], 0,10)?></td> 
                            <td><?=$ft_sel['Names']?></td> 
                            <td><?=$ft_sel['Position']?></td> 
                            <td><?=$category?></td> -->
                           <td style="font-weight:bold;font-style: italic;"><?=$this->shift_in($user,$dattte);?></td>
                            <td><center><?=$this->observationIn($user,$this->shift_in($user,$dattte))?></center></td>
                            <td style="font-weight:bold;font-style: italic;"><?=$this->shift_out($user,$dattte);?></td>
                            <td><?=$this->observationOut($user,$this->shift_out($user,$dattte))?></td> -->
                            <td style="font-weight: bolder;"><?=$this->generalObservation($this->shift_in($user,$dattte),$this->shift_out($user,$dattte));?></td>
                        </tr>
                        <?php
                    }
                }
            }else{
                continue;
                // echo "<table class='table table-bordered'><tr> <td colspan='9'> <center style='font-weight:bolder'>No data found ...</center> </td> </tr></table>";
            }
        }

    }



}

function missedEmployeesBYDate($srchDate,$srchDatTo,$srchCategory){
    $con = parent::connect();

    $begin = new DateTime($srchDate);
    $end = new DateTime($srchDatTo);

    $interval = DateInterval::createFromDateString('1 day');
    $period = new DatePeriod($begin, $interval, $end);
    ?>
    <table class="table table-bordered" id="tbl_exporttable_to_xls" width="100%" cellspacing="0">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Date</th>
          <th>Position</th>
        </tr>
      </thead>
      <tbody id="resspp">
<?php
    $cnt = 1;
    foreach ($period as $dt) {
        // echo $dt->format("l Y-m-d H:i:s\n");
        $datee = $dt->format("Y-m-d");
        $sel = $con->prepare("SELECT * FROM attendance_users WHERE attendance_users.UserId NOT IN(SELECT attendance_users.UserId FROM attendance_records,attendance_users WHERE
        attendance_users.UserId=attendance_records.RecordUser
        AND attendance_records.RecordTime LIKE '$datee%' AND attendance_users.Class='$srchCategory') AND attendance_users.Class='$srchCategory' ORDER BY attendance_users.Names");
        $sel->execute();
        if ($sel->rowCount()!=0) {    
            while ($ft_sel = $sel->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <tr>
                    <td><?=$cnt++?></td>
                    <td><?=$ft_sel['Names']?></td>
                    <td><?=$datee?></td>
                    <td><?=$ft_sel['Position']?></td>
                </tr>
                <?php
                // $cnt++;
            }      
        }
        ?>
        <tr>
            <td colspan=4><hr></td>
        </tr>
        <?php
    }
    ?>
        </tbody>
     </table>
    <?php  
}


public function saveLeaveRequest($from,$to,$days,$type,$supervisor)     //======================================= Employee requesting to a Leave
{
    $con = parent::connect();
    $user = $_SESSION['utb_att_user_id'];
    $maax_days = $_SESSION['utb_att_max_leave_days'];
    $avial = false;
    if (($days<=$maax_days) OR $type!=0) {
        $sel = $con->prepare("SELECT leaves.LeaveRemainig AS rem FROM leaves WHERE leaves.LeaveEmployee='$user' AND leaves.LeaveStatus IN(0,1) ORDER BY leaves.LeaveId DESC LIMIT 1");
        $sel->execute();
        if ($sel->rowCount()>=1) {
            $ft_sel = $sel->fetch(PDO::FETCH_ASSOC);
            if ($ft_sel['rem']>=$days) {
                $ins = $con->prepare("INSERT INTO leaves(LeaveEmployee,LeaveFrom,LeaveTo,LeaveDays,LeaveRemainig,LeaveType,Supervisor) VALUES(?,?,?,?,?,?,?)");
                $ins->bindValue(1,$user);
                $ins->bindValue(2,$from);
                $ins->bindValue(3,$to);
                $ins->bindValue(4,$days);
                if ($type==1) {
                    $ins->bindValue(5,($ft_sel['rem']-$days));
                }else{
                    $ins->bindValue(5,$ft_sel['rem']);
                }
                $ins->bindValue(6,$type);
                $ins->bindValue(7,$supervisor);
                $ok = $ins->execute();
                if ($ok) {
                    echo "suceess";
                    // echo $sel->rowCount()."   -   One";
                }else{
                    echo "failed1";
                }
            }else{
                echo "so_many";
            }
        }else{
            $ins = $con->prepare("INSERT INTO leaves(LeaveEmployee,LeaveFrom,LeaveTo,LeaveDays,LeaveRemainig,LeaveType,Supervisor) VALUES(?,?,?,?,?,?,?)");
            $ins->bindValue(1,$user);
            $ins->bindValue(2,$from);
            $ins->bindValue(3,$to);
            $ins->bindValue(4,$days);
            if ($type==1) {
                $ins->bindValue(5,($maax_days-($days)));
            }else{
                $ins->bindValue(5,$maax_days);
            }
            $ins->bindValue(6,$type);
            $ins->bindValue(7,$supervisor);
            $ok = $ins->execute();
            if ($ok) {
                echo "suceess";
            }else{
                echo print_r($ins->errorInfo());
            }
        }
    }else{
        echo "too_many";
    }
}


public function ApproveLeave($user, $leave)         //========================================= Approve Leave
{
    $con = parent::connect();
    switch ($user) {
        case 1:                 // ========================  Supervisor
            $upd = $con->prepare("UPDATE leaves SET leaves.StatusSupervisor=1 WHERE leaves.LeaveId='$leave'");
            break;
        case 2:                 // ========================  HR
            $upd = $con->prepare("UPDATE leaves SET leaves.StatusHR=1 WHERE leaves.LeaveId='$leave'");
            break;
        case 3:                 // ========================  DVCPAF
            $upd = $con->prepare("UPDATE leaves SET leaves.StatusDVCPAF=1 WHERE leaves.LeaveId='$leave'");
            break;
        case 4:                 // ========================  DVCA
            $upd = $con->prepare("UPDATE leaves SET leaves.StatusDVCA=1 WHERE leaves.LeaveId='$leave'");
            break;
        case 5:                 // ========================  VC
            $upd = $con->prepare("UPDATE leaves SET leaves.StatusVC=1,leaves.LeaveStatus=1 WHERE leaves.LeaveId='$leave'");
            break;
        
        default:
            // code...
            break;
    }
    $ok = $upd->execute();
    if ($ok) {
        echo "suceess";
    }else{
        echo "fail";
    }
}

public function RejectLeave($user, $leave, $reason)         //========================================= Reject Leave
{
    $con = parent::connect();
    switch ($user) {
        case 1:                 // ========================  Supervisor
            $upd = $con->prepare("UPDATE leaves SET leaves.StatusSupervisor=2,leaves.RejectionReason='$reason' WHERE leaves.LeaveId='$leave'");
            break;
        case 2:                 // ========================  HR
            $upd = $con->prepare("UPDATE leaves SET leaves.StatusHR=2,leaves.RejectionReason='$reason' WHERE leaves.LeaveId='$leave'");
            break;
        case 3:                 // ========================  DVCPAF
            $upd = $con->prepare("UPDATE leaves SET leaves.StatusDVCPAF=2,leaves.RejectionReason='$reason' WHERE leaves.LeaveId='$leave'");
            break;
        case 4:                 // ========================  DVCA
            $upd = $con->prepare("UPDATE leaves SET leaves.StatusDVCA=2,leaves.RejectionReason='$reason' WHERE leaves.LeaveId='$leave'");
            break;
        case 5:                 // ========================  VC
            $upd = $con->prepare("UPDATE leaves SET leaves.StatusVC=2,leaves.LeaveStatus=2,leaves.RejectionReason='$reason' WHERE leaves.LeaveId='$leave'");
            break;
        
        default:
            // code...
            break;
    }
    $ok = $upd->execute();
    if ($ok) {
        echo "suceess";
    }else{
        echo "fail";
    }
}

public function PendingLeave($user, $leave)         //========================================= Pending Leave
{
    $con = parent::connect();
    switch ($user) {
        case 1:                 // ========================  Supervisor
            $upd = $con->prepare("UPDATE leaves SET leaves.StatusSupervisor=0 WHERE leaves.LeaveId='$leave'");
            break;
        case 2:                 // ========================  HR
            $upd = $con->prepare("UPDATE leaves SET leaves.StatusHR=0 WHERE leaves.LeaveId='$leave'");
            break;
        case 3:                 // ========================  DVCPAF
            $upd = $con->prepare("UPDATE leaves SET leaves.StatusDVCPAF=0 WHERE leaves.LeaveId='$leave'");
            break;
        case 4:                 // ========================  DVCA
            $upd = $con->prepare("UPDATE leaves SET leaves.StatusDVCA=0 WHERE leaves.LeaveId='$leave'");
            break;
        case 5:                 // ========================  VC
            $upd = $con->prepare("UPDATE leaves SET leaves.StatusVC=0,leaves.LeaveStatus=0 WHERE leaves.LeaveId='$leave'");
            break;
        
        default:
            // code...
            break;
    }
    $ok = $upd->execute();
    if ($ok) {
        echo "suceess";
    }else{
        echo "fail";
    }
}


public function saveGoal($goalname, $goaldetails)         //========================================= Save Goal
{
    $user = $_SESSION['utb_att_user_id'];
    $con = parent::connect();
    $sel = $con->prepare("SELECT * FROM imihigo WHERE ImihigoName=? AND ImihigoDetails=? AND ImihogoOwner=? AND ImihogoDescendents=? AND ImihigoType=? AND ImihigoStatus=?");
    $sel->bindValue(1,$goalname);
    $sel->bindValue(2,$goaldetails);
    $sel->bindValue(3,$user);
    $sel->bindValue(4,NULL);
    $sel->bindValue(5,0);
    $sel->bindValue(6,0);
    if ($sel->rowCount()<=0) {
        if ($_SESSION['utb_att_position']!='Employee') {
            $ins = $con->prepare("INSERT INTO imihigo(ImihigoName,ImihigoDetails,ImihogoOwner,ImihogoDescendents,ImihigoType,ImihigoStatus) VALUES(?,?,?,?,?,?)");
            $ins->bindValue(1,$goalname);
            $ins->bindValue(2,$goaldetails);
            $ins->bindValue(3,$user);
            $ins->bindValue(4,NULL);
            $ins->bindValue(5,0);
            $ins->bindValue(6,0);
        }else{
            $ins = $con->prepare("INSERT INTO imihigo(ImihigoName,ImihigoDetails,ImihogoOwner,ImihogoDescendents,ImihigoType,ImihigoStatus,UserStatus) VALUES(?,?,?,?,?,?,?)");
            $ins->bindValue(1,$goalname);
            $ins->bindValue(2,$goaldetails);
            $ins->bindValue(3,$user);
            $ins->bindValue(4,NULL);
            $ins->bindValue(5,0);
            $ins->bindValue(6,0);
            $ins->bindValue(7,0);
        }
        // $ok = $ins->execute();
        if ($ins->execute()) {
            echo "success";
        }else{
            echo "failed";
            // print_r($ins->errorInfo());
        }
    }else{
        echo "failed";
    }



}

public function PublishGoal($goal)         //========================================= Publish Goal
{
    $user = $_SESSION['utb_att_user_id'];
    $con = parent::connect();
    $ins = $con->prepare("UPDATE imihigo SET ImihigoStatus=1 WHERE imihigo.ImihigoId='$goal'");
    $ok = $ins->execute();
    if ($ok) {
        echo "success";
    }else{
        echo "failed";
        // print_r($ins->errorInfo());
    }
}

public function UnpublishGoal($goal)         //========================================= Publish Goal
{
    $user = $_SESSION['utb_att_user_id'];
    $con = parent::connect();
    $ins = $con->prepare("UPDATE imihigo SET ImihigoStatus=0 WHERE imihigo.ImihigoId='$goal'");
    $ok = $ins->execute();
    if ($ok) {
        echo "success";
    }else{
        echo "failed";
        // print_r($ins->errorInfo());
    }
}

public function DeleteGoal($goal)         //========================================= Publish Goal
{
    $user = $_SESSION['utb_att_user_id'];
    $con = parent::connect();
    $ins = $con->prepare("UPDATE imihigo SET ImihigoStatus=2 WHERE imihigo.ImihigoId='$goal'");
    $ok = $ins->execute();
    if ($ok) {
        echo "success";
    }else{
        echo "failed";
        // print_r($ins->errorInfo());
    }
}

public function saveGoalNew($Umuhigo, $UmuhigoName, $UmuhigoDetails, $goaldetails)         //========================================= Save Goal
{
    $user = $_SESSION['utb_att_user_id'];
    $con = parent::connect();
    $sel = $con->prepare("SELECT * FROM imihigo WHERE ImihigoName=? AND ImihigoDetails=? AND ImihogoOwner=? AND ImihogoDescendents=? AND ImihigoType=? AND ImihigoStatus=?");
    $sel->bindValue(1,$UmuhigoName);
    $sel->bindValue(2,$UmuhigoDetails);
    $sel->bindValue(3,$user);
    $sel->bindValue(4,$Umuhigo);
    $sel->bindValue(5,1);
    $sel->bindValue(6,0);
    if ($sel->rowCount()<=0) {
        if ($_SESSION['utb_att_position']!='Employee') {
            $ins = $con->prepare("INSERT INTO imihigo(ImihigoName,ImihigoDetails,ImihogoOwner,ImihogoDescendents,ImihigoType,ImihigoStatus) VALUES(?,?,?,?,?,?)");
            $ins->bindValue(1,$UmuhigoName);
            $ins->bindValue(2,$UmuhigoDetails);
            $ins->bindValue(3,$user);
            $ins->bindValue(4,$Umuhigo);
            $ins->bindValue(5,1);
            $ins->bindValue(6,0);
        }else{
            $ins = $con->prepare("INSERT INTO imihigo(ImihigoName,ImihigoDetails,ImihogoOwner,ImihogoDescendents,ImihigoType,ImihigoStatus,UserStatus) VALUES(?,?,?,?,?,?,?)");
            $ins->bindValue(1,$UmuhigoName);
            $ins->bindValue(2,$UmuhigoDetails);
            $ins->bindValue(3,$user);
            $ins->bindValue(4,$Umuhigo);
            $ins->bindValue(5,1);
            $ins->bindValue(6,0);
            $ins->bindValue(7,0);
        }

        // $ok = $ins->execute();
        if ($ins->execute()) {
            echo "success";
        }else{
            echo "failed";
            // print_r($ins->errorInfo());
        }
}else{
    echo "failed";
}
}
public function assignUmuhigo($goal, $toAssign)         //========================================= Assign Goal
{
    $user = $_SESSION['utb_att_user_id'];
    $con = parent::connect();
    $upd = $con->prepare("UPDATE imihigo SET ImihigoAssignedTo='$toAssign' WHERE ImihigoId='$goal'");
    $ok = $upd->execute();
    if ($ok) {
        echo "success";
    }else{
        echo "failed";
        // print_r($ins->errorInfo());
    }
}

public function saveAchievement($goal,$achievement)
{
    $con = parent::connect();
    $sel = $con->prepare("SELECT * FROM imihigo_achievements WHERE imihigo_achievements.ImihigoId='$goal' AND imihigo_achievements.AchievementDetails='$achievement'");
    $sel->execute();
    if ($sel->rowCount()==0) {
        $ins = $con->prepare("INSERT INTO imihigo_achievements(ImihigoId,AchievementDetails) VALUES(?,?)");
        $ins->bindValue(1,$goal);
        $ins->bindValue(2,$achievement);
        $ok = $ins->execute();
        if ($ok) {
            echo "success";
        }else{
            echo "failed";
        }
    }
}

public function saveLeaveRange($range)
{
    $user = $_SESSION['utb_att_user_id'];
    $con = parent::connect();
    $ins = $con->prepare("INSERT INTO leave_range(RangeDetails,UserId) VALUES('$range','$user')");
    $ok = $ins->execute();
    if ($ok) {
        echo "success";
    }else{
        echo "failed";
    }
}





























}

$MainOpoerations = new MainOpoerations();

if (isset($_GET['login'])) {
    $MainOpoerations->login($_GET['email'],$_GET['password']);
}elseif (isset($_GET['scan_card'])) {
    $MainOpoerations->scan_card($_GET['content']);
}elseif (isset($_GET['print_card'])) {
    $MainOpoerations->print_card($_GET['contenttt']);
}elseif (isset($_GET['print_qr'])) {
    $MainOpoerations->print_qr($_GET['contenttt']);
}elseif (isset($_GET['savelfid'])) {
    $MainOpoerations->saveLfid($_GET['userCode'],$_GET['lfid']);
}elseif (isset($_GET['searchAtt'])) {
    $MainOpoerations->searchAttendanceByDateAndCategory($_GET['srchDate'],$_GET['srchCategory'],$_GET['srchDateTo']);
}elseif (isset($_GET['missedEmployeesBYDate'])) {
    $MainOpoerations->missedEmployeesBYDate($_GET['srchDate'],$_GET['srchDateTo'],$_GET['srchCategory']);
}elseif (isset($_GET['saveLeaveRequest'])) {
    $MainOpoerations->saveLeaveRequest($_GET['dateFrom'],$_GET['dateTo'],$_GET['days'],$_GET['leaveType'],$_GET['supervisor']);
}elseif (isset($_GET['ApproveLeave'])) {
    $MainOpoerations->ApproveLeave($_GET['user'],$_GET['leave']);
}elseif (isset($_GET['RejectLeave'])) {
    $MainOpoerations->RejectLeave($_GET['user'],$_GET['leave'],$_GET['reason']);
}elseif (isset($_GET['PendingLeave'])) {
    $MainOpoerations->PendingLeave($_GET['user'],$_GET['leave']);
}elseif (isset($_GET['saveGoal'])) {
    $MainOpoerations->saveGoal($_GET['goalname'],$_GET['goaldetails']);
}elseif (isset($_GET['PublishGoal'])) {
    $MainOpoerations->PublishGoal($_GET['goal']);
}elseif (isset($_GET['UnpublishGoal'])) {
    $MainOpoerations->UnpublishGoal($_GET['goal']);
}elseif (isset($_GET['DeleteGoal'])) {
    $MainOpoerations->DeleteGoal($_GET['goal']);
}elseif (isset($_GET['saveGoalNew'])) {
    $MainOpoerations->saveGoalNew($_GET['Umuhigo'],$_GET['UmuhigoName'],$_GET['UmuhigoDetails'],$_GET['UmuhigoOwner']);
}elseif (isset($_GET['assignUmuhigo'])) {
    $MainOpoerations->assignUmuhigo($_GET['goal'],$_GET['toAssign']);
}elseif (isset($_GET['saveAchievement'])) {
    $MainOpoerations->saveAchievement($_GET['myGoals'],$_GET['goalAchievement']);
}elseif (isset($_GET['saveLeaveRange'])) {
    $MainOpoerations->saveLeaveRange($_GET['range']);
}


?>