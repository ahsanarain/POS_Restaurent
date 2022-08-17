    	<?php
			if($_SESSION['user_id']=="thames"){
		?>
		<ul>
			<li class="hd">Point of Sale</li>
			<li><a href="items.php">Main Menu Item</a></li>
			<li class="line"></li>
			<li><a href="sub_items.php">Sub Menu Item</a></li>
        	<li class="line"></li>
			<li><a href="pos_activity.php" target="_blank">POS Activity</a></li>
        	<li class="line"></li>
                <li><a href="detail_sub_orders.php" target="_blank">POS Details</a></li>
        	<li class="line"></li>
			<li><a href="pos_order.php" target="_blank">POS Screen</a></li>
			<li class="line"></li>
			<li><a href="pos_manual.php" target="_blank">POS Manual Order</a></li>
			<li class="line"></li>
			<li><a href="pos_sale_summary.php">Daily Summary Report</a></li>
			<li class="line"></li>
			<li><a href="detailSaleRpt.php">Detail Summary Report</a></li>
        	<li class="line"></li>
			<li><a href="orders.php">Orders</a></li>
        	<li class="line"></li>
        	<li class="hd">Operations</li>
        	<li><a href="register.php">Attendance</a></li>
        	<li class="line"></li>
			<li> <a href="salaryCalc.php">Attendance vs Salary Detail</a> </li>
			<li class="line"></li>
			<li><a href="expEntry.php">Expanses</a></li>
			<li class="line"></li>
			<li><a href="filterExpDetail.php">Expanses Detail</a> 
			<li class="line"></li>
			<li><a href="salaryDetail.php">Salary Detail Report</a>
                        <li class="line"></li>
			<li><a href="notes.php">Notifications</a>
                            <li class="line"></li>
			<li class="hd">Employees</li>
			<li><a href="empList.php">Employees List</a></li>
        	<li class="line"></li>
			<li><a href="logout.php">Logout</a></li>
			<li class="hd">Others</li>
			<li><a href="http://192.168.1.10/">Security Cameras</a></li>
        	<li class="line"></li>
			<li class="line"></li>
			<li><a href="http://www.thamesburger.com">Online Website</a></li>
        	<li class="line"></li>
        </ul>
		<?php
			}
			else if($_SESSION['user_id']=="POS"){
		?>
			<ul>
			<li class="hd">Point of Sale</li>
			<li class="line"></li>
			<li><a href="pos_order.php" target="_blank">POS Screen</a></li>
                        <li class="line"></li>
                        
                        <li><a href="detail_sub_orders.php" target="_blank">POS Details</a></li>
                        <li class="line"></li>
                <li><a href="logout.php">Logout</a></li>
			<li class="line"></li>
		<?php
			}
			else if($_SESSION['user_id']=="imran"){
		?>
			<ul>
			<li class="hd">Point of Sale</li>
                        <li><a href="items.php">Main Menu Item</a></li>
			<li class="line"></li>
			<li><a href="sub_items.php">Sub Menu Item</a></li>
        	<li class="line">
			<li><a href="pos_activity.php" target="_blank">POS Activity</a></li>
        	<li class="line"></li>
                <li><a href="detail_sub_orders.php" target="_blank">POS Details</a></li>
                        <li class="line"></li>
			<li><a href="pos_order.php" target="_blank">POS Screen</a></li>
        	<li class="line"></li>
			<li><a href="pos_manual.php" target="_blank">POS Manual Order</a></li>
			<li class="line"></li>
			<li class="hd">Operations</li>
        	<li class="line"></li>
			<li><a href="expEntry.php">Expanses</a></li>
			<li class="line"></li>
                        
			<li><a href="filterExpDetail.php">Expanses Detail</a> 
			<li class="line"></li>
			<li><a href="detailSaleRpt.php">Detail Summary Report</a></li>
        	<li class="line"></li>
			<li><a href="logout.php">Logout</a></li>
			<li class="line"></li>
			<li class="hd">Others</li>
			<li><a href="http://www.thamesburger.com">Online Website</a></li>
        	
        </ul>
		<?php
			}			else{
		?>
			<ul>
			<li class="hd">Point of Sale</li>
			<li><a href="pos_activity.php" target="_blank">POS Activity</a></li>
        	<li class="line"></li>
                <li><a href="detail_sub_orders.php" target="_blank">POS Details</a></li>
                        <li class="line"></li>
			<li><a href="pos_order.php" target="_blank">POS Screen</a></li>
                        
        	<li class="line"></li>
			<li><a href="pos_manual.php" target="_blank">POS Manual Order</a></li>
			<li class="line"></li>
			<li class="hd">Operations</li>
        	<li><a href="register.php">Attendance</a></li>
        	<li class="line"></li>
			<li><a href="expEntry.php">Expanses</a></li>
                        <li class="line"></li>
                        <li><a href="filterExpDetail.php">Expanses Detail</a> 
			<li class="line"></li>
			<li><a href="detailSaleRpt.php">Detail Summary Report</a></li>
			<li class="line"></li>
			<li><a href="logout.php">Logout</a></li>
			<li class="line"></li>
			<li class="hd">Others</li>
			<li><a href="http://www.thamesburger.com">Online Website</a></li>
        	
        </ul>
		<?php
			}
		?>
                   
