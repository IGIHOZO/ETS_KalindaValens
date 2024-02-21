$("#login").click(function () {
  // Change button text to "Loading..." during the AJAX request
  $("#login").prop("disabled", true).html("Loading...");

  var email = document.getElementById('exampleInputEmail1').value;
  var password = document.getElementById('exampleInputPassword1').value;
  var login = true;

  $.ajax({
    url: "main/main.php",
    type: "GET",
    data: {
      login: login,
      email: email,
      password: password
    },
    cache: false,
    success: function (res) {
      // Revert button text after the AJAX request completes
      $("#login").prop("disabled", false).html("Login");

      if (res == 'success-reception') {
        window.location = "reception.php";
      } else {
        $("#respp").html("<span style='color:red;'>Wrong email or password ...</span>");
      }
    }
  });
});



//================== Save Card 
$("#savelfid").click(function(){
  $("#savelfid").val("Loading ...");
  var userCode = document.getElementById('userCode').value;
  var lfid = document.getElementById('lfid').value;
  var savelfid = true;
    $.ajax({url:"main/main.php",
    type:"GET",data:{
      savelfid:savelfid,userCode:userCode,lfid:lfid
    },cache:false,success:function(res){
      if (res=='null') {
        $("#respp").html("<h2 style='color:red'>Please fill all fields ...</h2>");
      }else if(res=='failed'){
        $("#respp").html("<h2 style='color:red'>Failed</h2>");
      }else{
        $("#respp").html(res);
        // $("#respp").css("background-color","red");
      }
    }
    });
});
//================== Save Card 
$("#lfid").change(function(){
  $("#savelfid").val("Loading ...");
  var userCode = document.getElementById('userCode').value;
  var lfid = document.getElementById('lfid').value;
  var savelfid = true;
    $.ajax({url:"main/main.php",
    type:"GET",data:{
      savelfid:savelfid,userCode:userCode,lfid:lfid
    },cache:false,success:function(res){
      if (res=='null') {
        $("#respp").html("<h2 style='color:red'>Please fill all fields ...</h2>");
      }else if(res=='failed'){
        $("#respp").html("<h2 style='color:red'>Failed</h2>");
      }else{
        $("#respp").html(res);
        // $("#respp").css("background-color","red");
      }
    }
    });
});
//================== Save Card 
$("#userCode").click(function(){
  $("#respp").html("");
  $("#lfid").html("");
});


//================== SCAN CARD 
$("#scan_card").change(function(){
  var content = document.getElementById('scan_card').value;
  var scan_card = true;
    $.ajax({url:"main/main.php",
    type:"GET",data:{
      scan_card:scan_card,content:content
    },cache:false,success:function(res){
        $("#scan_card").html("");
        $("#scan_card").val("");

      if (res=='arleady') {
        $("#respp").html("<h3>already Attended ...</h3>");
      }else{
        $("#respp").html(res);
        // $("#respp").css("background-color","red");
      }
    }
    });
});

//================== Search Attendance
$("#srch_att").click(function(){
    var srchDate = document.getElementById('ddate').value;
    var srchDateTo = document.getElementById('ddate_to').value;
    var srchCategory = document.getElementById('att_categry').value;
    if (srchCategory=='' || srchCategory == null || srchDate=='' == null || srchDateTo == null) {
      alert("Please fill all forms ...");
    }else{
      var searchAtt = true;
      $.ajax({url:"main/main.php",
      type:"GET",data:{
        searchAtt:searchAtt,srchDate:srchDate,srchCategory:srchCategory,srchDateTo:srchDateTo
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
    }
});

//================== Search Missed Attendance

$("#srch_att_missed").click(function(){
  var srchDate = document.getElementById('ddate').value;  // from
  var srchDateTo = document.getElementById('to_ddate').value;  //to
  var srchCategory = document.getElementById('att_categry').value;
  if (srchCategory=='' || srchCategory == null || srchDate=='' || srchDateTo == null) {
    alert("Please fill all forms ...");
  }else{
    var missedEmployeesBYDate = true;
    $.ajax({url:"main/main.php",
    type:"GET",data:{
      missedEmployeesBYDate:missedEmployeesBYDate,srchDate:srchDate,srchCategory:srchCategory,srchDateTo:srchDateTo
    },cache:false,success:function(res){
      if (res=='null') {
              alert("Please fill all forms ...");
      }else{
        $("#resspp").html(res);
        // $("#respp").css("background-color","red");
      }
    }
    });
  }
});

  //================================= Employee requesting to a Leave
function saveLeaveRequest(){
  var from = document.getElementById('dateFrom').value;
  var to = document.getElementById('dateTo').value;
  var leaveType = document.getElementById('leaveType').value;
  var supervisor = document.getElementById('supervisor').value;
  // var dateFrom = new Date(from);
  // var dateTo = new Date(to);
  var dateFrom = from;
  var dateTo = to;
  console.log(dateFrom);
  console.log(from);
  if (from!='' && to!='' && leaveType!='' && supervisor!='' && to!=null && from!=null && to!=null && leaveType!=null && supervisor!=null) {
    var difference = (new Date(to)).getTime() - (new Date(from)).getTime();
    var days = Math.ceil(difference / (1000 * 3600 * 24));
    if (days>=0) {
      console.log(days);
      days+=1;
      var saveLeaveRequest = true;
      $.ajax({url:"main/main.php",
      type:"GET",data:{
        saveLeaveRequest:saveLeaveRequest,dateFrom:dateFrom,dateTo:dateTo,leaveType:leaveType,supervisor:supervisor,days:days
      },cache:false,success:function(res){
        if (res=='too_many') {
                document.getElementById("ress").innerHTML='Requested number of days are more than <b>availed</b> days.';
        }else if(res=='so_many'){
                document.getElementById("ress").innerHTML='Requested number of days are more than <b>Remaing</b> days.';
        }else if(res=='suceess'){
                // document.getElementById("ress").innerHTML='Requested number of days are more than <b>Remaing</b> days.';
                window.location.reload(true);
        }else{
                $("#ress").html(res);
        }
      }
      });
    }else{
    document.getElementById("ress").innerHTML='Invalid day range';

    }
  }else{
    document.getElementById("ress").innerHTML='Fill aall fields';
  }

}

function RejectLeave() {     //=========================================== Reject Leave
  var user = document.getElementById('TypeOfApprover').value; 
  var leave = document.getElementById('LeaveRequested').value;
  var reason = document.getElementById('rejectReason').value;
  if (reason=="" || leave=="" || user=="") {
    alert("Fill all fields ...");
  }else{
    var RejectLeave = true;
    $.ajax({url:"main/main.php",
    type:"GET",data:{
      RejectLeave:RejectLeave,user:user,leave:leave,reason:reason
    },cache:false,success:function(res){
      window.location.reload(true);
    }
    });
  }
}
function RejectModal(user, leave) {        //=================================== REJECT Modal
  document.getElementById('TypeOfApprover').value=user;
  document.getElementById('LeaveRequested').value=leave;
  $("#opppe").click();
}
function ApproveLeave(user, leave) {     //=========================================== Approve Leave
  var ApproveLeave = true;
      $.ajax({url:"main/main.php",
      type:"GET",data:{
        ApproveLeave:ApproveLeave,user:user,leave:leave
      },cache:false,success:function(res){
        window.location.reload(true);
      }
      });
}

function PendingLeave(user, leave) {     //=========================================== Pending Leave
  var PendingLeave = true;
      $.ajax({url:"main/main.php",
      type:"GET",data:{
        PendingLeave:PendingLeave,user:user,leave:leave
      },cache:false,success:function(res){
        window.location.reload(true);
      }
      });
}

  $("#saveGoal").click(function(){            //=========================================== Save Goal

    var goalname = document.getElementById("goalname").value;
    var goaldetails = document.getElementById("goaldetails").value;
    if (goalname!='' && goaldetails!='') {
        var saveGoal = true;
        $.ajax({url:"main/main.php",
        type:"GET",data:{
          saveGoal:saveGoal,goalname:goalname,goaldetails:goaldetails
        },cache:false,success:function(res){
          window.location.reload(true);
        // $("#ress").html(res);

        }
        });
      }else{
        $("#ress").html("Fill all fields ...");
      }
  })

function CasdcadeGoal(goal) {     //=========================================== Publish Goal
  var PublishGoal = true;
      $.ajax({url:"main/main.php",
      type:"GET",data:{
        PublishGoal:PublishGoal,goal:goal
      },cache:false,success:function(res){
        window.location.reload(true);
        // alert(res);

      }
      });
    }
function UnCascadeGoal(goal) {     //=========================================== UnPublish Goal
  var UnpublishGoal = true;
      $.ajax({url:"main/main.php",
      type:"GET",data:{
        UnpublishGoal:UnpublishGoal,goal:goal
      },cache:false,success:function(res){
        window.location.reload(true);
      }
      });
    }
function DeleteGoal(goal) {     //=========================================== Delete Goal
  var DeleteGoal = true;
      $.ajax({url:"main/main.php",
      type:"GET",data:{
        DeleteGoal:DeleteGoal,goal:goal
      },cache:false,success:function(res){
        window.location.reload(true);
      }
      });
    }
