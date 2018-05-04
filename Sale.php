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
//====================================
function AmountTotal($xDate,$itc)
		{
			$sDate = date('Y-'.$mm.'-01');
			$eDate  = date('Y-'.$mm.'-t');
			$xSql = "SELECT Sum(saleorder_detail.Qty) AS Amount
			FROM saleorder
			inner join saleorder_detail on saleorder_detail.DocNo = saleorder.DocNo
			where date(saleorder.DueDate) = date('$xDate')
			AND saleorder_detail.Item_Code = '$itc'
			AND saleorder.IsFinish = 3
			AND saleorder.IsCancel = 0
			AND saleorder.Objective = 1
			AND saleorder.IsNormal = 1";
			$result = mysql_query( $xSql );
			while ($Row = mysql_fetch_array($result)){
				$xVal = $Row["Amount"];
			}

			return $xVal;
		}

//====================================
$Sql = "SELECT
sale_pack_run_detail.Id,
item.Barcode,
item.NameTH,
item.SalePrice,
CONVERT( IFNULL((SELECT Sum(saleorder_detail.Qty)
FROM saleorder
inner join saleorder_detail on saleorder_detail.DocNo = saleorder.DocNo
where date(saleorder.DueDate) = date('$eDate')
AND saleorder_detail.Item_Code = sale_pack_run_detail.Item_Code
AND saleorder.Cus_Code = SO.Cus_Code
AND saleorder.IsFinish = 3
AND saleorder.IsCancel = 0
AND saleorder.Objective = SO.Objective
AND saleorder.IsNormal = 1 ),0),
DECIMAL(4,0) )  AS  OrderQty,
sale_pack_run_detail.Qty,
sale_pack_run.Modify_Date,
CONCAT(customer.FName,' ',customer.LName) AS xName
FROM sale_pack_run
INNER JOIN sale_pack_run_detail ON sale_pack_run_detail.DocNo = sale_pack_run.DocNo
INNER JOIN saleorder AS SO ON sale_pack_run.RefDocNo = SO.DocNo
INNER JOIN item ON sale_pack_run_detail.Item_Code = item.Item_Code
INNER JOIN customer ON sale_pack_run.Cus_Code = customer.Cus_Code
where sale_pack_run.DocDate BETWEEN '$sDate 15:00:00' AND '$eDate 15:00:00'
AND SO.Objective = 1
AND sale_pack_run_detail.Item_Code = '$xItc'
AND sale_pack_run.IsCancel = 0";

				$row = 1;
				$meQuery = mysql_query( $Sql );
    			while ($Result = mysql_fetch_assoc($meQuery)){
					$c1 = $Result["OrderQty"];
					$c2 = $Result["Qty"];
					if( $c1 <> $c2 ) $upeQuery = mysql_query( "UPDATE sale_pack_run_detail SET chkOrder = 1 WHERE sale_pack_run_detail.Id = '".$Result["Id"]."'" );
				}
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
			<?php } elseif($xUrl=="m1_1.php") { ?>
				<a href="#" onClick='gotoMenu("m1_1.php","<?=$eDate?>","","","","<?=$xUrl?>");' class="ui-btn-left ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-circle-triangle-w ui-icon-carat-l">Back</a>
			<?php } elseif($xUrl=="m1_2.php") { ?>
				<a href="#" onClick='gotoMenu("m1_2.php","<?=$eDate?>","","","","<?=$xUrl?>");' class="ui-btn-left ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-circle-triangle-w ui-icon-carat-l">Back</a>
			<?php } elseif($flag=="notenough1"){ ?>
				<a href="#" onClick='gotoMenu("m2_1.php","<?=$eDate?>","","","","<?=$xUrl?>","<?=$flag?>");' class="ui-btn-left ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-circle-triangle-w ui-icon-carat-l">Back</a>
			<?php } elseif($flag=="notenough2"){ ?>
				<a href="#" onClick='gotoMenu("m2_2.php","<?=$eDate?>","","","","<?=$xUrl?>","<?=$flag?>");' class="ui-btn-left ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-circle-triangle-w ui-icon-carat-l">Back</a>
			<?php } elseif($flag=="over1"){ ?>
				<a href="#" onClick='gotoMenu("m5_1.php","<?=$eDate?>","","","","<?=$xUrl?>","<?=$flag?>");' class="ui-btn-left ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-circle-triangle-w ui-icon-carat-l">Back</a>
			<?php } elseif($flag=="over2"){ ?>
				<a href="#" onClick='gotoMenu("m5_2.php","<?=$eDate?>","","","","<?=$xUrl?>","<?=$flag?>");' class="ui-btn-left ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-circle-triangle-w ui-icon-carat-l">Back</a>
			<?php } elseif($flag=="m3_new"){ ?>
				<a href="#" onClick='gotoMenu("m3_new.php","<?=$eDate?>","","","","<?=$xUrl?>","<?=$flag?>");' class="ui-btn-left ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-circle-triangle-w ui-icon-carat-l">Back</a>
			<?php } else{
				?> <a href="#" onClick='gotoMenu("m3_1.php","<?=$eDate?>","<?=$xItc?>","<?=$xUrl2?>","<?=$CusCode?>","<?=$xUrl?>");' class="ui-btn-left ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-circle-triangle-w ui-icon-carat-l">Back</a>
			<?php } ?>
            		<h1>วันที่รับ : <?=$eDate?></h1>
			<a href="#" onClick='gotoMenu("fai_menu.php","<?=$eDate?>");' class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-right ui-icon-power">Exit</a>
		</div>
	</div>
    <form action="m0.php">
	<div data-role="content">
		<table data-role="table" id="table-custom-2" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive" data-column-btn-theme="b" data-column-btn-text="Columns to display..." data-column-popup-theme="a">
			<thead>
				<tr class="ui-bar-d">
                	 <th data-priority="2">ลำดับ</th>
                     <th data-priority="2">ชื่อลูกค้า</th>
					 <th data-priority="2">บาร์โค้ด</th>
					 <th>ชื่อสินค้า</th>
					 <th>ราคา</th>
                     <th>ยอดสั่ง</th>
                     <th>ยอดจัด</th>
				</tr>
			</thead>

			<tbody>

			<?

$Sql = "SELECT
item.Barcode,
item.NameTH,
item.SalePrice,
CONVERT( IFNULL((SELECT Sum(saleorder_detail.Qty)
FROM saleorder
inner join saleorder_detail on saleorder_detail.DocNo = saleorder.DocNo
where date(saleorder.DueDate) = date('$eDate')
AND saleorder_detail.Item_Code = sale_pack_run_detail.Item_Code
AND saleorder.Cus_Code = SO.Cus_Code
AND saleorder.IsFinish = 3
AND saleorder.IsCancel = 0
AND saleorder.Objective = SO.Objective
AND saleorder.IsNormal = 1 ),0),
DECIMAL(4,0) )  AS  OrderQty,
sale_pack_run_detail.Qty,
sale_pack_run.Modify_Date,
customer.Cus_Code,
CONCAT(customer.FName,' ',customer.LName) AS xName
FROM sale_pack_run
INNER JOIN sale_pack_run_detail ON sale_pack_run_detail.DocNo = sale_pack_run.DocNo
INNER JOIN saleorder AS SO ON sale_pack_run.RefDocNo = SO.DocNo
INNER JOIN item ON sale_pack_run_detail.Item_Code = item.Item_Code
INNER JOIN customer ON sale_pack_run.Cus_Code = customer.Cus_Code
where sale_pack_run.DocDate BETWEEN '$sDate 15:00:00' AND '$eDate 15:00:00'
AND SO.Objective = 1
AND sale_pack_run_detail.Item_Code = '$xItc'
AND sale_pack_run.IsCancel = 0
ORDER BY sale_pack_run_detail.chkOrder DESC";

				$row = 1;
				$meQuery = mysql_query( $Sql );
    			while ($Result = mysql_fetch_assoc($meQuery)){
					$TotalQty1 += $Result["OrderQty"];
					$TotalQty2 += $Result["Qty"];
					$c1 = $Result["OrderQty"];
					$c2 = $Result["Qty"];
					if( $c1 <> $c2 )
						echo '<tr style="color:red;">';
					else
						echo '<tr>';
			?>
            		<td><?=$row?></td>
					<th><?=$Result["Cus_Code"]?> : <?=$Result["xName"]?></th>
                    <th><?=$Result["Barcode"]?></th>
                    <th><?=$Result["NameTH"]?></th>
                    <th><?=$Result["SalePrice"]?></th>
                    <th><?=number_format($Result["OrderQty"], 0, '.', '');?></th>
                    <th><?=number_format($Result["Qty"], 0, '.', '');?></th>
				</tr>
			<?
				$row++;
				}
			?>
            	<tr>
            		<td  colspan="5" >รวมทั้งสิ้น</td>
                    <th><?=AmountTotal($eDate,$xItc)?></th>
                    <th><?=number_format($TotalQty2, 0, '.', '');?></th>
                    <th></th>
				</tr>
			</tbody>
		</table>
    </form>
</div>

<div data-role="footer">
			<h1>FAI BAKERY CHIANGMAI</h1>
</div>

	</body>
</html>
