<?php
require 'db_connect.php';
date_default_timezone_set("Asia/Bangkok");
if($_REQUEST["xDate"] == "") header('location:index.php');
$eDate = $_REQUEST["xDate"];

$date = strtotime($eDate);
$date = strtotime("-1 day", $date);
$sDate =  date('Y-m-d', $date);

$CusCode = $_REQUEST["CusCode"];
$xUrl = $_REQUEST["xUrl"];
$xUrl2 = $_REQUEST["xUrl2"];
$xItc = $_REQUEST["xItc"];
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
			function gotoUrl(CusCode,DueDate) {
				window.location.href = "Order.php?CusCode="+CusCode+"&xDate="+DueDate+"&xUrl=Customer.php";
			}

			function gotoMenu(xLink,DueDate,xItc,xUrl2,CusCode,xUrl,flag) {
				location.href = xLink+"?xDate="+DueDate+"&xItc="+xItc+"&xUrl="+xUrl+"&xUrl2="+xUrl2+"&CusCode="+CusCode+"&flag="+flag;
			}

		</script>
	</head>

	<body>
 	<div data-demo-html="true">
		<div data-role="header">
			<?php if($xUrl=="fai_menu.php" && $flag=="daily")
			{ ?>
				<a href="#" onClick='gotoMenu("m3_1.php","<?=$eDate?>","","","","<?=$xUrl?>","<?=$flag?>");' class="ui-btn-left ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-circle-triangle-w ui-icon-carat-l">Back</a>
			<?php }else{
				?> <a href="#" onClick='gotoMenu("m3_1.php","<?=$eDate?>","<?=$xItc?>","<?=$xUrl2?>","<?=$CusCode?>","<?=$xUrl?>","");' class="ui-btn-left ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-circle-triangle-w ui-icon-carat-l">Back</a>
			<?php } ?>
            		<h1>วันที่รับ : <?=$eDate?></h1>
			<a href="#" onClick='gotoMenu("fai_menu.php","<?=$eDate?>");' class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-right ui-icon-power">Exit</a>
		</div>
	</div>

	<div data-role="content">
    <h3>ยอดสั่งลูกค้า</h3>
		<table data-role="table" id="table-custom-2" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive" data-column-btn-theme="b" data-column-btn-text="Columns to display..." data-column-popup-theme="a">
			<thead>
				<tr class="ui-bar-d">
                	 <th data-priority="2">ลำดับ</th>
					 <th data-priority="2">บาร์โค้ด</th>
					 <th>ชื่อสินค้า</th>
					 <th>ราคา</th>
                     <th>จำนวน</th>
                     <th>เวลา</th>
				</tr>
			</thead>

			<tbody>

			<?
$Sql = "SELECT
item.Item_Code,
item.Barcode,
item.NameTH,
item.SalePrice,
saleorder_detail.Qty,
saleorder.CurrentTime
FROM saleorder
INNER JOIN saleorder_detail ON saleorder_detail.DocNo = saleorder.DocNo
INNER JOIN item ON saleorder_detail.Item_Code = item.Item_Code
WHERE DATE(saleorder.DueDate) = DATE('$eDate')
AND saleorder.Objective = 1
AND saleorder.IsFinish = 3
AND saleorder.IsCancel = 0
AND saleorder.IsNormal = 1
AND saleorder_detail.Item_Code = '$xItc'";

				$row = 1;
				$TotalQty = 0;
				$meQuery = mysql_query( $Sql );
    			while ($Result = mysql_fetch_assoc($meQuery)){
				$TotalQty += $Result["Qty"];
			?>
            	<tr>
            		<td><?=$row?></td>
					<th><?=$Result["Barcode"]?></th>
                    <th><?=$Result["NameTH"]?></th>
                    <th><?=$Result["SalePrice"]?></th>
                    <th><?=number_format($Result["Qty"], 0, '.', '');?></th>
                    <th><?=$Result["CurrentTime"]?></th>
				</tr>
			<?
				$row++;
				}
			?>
            	<tr>
            		<td  colspan="4" >รวมทั้งสิ้น</td>
                    <th><?=number_format($TotalQty, 0, '.', '');?></th>
                    <th></th>
				</tr>

			</tbody>
		</table>

        <h3>ยอดสินค้าในสต๊อก</h3>

      		<table data-role="table" id="table-custom-2" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive" data-column-btn-theme="b" data-column-btn-text="Columns to display..." data-column-popup-theme="a">
			<thead>
				<tr class="ui-bar-d">
                	 <th data-priority="2">ลำดับ</th>
					 <th data-priority="2">บาร์โค้ด</th>
					 <th>ชื่อสินค้า</th>
					 <th>ราคา</th>
                     <th>จำนวน</th>
                     <th>เวลา</th>
				</tr>
			</thead>

			<tbody>

			<?
$Sql = "SELECT
item.Item_Code,
item.Barcode,
item.NameTH,
item.SalePrice,
itemstock.Qty,
itemstock.DueDate
FROM itemstock
INNER JOIN item ON itemstock.ItemCode = item.Item_Code
WHERE DATE(itemstock.DueDate) = DATE('$eDate')
AND itemstock.ItemCode = '$xItc'";

				$row = 1;
				$TotalQty = 0;
				$meQuery = mysql_query( $Sql );
    			while ($Result = mysql_fetch_assoc($meQuery)){
				$TotalQty += $Result["Qty"];
			?>
            	<tr>
            		<td><?=$row?></td>
					<th><?=$Result["Barcode"]?></th>
                    <th><?=$Result["NameTH"]?></th>
                    <th><?=$Result["SalePrice"]?></th>
                    <th><?=number_format($Result["Qty"], 0, '.', '');?></th>
                    <th><?=$Result["DueDate"]?></th>
				</tr>
			<?
				$row++;
				}
			?>
                <tr>
            		<td  colspan="4" >รวมทั้งสิ้น</td>
                    <th><?=number_format($TotalQty, 0, '.', '');?></th>
                    <th></th>
				</tr>
			</tbody>
		</table>
</div>

<div data-role="footer">
			<h1>FAI BAKERY CHIANGMAI</h1>
</div>

	</body>
</html>
