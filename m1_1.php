<?php
require 'db_connect.php';
date_default_timezone_set("Asia/Bangkok");
if($_REQUEST["xDate"] == "") header('location:index.php');
$eDate = $_REQUEST["xDate"];
$Fl = $_REQUEST["Fl"];
$date = strtotime($eDate);
$date = strtotime("-1 day", $date);
$sDate =  date('Y-m-d', $date);
$xItc = "";
if($_REQUEST["xItc"]!="") $xItc = $_REQUEST["xItc"];
$xUrl = $_REQUEST["xUrl"];
$xCusCode = $_REQUEST["CusCode"];
$xUrl2 = $_REQUEST["xUrl2"];
$flag = $_REQUEST["flag"];
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
			function gotoUrl(xLink,xItc,DueDate,flag) {
				//alert("mCustomer.php?xItc="+xItc+"&DueDate="+DueDate)
				window.location.href = xLink+"?xItc="+xItc+"&xDate="+DueDate+"&xUrl=m1_1.php"+"&flag="+flag;
			}

			function gotoMenu(xLink,DueDate,CusCode) {
				location.href = xLink+"?xDate="+DueDate+"&CusCode="+CusCode;
			}

			function gotoFilter(xLink,DueDate,Fl) {
				location.href = xLink+"?xDate="+DueDate+"&Fl="+Fl;
			}
		</script>
	</head>

	<body>
 	<div data-demo-html="true">
		<div data-role="header">
			<a href="#" onClick='gotoMenu("fai_menu.php","<?=$eDate?>","<?=$xCusCode?>");' class="ui-btn-left ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-circle-triangle-w ui-icon-carat-l">Back</a>
            		<h1>สินค้าประจำวัน</h1>
			<a href="#" onClick='gotoMenu("fai_menu.php","<?=$eDate?>","");' class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-right ui-icon-power">Exit</a>
		</div>
	</div>

<div data-role="content">


		<table data-role="table" id="table-custom-2" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive" data-column-btn-theme="b" data-column-btn-text="Columns to display..." data-column-popup-theme="a">
			<thead style='height:35px;display:block;'>
				<tr class="ui-bar-d">
                	 <th width="50px" data-priority="2">ลำดับ</th>
                     <th width="150px"data-priority="2">ห้องผลิต</th>
					 <th width="200px">ชื่อสินค้า</th>
					 <th width="50px">ราคา</th>
					 <th width="50px">order</th>
					 <th width="50px" data-priority="2">com</th>
                     <th width="50px" data-priority="2">ยกมา</th>
					 <th width="50px" data-priority="2" >get</th>
                     <!-- <th width="50px" data-priority="2" >sale</th> -->
                     <th width="500px" data-priority="2" >รายละเอียด</th>
				</tr>
			</thead>

			<tbody style='height:300px;display:block;overflow:scroll'>
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
buffer_fac_order.CrQty,
buffer_fac_order.FacQty,
buffer_fac_order.CrQty,
buffer_fac_order.RvQty,
buffer_fac_order.SaQty,
buffer_fac_order.StQty,
buffer_fac_order.Detail,
roomtype.roomname
FROM buffer_fac_order
LEFT JOIN roomtype ON buffer_fac_order.roomtypeID = roomtype.roomtypeID
WHERE buffer_fac_order.RvQty = 0 AND buffer_fac_order.FacQty > 0
AND buffer_fac_order.xDt BETWEEN '$sDate 15:00:00' AND '$eDate 15:00:00'
AND buffer_fac_order.Objective = 1
AND buffer_fac_order.IsForm != 'ของกรอบ'
AND buffer_fac_order.Item_Code LIKE '%$xItc%'";

if($Fl==2)
	$Sql .= "ORDER BY  roomtype.roomname ASC,buffer_fac_order.SalePrice ASC" ;
else
	$Sql .= "ORDER BY  buffer_fac_order.SalePrice ASC";

				$row = 1;
				$meQuery = mysql_query( $Sql );
    			while ($Result = mysql_fetch_assoc($meQuery)){
			?>
				<tr>
                	<th width="50px"><?=$row?></th>
                    <th width="150px"><?=$Result["roomname"]?></th>
					<td width="200px"><?=$Result["NameTH"]?></td>
					<td width="50px"><?=$Result["SalePrice"]?></td>
					<td width="50px"><?=$Result["SoQty"]?></td>
					<td width="50px"><?=$Result["FacQty"]?></td>
                    <td width="50px"><?=$Result["CrQty"]?></td>
                    <td width="50px"><?=$Result["RvQty"]?></td>
					<!-- <td width="50px" style="cursor: pointer;" onClick='gotoUrl("Sale.php","<?=$Result["Item_Code"]?>","<?=$eDate?>","<?=$flag?>")'><?=$Result["SaQty"]?></td> -->
                    <td width="500px"><?=$Result["Detail"]?></td>


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
