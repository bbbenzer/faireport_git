<?php
require 'db_connect.php';
date_default_timezone_set("Asia/Bangkok");
if($_REQUEST["xDate"] == "") header('location:index.php');
$eDate = $_REQUEST["xDate"];

$date = strtotime($eDate);
$date = strtotime("-1 day", $date);
$sDate =  date('Y-m-d', $date);

$xUrl = $_REQUEST["xUrl"];
$CusCode = $_REQUEST["CusCode"];

  $upeQuery = mysql_query( "UPDATE saleorder_detail SET chkOrder = 0" );
$Sql = "";

				$row = 1;
				$TotalQty1 = 0;
				$TotalQty2 = 0;
				$meQuery = mysql_query( $Sql );
    			while ($Result = mysql_fetch_assoc($meQuery)){
					$TotalQty1 += $Result["Qty"];
					$TotalQty2 += $Result["SaleQty"];
					$c1 = $Result["Qty"];
					$c2 = $Result["SaleQty"];
					if( $c1 > $c2 ) $upeQuery = mysql_query( "UPDATE saleorder_detail SET chkOrder = 1 WHERE saleorder_detail.Id = '".$Result["Id"]."'" );

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
			function gotoUrl(xLink,xItc,DueDate,xCusCode,xUrl2) {
				//alert("mCustomer.php?xItc="+xItc+"&DueDate="+DueDate)
				window.location.href = xLink+"?xItc="+xItc+"&xDate="+DueDate+"&xUrl=Order.php"+"&CusCode="+xCusCode+"&xUrl2="+xUrl2;
			}

			function gotoMenu(xLink,DueDate) {
				location.href = xLink+"?xDate="+DueDate;
			}

		</script>
	</head>

	<body>
 	<div data-demo-html="true">
		<div data-role="header">
			<a href="#" onClick='gotoMenu("<?=$xUrl?>","<?=$eDate?>");' class="ui-btn-left ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-circle-triangle-w ui-icon-carat-l">Back</a>
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
					 <th data-priority="2">บาร์โค้ด</th>
					 <th>ชื่อสินค้า</th>
					 <th>ราคา</th>
                     <th>order</th>
                     <th>get</th>
				</tr>
			</thead>

			<tbody>

			<?

				$Sql = "SELECT
saleorder.DocNo,
item.Item_Code,
SUBSTR(item.Barcode,10,4) AS Barcode,
item.NameTH,
item.SalePrice,
saleorder_detail.Qty,
CONVERT( IFNULL((SELECT Sum(sale_pack_run_detail.Qty)
						FROM sale_pack_run
						inner join sale_pack_run_detail on sale_pack_run_detail.DocNo = sale_pack_run.DocNo
						INNER JOIN saleorder AS SO ON sale_pack_run.RefDocNo = SO.DocNo
						where sale_pack_run.DocDate BETWEEN '$sDate 17:00:00' AND '$eDate 16:00:00'
						AND date(SO.DueDate) = date(saleorder.DueDate)
						AND SO.Objective = 1
						AND sale_pack_run.Cus_Code = saleorder.Cus_Code
						AND sale_pack_run_detail.Item_Code = item.Item_Code
						AND sale_pack_run.IsCancel = 0
					GROUP BY sale_pack_run_detail.Item_Code
),0), DECIMAL(4,0) )  AS  SaleQty
FROM saleorder
INNER JOIN saleorder_detail ON saleorder.DocNo = saleorder_detail.DocNo
INNER JOIN item ON saleorder_detail.Item_Code = item.Item_Code
WHERE date(saleorder.DueDate) = DATE('$eDate')
AND saleorder.Cus_Code = '$CusCode'
AND saleorder.IsFinish = 3
AND saleorder.IsCancel = 0
AND saleorder.IsNormal = 1
AND saleorder.Objective = 1
ORDER BY saleorder_detail.chkOrder DESC,item.SalePrice ASC";

				$row = 1;
				$TotalQty1 = 0;
				$TotalQty2 = 0;
				$meQuery = mysql_query( $Sql );
    			while ($Result = mysql_fetch_assoc($meQuery)){
					$TotalQty1 += $Result["Qty"];
					$TotalQty2 += $Result["SaleQty"];
					$c1 = $Result["Qty"];
					$c2 = $Result["SaleQty"];
					if( $c1 > $c2 )
						echo '<tr style="color:red;">';
					else
						echo '<tr>';
			?>
            		<td><?=$row?></td>
					<th><?=$Result["Barcode"]?></th>
                    <th style="cursor: pointer;" onClick='gotoUrl("item.php","<?=$Result["Item_Code"]?>","<?=$eDate?>","<?=$CusCode?>","<?=$xUrl?>")'><?=$Result["NameTH"]?></th>
                    <th><?=$Result["SalePrice"]?></th>
                    <th><?=$Result["Qty"]?></th>
                    <th><?=$Result["SaleQty"]?></th>
				</tr>
			<?
				$row++;
				}
			?>
            	<tr>
            		<td  colspan="4" >รวมทั้งสิ้น</td>
                    <th><?=number_format($TotalQty1, 0, '.', '');?></th>
                    <th><?=number_format($TotalQty2, 0, '.', '');?></th>
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
