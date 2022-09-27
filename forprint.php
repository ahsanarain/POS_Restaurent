<?php
include('lib/iq.php');
include('Connections/cn.php');

$qmsg = "SELECT msg FROM qtmsg where status='1'";
$rmsg = mysqli_query($cn, $qmsg) or die(mysqli_error($cn));
$datamsg= mysqli_fetch_assoc($rmsg);

$footer_msg = (isset($datamsg['msg'])?$datamsg['msg']:'');

$strForPrint ="";
if(isset($_POST['method']))
{
    if($_POST['method']=='printCustomer')
    {
        $strForPrint ="";
        $order_id = $_POST['order_id'];
        $fetch = "select * from order_tab as o
                    inner join sub_order_tab as s on o.order_id = s.order_id
					WHERE o.order_id = '".$order_id."'";
        $rowrsauto=mysqli_query($cn, $fetch);
        $arrAuto=array();
        while($data= mysqli_fetch_assoc($rowrsauto)) $arrAuto[] = $data;

        $order_id = $arrAuto[0]['order_id'];
        $customer = $arrAuto[0]['customer_name'];
        $arrdt = explode(" ",$arrAuto[0]['date_time']);
        $tm = $arrdt[1];
        $arrdt = explode("-",$arrdt[0]);

        $dd = $arrdt[2];
        $mm = $arrdt[1];
        $yy = $arrdt[0];

        $dt = $dd.'-'.$mm.'-'.$yy.' '.$tm;

        $phone = $arrAuto[0]['phone'];
        $order_type = $arrAuto[0]['order_type'];
        $order_desc = "";
        if($order_type == "S"){
            $order_desc = "Service";
        }
        elseif($order_type=="D"){
            $order_desc = "Delivery";
        }
        else{
            $order_desc = "Take Away";
        }

        $user = $arrAuto[0]['usr'];
        $sc = $arrAuto[0]['service_charge'];
        $discount = $arrAuto[0]['discount'];
        $amount = $arrAuto[0]['amount'];
        $comments = $arrAuto[0]['comments'];
        $amount_status = $arrAuto[0]['amount_status'];
        $amount_desc = "";

        if($amount_status == "P"){
            $amount_desc = "Not Paid";
        }
        elseif($amount_status == "P"){
            $amount_desc = "Paid";
        }


        $strForPrint .= "<table width='288' border='0' align='center' cellpadding='0' cellspacing='0'>
  <tr>
<td align='center' valign='top'>
			<style type='text/css'>
            	body { margin-left: 0px; margin-top: 0px; margin-right: 0px; margin-bottom: 0px; }
            	#newprint { width:288px; margin:auto; height:auto; border:0px solid red; }
            	body,td,th { font-family: Verdana; font-size: 10px; }
            	.small{ font-family:verdana; font-size:10px;}
            </style>
<table width='100%' border='0' align='center'>
            <tr>
            <td colspan='3' rowspan='3' align='left' valign='top'><strong><img width='150px' src='images/logo-qt.png'></strong></td>
            <td colspan='2' align='right'><span class='small'>Order # :".$order_id."</span></td>
            </tr>
            <tr>
            <td colspan='2' align='right'><span class='small'>".$dt."</span></td>
            </tr>
            <tr>
            <td colspan='2' align='right'><span class='small'>".$user."</span></td>
            </tr>
            <tr>
            <td colspan='3'><span class='small'>".$customer."</span></td>
            <td width='52'><span class='small'>".$order_desc."</span></td>
            <td width='130' align='right'><span class='small'>".$phone."</span></td>
            </tr>
      </table>
	    	<hr>
            <table width='100%' border='0'>
            <tr bgcolor='#F0F0F0'>
            <td width='100'><strong>Item</strong></td>
            <td width='34' align='right'><strong>Qty</strong></td>
            <td width='60' align='right'><strong>Price</strong></td>
            <td width='73' align='right'><strong>Total</strong></td>
            ";
        $gross=0;
        foreach($arrAuto as $data){

            $strForPrint.="	</tr>
            
                <tr>
                <td>".$data['item']."</td>
                <td align='right'>".$data['qty']."</td>
                <td align='right'>".$data['price']."</td>
                <td align='right'>".($data['qty'] * $data['price'])."</td>
           </tr>";
            $gross = $gross + ($data['qty'] * $data['price']);
        }

        $strForPrint .="</table>
            
			<hr>
            <table width='100%' border='0'>
			<tr>
     			<td colspan='2'>".$comments."</td>
            </tr>
	    <tr>
            <td colspan='2' align='center' valign='top'>    	  </td>
            </tr>
            <tr>
            <td colspan='2' align='center' valign='top'>
		
			<hr>	    	</td>
            </tr>
            <tr>
              <td colspan='2' align='center'><strong>".$amount_desc."</strong></td>
              </tr>
            <tr>
            <td><strong>Gross Total : </strong></td>
          
            <td align='right' width='54'><font size='2'><b>".($gross)."/-</b></font></td>
           </tr>
           <tr>
           <td><strong> Service Charge:</strong></td>
           <td align='right'><font size='2'><b>".$sc."/-</b></font></td>
            </tr>
	    <tr>
           <td><strong></strong><strong>Discount:</strong></td>
	    <td align='right'><font size='2'><b>".$discount."/-</b></font></td>
        </tr>
	    <tr>
		<td><strong></strong><strong>Net Total:</strong></td>
	    <td align='right'><font size='2'><b>".(($gross + $sc) -$discount)."/-</b></font></td>
          </tr>
 
          <tr>
          <td colspan='2' align='center' valign='top'><span class='small'><br />".$org_address."<br>NTN : ".$ntn."</span><br><strong>".$footer_msg."</strong></td>
           </tr>
      </table>
             
	</td>
  </tr>
</table>
<p style='page-break-before: always'></p>
";
        echo $strForPrint; exit();
    }
}

if(isset($_POST['method'])){
    if($_POST['method']=='printKitchen')
    {
        $order_id = $_POST['order_id'];
        $strForPrint ="";
        $fetch = "select * from order_tab as o
                    inner join sub_order_tab as s on o.order_id = s.order_id
					WHERE o.order_id = '".$order_id."'";
        $rowrsauto=mysqli_query($cn, $fetch);
        $arrAuto=array();
        while($data= mysqli_fetch_assoc($rowrsauto)) $arrAuto[] = $data;
        $order_id = $arrAuto[0]['order_id'];
        $customer = $arrAuto[0]['customer_name'];
        $arrdt = explode(" ",$arrAuto[0]['date_time']);
        $tm = $arrdt[1];
        $arrdt = explode("-",$arrdt[0]);

        $dd = $arrdt[2];
        $mm = $arrdt[1];
        $yy = $arrdt[0];

        $dt = $dd.'-'.$mm.'-'.$yy.' '.$tm;

        $phone = $arrAuto[0]['phone'];
        $order_type = $arrAuto[0]['order_type'];
        $order_desc = "";
        if($order_type == "S"){
            $order_desc = "Service";
        }
        elseif($order_type=="D"){
            $order_desc = "Delivery";
        }
        else{
            $order_desc = "Take Away";
        }

        $user = $arrAuto[0]['usr'];
        $sc = $arrAuto[0]['service_charge'];
        $discount = $arrAuto[0]['discount'];
        $amount = $arrAuto[0]['amount'];
        $comments = $arrAuto[0]['comments'];
        $amount_status = $arrAuto[0]['amount_status'];
        $amount_desc = "";

        if($amount_status == "P"){
            $amount_desc = "Not Paid";
        }
        elseif($amount_status == "P"){
            $amount_desc = "Paid";
        }
        $strForPrint .= "<table width='288' border='0' align='center' cellpadding='0' cellspacing='0'>
  <tr>
<td align='center' valign='top'>
			<style type='text/css'>
            	body { margin-left: 0px; margin-top: 0px; margin-right: 0px; margin-bottom: 0px; }
            	#newprint { width:288px; margin:auto; height:auto; border:0px solid red; }
            	body,td,th { font-family: Verdana; font-size: 10px; }
            	.small{ font-family:verdana; font-size:10px;}
            </style>
<table width='100%' border='0' align='center'>
            
            <tr>
              <td colspan='3' align='center' valign='middle'><strong><img width='150px' src='images/logo-qt.png'></strong></td>
            </tr>
			<tr>
            <td colspan='3' align='center'><strong>".$dt."</strong></td>
            </tr>
            <tr>
            <td><span class='small'><strong>Order#:".$order_id."</strong></span></td>
            <td width='52' colspan='-1' align='center'></strong>".$customer."</strong></td>
            <td width='130' align='right'><span class='small'><strong>".$order_desc."</span></strong></td>
            </tr>
      </table>
	    	<hr>
            <table width='100%' border='0'>
            <tr bgcolor='#F0F0F0'>
              <td align='left' valign='top'><strong>Srno</strong></td>
            <td align='left' valign='top'><strong>Item</strong></td>
            <td width='73' align='right'><strong>Qty</strong></td>
            ";
        $srno = 0;
        foreach($arrAuto as $data){
            $srno++;
            $strForPrint.="	</tr>
            
                <tr>
                  <td align='left' valign='top'>".$srno."</td>
                <td align='left' valign='top'>".$data['item']."</td>
                <td align='right'>(".$data['qty'].")</td>
           </tr>";
        }

        $strForPrint .="
 <tr>
                  <td colspan='3'><hr></td>
                
           </tr>
 <tr>
                <td align='center' valign='top' colspan='2'><strong>Total Quantity</strong></td>
                <td align='right'><strong>(".$srno.")</strong></td>
           </tr>
 </table>
            
			<hr>
            <table width='100%' border='0'>
			<tr>
     			<td width='54'>".$comments."</td>
            </tr>
	    <tr>
            <td align='center' valign='top'>    	  </td>
            </tr>
            <tr>
            <td align='center' valign='top'>
		
			<hr>	    	</td>
            </tr>
 
          <tr>
          <td align='center' valign='top'><span class='small'><br />
          </span></td>
           </tr>
      </table>
             
	</td>
  </tr>
</table>
<p style='page-break-before: always'>";
        echo $strForPrint; exit();
    }
}