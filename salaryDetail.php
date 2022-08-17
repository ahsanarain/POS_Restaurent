<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
include('lib/iq.php');

$month;
if(isset($_REQUEST['mon'])){
    $month = $_REQUEST['mon'];
}else{
    $month = Date('m');
}

$sql = "SELECT
        a.sid,
        a.sname,
        a.ssalary,

        sum(b.attendance_today_service_charge) service_charge,
        b.sal_paid,
        b.sc_paid

        FROM

        staff_reg a,
        staff_attendance b

        WHERE
        a.sid = b.sid
        and DATE_FORMAT(b.attendance_date,'%m') = '$month' 
        group by b.sid";

        $result = mysqli_query($cn, $sql);
	$dataArr = array();
	while($data = mysqli_fetch_assoc($result)){
		$dataArr[] = $data;
	}
        
        
        if(isset($_POST['method'])){
	if($_POST['method']=='salsc'){
		$empid = $_POST['emp_id'];
                $month = $_POST['mon'];
                $salary = $_POST['sal'];
                $service_charge = $_POST['sc'];
                $year = Date('Y');
		
$updateQry = "update staff_attendance set sc_paid = '$service_charge' ,sal_paid = '$salary' where sid = '$empid' and YEAR(attendance_date) = '$year' AND MONTH(attendance_date) = '$month'";
		mysqli_query($cn, $updateQry);
                exit();
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pending Salaries & Service Charge</title>
<link href="css/styles.css" type="text/css" rel="stylesheet" />
<script src="js/jquery.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/jquery.datepick.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.datepick.css">
<link rel="stylesheet" type="text/css" href="css/jquery.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">

<style>
.table-hover thead tr:hover th, 
 .table-hover tbody tr:hover td {
        background-color: lightyellow;
}
</style>
<link href="css/styles.css" type="text/css" rel="stylesheet" />
<script>
$(document).on('click','.sal',function(){
   
   if($(this).prop('checked')==true){
       $(this).parent().css("background-color","green");
       $(this).parent().css("color","yellow");
   } else{
         $(this).parent().css("background-color","red");
       $(this).parent().css("color","white");
   }
});
$(document).on('click','.sc',function(){
   if($(this).prop('checked')==true){
       $(this).parent().css("background-color","green");
       $(this).parent().css("color","yellow");
   } else{
         $(this).parent().css("background-color","red");
       $(this).parent().css("color","white");
   }
});

$(document).on('click','.paid',function(){
    var x = confirm("Want to perform this action ?");
    if(!true){
        return false;
    }
    var emp_id;
    var sal;
    var sc;
    emp_id = $(this).attr("emp_id");
   if($(this).parent().parent().find('td:nth-child(3) input').is(':checked') == true){
       sal = 1
   }else{
       sal=  0;
   }
   if($(this).parent().parent().find('td:nth-child(4) input').is(':checked') == true){
       sc = 1
   }else{
       sc= 0;
   }
   
   
  // alert(emp_id + '   '+sal+ '   '+sc);
   
   $.ajax({
        type:'POST', 
        url: '<?=$_SERVER['PHP_SELF']?>', 
        data:{method:'salsc',emp_id:emp_id,sal:sal,sc:sc,mon:$("#mon").val()}, 
        success: function(response) {
            location.reload();
            alert("Record Updated Successfully");
        }
    });
   
});


</script>
</head>

<body>
<?php include 'include/header.php' ?>
<div class="admin-greyBg">
  <div class="admin-wrapper">
    <div id="admin-leftNav" class="admin-fLeft">
<?php include 'include/left_nav.php' ?>
    </div>
    <div id="admin-body" class="admin-fRight">
		<h1>Salary & Service Charge Pending </h1>
                <form name='frmsearch' action='salaryDetail.php'>
<?php
	$mn = array(
				'01'=>'January',
				'02'=>'February',
				'03'=>'March',
				'04'=>'April',
				'05'=>'May',
				'06'=>'June',
				'07'=>'July',
				'08'=>'August',
				'09'=>'September',
				'10'=>'October',
				'11'=>'November',
				'12'=>'December'
				);
?>
	 <select name='mon' id='mon' class="admin-inputBox" onchange="this.form.submit()">
					<?php
						$selected="";
                                                 $moonntthh;
						foreach($mn as $key => $mondata){
							if(!empty($month)){
								if($month==$key){
									$selected = "selected='selected'";
                                                                        $moonntthh = $mondata;
								}else{
									$selected = "";
								}
							}
					?>
						<option value="<?=$key?>" <?=$selected?>><?=$mondata?></option>
					<?php
						}
					?>
				  </select> RED for <font color='red' size='4'> NOT PAID </font> and Green for <font color='green' size='4'> PAID </font>
	</form>
               <br/>
<h2><font color='green'>"<?=$moonntthh.' '.Date('Y')?>"</font> Record</h2>
               <br/>
                <?php
                    if(empty($dataArr)){
                        
                        echo "Sorry No Record Found ..."; exit();
                    }
                ?>
        <table width="100%" id="pending" border="0" align="center" class="table-hover">
            <thead>
                <tr bgcolor="#F0F0F0">
                    <td class="admin-tbHdRow1">Sno.</td>
                    <td class="admin-tbHdRow1">Name</td>
                    <td class="admin-tbHdRow1">Salary</td>
                    <td class="admin-tbHdRow1">Serv Charge</td>
                    <td class="admin-tbHdRow1 admin-tbHdRow3" width="10%">Operation</td>
                </tr>
            </thead>
            <tbody>
                <?php
                    $sno = 1;
                foreach($dataArr as $data){
                ?>
                <tr>
                    <td class="admin-tbRow1"><?=$sno?></td>
                    <td class="admin-tbRow1"><?=$data['sname']?></td>
                    <?php
                        $bgcolor1;
                        if($data['sal_paid']=='0')
                            $bgcolor1="style='background-color:red; color:white;'";
                        else
                            $bgcolor1="style='background-color:green; color:yellow;'";
                        
                         $bgcolor2;
                        if($data['sc_paid']=='0')
                            $bgcolor2="style='background-color:red; color:white;'";
                        else
                            $bgcolor2="style='background-color:green; color:yellow;'";
                    ?>
                    <td class="admin-tbRow1" align="right" width="20%" <?=$bgcolor1?>>
                        <strong>
                         <!--   <?=$data['ssalary']?> -->
                        </strong> &nbsp; <input class='sal' type="checkbox" salary="<?=$data['sal_paid']?>">
                    </td>
                    <td class="admin-tbRow1" align="right" width="20%" <?=$bgcolor2?>>
                        <strong>
                        <!--    <?=$data['service_charge']?>
                        -->
                        </strong>  &nbsp; <input class='sc' type="checkbox" service_charge="<?=$data['sc_paid']?>">
                    </td>
                    <td align='right' class="admin-tbRow1 admin-tbRow2">
                        <button emp_id='<?=$data['sid']?>' class='paid admin-button'> Paid </button>
                    </td>
                </tr>
                <?php
                    $sno++;
                }
                ?>
            </tbody>
        </table>
               <br/><br/>
		
    </div>
    <br clear="all" />
  </div>
</div>
<?php include 'include/footer.php' ?>
</body>
</html>
