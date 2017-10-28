<?php
require 'db_connect.php';
date_default_timezone_set("Asia/Bangkok");
if($_REQUEST["xDate"] == "") header('location:index.php');
$eDate = $_REQUEST["xDate"];

$date = strtotime($eDate);
$date = strtotime("-1 day", $date);
$sDate =  date('Y-m-d', $date);

?>
<!--	
 *  -- ************************************************************
 -- Author		:	Tanadech
 -- Create date	:	03-09-2017
 -- Update By	:	Tanadech
 -- Update date	:   03-09-2017
 -- Description	:	
 -- ************************************************************
-->

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="css/themes/default/jquery.mobile-1.4.5.min.css">
		<script src="js/jquery-1.11.3.min.js"></script>
		<script src="js/jquery.js"></script>
		<script src="js/jquery.mobile-1.4.5.min.js"></script>
        <script type="application/javascript">
			function gotoUrl(xItc,DueDate) {
				//alert("mCustomer.php?xItc="+xItc+"&DueDate="+DueDate)
				window.location.href = "mCustomer.php?xItc="+xItc+"&xDate="+DueDate+"&xUrl=m1.php";
			}

			function gotoMenu(xLink,DueDate) {
				location.href = xLink+"?xDate="+DueDate;
			}
		</script>
	</head>

	<body>
 	<div data-demo-html="true">
		<div data-role="header">
			<a href="#" onClick='gotoMenu("fai_menu.php","<?=$eDate?>");' class="ui-btn-left ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-circle-triangle-w ui-icon-carat-l">Back</a>
            		<h1>ขนมชิ้น</h1>
			<a href="#" onClick='gotoMenu("fai_menu.php","<?=$eDate?>");' class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-right ui-icon-power">Exit</a>
		</div>
	</div>

	<div data-role="content">
		<table data-role="table" id="table-custom-2" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive" data-column-btn-theme="b" data-column-btn-text="Columns to display..." data-column-popup-theme="a">
			<thead>
				<tr class="ui-bar-d">
                	 <th data-priority="2">ลำดับ</th>
                     <th data-priority="2">ห้องผลิต</th>
					 <th data-priority="2">บาร์โค้ด</th>
					 <th>ชื่อสินค้า</th>
					 <th>ราคา</th>
                     <th data-priority="2">แบบ</th>
					 <th>order</th>
					 <th data-priority="2">com</th>
					 <th data-priority="2" >get</th>
                     <th data-priority="2" >sale</th>
                     <th data-priority="2" >stock</th>
				</tr>
			</thead>
		 
			<tbody>
			
			<?
				$Sql = "SELECT
buffer_fac_order.xDt,
buffer_fac_order.id,
buffer_fac_order.Item_Code,
buffer_fac_order.Barcode,
buffer_fac_order.NameTH,
buffer_fac_order.IsForm,
buffer_fac_order.SalePrice,
buffer_fac_order.SoQty,
buffer_fac_order.FacQty,
buffer_fac_order.RvQty,
buffer_fac_order.SaQty,
buffer_fac_order.StQty,
roomtype.roomname
FROM buffer_fac_order
LEFT JOIN roomtype ON buffer_fac_order.roomtypeID = roomtype.roomtypeID
WHERE buffer_fac_order.RvQty = 0
AND buffer_fac_order.Objective = 7
ORDER BY  buffer_fac_order.SalePrice ASC" ;

				$row = 1;
				$meQuery = mysql_query( $Sql );
    			while ($Result = mysql_fetch_assoc($meQuery)){
			?>
				<tr>
                	<th><?=$row?></th>
                    <th><?=$Result["roomname"]?></th>
					<th><?=$Result["Barcode"]?></th>
					<td><?=$Result["NameTH"]?></td>
					<td><?=$Result["SalePrice"]?></td>
                    <td><?=$Result["IsForm"]?></td>
					<td><?=$Result["SoQty"]?></td>
					<td><?=$Result["FacQty"]?></th>
                    <td><?=$Result["RvQty"]?></th>
					<td><?=$Result["SaQty"]?></th>
                    <td><?=$Result["StQty"]?></th>
                    
				</tr>
			<?
				$row++;
				
				}
			?>  
			
			</tbody>
		</table>
 
</div> 

<div data-role="footer">
			<h1>FAI BAKERY CHIANGMAI</h1>
</div>
	
	</body>
</html>


