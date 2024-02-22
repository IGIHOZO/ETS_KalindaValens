      <?php
    // session_start();

    // $dbname = 'mpjusdko_seveeen_web';
    // $user = 'mpjusdko';
    // $pass = 'z0HpWFx1%@48';


    $dbname = 'seveeen_web';
    $user = 'root';
    $pass = '';



    $con = new PDO("mysql:host=localhost;dbname=$dbname", $user, $pass);
    
class DbConnect
{
    // private $host='localhost';
    // private $dbName = 'mpjusdko_seveeen_web';
    // private $user = 'mpjusdko';
    // private $pass = 'z0HpWFx1%@48';



    private $host='localhost';
    private $dbName = 'seveeen_web';
    private $user = 'root';
    private $pass = '';
    public $conn; 


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

/**
 * ============================= Main View
 */
class MainView extends DbConnect
{
    
    function todays_attendance(){
        $con = parent::connect();
        $sel = $con->prepare("SELECT * FROM attendance_records WHERE CAST(attendance_records.RecordTime AS DATE) = CAST( curdate() AS DATE) ORDER BY attendance_records.RecordTime DESC");
        $sel->execute();
        $cnt = 0;
        if ($sel->rowCount()>=1) {
            $arr = [];
            while ($ft_sel = $sel->fetch(PDO::FETCH_ASSOC)) {
                $user = $ft_sel['RecordUser'];
                if (in_array($user, $arr)) {
                    continue;
                }else{
                    array_push($arr, $user);
                    $cnt++;
                }
            }
        }
        // $ft_sel = $sel->fetch(PDO::FETCH_ASSOC);
        return $cnt;
    }

    function todays_right_arrival(){
        $con = parent::connect();
        $sel = $con->prepare("SELECT * FROM attendance_records WHERE CAST(attendance_records.RecordTime AS DATE) = CAST( curdate() AS DATE) AND substr(attendance_records.RecordTime, 12,2)<'08' ORDER BY attendance_records.RecordTime DESC");
        $sel->execute();
        $cnt = 0;
        if ($sel->rowCount()>=1) {
            $arr = [];
            while ($ft_sel = $sel->fetch(PDO::FETCH_ASSOC)) {
                $user = $ft_sel['RecordUser'];
                if (in_array($user, $arr)) {
                    continue;
                }else{
                    array_push($arr, $user);
                    $cnt++;
                }
            }
        }
        // $ft_sel = $sel->fetch(PDO::FETCH_ASSOC);
        return $cnt;
    }

    function todays_lates(){
        $con = parent::connect();
        $sel = $con->prepare("SELECT * FROM attendance_records WHERE CAST(attendance_records.RecordTime AS DATE) = CAST( curdate() AS DATE) AND substr(attendance_records.RecordTime, 12,2)>='08' ORDER BY attendance_records.RecordTime DESC");
        $sel->execute();
        $cnt = 0;
        if ($sel->rowCount()>=1) {
            $arr = [];
            while ($ft_sel = $sel->fetch(PDO::FETCH_ASSOC)) {
                $user = $ft_sel['RecordUser'];
                if (in_array($user, $arr)) {
                    continue;
                }else{
                    array_push($arr, $user);
                    $cnt++;
                }
            }
        }
        // $ft_sel = $sel->fetch(PDO::FETCH_ASSOC);
        return $cnt;
    }
    function all_cards(){
        $con = parent::connect();
        $sel = $con->prepare("SELECT * FROM ets_workers WHERE ets_workers.worker_status=1");
        $sel->execute();
        while ($ft_sel = $sel->fetch(PDO::FETCH_ASSOC)) {
            $user_id = $ft_sel['worker_id'];
            $user_photo = $ft_sel['worker_photo'];
?> 
<center>
    <table>
        <tr>
            <td><button class="btn btn-primary" id="download<?=$user_photo?>" onclick="return downloadimage('imageDIV<?=$user_photo?>','<?=$ft_sel['worker_fname']?>','card')">Download Card</button><hr></td>
            <!-- <td><button class="btn btn-secondary" onclick="return downloadimage('qrImg<?=$user_photo?>','<?=$ft_sel['worker_fname']?>','qqr');">Download QR</button><hr></td> -->
        </tr>
        <tr>
            <td>
                <div style="display: none;" id="previewImage<?=$user_photo?>"></div>
                <div id="imageDIV<?=$user_photo?>" style="width: 500px;background-image: url('img/card.jpg');background-repeat: no-repeat;background-size: 100%;float: left;">
                    <center>
                        <img src="<?=$user_photo?>" style="width: 188px;height: 200px;margin-top: 41.5%;margin-left: -20px;">
                    </center>
                    <h3 style="margin-top: 13%;font-weight: bold;color: #fff;text-align: left;margin-left: 24%;font-size: 20px;"><?=$ft_sel['worker_fname']?></h3>
                    <h3 style="margin-top: 4%;font-weight: bold;color: #fff;text-align: left;margin-left: 30%;font-size: 20px"><?=$ft_sel['worker_phone']?></h3>
                    <h3 style="margin-top: 3%;font-weight: bold;color: #fff;text-align: left;margin-left: 24%;font-size: 20px">0<?=$ft_sel['worker_phone']?></h3>
                    <h3 style="margin-top: 3%;font-weight: bold;color: #fff;text-align: left;margin-left: 24%;font-size: 20px"><?=$ft_sel['worker_phone']?></h3>
                    <h3 style="margin-top: 3%;font-weight: bold;color: #fff;text-align: left;margin-left: 24%;font-size: 20px">UTB-<?=$filled_int = sprintf("%04d", $ft_sel['worker_id']) ?></h3>
                </div>
            </td>
            <td><?php 

                $vv = "https://utb.ac.rw/attendance/scan.php?content=".$ft_sel['worker_id'];
            ?>
                <div id="qrImg<?=$user_photo?>" style="background-color: white;width: 500px;background-repeat: no-repeat;background-size: 100%;float: right;">
                    <img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=<?=$vv?>&choe=UTF-8" filename="<?=$ft_sel['worker_fname']?>.png" basename="<?=$ft_sel['worker_fname']?>" name='<?=$ft_sel['worker_fname']?>' id='<?=$ft_sel['worker_fname']?>'/>
                        <!-- <img src="img/users/<?=$user_photo?>.jpg" style="width: 188px;height: 200px;margin-top: 41.5%;margin-left: -20px;"> -->

                </div>
            </td>

        </tr>
    </table>
</center>
<?php
        }
    }

    function todays_attendance_detailed(){
        $con = parent::connect();
        $sel = $con->prepare("SELECT substr(attendance_records.RecordTime, 12,2)-'08' AS diff,attendance_records.*,attendance_users.* FROM attendance_records,attendance_users WHERE attendance_records.RecordUser=attendance_users.UserId AND CAST(attendance_records.RecordTime AS DATE) = CAST( curdate() AS DATE) ORDER BY attendance_records.RecordTime DESC");
        $sel->execute();
        if ($sel->rowCount()>=1) {
            $cnt = 1;
            while ($ft_sel = $sel->fetch(PDO::FETCH_ASSOC)) {
                switch (true) {
                    case $ft_sel['diff']<0:
                        $diff = '+';
                        $obs = 'Excellent';
                        break;
                    case $ft_sel['diff']<=1:
                        $diff = '< 1Hrs';
                        $obs = 'Late';
                        break;
                    case $ft_sel['diff']<=4:
                        $diff = ">".$ft_sel['diff'].'Hrs';
                        $obs = 'Too Late';
                        break;    
                    default:
                        $diff = $ft_sel['diff'].'Hrs';
                        $obs = 'Not Worked';
                        break;
                }
                ?>
                <tr>
                    <td><?=$cnt?></td>
                    <td><?=$ft_sel['Names']?></td>
                    <td><?=$ft_sel['Position']?></td>
<!--                     <td><?=$ft_sel['']?></td> -->
                    <td><?=substr($ft_sel['RecordTime'], 11,5)?></td>
                    <td><?=$diff?></td>
                    <td><?=$obs?></td>
                </tr>
                <?php
                $cnt++;
            }
        }
    }

public function MaxmumAllowedLeaves()
{
    $con = parent::connect();
    $sel = $con->prepare("SELECT LeaveValues FROM max_leaves");
    $sel->execute();
    $ft_sel = $sel->fetch(PDO::FETCH_ASSOC);
    return $ft_sel['LeaveValues'];
}
public function MyMaxmumAllowedLeaves()
{
    $user = $_SESSION['utb_att_user_id'];
    $con = parent::connect();
    $sel = $con->prepare("SELECT leaves.LeaveRemainig AS remm FROM leaves WHERE leaves.LeaveEmployee='$user' AND leaves.LeaveStatus IN(0,1)  ORDER BY leaves.LeaveId DESC LIMIT 1");
    $sel->execute();
    if ($sel->rowCount()>=1) {
        $ft_sel = $sel->fetch(PDO::FETCH_ASSOC);
        $res =  $ft_sel['remm'];
    }else{
        $res = $this->MaxmumAllowedLeaves();
    }
    return $res;
}

public function StatusFunc($data)
{
    switch ($data) {
        case 0:
            $res = "Pending";
            break;
        case 1:
            $res = "Approved";
            break;
        case 2:
            $res = "Rejected";
            break;
        
        default:
            $res = $data;
            break;
    }
    return $res;
}

public function MyAllLeaves()     //=================================================== Employee requesting to a Leave
  {
      $con = parent::connect();
      $user = $_SESSION['utb_att_user_id'];
      switch ($_SESSION['utb_att_position']) {
          case 'supervisor':
              $sel = $con->prepare("SELECT * FROM leaves WHERE leaves.LeaveEmployee='$user' AND leaves.StatusSupervisor=0 ORDER BY leaves.LeaveId DESC");
              break;
          case 'HR':
              $sel = $con->prepare("SELECT * FROM leaves WHERE leaves.LeaveEmployee='$user' AND leaves.StatusSupervisor=1 AND leaves.StatusHR=0 ORDER BY leaves.LeaveId DESC");
              break;
          case 'DVCPAF':
              $sel = $con->prepare("SELECT * FROM leaves WHERE leaves.LeaveEmployee='$user' AND leaves.StatusHR=1 AND leaves.StatusDVCPAF=0 ORDER BY leaves.LeaveId DESC");
              break;
          case 'DVCA':
              $sel = $con->prepare("SELECT * FROM leaves WHERE leaves.LeaveEmployee='$user' AND leaves.StatusDVCPAF=1 AND leaves.StatusDVCA=0 ORDER BY leaves.LeaveId DESC");
              break;
          case 'VC':
              $sel = $con->prepare("SELECT * FROM leaves WHERE leaves.LeaveEmployee='$user' AND leaves.StatusDVCA=1 AND leaves.VC=0 ORDER BY leaves.LeaveId DESC");
              break;
          
          default:
              $sel = $con->prepare("SELECT leave_types.LeaveDays AS allowed,leave_types.LeaveName AS LeaveName,leaves.LeaveId AS LeaveId,leaves.RejectionReason AS RejectionReason,leaves.LeaveFrom AS LeaveFrom,leaves.LeaveTo AS LeaveTo,leaves.LeaveDays AS LeaveDays,leaves.LeaveRemainig AS LeaveRemainig,leaves.StatusSupervisor AS StatusSupervisor,leaves.StatusHR AS StatusHR,leaves.StatusDVCPAF AS StatusDVCPAF,leaves.StatusDVCA AS StatusDVCA,leaves.StatusVC AS StatusVC,leaves.LeaveDate AS LeaveDate,leaves.Supervisor AS Supervisor FROM leaves,leave_types WHERE leaves.LeaveType=leave_types.TypeId AND leaves.LeaveEmployee='$user' ORDER BY leaves.LeaveId DESC");
              break;
      }
      
      $sel->execute();
      if ($sel->rowCount()>=1) {
        $cnt = 0;
          while ($ft_sel = $sel->fetch(PDO::FETCH_ASSOC)) {
            if ($ft_sel['RejectionReason']==NULL) {
                $reason = '-';
            }else{
                $reason = $ft_sel['RejectionReason'];
            }
                $arr['found'] = 1;
                $arr['res'][$cnt]['LeaveId'] = $ft_sel['LeaveId'];
                $arr['res'][$cnt]['LeaveFrom'] = $ft_sel['LeaveFrom'];
                $arr['res'][$cnt]['LeaveTo'] = $ft_sel['LeaveTo'];
                $arr['res'][$cnt]['LeaveDays'] = $ft_sel['LeaveDays'];
                $arr['res'][$cnt]['LeaveName'] = $ft_sel['LeaveName'];
                $arr['res'][$cnt]['LeaveRemainig'] = $ft_sel['LeaveRemainig'];
                $arr['res'][$cnt]['StatusSupervisor'] = $this->StatusFunc($ft_sel['StatusSupervisor']);
                $arr['res'][$cnt]['StatusHR'] = $this->StatusFunc($ft_sel['StatusHR']);
                $arr['res'][$cnt]['StatusDVCPAF'] = $this->StatusFunc($ft_sel['StatusDVCPAF']);
                $arr['res'][$cnt]['StatusDVCA'] = $this->StatusFunc($ft_sel['StatusDVCA']);
                $arr['res'][$cnt]['StatusVC'] = $this->StatusFunc($ft_sel['StatusVC']);
                $arr['res'][$cnt]['LeaveDate'] = substr($ft_sel['LeaveDate'], 0, 10);
                // $arr['res'][$cnt]['LeaveDate'] = substr($ft_sel['LeaveDate'], 0, 10);
                $arr['res'][$cnt]['Supervisor'] = $ft_sel['Supervisor'];
                $arr['res'][$cnt]['allowed'] = $ft_sel['allowed'];
                $arr['res'][$cnt]['RejectionReason'] = ucfirst(strtolower($reason));
                $cnt++;
          }
      }else{
        $arr['found'] = $user;
      }
      print(json_encode($arr));
  }  

public function AllAvailableLeavesPending()     //=================================================== All Available Leaves  == Pending
  {
      $con = parent::connect();
      $user = $_SESSION['utb_att_user_id'];

      switch ($_SESSION['utb_att_position']) {
          case 'supervisor':
                $sel = $con->prepare("SELECT users.Names AS superv,leaves.RejectionReason AS RejectionReason,leave_types.LeaveName AS LvName,leave_types.LeaveDays AS allowed,leaves.LeaveId AS LeaveId,attendance_users.Names AS nm,attendance_users.Position AS pst,leaves.LeaveFrom AS lv_f,leaves.LeaveTo AS lv_t,leaves.LeaveDays AS lv_dys,leaves.LeaveRemainig AS lv_rmng,leaves.LeaveDate AS lv_dte,leaves.StatusSupervisor AS st_sup,leaves.StatusHR AS st_hr,leaves.StatusDVCPAF AS st_dvcpaf,leaves.StatusDVCA AS st_dvca,leaves.StatusVC AS st_vc FROM leaves,attendance_users,leave_types,users WHERE leaves.Supervisor=users.UserId AND leaves.LeaveType=leave_types.TypeId AND leaves.LeaveEmployee=attendance_users.UserId AND leaves.StatusSupervisor=0 AND leaves.Supervisor='$user' ORDER BY leaves.LeaveId DESC");
              break;
          case 'HR':
                $sel = $con->prepare("SELECT users.Names AS superv,leaves.RejectionReason AS RejectionReason,leave_types.LeaveName AS LvName,leave_types.LeaveDays AS allowed,leaves.LeaveId AS LeaveId,attendance_users.Names AS nm,attendance_users.Position AS pst,leaves.LeaveFrom AS lv_f,leaves.LeaveTo AS lv_t,leaves.LeaveDays AS lv_dys,leaves.LeaveRemainig AS lv_rmng,leaves.LeaveDate AS lv_dte,leaves.StatusSupervisor AS st_sup,leaves.StatusHR AS st_hr,leaves.StatusDVCPAF AS st_dvcpaf,leaves.StatusDVCA AS st_dvca,leaves.StatusVC AS st_vc FROM leaves,attendance_users,leave_types,users WHERE leaves.Supervisor=users.UserId AND leaves.LeaveType=leave_types.TypeId AND leaves.LeaveEmployee=attendance_users.UserId AND leaves.LeaveStatus=0 AND leaves.StatusSupervisor=1 AND leaves.StatusHR=0 ORDER BY leaves.LeaveId DESC");
              break;
          case 'DVCPAF':
                $sel = $con->prepare("SELECT users.Names AS superv,leaves.RejectionReason AS RejectionReason,leave_types.LeaveName AS LvName,leave_types.LeaveDays AS allowed,leaves.LeaveId AS LeaveId,attendance_users.Names AS nm,attendance_users.Position AS pst,leaves.LeaveFrom AS lv_f,leaves.LeaveTo AS lv_t,leaves.LeaveDays AS lv_dys,leaves.LeaveRemainig AS lv_rmng,leaves.LeaveDate AS lv_dte,leaves.StatusSupervisor AS st_sup,leaves.StatusHR AS st_hr,leaves.StatusDVCPAF AS st_dvcpaf,leaves.StatusDVCA AS st_dvca,leaves.StatusVC AS st_vc FROM leaves,attendance_users,leave_types,users WHERE leaves.Supervisor=users.UserId AND leaves.LeaveType=leave_types.TypeId AND leaves.LeaveEmployee=attendance_users.UserId AND leaves.LeaveStatus=0 AND leaves.StatusHR=1 AND leaves.StatusDVCPAF=0 ORDER BY leaves.LeaveId DESC");
              break;
          case 'DVCA':
                $sel = $con->prepare("SELECT users.Names AS superv,leaves.RejectionReason AS RejectionReason,leave_types.LeaveName AS LvName,leave_types.LeaveDays AS allowed,leaves.LeaveId AS LeaveId,attendance_users.Names AS nm,attendance_users.Position AS pst,leaves.LeaveFrom AS lv_f,leaves.LeaveTo AS lv_t,leaves.LeaveDays AS lv_dys,leaves.LeaveRemainig AS lv_rmng,leaves.LeaveDate AS lv_dte,leaves.StatusSupervisor AS st_sup,leaves.StatusHR AS st_hr,leaves.StatusDVCPAF AS st_dvcpaf,leaves.StatusDVCA AS st_dvca,leaves.StatusVC AS st_vc FROM leaves,attendance_users,leave_types,users WHERE leaves.Supervisor=users.UserId AND leaves.LeaveType=leave_types.TypeId AND leaves.LeaveEmployee=attendance_users.UserId AND leaves.LeaveStatus=0 AND leaves.StatusDVCPAF=1 AND leaves.StatusDVCA=0 ORDER BY leaves.LeaveId DESC");
              break;
          case 'VC':
                $sel = $con->prepare("SELECT users.Names AS superv,leaves.RejectionReason AS RejectionReason,leave_types.LeaveName AS LvName,leave_types.LeaveDays AS allowed,leaves.LeaveId AS LeaveId,attendance_users.Names AS nm,attendance_users.Position AS pst,leaves.LeaveFrom AS lv_f,leaves.LeaveTo AS lv_t,leaves.LeaveDays AS lv_dys,leaves.LeaveRemainig AS lv_rmng,leaves.LeaveDate AS lv_dte,leaves.StatusSupervisor AS st_sup,leaves.StatusHR AS st_hr,leaves.StatusDVCPAF AS st_dvcpaf,leaves.StatusDVCA AS st_dvca,leaves.StatusVC AS st_vc FROM leaves,attendance_users,leave_types,users WHERE leaves.Supervisor=users.UserId AND leaves.LeaveType=leave_types.TypeId AND leaves.LeaveEmployee=attendance_users.UserId AND leaves.LeaveStatus=0 AND leaves.StatusDVCA=1 AND leaves.StatusVC=0 ORDER BY leaves.LeaveId DESC");
              break;
          
          default:
                $sel = $con->prepare("SELECT users.Names AS superv,leaves.RejectionReason AS RejectionReason,leave_types.LeaveName AS LvName,leave_types.LeaveDays AS allowed,leaves.LeaveId AS LeaveId,attendance_users.Names AS nm,attendance_users.Position AS pst,leaves.LeaveFrom AS lv_f,leaves.LeaveTo AS lv_t,leaves.LeaveDays AS lv_dys,leaves.LeaveRemainig AS lv_rmng,leaves.LeaveDate AS lv_dte,leaves.StatusSupervisor AS st_sup,leaves.StatusHR AS st_hr,leaves.StatusDVCPAF AS st_dvcpaf,leaves.StatusDVCA AS st_dvca,leaves.StatusVC AS st_vc FROM leaves,attendance_users,leave_types,users WHERE leaves.Supervisor=users.UserId AND leaves.LeaveType=leave_types.TypeId AND leaves.LeaveEmployee=attendance_users.UserId AND leaves.LeaveStatus=0 AND leaves.StatusSupervisor NOT IN(0,1,2) ORDER BY leaves.LeaveId DESC");
              break;
      }

      
      $sel->execute();
      if ($sel->rowCount()>=1) {
        $cnt = 0;
          while ($ft_sel = $sel->fetch(PDO::FETCH_ASSOC)) {
            if ($ft_sel['RejectionReason']==NULL) {
                $reason = '-';
            }else{
                $reason = $ft_sel['RejectionReason'];
            }
                $arr['found'] = 1;
                $arr['res'][$cnt]['LeaveId'] = $ft_sel['LeaveId'];
                $arr['res'][$cnt]['LeaveFrom'] = $ft_sel['lv_f'];
                $arr['res'][$cnt]['LeaveTo'] = $ft_sel['lv_t'];
                $arr['res'][$cnt]['LeaveDays'] = $ft_sel['lv_dys'];
                $arr['res'][$cnt]['LeaveRemainig'] = $ft_sel['lv_rmng'];
                $arr['res'][$cnt]['StatusSupervisor'] = $this->StatusFunc($ft_sel['st_sup']);
                $arr['res'][$cnt]['StatusHR'] = $this->StatusFunc($ft_sel['st_hr']);
                $arr['res'][$cnt]['StatusDVCPAF'] = $this->StatusFunc($ft_sel['st_dvcpaf']);
                $arr['res'][$cnt]['StatusDVCA'] = $this->StatusFunc($ft_sel['st_dvca']);
                $arr['res'][$cnt]['StatusVC'] = $this->StatusFunc($ft_sel['st_vc']);
                $arr['res'][$cnt]['LeaveDate'] = substr($ft_sel['lv_dte'], 0, 10);
                $arr['res'][$cnt]['Names'] = $ft_sel['nm'];
                $arr['res'][$cnt]['Post'] = $ft_sel['pst'];
                $arr['res'][$cnt]['Supervisor'] = $ft_sel['superv'];
                $arr['res'][$cnt]['Reason'] = $ft_sel['LvName'];
                $arr['res'][$cnt]['allowed'] = $ft_sel['allowed'];
                $arr['res'][$cnt]['RejectionReason'] = ucfirst(strtolower($reason));
                $cnt++;
          }
      }else{
        $arr['found'] = 0;
      }
      print(json_encode($arr));
  } 

public function AllAvailableLeavesApproved()     //=================================================== All Available Leaves  == Approved
  {
      $con = parent::connect();
      $user = $_SESSION['utb_att_user_id'];
      switch ($_SESSION['utb_att_position']) {
          case 'supervisor':
                $sel = $con->prepare("SELECT users.Names AS superv,leaves.RejectionReason AS RejectionReason,leave_types.LeaveName AS LvName,leave_types.LeaveDays AS allowed,leaves.LeaveId AS LeaveId,attendance_users.Names AS nm,attendance_users.Position AS pst,leaves.LeaveFrom AS lv_f,leaves.LeaveTo AS lv_t,leaves.LeaveDays AS lv_dys,leaves.LeaveRemainig AS lv_rmng,leaves.LeaveDate AS lv_dte,leaves.StatusSupervisor AS st_sup,leaves.StatusHR AS st_hr,leaves.StatusDVCPAF AS st_dvcpaf,leaves.StatusDVCA AS st_dvca,leaves.StatusVC AS st_vc FROM leaves,attendance_users,leave_types,users WHERE leaves.Supervisor=users.UserId AND leaves.LeaveType=leave_types.TypeId AND leaves.LeaveEmployee=attendance_users.UserId AND leaves.StatusSupervisor=1 AND leaves.Supervisor='$user' ORDER BY leaves.LeaveId DESC");
              break;
          case 'HR':
                $sel = $con->prepare("SELECT users.Names AS superv,leaves.RejectionReason AS RejectionReason,leave_types.LeaveName AS LvName,leave_types.LeaveDays AS allowed,leaves.LeaveId AS LeaveId,attendance_users.Names AS nm,attendance_users.Position AS pst,leaves.LeaveFrom AS lv_f,leaves.LeaveTo AS lv_t,leaves.LeaveDays AS lv_dys,leaves.LeaveRemainig AS lv_rmng,leaves.LeaveDate AS lv_dte,leaves.StatusSupervisor AS st_sup,leaves.StatusHR AS st_hr,leaves.StatusDVCPAF AS st_dvcpaf,leaves.StatusDVCA AS st_dvca,leaves.StatusVC AS st_vc FROM leaves,attendance_users,leave_types,users WHERE leaves.Supervisor=users.UserId AND leaves.LeaveType=leave_types.TypeId AND leaves.LeaveEmployee=attendance_users.UserId AND leaves.StatusHR=1 ORDER BY leaves.LeaveId DESC");
              break;
          case 'DVCPAF':
                $sel = $con->prepare("SELECT users.Names AS superv,leaves.RejectionReason AS RejectionReason,leave_types.LeaveName AS LvName,leave_types.LeaveDays AS allowed,leaves.LeaveId AS LeaveId,attendance_users.Names AS nm,attendance_users.Position AS pst,leaves.LeaveFrom AS lv_f,leaves.LeaveTo AS lv_t,leaves.LeaveDays AS lv_dys,leaves.LeaveRemainig AS lv_rmng,leaves.LeaveDate AS lv_dte,leaves.StatusSupervisor AS st_sup,leaves.StatusHR AS st_hr,leaves.StatusDVCPAF AS st_dvcpaf,leaves.StatusDVCA AS st_dvca,leaves.StatusVC AS st_vc FROM leaves,attendance_users,leave_types,users WHERE leaves.Supervisor=users.UserId AND leaves.LeaveType=leave_types.TypeId AND leaves.LeaveEmployee=attendance_users.UserId AND leaves.StatusDVCPAF=1 ORDER BY leaves.LeaveId DESC");
              break;
          case 'DVCA':
                $sel = $con->prepare("SELECT users.Names AS superv,leaves.RejectionReason AS RejectionReason,leave_types.LeaveName AS LvName,leave_types.LeaveDays AS allowed,leaves.LeaveId AS LeaveId,attendance_users.Names AS nm,attendance_users.Position AS pst,leaves.LeaveFrom AS lv_f,leaves.LeaveTo AS lv_t,leaves.LeaveDays AS lv_dys,leaves.LeaveRemainig AS lv_rmng,leaves.LeaveDate AS lv_dte,leaves.StatusSupervisor AS st_sup,leaves.StatusHR AS st_hr,leaves.StatusDVCPAF AS st_dvcpaf,leaves.StatusDVCA AS st_dvca,leaves.StatusVC AS st_vc FROM leaves,attendance_users,leave_types,users WHERE leaves.Supervisor=users.UserId AND leaves.LeaveType=leave_types.TypeId AND leaves.LeaveEmployee=attendance_users.UserId AND leaves.StatusDVCA=1 ORDER BY leaves.LeaveId DESC");
              break;
          case 'VC':
                $sel = $con->prepare("SELECT users.Names AS superv,leaves.RejectionReason AS RejectionReason,leave_types.LeaveName AS LvName,leave_types.LeaveDays AS allowed,leaves.LeaveId AS LeaveId,attendance_users.Names AS nm,attendance_users.Position AS pst,leaves.LeaveFrom AS lv_f,leaves.LeaveTo AS lv_t,leaves.LeaveDays AS lv_dys,leaves.LeaveRemainig AS lv_rmng,leaves.LeaveDate AS lv_dte,leaves.StatusSupervisor AS st_sup,leaves.StatusHR AS st_hr,leaves.StatusDVCPAF AS st_dvcpaf,leaves.StatusDVCA AS st_dvca,leaves.StatusVC AS st_vc FROM leaves,attendance_users,leave_types,users WHERE leaves.Supervisor=users.UserId AND leaves.LeaveType=leave_types.TypeId AND leaves.LeaveEmployee=attendance_users.UserId AND leaves.LeaveStatus=1 AND leaves.StatusVC=1 ORDER BY leaves.LeaveId DESC");
              break;
          
          default:
                $sel = $con->prepare("SELECT users.Names AS superv,leaves.RejectionReason AS RejectionReason,leave_types.LeaveName AS LvName,leave_types.LeaveDays AS allowed,leaves.LeaveId AS LeaveId,attendance_users.Names AS nm,attendance_users.Position AS pst,leaves.LeaveFrom AS lv_f,leaves.LeaveTo AS lv_t,leaves.LeaveDays AS lv_dys,leaves.LeaveRemainig AS lv_rmng,leaves.LeaveDate AS lv_dte,leaves.StatusSupervisor AS st_sup,leaves.StatusHR AS st_hr,leaves.StatusDVCPAF AS st_dvcpaf,leaves.StatusDVCA AS st_dvca,leaves.StatusVC AS st_vc FROM leaves,attendance_users,leave_types,users WHERE leaves.Supervisor=users.UserId AND leaves.LeaveType=leave_types.TypeId AND leaves.LeaveEmployee=attendance_users.UserId AND leaves.StatusSupervisor NOT IN(0,1,2) ORDER BY leaves.LeaveId DESC");
              break;
      }


      $sel->execute();
      if ($sel->rowCount()>=1) {
        $cnt = 0;
          while ($ft_sel = $sel->fetch(PDO::FETCH_ASSOC)) {
            if ($ft_sel['RejectionReason']==NULL) {
                $reason = '-';
            }else{
                $reason = $ft_sel['RejectionReason'];
            }
                $arr['found'] = 1;
                $arr['res'][$cnt]['LeaveId'] = $ft_sel['LeaveId'];
                $arr['res'][$cnt]['LeaveFrom'] = $ft_sel['lv_f'];
                $arr['res'][$cnt]['LeaveTo'] = $ft_sel['lv_t'];
                $arr['res'][$cnt]['LeaveDays'] = $ft_sel['lv_dys'];
                $arr['res'][$cnt]['LeaveRemainig'] = $ft_sel['lv_rmng'];
                $arr['res'][$cnt]['StatusSupervisor'] = $this->StatusFunc($ft_sel['st_sup']);
                $arr['res'][$cnt]['StatusHR'] = $this->StatusFunc($ft_sel['st_hr']);
                $arr['res'][$cnt]['StatusDVCPAF'] = $this->StatusFunc($ft_sel['st_dvcpaf']);
                $arr['res'][$cnt]['StatusDVCA'] = $this->StatusFunc($ft_sel['st_dvca']);
                $arr['res'][$cnt]['StatusVC'] = $this->StatusFunc($ft_sel['st_vc']);
                $arr['res'][$cnt]['LeaveDate'] = substr($ft_sel['lv_dte'], 0, 10);
                $arr['res'][$cnt]['Names'] = $ft_sel['nm'];
                $arr['res'][$cnt]['Post'] = $ft_sel['pst'];
                $arr['res'][$cnt]['Supervisor'] = $ft_sel['superv'];
                $arr['res'][$cnt]['Reason'] = $ft_sel['LvName'];
                $arr['res'][$cnt]['allowed'] = $ft_sel['allowed'];
                $arr['res'][$cnt]['RejectionReason'] = ucfirst(strtolower($reason));
                $cnt++;
          }
      }else{
        $arr['found'] = 0;
      }
      print(json_encode($arr));
  } 

public function AllAvailableLeavesRejected()     //=================================================== All Available Leaves  == Rejected
  {
      $con = parent::connect();
      $user = $_SESSION['utb_att_user_id'];


      switch ($_SESSION['utb_att_position']) {
          case 'supervisor':
            $sel = $con->prepare("SELECT users.Names AS superv,leaves.RejectionReason AS RejectionReason,leave_types.LeaveName AS LvName,leave_types.LeaveDays AS allowed,leaves.LeaveId AS LeaveId,attendance_users.Names AS nm,attendance_users.Position AS pst,leaves.LeaveFrom AS lv_f,leaves.LeaveTo AS lv_t,leaves.LeaveDays AS lv_dys,leaves.LeaveRemainig AS lv_rmng,leaves.LeaveDate AS lv_dte,leaves.StatusSupervisor AS st_sup,leaves.StatusHR AS st_hr,leaves.StatusDVCPAF AS st_dvcpaf,leaves.StatusDVCA AS st_dvca,leaves.StatusVC AS st_vc FROM leaves,attendance_users,leave_types,users WHERE leaves.Supervisor=users.UserId AND leaves.LeaveType=leave_types.TypeId AND leaves.LeaveEmployee=attendance_users.UserId AND leaves.StatusSupervisor=2 AND leaves.Supervisor='$user' ORDER BY leaves.LeaveId DESC");
              break;
          case 'HR':
            $sel = $con->prepare("SELECT users.Names AS superv,leaves.RejectionReason AS RejectionReason,leave_types.LeaveName AS LvName,leave_types.LeaveDays AS allowed,leaves.LeaveId AS LeaveId,attendance_users.Names AS nm,attendance_users.Position AS pst,leaves.LeaveFrom AS lv_f,leaves.LeaveTo AS lv_t,leaves.LeaveDays AS lv_dys,leaves.LeaveRemainig AS lv_rmng,leaves.LeaveDate AS lv_dte,leaves.StatusSupervisor AS st_sup,leaves.StatusHR AS st_hr,leaves.StatusDVCPAF AS st_dvcpaf,leaves.StatusDVCA AS st_dvca,leaves.StatusVC AS st_vc FROM leaves,attendance_users,leave_types,users WHERE leaves.Supervisor=users.UserId AND leaves.LeaveType=leave_types.TypeId AND leaves.LeaveEmployee=attendance_users.UserId AND leaves.StatusHR=2 ORDER BY leaves.LeaveId DESC");
              break;
          case 'DVCPAF':
            $sel = $con->prepare("SELECT users.Names AS superv,leaves.RejectionReason AS RejectionReason,leave_types.LeaveName AS LvName,leave_types.LeaveDays AS allowed,leaves.LeaveId AS LeaveId,attendance_users.Names AS nm,attendance_users.Position AS pst,leaves.LeaveFrom AS lv_f,leaves.LeaveTo AS lv_t,leaves.LeaveDays AS lv_dys,leaves.LeaveRemainig AS lv_rmng,leaves.LeaveDate AS lv_dte,leaves.StatusSupervisor AS st_sup,leaves.StatusHR AS st_hr,leaves.StatusDVCPAF AS st_dvcpaf,leaves.StatusDVCA AS st_dvca,leaves.StatusVC AS st_vc FROM leaves,attendance_users,leave_types,users WHERE leaves.Supervisor=users.UserId AND leaves.LeaveType=leave_types.TypeId AND leaves.LeaveEmployee=attendance_users.UserId AND leaves.StatusDVCPAF=2 ORDER BY leaves.LeaveId DESC");
              break;
          case 'DVCA':
            $sel = $con->prepare("SELECT users.Names AS superv,leaves.RejectionReason AS RejectionReason,leave_types.LeaveName AS LvName,leave_types.LeaveDays AS allowed,leaves.LeaveId AS LeaveId,attendance_users.Names AS nm,attendance_users.Position AS pst,leaves.LeaveFrom AS lv_f,leaves.LeaveTo AS lv_t,leaves.LeaveDays AS lv_dys,leaves.LeaveRemainig AS lv_rmng,leaves.LeaveDate AS lv_dte,leaves.StatusSupervisor AS st_sup,leaves.StatusHR AS st_hr,leaves.StatusDVCPAF AS st_dvcpaf,leaves.StatusDVCA AS st_dvca,leaves.StatusVC AS st_vc FROM leaves,attendance_users,leave_types,users WHERE leaves.Supervisor=users.UserId AND leaves.LeaveType=leave_types.TypeId AND leaves.LeaveEmployee=attendance_users.UserId AND leaves.StatusDVCA=2 ORDER BY leaves.LeaveId DESC");
              break;
          case 'VC':
            $sel = $con->prepare("SELECT users.Names AS superv,leaves.RejectionReason AS RejectionReason,leave_types.LeaveName AS LvName,leave_types.LeaveDays AS allowed,leaves.LeaveId AS LeaveId,attendance_users.Names AS nm,attendance_users.Position AS pst,leaves.LeaveFrom AS lv_f,leaves.LeaveTo AS lv_t,leaves.LeaveDays AS lv_dys,leaves.LeaveRemainig AS lv_rmng,leaves.LeaveDate AS lv_dte,leaves.StatusSupervisor AS st_sup,leaves.StatusHR AS st_hr,leaves.StatusDVCPAF AS st_dvcpaf,leaves.StatusDVCA AS st_dvca,leaves.StatusVC AS st_vc FROM leaves,attendance_users,leave_types,users WHERE leaves.Supervisor=users.UserId AND leaves.LeaveType=leave_types.TypeId AND leaves.LeaveEmployee=attendance_users.UserId AND leaves.StatusVC=2 AND leaves.LeaveStatus=2 ORDER BY leaves.LeaveId DESC");
              break;
          
          default:
            $sel = $con->prepare("SELECT users.Names AS superv,leaves.RejectionReason AS RejectionReason,leave_types.LeaveName AS LvName,leave_types.LeaveDays AS allowed,leaves.LeaveId AS LeaveId,attendance_users.Names AS nm,attendance_users.Position AS pst,leaves.LeaveFrom AS lv_f,leaves.LeaveTo AS lv_t,leaves.LeaveDays AS lv_dys,leaves.LeaveRemainig AS lv_rmng,leaves.LeaveDate AS lv_dte,leaves.StatusSupervisor AS st_sup,leaves.StatusHR AS st_hr,leaves.StatusDVCPAF AS st_dvcpaf,leaves.StatusDVCA AS st_dvca,leaves.StatusVC AS st_vc FROM leaves,attendance_users,leave_types,users WHERE leaves.Supervisor=users.UserId AND leaves.LeaveType=leave_types.TypeId AND leaves.LeaveEmployee=attendance_users.UserId AND leaves.LeaveStatus NOT IN(0,1,2) ORDER BY leaves.LeaveId DESC");
              break;
      }


      $sel->execute();
      if ($sel->rowCount()>=1) {
        $cnt = 0;
          while ($ft_sel = $sel->fetch(PDO::FETCH_ASSOC)) {
            if ($ft_sel['RejectionReason']==NULL) {
                $reason = '-';
            }else{
                $reason = $ft_sel['RejectionReason'];
            }
                $arr['found'] = 1;
                $arr['res'][$cnt]['LeaveId'] = $ft_sel['LeaveId'];
                $arr['res'][$cnt]['LeaveFrom'] = $ft_sel['lv_f'];
                $arr['res'][$cnt]['LeaveTo'] = $ft_sel['lv_t'];
                $arr['res'][$cnt]['LeaveDays'] = $ft_sel['lv_dys'];
                $arr['res'][$cnt]['LeaveRemainig'] = $ft_sel['lv_rmng'];
                $arr['res'][$cnt]['StatusSupervisor'] = $this->StatusFunc($ft_sel['st_sup']);
                $arr['res'][$cnt]['StatusHR'] = $this->StatusFunc($ft_sel['st_hr']);
                $arr['res'][$cnt]['StatusDVCPAF'] = $this->StatusFunc($ft_sel['st_dvcpaf']);
                $arr['res'][$cnt]['StatusDVCA'] = $this->StatusFunc($ft_sel['st_dvca']);
                $arr['res'][$cnt]['StatusVC'] = $this->StatusFunc($ft_sel['st_vc']);
                $arr['res'][$cnt]['LeaveDate'] = substr($ft_sel['lv_dte'], 0, 10);
                $arr['res'][$cnt]['Names'] = $ft_sel['nm'];
                $arr['res'][$cnt]['Post'] = $ft_sel['pst'];
                $arr['res'][$cnt]['Supervisor'] = $ft_sel['superv'];
                $arr['res'][$cnt]['Reason'] = $ft_sel['LvName'];
                $arr['res'][$cnt]['allowed'] = $ft_sel['allowed'];
                $arr['res'][$cnt]['RejectionReason'] = ucfirst(strtolower($reason));
                $cnt++;
          }
      }else{
        $arr['found'] = 0;
      }
      print(json_encode($arr));
  } 

public function AllLeaveTypes()     //=================================================== All Leave Categories
  {
      $con = parent::connect();
      $sel = $con->prepare("SELECT * FROM leave_types WHERE leave_types.Status=1 ORDER BY leave_types.TypeId");
      $sel->execute();
      $cnt = 0;
      if ($sel->rowCount()>=1) {
          while ($ft_sel = $sel->fetch(PDO::FETCH_ASSOC)) {
              $arr['found'] = 1;
              $arr['res'][$cnt]['TypeId'] = $ft_sel['TypeId'];
              $arr['res'][$cnt]['LeaveName'] = $ft_sel['LeaveName'];
              $cnt++;
          }
      }else{
        $arr['found'] = 0;
      }
      print(json_encode($arr));
  } 

  public function AllSupervisors()     //=================================================== All Supervisors
  {
      $con = parent::connect();
      $sel = $con->prepare("SELECT * FROM users WHERE users.Status=1 AND users.Position='supervisor' ORDER BY users.UserId");
      $sel->execute();
      $cnt = 0;
      if ($sel->rowCount()>=1) {
          while ($ft_sel = $sel->fetch(PDO::FETCH_ASSOC)) {
              $arr['found'] = 1;
              $arr['res'][$cnt]['UserId'] = $ft_sel['UserId'];
              $arr['res'][$cnt]['Names'] = $ft_sel['Names'];
              $cnt++;
          }
      }else{
        $arr['found'] = 0;
      }
      print(json_encode($arr));
  }  

public function VCGoals()
{
    $con = parent::connect();
    $sel = $con->prepare("SELECT * FROM imihigo WHERE imihigo.ImihigoType=0 AND imihigo.ImihigoStatus<>2 ORDER BY imihigo.ImihigoStatus DESC");
    $sel->execute();
    if ($sel->rowCount()>=1) {
        $cnt = 0;
        while ($ft_sel = $sel->fetch(PDO::FETCH_ASSOC)) {
            if ($ft_sel['ImihigoStatus']==1) {
                $status = "Cascaded";
                $button = "UnCascade";
                $color = "warning";
            }else{
                $status = "UnCascaded";
                $button = "Casdcade";
                $color = "primary";
            }
            $arr['found'] = 1;
            $arr['res'][$cnt]['ImihigoId'] = $ft_sel['ImihigoId'];
            $arr['res'][$cnt]['ImihigoName'] = $ft_sel['ImihigoName'];
            $arr['res'][$cnt]['ImihigoDetails'] = $ft_sel['ImihigoDetails'];
            $arr['res'][$cnt]['ImihigoDate'] = substr($ft_sel['ImihigoDate'], 0,16);
            $arr['res'][$cnt]['ImihigoStatus'] = $status;
            $arr['res'][$cnt]['Button'] = $button;
            $arr['res'][$cnt]['Color'] = $color;
            $cnt++;
        }
    }else{
        $arr['found'] = 0;
    }
    print(json_encode($arr));
}

public function DVCsGoals()
{
    $con = parent::connect();
    $user = $_SESSION['utb_att_user_id'];
    $sel = $con->prepare("SELECT * FROM imihigo WHERE imihigo.ImihigoType=0 AND imihigo.ImihigoStatus<>2 AND imihigo.ImihigoStatus=1 AND imihigo.ImihigoAssignedTo='$user' ORDER BY imihigo.ImihigoStatus DESC");
    $sel->execute();
    if ($sel->rowCount()>=1) {
        $cnt = 0;
        while ($ft_sel = $sel->fetch(PDO::FETCH_ASSOC)) {
            if ($ft_sel['ImihigoStatus']==1) {
                $status = "Cascaded";
                $button = "UnCascade";
                $color = "warning";
            }else{
                $status = "UnCascaded";
                $button = "Casdcade";
                $color = "primary";
            }
            $arr['found'] = 1;
            $arr['res'][$cnt]['ImihigoId'] = $ft_sel['ImihigoId'];
            $arr['res'][$cnt]['ImihogoOwner'] = $ft_sel['ImihogoOwner'];
            $arr['res'][$cnt]['ImihigoName'] = $ft_sel['ImihigoName'];
            $arr['res'][$cnt]['ImihigoDetails'] = $ft_sel['ImihigoDetails'];
            $arr['res'][$cnt]['ImihigoDate'] = substr($ft_sel['ImihigoDate'], 0,16);
            $arr['res'][$cnt]['ImihigoStatus'] = $status;
            $arr['res'][$cnt]['Button'] = $button;
            $arr['res'][$cnt]['Color'] = $color;
            $arr['res'][$cnt]['MyGoals'] = $this->count_my_sub_imihigo($ft_sel['ImihigoId']);
            $cnt++;
        }
    }else{
        $arr['found'] = 0;
    }
    print(json_encode($arr));
}
public function SupervisorsGoals()
{
    $con = parent::connect();
    $user = $_SESSION['utb_att_user_id'];
    $sel = $con->prepare("SELECT * FROM imihigo WHERE imihigo.ImihigoType=1 AND imihigo.ImihigoStatus<>2 AND imihigo.ImihigoStatus=0 AND imihigo.ImihigoAssignedTo='$user' ORDER BY imihigo.ImihigoStatus DESC");
    $sel->execute();
    if ($sel->rowCount()>=1) {
        $cnt = 0;
        while ($ft_sel = $sel->fetch(PDO::FETCH_ASSOC)) {
            if ($ft_sel['ImihigoStatus']==1) {
                $status = "Cascaded";
                $button = "UnCascade";
                $color = "warning";
            }else{
                $status = "UnCascaded";
                $button = "Casdcade";
                $color = "primary";
            }
            $arr['found'] = 1;
            $arr['res'][$cnt]['ImihigoId'] = $ft_sel['ImihigoId'];
            $arr['res'][$cnt]['ImihogoOwner'] = $ft_sel['ImihogoOwner'];
            $arr['res'][$cnt]['ImihigoName'] = $ft_sel['ImihigoName'];
            $arr['res'][$cnt]['ImihigoDetails'] = $ft_sel['ImihigoDetails'];
            $arr['res'][$cnt]['ImihigoDate'] = substr($ft_sel['ImihigoDate'], 0,16);
            $arr['res'][$cnt]['ImihigoStatus'] = $status;
            $arr['res'][$cnt]['Button'] = $button;
            $arr['res'][$cnt]['Color'] = $color;
            $arr['res'][$cnt]['MyGoals'] = $this->count_my_sub_imihigo($ft_sel['ImihigoId']);
            $cnt++;
        }
    }else{
        $arr['found'] = 0;
    }
    print(json_encode($arr));
}

public function EmployeeGoals()
{
    $con = parent::connect();
    $user = $_SESSION['utb_att_user_id'];
    $sel = $con->prepare("SELECT * FROM imihigo WHERE imihigo.ImihigoType=1 AND imihigo.ImihigoStatus<>2 AND imihigo.ImihigoStatus=0 AND imihigo.ImihigoAssignedTo='$user' ORDER BY imihigo.ImihigoStatus DESC");
    $sel->execute();
    if ($sel->rowCount()>=1) {
        $cnt = 0;
        while ($ft_sel = $sel->fetch(PDO::FETCH_ASSOC)) {
            if ($ft_sel['ImihigoStatus']==1) {
                $status = "Cascaded";
                $button = "UnCascade";
                $color = "warning";
            }else{
                $status = "UnCascaded";
                $button = "Casdcade";
                $color = "primary";
            }
            $arr['found'] = 1;
            $arr['res'][$cnt]['ImihigoId'] = $ft_sel['ImihigoId'];
            $arr['res'][$cnt]['ImihogoOwner'] = $ft_sel['ImihogoOwner'];
            $arr['res'][$cnt]['ImihigoName'] = $ft_sel['ImihigoName'];
            $arr['res'][$cnt]['ImihigoDetails'] = $ft_sel['ImihigoDetails'];
            $arr['res'][$cnt]['ImihigoDate'] = substr($ft_sel['ImihigoDate'], 0,16);
            $arr['res'][$cnt]['ImihigoStatus'] = $status;
            $arr['res'][$cnt]['Button'] = $button;
            $arr['res'][$cnt]['Color'] = $color;
            $arr['res'][$cnt]['MyGoals'] = $this->count_my_sub_imihigo($ft_sel['ImihigoId']);
            $cnt++;
        }
    }else{
        $arr['found'] = 0;
    }
    print(json_encode($arr));
}

public function count_my_sub_imihigo($umuhigo)
{
    $con = parent::connect();
    $sel = $con->prepare("SELECT * FROM imihigo WHERE imihigo.ImihogoDescendents='$umuhigo'");
    $sel->execute();
    return $sel->rowCount();
}

public function VCOrientedGoals()
{
    $con = parent::connect();
    $sel = $con->prepare("SELECT * FROM imihigo,users WHERE imihigo.ImihigoAssignedTo=users.UserId AND imihigo.ImihigoType=0 AND imihigo.ImihigoAssignedTo IS NOT NULL AND imihigo.ImihigoStatus=1");
    $sel->execute();
    if ($sel->rowCount()>=1) {
        $cnt = 0;
        while ($ft_sel = $sel->fetch(PDO::FETCH_ASSOC)) {
            $arr['found'] = 1;
            $arr['res'][$cnt]['ImihigoName'] = $ft_sel['ImihigoName'];
            $arr['res'][$cnt]['Position'] = $ft_sel['Position'];
            $cnt++;
        }
    }else{
            $arr['found'] = 0;
        }
    print(json_encode($arr));
}

public function DVCsOrientedGoals()
{
    $con = parent::connect();
    $sel = $con->prepare("SELECT * FROM imihigo,users WHERE imihigo.ImihigoAssignedTo=users.UserId AND imihigo.ImihigoType=1 AND imihigo.ImihigoAssignedTo IS NOT NULL");
    $sel->execute();
    if ($sel->rowCount()>=1) {
        $cnt = 0;
        while ($ft_sel = $sel->fetch(PDO::FETCH_ASSOC)) {
            $arr['found'] = 1;
            $arr['res'][$cnt]['ImihigoName'] = $ft_sel['ImihigoName'];
            $arr['res'][$cnt]['Position'] = ucfirst($ft_sel['Position'])."  -  ".$ft_sel['Names'];
            $cnt++;
        }
    }else{
            $arr['found'] = 0;
        }
    print(json_encode($arr));
}

public function MineDVCsOrientedGoals()
{
    $user = $_SESSION['utb_att_user_id'];
    $con = parent::connect();
    $sel = $con->prepare("SELECT * FROM imihigo,users WHERE imihigo.ImihigoAssignedTo=users.UserId AND imihigo.ImihogoOwner='$user' AND imihigo.ImihigoType=1 AND imihigo.ImihigoAssignedTo IS NOT NULL");
    $sel->execute();
    if ($sel->rowCount()>=1) {
        $cnt = 0;
        while ($ft_sel = $sel->fetch(PDO::FETCH_ASSOC)) {
            $arr['found'] = 1;
            $arr['res'][$cnt]['ImihigoName'] = $ft_sel['ImihigoName'];
            $arr['res'][$cnt]['Position'] = ucfirst($ft_sel['Position'])."  -  ".$ft_sel['Names'];
            $cnt++;
        }
    }else{
            $arr['found'] = 0;
        }
    print(json_encode($arr));
}

public function MineSupervisorsOrientedGoals()
{
    $user = $_SESSION['utb_att_user_id'];
    $con = parent::connect();
    $sel = $con->prepare("SELECT * FROM imihigo,attendance_users WHERE imihigo.ImihigoAssignedTo=attendance_users.UserId AND imihigo.ImihogoOwner='$user' AND imihigo.ImihigoAssignedTo IS NOT NULL");
    $sel->execute();
    if ($sel->rowCount()>=1) {
        $cnt = 0;
        while ($ft_sel = $sel->fetch(PDO::FETCH_ASSOC)) {
            $arr['found'] = 1;
            $arr['res'][$cnt]['ImihigoName'] = $ft_sel['ImihigoName'];
            $arr['res'][$cnt]['Position'] = ucfirst($ft_sel['Position'])."  -  ".$ft_sel['Names'];
            $cnt++;
        }
    }else{
            $arr['found'] = 0;
        }
    print(json_encode($arr));
}

public function LeaveDays($leaveType)
{
    $con = parent::connect();
    $sel = $con->prepare("SELECT * FROM leave_types WHERE leave_types.TypeId='$leaveType'");
    $sel->execute();
    if ($sel->rowCount()>=1) {
        $cnt = 0;
        $ft_sel = $sel->fetch(PDO::FETCH_ASSOC);
            $numm = $ft_sel['LeaveDays'];
    }else{
            $numm = 0;
        }
    return $numm;
}

public function MyAssignedGoals()
{
    $user = $_SESSION['utb_att_user_id'];
    $con = parent::connect();
    $sel = $con->prepare("SELECT * FROM imihigo WHERE imihigo.ImihogoOwner='$user'");
    $sel->execute();
    if ($sel->rowCount()>=1) {
        $cnt = 0;
        while ($ft_sel = $sel->fetch(PDO::FETCH_ASSOC)) {
            $arr['found'] = 1;
            $arr['res'][$cnt]['ImihigoId'] = $ft_sel['ImihigoId'];
            $arr['res'][$cnt]['ImihigoName'] = $ft_sel['ImihigoName'];
            $cnt++;
        }
    }else{
            $arr['found'] = 0;
        }
    print(json_encode($arr));
}

public function MyAchievements()            //Goals that were assigned to me
{
    $user = $_SESSION['utb_att_user_id'];
    $con = parent::connect();
    $sel = $con->prepare("SELECT * FROM imihigo_achievements,imihigo WHERE imihigo.ImihigoId=imihigo_achievements.ImihigoId AND imihigo.ImihogoOwner='$user'");
    $sel->execute();
    if ($sel->rowCount()>=1) {
        $cnt = 0;
        while ($ft_sel = $sel->fetch(PDO::FETCH_ASSOC)) {
            $arr['found'] = 1;
            $arr['res'][$cnt]['AchievementDetails'] = $ft_sel['AchievementDetails'];
            $arr['res'][$cnt]['ImihigoName'] = $ft_sel['ImihigoName'];
            $arr['res'][$cnt]['AchievementStatus'] = 'Cascaded';
            $cnt++;
        }
    }else{
            $arr['found'] = 0;
        }
    print(json_encode($arr));
}
public function UserName($userr)         // FROM (attendance_users)
{
    $con = parent::connect();
    $sel = $con->prepare("SELECT * FROM attendance_users WHERE attendance_users.UserId='$userr'");
    $sel->execute();
    if ($sel->rowCount()==1) {
        $ft_sel = $sel->fetch(PDO::FETCH_ASSOC);
        $name = $ft_sel['Names'];
    }else{
            $name = "-";
        }
    return $name;
}
public function UserNameSenior($userr)         // FROM (users)
{
    $con = parent::connect();
    $sel = $con->prepare("SELECT * FROM users WHERE users.UserId='$userr'");
    $sel->execute();
    if ($sel->rowCount()==1) {
        $ft_sel = $sel->fetch(PDO::FETCH_ASSOC);
        $name = $ft_sel['Names'];
    }else{
            $name = "-";
        }
    return $name;
}
public function UserPosision($userr)         // FROM (attendance_users)
{
    $con = parent::connect();
    $sel = $con->prepare("SELECT * FROM attendance_users WHERE attendance_users.UserId='$userr'");
    $sel->execute();
    if ($sel->rowCount()==1) {
        $ft_sel = $sel->fetch(PDO::FETCH_ASSOC);
        $position = $ft_sel['Position'];
    }else{
            $position = "-";
        }
    return $position;
}
public function UserPosisionSenior($userr)         // FROM (users)
{
    $con = parent::connect();
    $sel = $con->prepare("SELECT * FROM users WHERE users.UserId='$userr'");
    $sel->execute();
    if ($sel->rowCount()==1) {
        $ft_sel = $sel->fetch(PDO::FETCH_ASSOC);
        $position = $ft_sel['Position'];
    }else{
            $position = "-";
        }
    return $position;
}
public function MySubAchevements_Supervisors()         //Goals that were created by me
{
    $user = $_SESSION['utb_att_user_id'];
    $con = parent::connect();
    $sel = $con->prepare("SELECT * FROM imihigo WHERE imihigo.ImihogoOwner='$user' AND imihigo.ImihigoAssignedTo IS NOT NULL");
    $sel->execute();
    if ($sel->rowCount()>=1) {
        while ($ft_sel = $sel->fetch(PDO::FETCH_ASSOC)) {
            $emp_id = $ft_sel['ImihigoAssignedTo'];
            $ssl = $con->prepare("SELECT * FROM imihigo_achievements,imihigo WHERE imihigo.ImihigoId=imihigo_achievements.ImihigoId AND imihigo.ImihogoOwner='$emp_id'");
            $ssl->execute();
            if ($ssl->rowCount()>=1) {
                $cnt = 0;
                while ($ftt_sel = $ssl->fetch(PDO::FETCH_ASSOC)) {
                    $arr['found'] = 1;
                    $arr['res'][$cnt]['AchievementDetails'] = $ftt_sel['AchievementDetails'];
                    $arr['res'][$cnt]['EmployeeName'] = $this->UserName($ftt_sel['ImihogoOwner']);
                    $arr['res'][$cnt]['EmployeePosition'] = $this->UserPosision($ftt_sel['ImihogoOwner']);
                    $arr['res'][$cnt]['ImihigoName'] = $ftt_sel['ImihigoName'];
                    $arr['res'][$cnt]['AchievementStatus'] = 'Cascaded';
                    $cnt++;
                }
            }else{
                $arr['found'] = 0;
            }
        }
    }else{
            $arr['found'] = 0;
        }
    print(json_encode($arr));
}

public function MyAchievementsDVCs()
{
    $user = $_SESSION['utb_att_user_id'];
    $con = parent::connect();
    $sel = $con->prepare("SELECT * FROM imihigo WHERE imihigo.ImihogoOwner='$user' AND imihigo.ImihigoAssignedTo IS NOT NULL");
    $sel->execute();
    if ($sel->rowCount()>=1) {
        while ($ft_sel = $sel->fetch(PDO::FETCH_ASSOC)) {
            $emp_id = $ft_sel['ImihigoAssignedTo'];
            $ssl = $con->prepare("SELECT * FROM imihigo_achievements,imihigo WHERE imihigo.ImihigoId=imihigo_achievements.ImihigoId OR imihigo.ImihogoDescendents=imihigo.ImihigoId AND imihigo.ImihogoOwner='$emp_id'");
            $ssl->execute();
            if ($ssl->rowCount()>=1) {
                $cnt = 0;
                while ($ftt_sel = $ssl->fetch(PDO::FETCH_ASSOC)) {
                    if ($ftt_sel['UserStatus']!=NULL) {
                        $arr['res'][$cnt]['EmployeeName'] = $this->UserName($ftt_sel['ImihogoOwner']);
                        $arr['res'][$cnt]['EmployeePosition'] = $this->UserPosision($ftt_sel['ImihogoOwner']);
                    }else{
                        $arr['res'][$cnt]['EmployeeName'] = $this->UserNameSenior($ftt_sel['ImihogoOwner']);
                        $arr['res'][$cnt]['EmployeePosition'] = ucfirst($this->UserPosisionSenior($ftt_sel['ImihogoOwner']));
                    }
                    $arr['found'] = 1;
                    $arr['res'][$cnt]['AchievementDetails'] = $ftt_sel['AchievementDetails'];
                    $arr['res'][$cnt]['ImihigoName'] = $ftt_sel['ImihigoName'];
                    $arr['res'][$cnt]['AchievementStatus'] = 'Cascaded';
                    $cnt++;
                }
            }else{
                $arr['found'] = 0;
            }
        }
    }else{
            $arr['found'] = 0;
        }
    print(json_encode($arr));
}


public function AchievementsHR_Report()
{
    $user = $_SESSION['utb_att_user_id'];
    $con = parent::connect();
    $sel = $con->prepare("SELECT * FROM imihigo WHERE imihigo.ImihigoStatus<>2");
    $sel->execute();
    if ($sel->rowCount()>=1) {
        while ($ft_sel = $sel->fetch(PDO::FETCH_ASSOC)) {
            $emp_id = $ft_sel['ImihigoAssignedTo'];
            $ssl = $con->prepare("SELECT * FROM imihigo_achievements,imihigo WHERE imihigo.ImihigoId=imihigo_achievements.ImihigoId OR imihigo.ImihogoDescendents=imihigo.ImihigoId AND imihigo.ImihogoOwner='$emp_id'");
            $ssl->execute();
            if ($ssl->rowCount()>=1) {
                $cnt = 0;
                while ($ftt_sel = $ssl->fetch(PDO::FETCH_ASSOC)) {
                    if ($ftt_sel['UserStatus']!=NULL) {
                        $arr['res'][$cnt]['EmployeeName'] = $this->UserName($ftt_sel['ImihogoOwner']);
                        $arr['res'][$cnt]['EmployeePosition'] = $this->UserPosision($ftt_sel['ImihogoOwner']);
                    }else{
                        $arr['res'][$cnt]['EmployeeName'] = $this->UserNameSenior($ftt_sel['ImihogoOwner']);
                        $arr['res'][$cnt]['EmployeePosition'] = ucfirst($this->UserPosisionSenior($ftt_sel['ImihogoOwner']));
                    }
                    $arr['found'] = 1;
                    $arr['res'][$cnt]['AchievementDetails'] = $ftt_sel['AchievementDetails'];
                    $arr['res'][$cnt]['ImihigoName'] = $ftt_sel['ImihigoName'];
                    $arr['res'][$cnt]['AchievementStatus'] = 'Cascaded';
                    $cnt++;
                }
            }else{
                $arr['found'] = 0;
            }
        }
    }else{
            $arr['found'] = 0;
        }
    print(json_encode($arr));
}

public function MyLeaveRange()     //=================================================== Employee requesting to a Leave
  {
      $con = parent::connect();
      $user = $_SESSION['utb_att_user_id'];
      $sel = $con->prepare("SELECT * FROM leave_range,attendance_users WHERE attendance_users.UserId=leave_range.UserId AND attendance_users.UserId='$user'");
      $sel->execute();
      if ($sel->rowCount()>=1) {
        $cnt = 0;
          while ($ft_sel = $sel->fetch(PDO::FETCH_ASSOC)) {
                $arr['found'] = 1;
                $arr['res'][$cnt]['RangeDate'] = $ft_sel['RangeDate'];
                $arr['res'][$cnt]['RangeDetails'] = $ft_sel['RangeDetails'];
                $cnt++;
          }
      }else{
        $arr['found'] = 0;
      }
      print(json_encode($arr));
  }

public function AllLeaveRange()     //=================================================== Employee requesting to a Leave
  {
      $con = parent::connect();
      $user = $_SESSION['utb_att_user_id'];
      $sel = $con->prepare("SELECT * FROM leave_range,attendance_users WHERE attendance_users.UserId=leave_range.UserId");
      $sel->execute();
      if ($sel->rowCount()>=1) {
        $cnt = 0;
          while ($ft_sel = $sel->fetch(PDO::FETCH_ASSOC)) {
                $arr['found'] = 1;
                $arr['res'][$cnt]['RangeDate'] = $ft_sel['RangeDate'];
                $arr['res'][$cnt]['Names'] = $ft_sel['Names'];
                $arr['res'][$cnt]['Position'] = $ft_sel['Position'];
                $arr['res'][$cnt]['RangeDetails'] = $ft_sel['RangeDetails'];
                $cnt++;
          }
      }else{
        $arr['found'] = 0;
      }
      print(json_encode($arr));
  }  

public function CheckAmINLeave()        //==================================== TO CHEKIF SOMEONE IS IN LEAVE TODAY
{
  $con = parent::connect();
  $user = $_SESSION['utb_att_user_id'];
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





// ===========================================================================================================================================================================
//=============================================================================================================================================================================
//                                      ETS
//=============================================================================================================================================================================
//============================================================================================================================================================================

public function StaffPositionName()        //==================================== staff Position name
{
  $con = parent::connect();
  $user = $_SESSION['worker_id'];
  $sel = $con->prepare("SELECT ets_staff_position.* FROM ets_staff_position,ets_workers WHERE ets_staff_position.PositionID=ets_workers.worker_position 
  AND ets_workers.worker_id='$user'");
  $sel->execute();
  if ($sel->rowCount()>=1) {
    $posData = $sel->fetch(PDO::FETCH_ASSOC);
    $all = $posData['PositionName'];
  }else{
    $all = '';
  }
  return $all;
}


public function WorkerSupervisor($userId)        //==================================== staff Position name
{
  $con = parent::connect();
  $sel = $con->prepare("SELECT * FROM ets_workers WHERE ets_workers.worker_id='$userId'");
  $sel->execute();
  if ($sel->rowCount()>=1) {
    $posData = $sel->fetch(PDO::FETCH_ASSOC);
    $all = strtoupper($posData['worker_fname']).'  '.$posData['worker_lname'];
  }else{
    $all = '-';
  }
  return $all;
}

public function WorkerCategory($user)        //==================================== display worker category
{
  $con = parent::connect();
  $sel = $con->prepare("SELECT ets_workers_category.* FROM ets_workers_category,ets_workers WHERE ets_workers_category.category_id=ets_workers.worker_category 
  AND ets_workers.worker_id='$user'");
  $sel->execute();
  if ($sel->rowCount()>=1) {
    $posData = $sel->fetch(PDO::FETCH_ASSOC);
    $all = $posData['category_name'];
  }else{
    $all = '-';
  }
  return $all;
}

public function WorkerPositionName($user)        //==================================== worker Position name
{
  $con = parent::connect();
  $sel = $con->prepare("SELECT ets_staff_position.* FROM ets_staff_position,ets_workers WHERE ets_staff_position.PositionID=ets_workers.worker_position 
  AND ets_workers.worker_id='$user'");
  $sel->execute();
  if ($sel->rowCount()>=1) {
    $posData = $sel->fetch(PDO::FETCH_ASSOC);
    $all = $posData['PositionName'];
  }else{
    $all = '-';
  }
  return $all;
}

public function ageFromDate($dob)       //==================================== return age from date
{
    $today = new DateTime();
    $birthdate = new DateTime($dob);
    $age = $today->diff($birthdate)->y;

    return $age;
}























}

$MainView = new MainView();
if (isset($_POST['MyAllLeaves'])) {
    $MainView->MyAllLeaves();
}else if (isset($_POST['AllAvailableLeavesPending'])) {
    $MainView->AllAvailableLeavesPending();
}else if (isset($_POST['AllAvailableLeavesApproved'])) {
    $MainView->AllAvailableLeavesApproved();
}else if (isset($_POST['AllAvailableLeavesRejected'])) {
    $MainView->AllAvailableLeavesRejected();
}else if (isset($_POST['AllLeaveTypes'])) {
    $MainView->AllLeaveTypes();
}else if (isset($_POST['AllSupervisors'])) {
    $MainView->AllSupervisors();
}else if (isset($_POST['VCGoals'])) {
    if ($_POST['VCGoals']=='VCGoals') {
        $MainView->VCGoals();
    }else if ($_POST['VCGoals']=='DVCsGoals') {
            $MainView->DVCsGoals();
    }
}elseif (isset($_POST['VCOrientedGoals'])) {
    $MainView->VCOrientedGoals();
}elseif (isset($_POST['DVCsOrientedGoals'])) {
    $MainView->DVCsOrientedGoals();
}elseif (isset($_POST['MineDVCsOrientedGoals'])) {
    $MainView->MineDVCsOrientedGoals();
}elseif (isset($_POST['MineSupervisorsOrientedGoals'])) {
    $MainView->MineSupervisorsOrientedGoals();
}elseif (isset($_POST['SupervisorGoals'])) {
    $MainView->SupervisorsGoals();
}elseif (isset($_POST['EmployeeGoals'])) {
    $MainView->EmployeeGoals();
}elseif (isset($_POST['MyAssignedGoals'])) {
    $MainView->MyAssignedGoals();
}elseif (isset($_POST['MyAchievements'])) {
    $MainView->MyAchievements();
}elseif (isset($_POST['MySubAchevements_Supervisors'])) {
    $MainView->MySubAchevements_Supervisors();
}elseif (isset($_POST['MyAchievementsDVCs'])) {
    $MainView->MyAchievementsDVCs();
}elseif (isset($_POST['AchievementsHR_Report'])) {
    $MainView->AchievementsHR_Report();
}elseif (isset($_POST['MyLeaveRange'])) {
    $MainView->MyLeaveRange();
}elseif (isset($_POST['AllLeaveRange'])) {
    $MainView->AllLeaveRange();
}




?>